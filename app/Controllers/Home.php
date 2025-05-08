<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\TagModel;
use App\Models\VoteModel;
use App\Models\CommentModel;
use App\Models\AnnouncementModel;

class Home extends BaseController
{
    public function index()
    {
        $postModel = new PostModel();
        $tagModel = new TagModel();
        $voteModel = new VoteModel();
        $commentModel = new CommentModel();
        $announcementModel = new AnnouncementModel();
        
        // Get approved posts
        $posts = $postModel->where('status', 'approved')
                          ->orderBy('created_at', 'DESC')
                          ->findAll(10);
        
        // Get tags for each post and vote counts
        foreach ($posts as &$post) {
            $post['tags'] = $postModel->getTags($post['id']);
            $post['vote_count'] = $voteModel->getVoteCount($post['id']);
            $post['comment_count'] = $commentModel->getCommentCount($post['id']);
            
            // Check if current user has voted
            if (session()->get('isLoggedIn')) {
                $post['user_vote'] = $voteModel->getUserVote($post['id'], session()->get('id'));
            } else {
                $post['user_vote'] = 0;
            }
        }
        
        // Get popular tags
        $popularTags = $tagModel->findAll(10);
        
        // Get recent announcements
        $announcements = $announcementModel->orderBy('created_at', 'DESC')
                                         ->findAll(3);
        
        $data = [
            'title' => 'Home',
            'posts' => $posts,
            'popularTags' => $popularTags,
            'announcements' => $announcements
        ];
        
        return view('home', $data);
    }
}
