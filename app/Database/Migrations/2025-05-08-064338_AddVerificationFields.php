<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVerificationFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'student_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'role'
            ],
            'verified' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'student_id'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'student_id');
        $this->forge->dropColumn('users', 'verified');
    }
}