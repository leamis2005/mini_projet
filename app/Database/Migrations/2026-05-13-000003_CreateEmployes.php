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
                'constraint' => 20,
                'default' => 'employe',
            ],
            'departement_id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
            ],
            'date_embauche' => [
                'type' => 'DATE',
            ],
            'actif' => [
                'type' => 'INTEGER',
                'constraint' => 1,
                'default' => 1,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('departement_id');
        $this->forge->addForeignKey('departement_id', 'departements', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('employes', true);
    }

    public function down()
    {
        $this->forge->dropTable('employes', true);
    }
}
