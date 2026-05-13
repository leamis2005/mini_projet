<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'prenom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'departement_id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'null' => true,
            ],
            'date_embauche' => [
                'type' => 'DATE',
            ],
            'actif' => [
                'type' => 'BOOLEAN',
                'default' => 1,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('email', true);
        $this->forge->addKey('departement_id');
        $this->forge->addForeignKey('departement_id', 'departements', 'id', 'SET NULL', 'RESTRICT');
        $this->forge->createTable('employes');
    }

    public function down()
    {
        $this->forge->dropTable('employes');
    }
}
