<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixVotesTable extends Migration
{
    public function up()
    {
        // Drop the votes table if it exists
        if ($this->db->tableExists('votes')) {
            $this->forge->dropTable('votes', true);
        }
        
        // Create a new votes table with correct structure
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'post_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'vote_value' => [
                'type' => 'INT',
                'constraint' => 1,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        
        $this->forge->addKey('id', true);
        // Use a regular index instead of a unique key to avoid constraint issues
        $this->forge->addKey(['post_id', 'user_id']);
        $this->forge->createTable('votes');
    }

    public function down()
    {
        $this->forge->dropTable('votes', true);
    }
}
