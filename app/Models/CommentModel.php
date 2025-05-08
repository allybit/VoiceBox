<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'post_id', 
        'user_id', 
        'content', 
        'status', 
        'created_at', 
        'updated_at'
    ];
    
    protected $useTimestamps = false;
    
    public function getCommentsByPostId($postId, $status = null)
    {
        $builder = $this->db->table('comments');
        $builder->select('comments.*, users.username');
        $builder->join('users', 'users.id = comments.user_id');
        $builder->where('comments.post_id', $postId);
        
        if ($status) {
            $builder->where('comments.status', $status);
        }
        
        $builder->orderBy('comments.created_at', 'ASC');
        $query = $builder->get();
        
        return $query->getResultArray();
    }
    
    public function getCommentCount($postId, $status = 'approved')
    {
        return $this->where('post_id', $postId)
                    ->where('status', $status)
                    ->countAllResults();
    }
}
