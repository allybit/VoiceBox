<?php

namespace App\Models;

use CodeIgniter\Model;

class VoteModel extends Model
{
    protected $table = 'votes';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'post_id', 
        'user_id', 
        'vote_value', 
        'created_at', 
        'updated_at'
    ];
    
    protected $useTimestamps = false;
    
    public function getVoteCount($postId)
    {
        $result = $this->selectSum('vote_value')
                      ->where('post_id', $postId)
                      ->first();
        
        // If no votes, return 0
        if (!$result || !isset($result['vote_value']) || is_null($result['vote_value'])) {
            return 0;
        }
        
        return (int)$result['vote_value'];
    }
    
    public function getUserVote($postId, $userId)
    {
        $vote = $this->where('post_id', $postId)
                    ->where('user_id', $userId)
                    ->first();
        
        if (!$vote) {
            return 0;
        }
        
        return (int)$vote['vote_value'];
    }
    
    // Add a direct method to toggle votes
    public function toggleVote($postId, $userId, $voteType)
    {
        $voteValue = ($voteType === 'upvote') ? 1 : -1;
        
        // Check if user has already voted
        $existingVote = $this->where('post_id', $postId)
                            ->where('user_id', $userId)
                            ->first();
        
        if ($existingVote) {
            // If same vote type, remove the vote (toggle)
            if (($existingVote['vote_value'] == 1 && $voteType === 'upvote') || 
                ($existingVote['vote_value'] == -1 && $voteType === 'downvote')) {
                
                // Use direct query to ensure deletion works
                $this->db->table($this->table)
                         ->where('id', $existingVote['id'])
                         ->delete();
                
                return 'removed';
            } else {
                // Change vote
                $this->db->table($this->table)
                         ->where('id', $existingVote['id'])
                         ->update([
                             'vote_value' => $voteValue,
                             'updated_at' => date('Y-m-d H:i:s')
                         ]);
                
                return 'changed';
            }
        } else {
            // Add new vote
            $this->db->table($this->table)
                     ->insert([
                         'post_id' => $postId,
                         'user_id' => $userId,
                         'vote_value' => $voteValue,
                         'created_at' => date('Y-m-d H:i:s'),
                         'updated_at' => date('Y-m-d H:i:s')
                     ]);
            
            return 'added';
        }
    }
}
