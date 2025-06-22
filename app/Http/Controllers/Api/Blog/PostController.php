<?php

namespace App\Http\Controllers\Api\Blog;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function index()
    {
        $posts = BlogPost::with(['user', 'category'])->get();

        return $posts;
    }
}
