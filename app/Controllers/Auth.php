<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    public function login()
    {
        // If already logged in, redirect to home
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        
        return view('auth/login', ['title' => 'Login']);
    }
    
    public function attemptLogin()
    {
        $rules = [
            'login' => 'required',
            'password' => 'required',
        ];
        
        if (!$this->validate($rules)) {
            // Join all error messages into a single string
            $errorString = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errorString);
        }
        
        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');
        
        $userModel = new UserModel();
        $user = $userModel->findUserByEmailOrUsername($login);
        
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'User not found');
        }
        
        if (!$userModel->verifyPassword($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Incorrect password');
        }
        
        // Check if user is verified (for students)
        if ($user['role'] === 'student' && isset($user['verified']) && !$user['verified']) {
            return redirect()->back()->withInput()->with('error', 'Your account is pending verification. Please wait for admin approval.');
        }
        
        $this->setUserSession($user);
        
        return redirect()->to('/')->with('success', 'Login successful');
    }
    
    public function register()
    {
        // If already logged in, redirect to home
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        
        return view('auth/register', ['title' => 'Register']);
    }
    
    public function attemptRegister()
    {
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'student_id' => 'required|min_length[5]|is_unique[users.student_id]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];
        
        if (!$this->validate($rules)) {
            // Join all error messages into a single string
            $errorString = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errorString);
        }
        
        // Check if email domain is from the school
        $email = $this->request->getPost('email');
        $allowedDomains = ['school.edu', 'university.edu']; // Add your school domains here
        
        $emailDomain = substr(strrchr($email, "@"), 1);
        if (!in_array($emailDomain, $allowedDomains)) {
            return redirect()->back()->withInput()->with('error', 'Please use your school email address');
        }
        
        $userModel = new UserModel();
        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $email,
            'student_id' => $this->request->getPost('student_id'),
            'password' => $this->request->getPost('password'),
            'role' => 'student', // Default role
            'verified' => 0, // Not verified by default
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $userModel->insert($userData);
        
        return redirect()->to('login')->with('success', 'Registration successful! Your account is pending verification by an administrator.');
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('login')->with('success', 'Logout successful');
    }
    
    private function setUserSession($user)
    {
        session()->set([
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'isLoggedIn' => true,
        ]);
    }
}
