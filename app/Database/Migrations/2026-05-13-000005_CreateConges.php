<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConges extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'employe_id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
            ],
            'type_conge_id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
            ],
            'date_debut' => [
                'type' => 'DATE',
            ],
            'date_fin' => [
                'type' => 'DATE',
            ],
            'nb_jours' => [
                'type' => 'INTEGER',
            ],
            'motif' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'statut' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'en_attente',
            ],
            'commentaire_rh' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'traite_par' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('employe_id');
        $this->forge->addKey('type_conge_id');
        $this->forge->addKey('traite_par');
        $this->forge->addForeignKey('employe_id', 'employes', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('traite_par', 'employes', 'id', 'SET NULL', 'RESTRICT');
        $this->forge->createTable('conges', true);
    }

    public function down()
    {
        $this->forge->dropTable('conges', true);
    }
}
