<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\TagModel;
use App\Models\VoteModel;
use App\Models\CommentModel;
use Config\Database;

class Post extends BaseController
{
    protected $postModel;
    protected $tagModel;
    protected $voteModel;
    protected $commentModel;
    protected $db;
    
    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->tagModel = new TagModel();
        $this->voteModel = new VoteModel();
        $this->commentModel = new CommentModel();
        $this->db = Database::connect();
    }
    
    public function show($id)
    {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return redirect()->to('/')->with('error', 'Post not found');
        }
        
        // Get tags for the post
        $post['tags'] = $this->postModel->getTags($id);
        
        // Get vote count
        $post['vote_count'] = $this->voteModel->getVoteCount($id);
        
        // Check if current user has voted
        if (session()->get('isLoggedIn')) {
            $post['user_vote'] = $this->voteModel->getUserVote($id, session()->get('id'));
        } else {
            $post['user_vote'] = 0;
        }
        
        // Get comments for the post
        $comments = $this->commentModel->getCommentsByPostId($id);

        $data = [
            'title' => $post['title'],
            'post' => $post,
            'comments' => $comments
        ];
        
        return view('post/show', $data);
    }
    
    public function create()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'You must be logged in to create a post');
        }
        
        $data = [
            'title' => 'Create Post',
            'tags' => $this->tagModel->findAll()
        ];
        
        return view('post/create', $data);
    }
    
    public function store()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'You must be logged in to create a post');
        }
        
        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'content' => 'required|min_length[10]',
            'tags' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }
        
        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');
        $tags = $this->request->getPost('tags');
        $userId = session()->get('id');
        
        // Determine post status based on user role
        $status = (session()->get('role') === 'admin') ? 'approved' : 'pending';
        
        $postId = $this->postModel->insert([
            'title' => $title,
            'content' => $content,
            'user_id' => $userId,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Add tags to the post
        if (is_array($tags)) {
            foreach ($tags as $tagId) {
                $this->db->table('post_tags')->insert([
                    'post_id' => $postId,
                    'tag_id' => $tagId
                ]);
            }
        }
        
        $message = ($status === 'approved') 
            ? 'Post created successfully' 
            : 'Post submitted successfully and is pending approval';
        
        return redirect()->to('post/my-posts')->with('success', $message);
    }
    
    public function myPosts()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'You must be logged in to view your posts');
        }
        
        $userId = session()->get('id');
        
        $posts = $this->postModel->where('user_id', $userId)
                               ->orderBy('created_at', 'DESC')
                               ->findAll();
        
        // Get tags for each post
        foreach ($posts as &$post) {
            $post['tags'] = $this->postModel->getTags($post['id']);
        }
        
        $data = [
            'title' => 'My Posts',
            'posts' => $posts
        ];
        
        return view('post/my_posts', $data);
    }
    
    public function edit($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'You must be logged in to edit a post');
        }
        
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return redirect()->to('post/my-posts')->with('error', 'Post not found');
        }
        
        // Check if user is the post owner or an admin
        if ($post['user_id'] != session()->get('id') && session()->get('role') !== 'admin') {
            return redirect()->to('post/my-posts')->with('error', 'You do not have permission to edit this post');
        }
        
        // Get tags for the post
        $post['tags'] = $this->postModel->getTags($id);
        
        // Get all tag IDs for the post
        $postTagIds = [];
        foreach ($post['tags'] as $tag) {
            $postTagIds[] = $tag['id'];
        }
        
        $data = [
            'title' => 'Edit Post',
            'post' => $post,
            'tags' => $this->tagModel->findAll(),
            'postTagIds' => $postTagIds
        ];
        
        return view('post/edit', $data);
    }
    
    public function update($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'You must be logged in to update a post');
        }
        
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return redirect()->to('post/my-posts')->with('error', 'Post not found');
        }
        
        // Check if user is the post owner or an admin
        if ($post['user_id'] != session()->get('id') && session()->get('role') !== 'admin') {
            return redirect()->to('post/my-posts')->with('error', 'You do not have permission to update this post');
        }
        
        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'content' => 'required|min_length[10]',
            'tags' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }
        
        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');
        $tags = $this->request->getPost('tags');
        
        // Determine post status based on user role and current status
        $status = $post['status'];
        if ($post['status'] === 'rejected' && session()->get('role') !== 'admin') {
            $status = 'pending'; // If a student edits a rejected post, it goes back to pending
        }
        
        $this->postModel->update($id, [
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Remove all existing tags for the post
        $this->db->table('post_tags')->where('post_id', $id)->delete();
        
        // Add new tags to the post
        if (is_array($tags)) {
            foreach ($tags as $tagId) {
                $this->db->table('post_tags')->insert([
                    'post_id' => $id,
                    'tag_id' => $tagId
                ]);
            }
        }
        
        return redirect()->to('post/' . $id)->with('success', 'Post updated successfully');
    }
    
    public function delete($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login')->with('error', 'You must be logged in to delete a post');
        }
        
        $post = $this->postModel->find($id);
        
        if (!$post) {
            return redirect()->to('post/my-posts')->with('error', 'Post not found');
        }
        
        // Check if user is the post owner or an admin
        if ($post['user_id'] != session()->get('id') && session()->get('role') !== 'admin') {
            return redirect()->to('post/my-posts')->with('error', 'You do not have permission to delete this post');
        }
        
        $this->postModel->delete($id);
        
        // Redirect based on user role
        if (session()->get('role') === 'admin') {
            return redirect()->to('admin/approved-posts')->with('success', 'Post deleted successfully');
        } else {
            return redirect()->to('post/my-posts')->with('success', 'Post deleted successfully');
        }
    }
}
