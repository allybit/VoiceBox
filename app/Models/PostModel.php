<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'title', 
        'content', 
        'user_id', 
        'status', 
        'created_at', 
        'updated_at'
    ];
    
    protected $useTimestamps = false;
    
    public function getTags($postId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('post_tags');
        $builder->select('tags.*');
        $builder->join('tags', 'tags.id = post_tags.tag_id');
        $builder->where('post_tags.post_id', $postId);
        $query = $builder->get();
        
        return $query->getResultArray();
    }
    
    public function getPostsByTag($tagId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('posts');
        $builder->select('posts.*, users.username');
        $builder->join('post_tags', 'posts.id = post_tags.post_id');
        $builder->join('users', 'posts.user_id = users.id', 'left');
        $builder->where('post_tags.tag_id', $tagId);
        $builder->where('posts.status', 'approved');
        $builder->orderBy('posts.created_at', 'DESC');
        $query = $builder->get();
        
        $posts = $query->getResultArray();
        
        // Get tags for each post
        foreach ($posts as &$post) {
            $post['tags'] = $this->getTags($post['id']);
            
            // Get vote count
            $voteModel = new VoteModel();
            $post['vote_count'] = $voteModel->getVoteCount($post['id']);
            
            // Check if current user has voted
            if (session()->get('isLoggedIn')) {
                $post['user_vote'] = $voteModel->getUserVote($post['id'], session()->get('id'));
            } else {
                $post['user_vote'] = 0;
            }
        }
        
        return $posts;
    }
    
    // Add other methods as needed...
}
