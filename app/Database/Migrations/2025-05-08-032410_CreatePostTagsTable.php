<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostTagsTable extends Migration
{
    public function up()
    {
        // Post Tags Table (Many-to-Many relationship)
        $this->forge->addField([
            'post_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'tag_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey(['post_id', 'tag_id'], true);
        $this->forge->addForeignKey('post_id', 'posts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tag_id', 'tags', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('post_tags');
    }

    public function down()
    {
        $this->forge->dropTable('post_tags');
    }
}