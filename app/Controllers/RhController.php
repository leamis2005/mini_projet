<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\DepartementModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class RhController extends BaseController
{
    public function index()
    {
        if ($redirect = $this->guardRoles(['rh'])) {
            return $redirect;
        }

        $statut = (string) $this->request->getGet('statut');
        $departementId = (string) $this->request->getGet('departement_id');

        $congeModel = new CongeModel();
        $builder = $congeModel
            ->select('conges.*, employes.nom as employe_nom, employes.prenom as employe_prenom, departements.nom as departement_nom, types_conge.libelle as type_libelle, types_conge.deductible')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->orderBy('conges.created_at', 'DESC');

        if ($statut !== '') {
            $builder->where('conges.statut', $statut);
        }

        if ($departementId !== '') {
            $builder->where('departements.id', (int) $departementId);
        }

        $demandes = $builder->findAll();

        $year = $this->currentYear();
        $empIds = array_unique(array_column($demandes, 'employe_id'));
        $typeIds = array_unique(array_column($demandes, 'type_conge_id'));

        $soldeMap = [];
        if (! empty($empIds) && ! empty($typeIds)) {
            $soldeRows = (new SoldeModel())
                ->where('annee', $year)
                ->whereIn('employe_id', $empIds)
                ->whereIn('type_conge_id', $typeIds)
                ->findAll();

            foreach ($soldeRows as $solde) {
                $key = $solde['employe_id'] . '-' . $solde['type_conge_id'];
                $soldeMap[$key] = $solde;
            }
        }

        foreach ($demandes as &$demande) {
            $key = $demande['employe_id'] . '-' . $demande['type_conge_id'];
            $solde = $soldeMap[$key] ?? null;
            $demande['solde_restant'] = $solde
                ? (int) $solde['jours_attribues'] - (int) $solde['jours_pris']
                : null;
        }
        unset($demande);

        $countRows = $congeModel
            ->select('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->findAll();

        $counts = [
            'en_attente' => 0,
            'approuvee' => 0,
            'refusee' => 0,
            'annulee' => 0,
        ];
        foreach ($countRows as $row) {
            $counts[$row['statut']] = (int) $row['total'];
        }

        $departements = (new DepartementModel())->orderBy('nom')->findAll();

        return view('rh/index', [
            'pageTitle' => 'Demandes a traiter',
            'breadcrumb' => 'Demandes',
            'activeMenu' => 'demandes',
            'demandes' => $demandes,
            'departements' => $departements,
            'statut' => $statut,
            'departementId' => $departementId,
            'counts' => $counts,
        ]);
    }

    public function approuver(int $id)
    {
        if ($redirect = $this->guardRoles(['rh'])) {
            return $redirect;
        }

        $congeModel = new CongeModel();
        $conge = $congeModel->find($id);
        if (! $conge) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($conge['statut'] !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette demande a deja ete traitee.');
        }

        $commentaire = (string) $this->request->getPost('commentaire_rh');

        $typeModel = new TypeCongeModel();
        $type = $typeModel->find($conge['type_conge_id']);
        if (! $type) {
            return redirect()->back()->with('error', 'Type de conge introuvable.');
        }

        $year = (int) date('Y', strtotime($conge['date_debut']));
        $soldeModel = new SoldeModel();
        $solde = $soldeModel
            ->where('employe_id', $conge['employe_id'])
            ->where('type_conge_id', $conge['type_conge_id'])
            ->where('annee', $year)
            ->first();

        if ((int) $type['deductible'] === 1) {
            if (! $solde) {
                return redirect()->back()->with('error', 'Solde introuvable pour cette demande.');
            }

            $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
            if ($restant < (int) $conge['nb_jours']) {
                return redirect()->back()->with('error', 'Solde insuffisant pour approuver.');
            }
        }

        $db = db_connect();
        $db->transStart();

        $congeModel->update($id, [
            'statut' => 'approuvee',
            'commentaire_rh' => $commentaire !== '' ? $commentaire : null,
            'traite_par' => auth_id(),
        ]);

        if ((int) $type['deductible'] === 1 && $solde) {
            $soldeModel->update($solde['id'], [
                'jours_pris' => (int) $solde['jours_pris'] + (int) $conge['nb_jours'],
            ]);
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise a jour.');
        }

        return redirect()->back()->with('success', 'Demande approuvee et solde mis a jour.');
    }

    public function refuser(int $id)
    {
        if ($redirect = $this->guardRoles(['rh'])) {
            return $redirect;
        }

        $congeModel = new CongeModel();
        $conge = $congeModel->find($id);
        if (! $conge) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($conge['statut'] !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette demande a deja ete traitee.');
        }

        $commentaire = (string) $this->request->getPost('commentaire_rh');

        $congeModel->update($id, [
            'statut' => 'refusee',
            'commentaire_rh' => $commentaire !== '' ? $commentaire : null,
            'traite_par' => auth_id(),
        ]);

        return redirect()->back()->with('success', 'Demande refusee.');
    }

    public function soldes()
    {
        if ($redirect = $this->guardRoles(['rh'])) {
            return $redirect;
        }

        $year = $this->currentYear();
        $soldes = (new SoldeModel())
            ->select('soldes.*, employes.nom as employe_nom, employes.prenom as employe_prenom, departements.nom as departement_nom, types_conge.libelle as type_libelle')
            ->join('employes', 'employes.id = soldes.employe_id')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.annee', $year)
            ->orderBy('employes.nom')
            ->findAll();

        return view('rh/soldes', [
            'pageTitle' => 'Soldes des employes',
            'breadcrumb' => 'Soldes',
            'activeMenu' => 'soldes',
            'soldes' => $soldes,
        ]);
    }

    private function currentYear(): int
    {
        return (int) date('Y');
    }
}
