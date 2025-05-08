<?php

namespace App\Controllers;

use App\Models\CommentModel;
use App\Models\PostModel;
use CodeIgniter\Controller;

class Comment extends BaseController
{
    protected $commentModel;
    protected $postModel;
    
    public function __construct()
    {
        $this->commentModel = new CommentModel();
        $this->postModel = new PostModel();
    }
    
    public function add()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'You must be logged in to comment');
        }
        
        $rules = [
            'post_id' => 'required|numeric',
            'content' => 'required|min_length[5]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }
        
        $postId = $this->request->getPost('post_id');
        $content = $this->request->getPost('content');
        $userId = session()->get('id');
        
        // Check if post exists
        $post = $this->postModel->find($postId);
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }
        
        // All comments are automatically approved
        $data = [
            'post_id' => $postId,
            'user_id' => $userId,
            'content' => $content,
            'status' => 'approved',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $this->commentModel->insert($data);
        
        return redirect()->to('post/' . $postId)->with('success', 'Comment added successfully');
    }
    
    public function delete($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'You must be logged in to delete a comment');
        }
        
        $comment = $this->commentModel->find($id);
        
        if (!$comment) {
            return redirect()->back()->with('error', 'Comment not found');
        }
        
        // Check if user is the comment owner or an admin
        if ($comment['user_id'] != session()->get('id') && session()->get('role') !== 'admin') {
            return redirect()->back()->with('error', 'You do not have permission to delete this comment');
        }
        
        $postId = $comment['post_id'];
        
        $this->commentModel->delete($id);
        
        return redirect()->to('post/' . $postId)->with('success', 'Comment deleted successfully');
    }
}
