<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\DepartementModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AdminController extends BaseController
{
    public function dashboard()
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $employeModel = new EmployeModel();
        $congeModel = new CongeModel();
        $departementModel = new DepartementModel();

        $activeCount = (int) $employeModel->where('actif', 1)->countAllResults();
        $pendingCount = (int) $congeModel->where('statut', 'en_attente')->countAllResults();

        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        $approvedThisMonth = (int) $congeModel
            ->where('statut', 'approuvee')
            ->where('date_debut >=', $monthStart)
            ->where('date_debut <=', $monthEnd)
            ->countAllResults();

        $departementCount = (int) $departementModel->countAllResults();

        $today = date('Y-m-d');
        $absentToday = (int) $congeModel
            ->where('statut', 'approuvee')
            ->where('date_debut <=', $today)
            ->where('date_fin >=', $today)
            ->countAllResults();

        $recentDemandes = $congeModel
            ->select('conges.*, employes.nom as employe_nom, employes.prenom as employe_prenom, types_conge.libelle as type_libelle')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->orderBy('conges.created_at', 'DESC')
            ->limit(6)
            ->findAll();

        $absencesMois = $congeModel
            ->select('conges.*, employes.nom as employe_nom, employes.prenom as employe_prenom, types_conge.libelle as type_libelle')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.statut', 'approuvee')
            ->where('conges.date_debut >=', $monthStart)
            ->where('conges.date_debut <=', $monthEnd)
            ->orderBy('conges.date_debut', 'ASC')
            ->findAll();

        return view('admin/dashboard', [
            'pageTitle' => 'Vue d\'ensemble',
            'breadcrumb' => 'Administration',
            'activeMenu' => 'dashboard',
            'activeCount' => $activeCount,
            'pendingCount' => $pendingCount,
            'approvedThisMonth' => $approvedThisMonth,
            'departementCount' => $departementCount,
            'absentToday' => $absentToday,
            'recentDemandes' => $recentDemandes,
            'absencesMois' => $absencesMois,
        ]);
    }

    public function employes()
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $employeModel = new EmployeModel();
        $departementModel = new DepartementModel();
        $typeModel = new TypeCongeModel();
        $soldeModel = new SoldeModel();

        $departements = $departementModel->orderBy('nom')->findAll();
        $employes = $employeModel
            ->select('employes.*, departements.nom as departement_nom')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->orderBy('employes.nom')
            ->findAll();

        $annualType = $typeModel->like('libelle', 'annuel')->first();
        $annualMap = [];
        if ($annualType) {
            $soldes = $soldeModel
                ->where('type_conge_id', $annualType['id'])
                ->where('annee', $this->currentYear())
                ->findAll();
            foreach ($soldes as $solde) {
                $annualMap[$solde['employe_id']] = $solde;
            }
        }

        return view('admin/employes', [
            'pageTitle' => 'Gestion des employes',
            'breadcrumb' => 'Employes',
            'activeMenu' => 'employes',
            'departements' => $departements,
            'employes' => $employes,
            'annualMap' => $annualMap,
            'editEmploye' => null,
        ]);
    }

    public function employeCreate()
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $rules = [
            'prenom' => 'required',
            'nom' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[4]',
            'departement_id' => 'required|is_natural_no_zero',
            'role' => 'required',
            'date_embauche' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $employeModel = new EmployeModel();
        $email = (string) $this->request->getPost('email');
        $exists = $employeModel->where('email', $email)->first();
        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Cet email est deja utilise.');
        }

        $employeId = $employeModel->insert([
            'prenom' => (string) $this->request->getPost('prenom'),
            'nom' => (string) $this->request->getPost('nom'),
            'email' => $email,
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'departement_id' => (int) $this->request->getPost('departement_id'),
            'role' => (string) $this->request->getPost('role'),
            'date_embauche' => (string) $this->request->getPost('date_embauche'),
            'actif' => 1,
        ]);

        $typeModel = new TypeCongeModel();
        $types = $typeModel->findAll();

        $soldes = [];
        foreach ($types as $type) {
            $soldes[] = [
                'employe_id' => $employeId,
                'type_conge_id' => $type['id'],
                'annee' => $this->currentYear(),
                'jours_attribues' => (int) $type['jours_annuels'],
                'jours_pris' => 0,
            ];
        }

        if (! empty($soldes)) {
            (new SoldeModel())->insertBatch($soldes);
        }

        return redirect()->to('/admin/employes')->with('success', 'Employe cree avec succes.');
    }

    public function employeEdit(int $id)
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $employeModel = new EmployeModel();
        $departementModel = new DepartementModel();

        $employe = $employeModel->find($id);
        if (! $employe) {
            throw PageNotFoundException::forPageNotFound();
        }

        $departements = $departementModel->orderBy('nom')->findAll();
        $employes = $employeModel
            ->select('employes.*, departements.nom as departement_nom')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->orderBy('employes.nom')
            ->findAll();

        return view('admin/employes', [
            'pageTitle' => 'Gestion des employes',
            'breadcrumb' => 'Employes',
            'activeMenu' => 'employes',
            'departements' => $departements,
            'employes' => $employes,
            'annualMap' => [],
            'editEmploye' => $employe,
        ]);
    }

    public function employeUpdate(int $id)
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $rules = [
            'prenom' => 'required',
            'nom' => 'required',
            'email' => 'required|valid_email',
            'departement_id' => 'required|is_natural_no_zero',
            'role' => 'required',
            'date_embauche' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $employeModel = new EmployeModel();
        $employe = $employeModel->find($id);
        if (! $employe) {
            throw PageNotFoundException::forPageNotFound();
        }

        $email = (string) $this->request->getPost('email');
        $existing = $employeModel
            ->where('email', $email)
            ->where('id !=', $id)
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Cet email est deja utilise.');
        }

        $data = [
            'prenom' => (string) $this->request->getPost('prenom'),
            'nom' => (string) $this->request->getPost('nom'),
            'email' => $email,
            'departement_id' => (int) $this->request->getPost('departement_id'),
            'role' => (string) $this->request->getPost('role'),
            'date_embauche' => (string) $this->request->getPost('date_embauche'),
        ];

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $employeModel->update($id, $data);

        return redirect()->to('/admin/employes')->with('success', 'Employe mis a jour.');
    }

    public function employeToggle(int $id)
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $employeModel = new EmployeModel();
        $employe = $employeModel->find($id);
        if (! $employe) {
            throw PageNotFoundException::forPageNotFound();
        }

        $newStatus = ((int) $employe['actif'] === 1) ? 0 : 1;
        $employeModel->update($id, ['actif' => $newStatus]);

        return redirect()->back()->with('success', 'Statut employe mis a jour.');
    }

    public function departements()
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $departements = (new DepartementModel())->orderBy('nom')->findAll();

        return view('admin/departements', [
            'pageTitle' => 'Departements',
            'breadcrumb' => 'Departements',
            'activeMenu' => 'departements',
            'departements' => $departements,
            'editDepartement' => null,
        ]);
    }

    public function departementCreate()
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $rules = [
            'nom' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new DepartementModel())->insert([
            'nom' => (string) $this->request->getPost('nom'),
            'description' => (string) $this->request->getPost('description'),
        ]);

        return redirect()->back()->with('success', 'Departement cree.');
    }

    public function departementEdit(int $id)
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $departementModel = new DepartementModel();
        $departement = $departementModel->find($id);
        if (! $departement) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('admin/departements', [
            'pageTitle' => 'Departements',
            'breadcrumb' => 'Departements',
            'activeMenu' => 'departements',
            'departements' => $departementModel->orderBy('nom')->findAll(),
            'editDepartement' => $departement,
        ]);
    }

    public function departementUpdate(int $id)
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $departementModel = new DepartementModel();
        $departement = $departementModel->find($id);
        if (! $departement) {
            throw PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'nom' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $departementModel->update($id, [
            'nom' => (string) $this->request->getPost('nom'),
            'description' => (string) $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/departements')->with('success', 'Departement mis a jour.');
    }

    public function departementDelete(int $id)
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $employeCount = (new EmployeModel())->where('departement_id', $id)->countAllResults();
        if ($employeCount > 0) {
            return redirect()->back()->with('error', 'Departement utilise par des employes.');
        }

        (new DepartementModel())->delete($id);

        return redirect()->back()->with('success', 'Departement supprime.');
    }

    public function typesConge()
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $types = (new TypeCongeModel())->orderBy('libelle')->findAll();

        return view('admin/types_conge', [
            'pageTitle' => 'Types de conge',
            'breadcrumb' => 'Types de conge',
            'activeMenu' => 'types',
            'types' => $types,
            'editType' => null,
        ]);
    }

    public function typeCongeCreate()
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $rules = [
            'libelle' => 'required',
            'jours_annuels' => 'required|is_natural',
            'deductible' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $typeModel = new TypeCongeModel();
        $typeId = $typeModel->insert([
            'libelle' => (string) $this->request->getPost('libelle'),
            'jours_annuels' => (int) $this->request->getPost('jours_annuels'),
            'deductible' => (int) $this->request->getPost('deductible'),
        ]);

        $employes = (new EmployeModel())->findAll();
        $soldes = [];
        foreach ($employes as $employe) {
            $soldes[] = [
                'employe_id' => $employe['id'],
                'type_conge_id' => $typeId,
                'annee' => $this->currentYear(),
                'jours_attribues' => (int) $this->request->getPost('jours_annuels'),
                'jours_pris' => 0,
            ];
        }

        if (! empty($soldes)) {
            (new SoldeModel())->insertBatch($soldes);
        }

        return redirect()->back()->with('success', 'Type de conge cree.');
    }

    public function typeCongeEdit(int $id)
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $typeModel = new TypeCongeModel();
        $type = $typeModel->find($id);
        if (! $type) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('admin/types_conge', [
            'pageTitle' => 'Types de conge',
            'breadcrumb' => 'Types de conge',
            'activeMenu' => 'types',
            'types' => $typeModel->orderBy('libelle')->findAll(),
            'editType' => $type,
        ]);
    }

    public function typeCongeUpdate(int $id)
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $rules = [
            'libelle' => 'required',
            'jours_annuels' => 'required|is_natural',
            'deductible' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $typeModel = new TypeCongeModel();
        $type = $typeModel->find($id);
        if (! $type) {
            throw PageNotFoundException::forPageNotFound();
        }

        $typeModel->update($id, [
            'libelle' => (string) $this->request->getPost('libelle'),
            'jours_annuels' => (int) $this->request->getPost('jours_annuels'),
            'deductible' => (int) $this->request->getPost('deductible'),
        ]);

        return redirect()->to('/admin/types-conge')->with('success', 'Type de conge mis a jour.');
    }

    public function typeCongeDelete(int $id)
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $congeCount = (new CongeModel())->where('type_conge_id', $id)->countAllResults();
        $soldeCount = (new SoldeModel())->where('type_conge_id', $id)->countAllResults();
        if ($congeCount > 0 || $soldeCount > 0) {
            return redirect()->back()->with('error', 'Type de conge utilise dans des demandes.');
        }

        (new TypeCongeModel())->delete($id);

        return redirect()->back()->with('success', 'Type de conge supprime.');
    }

    public function soldes()
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $year = $this->currentYear();
        $soldes = (new SoldeModel())
            ->select('soldes.*, employes.nom as employe_nom, employes.prenom as employe_prenom, types_conge.libelle as type_libelle')
            ->join('employes', 'employes.id = soldes.employe_id')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.annee', $year)
            ->orderBy('employes.nom')
            ->findAll();

        $employes = (new EmployeModel())->orderBy('nom')->findAll();
        $types = (new TypeCongeModel())->orderBy('libelle')->findAll();

        return view('admin/soldes', [
            'pageTitle' => 'Soldes annuels',
            'breadcrumb' => 'Soldes',
            'activeMenu' => 'soldes',
            'soldes' => $soldes,
            'employes' => $employes,
            'types' => $types,
            'year' => $year,
        ]);
    }

    public function soldesAdjust()
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $rules = [
            'employe_id' => 'required|is_natural_no_zero',
            'type_conge_id' => 'required|is_natural_no_zero',
            'annee' => 'required|is_natural_no_zero',
            'jours_attribues' => 'required|is_natural',
            'jours_pris' => 'required|is_natural',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $employeId = (int) $this->request->getPost('employe_id');
        $typeId = (int) $this->request->getPost('type_conge_id');
        $annee = (int) $this->request->getPost('annee');
        $attribues = (int) $this->request->getPost('jours_attribues');
        $pris = (int) $this->request->getPost('jours_pris');

        if ($pris > $attribues) {
            return redirect()->back()->withInput()->with('error', 'Les jours pris ne peuvent pas depasser les jours attribues.');
        }

        $soldeModel = new SoldeModel();
        $existing = $soldeModel
            ->where('employe_id', $employeId)
            ->where('type_conge_id', $typeId)
            ->where('annee', $annee)
            ->first();

        if ($existing) {
            $soldeModel->update($existing['id'], [
                'jours_attribues' => $attribues,
                'jours_pris' => $pris,
            ]);
        } else {
            $soldeModel->insert([
                'employe_id' => $employeId,
                'type_conge_id' => $typeId,
                'annee' => $annee,
                'jours_attribues' => $attribues,
                'jours_pris' => $pris,
            ]);
        }

        return redirect()->back()->with('success', 'Solde mis a jour.');
    }

    public function historique()
    {
        if ($redirect = $this->guardRoles(['admin'])) {
            return $redirect;
        }

        $demandes = (new CongeModel())
            ->select('conges.*, employes.nom as employe_nom, employes.prenom as employe_prenom, types_conge.libelle as type_libelle')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->orderBy('conges.created_at', 'DESC')
            ->findAll();

        return view('admin/historique', [
            'pageTitle' => 'Historique',
            'breadcrumb' => 'Historique',
            'activeMenu' => 'historique',
            'demandes' => $demandes,
        ]);
    }

    private function currentYear(): int
    {
        return (int) date('Y');
    }
}
