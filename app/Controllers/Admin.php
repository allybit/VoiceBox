<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\TagModel;
use App\Models\AnnouncementModel;
use CodeIgniter\Controller;

class Admin extends BaseController
{
    protected $userModel;
    protected $postModel;
    protected $tagModel;
    protected $announcementModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->postModel = new PostModel();
        $this->tagModel = new TagModel();
        $this->announcementModel = new AnnouncementModel();
    }
    
    public function dashboard()
    {
        $userModel = new \App\Models\UserModel();
        $postModel = new \App\Models\PostModel();
        $tagModel = new \App\Models\TagModel();
        $announcementModel = new \App\Models\AnnouncementModel();
        
        // Get verification statistics
        $totalStudents = $userModel->where('role', 'student')->countAllResults();
        $verifiedStudents = $userModel->where('role', 'student')->where('verified', 1)->countAllResults();
        $pendingStudents = $userModel->where('role', 'student')->where('verified', 0)->countAllResults();
        
        // Calculate verification percentage
        $verificationPercentage = ($totalStudents > 0) ? round(($verifiedStudents / $totalStudents) * 100) : 0;
        
        // Get recent registrations
        $recentUsers = $userModel->where('role', 'student')
                               ->orderBy('created_at', 'DESC')
                               ->limit(5)
                               ->findAll();
        
        $data = [
            'title' => 'Admin Dashboard',
            'totalUsers' => $userModel->countAll(),
            'totalStudents' => $totalStudents,
            'verifiedStudents' => $verifiedStudents,
            'pendingStudents' => $pendingStudents,
            'verificationPercentage' => $verificationPercentage,
            'recentUsers' => $recentUsers,
            'totalPosts' => $postModel->countAll(),
            'pendingPosts' => $postModel->where('status', 'pending')->countAllResults(),
            'approvedPosts' => $postModel->where('status', 'approved')->countAllResults(),
            'rejectedPosts' => $postModel->where('status', 'rejected')->countAllResults(),
            'totalTags' => $tagModel->countAll(),
            'totalAnnouncements' => $announcementModel->countAll(),
        ];
        
        return view('admin/dashboard', $data);
    }
    
    public function pendingPosts()
    {
        $posts = $this->postModel->where('status', 'pending')
                               ->orderBy('created_at', 'DESC')
                               ->findAll();
        
        // Get tags for each post
        foreach ($posts as &$post) {
            $post['tags'] = $this->postModel->getTags($post['id']);
        }
        
        $data = [
            'title' => 'Pending Posts',
            'posts' => $posts,
        ];
        
        return view('admin/pending_posts', $data);
    }
    
    public function approvedPosts()
    {
        $posts = $this->postModel->where('status', 'approved')
                               ->orderBy('created_at', 'DESC')
                               ->findAll();
        
        // Get tags for each post
        foreach ($posts as &$post) {
            $post['tags'] = $this->postModel->getTags($post['id']);
        }
        
        $data = [
            'title' => 'Approved Posts',
            'posts' => $posts,
        ];
        
        return view('admin/approved_posts', $data);
    }
    
    public function approvePost($id)
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }
        
        $this->postModel->update($id, ['status' => 'approved']);
        
        return redirect()->back()->with('success', 'Post approved successfully');
    }
    
    public function rejectPost($id)
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }
        
        $this->postModel->update($id, ['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'Post rejected successfully');
    }
    
    public function deletePost($id)
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }
        
        $this->postModel->delete($id);
        
        return redirect()->back()->with('success', 'Post deleted successfully');
    }
    
    public function manageTags()
    {
        $data = [
            'title' => 'Manage Tags',
            'tags' => $this->tagModel->findAll(),
        ];
        
        return view('admin/manage_tags', $data);
    }
    
    public function addTag()
    {
        $rules = [
            'name' => 'required|min_length[2]|is_unique[tags.name]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }
        
        $this->tagModel->insert([
            'name' => $this->request->getPost('name'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        return redirect()->to('admin/manage-tags')->with('success', 'Tag added successfully');
    }
    
    public function editTag($id)
    {
        $tag = $this->tagModel->find($id);
        
        if (!$tag) {
            return redirect()->to('admin/manage-tags')->with('error', 'Tag not found');
        }
        
        $data = [
            'title' => 'Edit Tag',
            'tag' => $tag,
        ];
        
        return view('admin/edit_tag', $data);
    }
    
    public function updateTag($id)
    {
        $rules = [
            'name' => "required|min_length[2]|is_unique[tags.name,id,$id]",
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }
        
        $this->tagModel->update($id, [
            'name' => $this->request->getPost('name'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        return redirect()->to('admin/manage-tags')->with('success', 'Tag updated successfully');
    }
    
    public function deleteTag($id)
    {
        $tag = $this->tagModel->find($id);
        
        if (!$tag) {
            return redirect()->to('admin/manage-tags')->with('error', 'Tag not found');
        }
        
        $this->tagModel->delete($id);
        
        return redirect()->to('admin/manage-tags')->with('success', 'Tag deleted successfully');
    }
    
    public function announcements()
    {
        $data = [
            'title' => 'Announcements',
            'announcements' => $this->announcementModel->orderBy('created_at', 'DESC')->findAll(),
        ];
        
        return view('admin/announcements', $data);
    }
    
    public function createAnnouncement()
    {
        $data = [
            'title' => 'Create Announcement',
        ];
        
        return view('admin/create_announcement', $data);
    }
    
    public function storeAnnouncement()
    {
        $rules = [
            'title' => 'required|min_length[3]',
            'content' => 'required|min_length[10]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }
        
        $this->announcementModel->insert([
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'user_id' => session()->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        return redirect()->to('admin/announcements')->with('success', 'Announcement created successfully');
    }
    
    public function editAnnouncement($id)
    {
        $announcement = $this->announcementModel->find($id);
        
        if (!$announcement) {
            return redirect()->to('admin/announcements')->with('error', 'Announcement not found');
        }
        
        $data = [
            'title' => 'Edit Announcement',
            'announcement' => $announcement,
        ];
        
        return view('admin/edit_announcement', $data);
    }
    
    public function updateAnnouncement($id)
    {
        $rules = [
            'title' => 'required|min_length[3]',
            'content' => 'required|min_length[10]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }
        
        $this->announcementModel->update($id, [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        return redirect()->to('admin/announcements')->with('success', 'Announcement updated successfully');
    }
    
    public function deleteAnnouncement($id)
    {
        $announcement = $this->announcementModel->find($id);
        
        if (!$announcement) {
            return redirect()->to('admin/announcements')->with('error', 'Announcement not found');
        }
        
        $this->announcementModel->delete($id);
        
        return redirect()->to('admin/announcements')->with('success', 'Announcement deleted successfully');
    }
}
