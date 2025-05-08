<?php

namespace App\Models;

use CodeIgniter\Model;

class VerificationHistoryModel extends Model
{
    protected $table = 'verification_history';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'user_id', 
        'admin_id', 
        'status', 
        'notes', 
        'created_at'
    ];
    
    protected $useTimestamps = false;
    
    public function getHistoryByUser($userId)
    {
        return $this->select('verification_history.*, admin.username as admin_name')
                    ->join('users as admin', 'admin.id = verification_history.admin_id')
                    ->where('verification_history.user_id', $userId)
                    ->orderBy('verification_history.created_at', 'DESC')
                    ->findAll();
    }
    
    public function addHistory($userId, $adminId, $status, $notes = null)
    {
        return $this->insert([
            'user_id' => $userId,
            'admin_id' => $adminId,
            'status' => $status,
            'notes' => $notes,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
