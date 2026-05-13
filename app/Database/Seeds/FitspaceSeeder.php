<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FitspaceSeeder extends Seeder
{
    public function run()
    {
        $year = (int) date('Y');
        $today = date('Y-m-d');

        $employes = [
            [
                'nom' => 'Admin',
                'prenom' => 'Super',
                'email' => 'admin@fitspace.local',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'departement_id' => null,
                'date_embauche' => $today,
                'actif' => 1,
            ],
            [
                'nom' => 'Dupont',
                'prenom' => 'Marie',
                'email' => 'marie.dupont@fitspace.local',
                'password' => password_hash('employe123', PASSWORD_DEFAULT),
                'role' => 'employe',
                'departement_id' => null,
                'date_embauche' => $today,
                'actif' => 1,
            ],
            [
                'nom' => 'Martin',
                'prenom' => 'Jean',
                'email' => 'jean.martin@fitspace.local',
                'password' => password_hash('employe123', PASSWORD_DEFAULT),
                'role' => 'employe',
                'departement_id' => null,
                'date_embauche' => $today,
                'actif' => 1,
            ],
        ];

        $employeIds = [];
        $employeTable = $this->db->table('employes');
        foreach ($employes as $employe) {
            $employeTable->insert($employe);
            $employeIds[] = $this->db->insertID();
        }

        $typesConge = [
            [
                'libelle' => 'Conge annuel',
                'jours_annuels' => 30,
                'deductible' => 1,
            ],
            [
                'libelle' => 'Maladie',
                'jours_annuels' => 15,
                'deductible' => 1,
            ],
            [
                'libelle' => 'Evenement familial',
                'jours_annuels' => 5,
                'deductible' => 0,
            ],
        ];

        $typeIds = [];
        $typeTable = $this->db->table('types_conge');
        foreach ($typesConge as $typeConge) {
            $typeTable->insert($typeConge);
            $typeIds[] = $this->db->insertID();
        }

        $soldes = [];
        foreach ($employeIds as $employeId) {
            foreach ($typeIds as $index => $typeId) {
                $soldes[] = [
                    'employe_id' => $employeId,
                    'type_conge_id' => $typeId,
                    'annee' => $year,
                    'jours_attribues' => $typesConge[$index]['jours_annuels'],
                    'jours_pris' => 0,
                ];
            }
        }

        if (! empty($soldes)) {
            $this->db->table('soldes')->insertBatch($soldes);
        }
    }
}
