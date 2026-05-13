<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class EmployeController extends BaseController
{
    public function dashboard()
    {
        if ($redirect = $this->guardRoles(['employe'])) {
            return $redirect;
        }

        $userId = auth_id();
        $year = $this->currentYear();

        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();

        $counts = [
            'en_attente' => 0,
            'approuvee' => 0,
            'refusee' => 0,
            'annulee' => 0,
        ];
        $countRows = $congeModel
            ->select('statut, COUNT(*) as total')
            ->where('employe_id', $userId)
            ->groupBy('statut')
            ->findAll();

        foreach ($countRows as $row) {
            $counts[$row['statut']] = (int) $row['total'];
        }

        $soldes = $soldeModel
            ->select('soldes.*, types_conge.libelle, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.employe_id', $userId)
            ->where('soldes.annee', $year)
            ->orderBy('types_conge.libelle')
            ->findAll();

        $latestDemandes = $congeModel
            ->select('conges.*, types_conge.libelle')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $userId)
            ->orderBy('conges.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        return view('employe/dashboard', [
            'pageTitle' => 'Tableau de bord',
            'breadcrumb' => 'Accueil',
            'activeMenu' => 'dashboard',
            'counts' => $counts,
            'soldes' => $soldes,
            'latestDemandes' => $latestDemandes,
            'pendingCount' => $counts['en_attente'] ?? 0,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->guardRoles(['employe'])) {
            return $redirect;
        }

        $userId = auth_id();
        $year = $this->currentYear();

        $typeModel = new TypeCongeModel();
        $soldeModel = new SoldeModel();

        $types = $typeModel->orderBy('libelle')->findAll();
        $soldeRows = $soldeModel
            ->where('employe_id', $userId)
            ->where('annee', $year)
            ->findAll();

        $soldeMap = [];
        foreach ($soldeRows as $solde) {
            $soldeMap[$solde['type_conge_id']] = $solde;
        }

        return view('employe/create', [
            'pageTitle' => 'Nouvelle demande',
            'breadcrumb' => 'Nouvelle demande',
            'activeMenu' => 'create',
            'types' => $types,
            'soldes' => $soldeMap,
            'pendingCount' => $this->pendingCount($userId),
        ]);
    }

    public function store()
    {
        if ($redirect = $this->guardRoles(['employe'])) {
            return $redirect;
        }

        $rules = [
            'type_conge_id' => 'required|is_natural_no_zero',
            'date_debut' => 'required',
            'date_fin' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = auth_id();
        $typeId = (int) $this->request->getPost('type_conge_id');
        $dateDebut = (string) $this->request->getPost('date_debut');
        $dateFin = (string) $this->request->getPost('date_fin');
        $motif = (string) $this->request->getPost('motif');

        if ($dateDebut > $dateFin) {
            return redirect()->back()->withInput()->with('error', 'La date de debut doit preceder la date de fin.');
        }

        $nbJours = count_working_days($dateDebut, $dateFin);
        if ($nbJours <= 0) {
            return redirect()->back()->withInput()->with('error', 'La duree calculee est invalide.');
        }

        $typeModel = new TypeCongeModel();
        $type = $typeModel->find($typeId);
        if (! $type) {
            return redirect()->back()->withInput()->with('error', 'Type de conge introuvable.');
        }

        $congeModel = new CongeModel();
        $overlap = $congeModel
            ->where('employe_id', $userId)
            ->whereIn('statut', ['en_attente', 'approuvee'])
            ->groupStart()
                ->where('date_debut <=', $dateFin)
                ->where('date_fin >=', $dateDebut)
            ->groupEnd()
            ->countAllResults();

        if ($overlap > 0) {
            return redirect()->back()->withInput()->with('error', 'Chevauchement detecte avec une demande active.');
        }

        $year = (int) date('Y', strtotime($dateDebut));
        if ((int) $type['deductible'] === 1) {
            $soldeModel = new SoldeModel();
            $solde = $soldeModel
                ->where('employe_id', $userId)
                ->where('type_conge_id', $typeId)
                ->where('annee', $year)
                ->first();

            if (! $solde) {
                return redirect()->back()->withInput()->with('error', 'Solde introuvable pour ce type de conge.');
            }

            $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
            if ($restant < $nbJours) {
                return redirect()->back()->withInput()->with('error', 'Solde insuffisant pour cette demande.');
            }
        }

        $congeModel->insert([
            'employe_id' => $userId,
            'type_conge_id' => $typeId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'nb_jours' => $nbJours,
            'motif' => $motif !== '' ? $motif : null,
            'statut' => 'en_attente',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/employe/demandes')->with('success', 'Demande soumise avec succes.');
    }

    public function demandes()
    {
        if ($redirect = $this->guardRoles(['employe'])) {
            return $redirect;
        }

        $userId = auth_id();
        $statut = (string) $this->request->getGet('statut');

        $congeModel = new CongeModel();
        $builder = $congeModel
            ->select('conges.*, types_conge.libelle')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $userId)
            ->orderBy('conges.created_at', 'DESC');

        if ($statut !== '') {
            $builder->where('conges.statut', $statut);
        }

        $demandes = $builder->findAll();

        return view('employe/index', [
            'pageTitle' => 'Mes demandes',
            'breadcrumb' => 'Mes demandes',
            'activeMenu' => 'demandes',
            'demandes' => $demandes,
            'statut' => $statut,
            'pendingCount' => $this->pendingCount($userId),
        ]);
    }

    public function annuler(int $id)
    {
        if ($redirect = $this->guardRoles(['employe'])) {
            return $redirect;
        }

        $userId = auth_id();
        $congeModel = new CongeModel();
        $conge = $congeModel
            ->where('id', $id)
            ->where('employe_id', $userId)
            ->first();

        if (! $conge) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! in_array($conge['statut'], ['en_attente', 'approuvee'], true)) {
            return redirect()->back()->with('error', 'Cette demande ne peut plus etre annulee.');
        }

        $typeModel = new TypeCongeModel();
        $type = $typeModel->find($conge['type_conge_id']);

        if ($conge['statut'] === 'approuvee' && $type && (int) $type['deductible'] === 1) {
            $soldeModel = new SoldeModel();
            $year = (int) date('Y', strtotime($conge['date_debut']));
            $solde = $soldeModel
                ->where('employe_id', $userId)
                ->where('type_conge_id', $conge['type_conge_id'])
                ->where('annee', $year)
                ->first();

            if ($solde) {
                $newPris = max(0, (int) $solde['jours_pris'] - (int) $conge['nb_jours']);
                $soldeModel->update($solde['id'], ['jours_pris' => $newPris]);
            }
        }

        $congeModel->update($conge['id'], ['statut' => 'annulee']);

        return redirect()->back()->with('success', 'Demande annulee.');
    }

    public function profil()
    {
        if ($redirect = $this->guardRoles(['employe'])) {
            return $redirect;
        }

        $userId = auth_id();
        $year = $this->currentYear();

        $employeModel = new EmployeModel();
        $employe = $employeModel
            ->select('employes.*, departements.nom as departement_nom')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->find($userId);

        $soldeModel = new SoldeModel();
        $soldes = $soldeModel
            ->select('soldes.*, types_conge.libelle')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.employe_id', $userId)
            ->where('soldes.annee', $year)
            ->orderBy('types_conge.libelle')
            ->findAll();

        return view('employe/profil', [
            'pageTitle' => 'Mon profil',
            'breadcrumb' => 'Mon profil',
            'activeMenu' => 'profil',
            'employe' => $employe,
            'soldes' => $soldes,
            'pendingCount' => $this->pendingCount($userId),
        ]);
    }

    public function updateProfil()
    {
        if ($redirect = $this->guardRoles(['employe'])) {
            return $redirect;
        }

        $rules = [
            'prenom' => 'required',
            'nom' => 'required',
            'password' => 'permit_empty|min_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = auth_id();
        $prenom = (string) $this->request->getPost('prenom');
        $nom = (string) $this->request->getPost('nom');
        $password = (string) $this->request->getPost('password');

        $data = [
            'prenom' => $prenom,
            'nom' => $nom,
        ];

        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $employeModel = new EmployeModel();
        $employeModel->update($userId, $data);

        $user = session()->get('user');
        if (is_array($user)) {
            $user['prenom'] = $prenom;
            $user['nom'] = $nom;
            session()->set('user', $user);
        }

        return redirect()->back()->with('success', 'Profil mis a jour.');
    }

    private function pendingCount(int $userId): int
    {
        $congeModel = new CongeModel();
        return (int) $congeModel->where('employe_id', $userId)->where('statut', 'en_attente')->countAllResults();
    }

    private function currentYear(): int
    {
        return (int) date('Y');
    }
}
