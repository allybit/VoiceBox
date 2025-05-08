<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\TagModel;
use App\Models\VoteModel;
use App\Models\CommentModel;

class Post extends BaseController
{
    protected $postModel;
    protected $tagModel;
    protected $voteModel;
    protected $commentModel;
    
    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->tagModel = new TagModel();
        $this->voteModel = new VoteModel();
        $this->commentModel = new CommentModel();
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
    
    // Other methods remain the same...
}
