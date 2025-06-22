<?php

namespace App\Http\Controllers\Api\Blog;

use App\Models\BlogPost;
use App\Repositories\BlogPostRepository;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    public function __construct()
    {
        parent::__construct();
        $this->blogPostRepository = app(BlogPostRepository::class); //app вертає об'єкт класа
    }

    public function index()
    {
        $posts = BlogPost::with(['user', 'category'])->get();

        return $posts;
    }
    public function show($id)
    {
        $item = $this->blogPostRepository->getById($id);
        if (empty($item)) {
            abort(404);
        }
        return $item;
    }
}
