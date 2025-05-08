<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class User extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    public function verifyUsers()
    {
        // Check if current user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'You do not have permission to verify users');
        }
        
        $users = $this->userModel->where('role', 'student')
                               ->orderBy('created_at', 'DESC')
                               ->findAll();
        
        $data = [
            'title' => 'Verify Users',
            'users' => $users
        ];
        
        return view('users/verify_users', $data);
    }
    
    public function verifyUser($id)
    {
        // Check if current user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('verify-users')->with('error', 'You do not have permission to verify users');
        }
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('verify-users')->with('error', 'User not found');
        }
        
        $this->userModel->update($id, ['verified' => 1]);
        
        return redirect()->to('verify-users')->with('success', 'User verified successfully');
    }
    
    public function unverifyUser($id)
    {
        // Check if current user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('verify-users')->with('error', 'You do not have permission to unverify users');
        }
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('verify-users')->with('error', 'User not found');
        }
        
        $this->userModel->update($id, ['verified' => 0]);
        
        return redirect()->to('verify-users')->with('success', 'User unverified successfully');
    }
    
    public function deleteUser($id)
    {
        // Check if current user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('verify-users')->with('error', 'You do not have permission to delete users');
        }
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('verify-users')->with('error', 'User not found');
        }
        
        // Don't allow deleting yourself
        if ($user['id'] == session()->get('id')) {
            return redirect()->to('verify-users')->with('error', 'You cannot delete your own account');
        }
        
        $this->userModel->delete($id);
        
        return redirect()->to('verify-users')->with('success', 'User deleted successfully');
    }
    
    public function verificationHistory($id)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'You do not have permission to view verification history');
        }
        
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('verify-users')->with('error', 'User not found');
        }
        
        $verificationHistoryModel = new \App\Models\VerificationHistoryModel();
        $history = $verificationHistoryModel->getHistoryByUser($id);
        
        $data = [
            'title' => 'Verification History',
            'user' => $user,
            'history' => $history
        ];
        
        return view('users/verification_history', $data);
    }
}
