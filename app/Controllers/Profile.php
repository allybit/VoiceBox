<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PostModel;

class Profile extends BaseController
{
    protected $userModel;
    protected $postModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->postModel = new PostModel();
    }
    
    public function index()
    {
        $userId = session()->get('id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/')->with('error', 'User not found');
        }
        
        // Get user's post statistics
        $totalPosts = $this->postModel->where('user_id', $userId)->countAllResults();
        $approvedPosts = $this->postModel->where('user_id', $userId)->where('status', 'approved')->countAllResults();
        $pendingPosts = $this->postModel->where('user_id', $userId)->where('status', 'pending')->countAllResults();
        $rejectedPosts = $this->postModel->where('user_id', $userId)->where('status', 'rejected')->countAllResults();
        
        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'totalPosts' => $totalPosts,
            'approvedPosts' => $approvedPosts,
            'pendingPosts' => $pendingPosts,
            'rejectedPosts' => $rejectedPosts
        ];
        
        return view('profile/index', $data);
    }
    
    public function update()
    {
        $userId = session()->get('id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/')->with('error', 'User not found');
        }
        
        $rules = [
            'username' => "required|min_length[3]|is_unique[users.username,id,$userId]",
            'email' => "required|valid_email|is_unique[users.email,id,$userId]",
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }
        
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->userModel->update($userId, $data);
        
        // Update session data
        session()->set([
            'username' => $data['username'],
            'email' => $data['email']
        ]);
        
        return redirect()->to('profile')->with('success', 'Profile updated successfully');
    }
    
    public function changePassword()
    {
        $userId = session()->get('id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/')->with('error', 'User not found');
        }
        
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }
        
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        
        // Verify current password
        if (!$this->userModel->verifyPassword($currentPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect');
        }
        
        // Update password
        $this->userModel->update($userId, [
            'password' => $newPassword,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->to('profile')->with('success', 'Password changed successfully');
    }
}
