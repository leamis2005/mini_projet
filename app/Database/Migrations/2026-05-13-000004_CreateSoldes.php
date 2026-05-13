<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSoldes extends Migration
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
            'annee' => [
                'type' => 'INTEGER',
            ],
            'jours_attribues' => [
                'type' => 'INTEGER',
                'default' => 0,
            ],
            'jours_pris' => [
                'type' => 'INTEGER',
                'default' => 0,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['employe_id', 'type_conge_id', 'annee']);
        $this->forge->addKey('employe_id');
        $this->forge->addKey('type_conge_id');
        $this->forge->addForeignKey('employe_id', 'employes', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('soldes', true);
    }

    public function down()
    {
        $this->forge->dropTable('soldes', true);
    }
}
