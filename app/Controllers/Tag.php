<?php

namespace App\Controllers;

use App\Models\TagModel;
use App\Models\PostModel;

class Tag extends BaseController
{
    protected $tagModel;
    protected $postModel;
    
    public function __construct()
    {
        $this->tagModel = new TagModel();
        $this->postModel = new PostModel();
    }
    
    public function index()
    {
        $tags = $this->tagModel->findAll();
        
        $data = [
            'title' => 'All Tags',
            'tags' => $tags
        ];
        
        return view('tags/index', $data);
    }
    
    public function posts($tagId)
    {
        $tag = $this->tagModel->find($tagId);
        
        if (!$tag) {
            return redirect()->to('/')->with('error', 'Tag not found');
        }
        
        // Get posts with this tag
        $posts = $this->postModel->getPostsByTag($tagId);
        
        $data = [
            'title' => 'Posts tagged with "' . $tag['name'] . '"',
            'tag' => $tag,
            'posts' => $posts
        ];
        
        return view('tags/posts', $data);
    }
}
