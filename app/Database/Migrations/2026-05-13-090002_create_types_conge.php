<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypesConge extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'libelle' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'jours_annuels' => [
                'type' => 'INTEGER',
            ],
            'deductible' => [
                'type' => 'BOOLEAN',
                'default' => 1,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('types_conge');
    }

    public function down()
    {
        $this->forge->dropTable('types_conge');
    }
}
