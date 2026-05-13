<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FitspaceSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;
        $year = (int) date('Y');

        $departements = [
            ['nom' => 'IT', 'description' => 'Tech et support'],
            ['nom' => 'RH', 'description' => 'Ressources humaines'],
            ['nom' => 'Finance', 'description' => 'Comptabilite et budget'],
        ];
        $db->table('departements')->insertBatch($departements);

        $types = [
            ['libelle' => 'Conge annuel', 'jours_annuels' => 30, 'deductible' => 1],
            ['libelle' => 'Conge maladie', 'jours_annuels' => 10, 'deductible' => 1],
            ['libelle' => 'Conge special', 'jours_annuels' => 5, 'deductible' => 1],
        ];
        $db->table('types_conge')->insertBatch($types);

        $deptRows = $db->table('departements')->get()->getResultArray();
        $deptByName = [];
        foreach ($deptRows as $dept) {
            $deptByName[$dept['nom']] = $dept['id'];
        }

        $employes = [
            [
                'nom' => 'Admin',
                'prenom' => 'TechMada',
                'email' => 'admin@techmada.mg',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'departement_id' => $deptByName['IT'] ?? 1,
                'date_embauche' => '2024-01-02',
                'actif' => 1,
            ],
            [
                'nom' => 'Rabe',
                'prenom' => 'Marie',
                'email' => 'rh@techmada.mg',
                'password' => password_hash('rh123', PASSWORD_DEFAULT),
                'role' => 'rh',
                'departement_id' => $deptByName['RH'] ?? 2,
                'date_embauche' => '2023-05-15',
                'actif' => 1,
            ],
            [
                'nom' => 'Rakoto',
                'prenom' => 'Soa',
                'email' => 'employe@techmada.mg',
                'password' => password_hash('emp123', PASSWORD_DEFAULT),
                'role' => 'employe',
                'departement_id' => $deptByName['IT'] ?? 1,
                'date_embauche' => '2022-03-01',
                'actif' => 1,
            ],
        ];
        $db->table('employes')->insertBatch($employes);

        $typesRows = $db->table('types_conge')->get()->getResultArray();
        $employeRows = $db->table('employes')->get()->getResultArray();
        $soldes = [];

        foreach ($employeRows as $emp) {
            foreach ($typesRows as $type) {
                $soldes[] = [
                    'employe_id' => $emp['id'],
                    'type_conge_id' => $type['id'],
                    'annee' => $year,
                    'jours_attribues' => (int) $type['jours_annuels'],
                    'jours_pris' => 0,
                ];
            }
        }

        if (! empty($soldes)) {
            $db->table('soldes')->insertBatch($soldes);
        }
    }
}
