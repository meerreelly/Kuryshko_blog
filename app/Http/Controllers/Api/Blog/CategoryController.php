<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Blog\Admin\BaseController;
use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    public function index()
    {
        $data = $this->blogCategoryRepository->getAll();
        return $data;
    }

    public function delete(string $id)
    {
        return BlogCategory::destroy($id);
    }

    public function create()
    {
        return $this->blogCategoryRepository->getForComboBox();
    }

    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();
        return (new BlogCategory())->create($data); //створюємо об'єкт і додаємо в БД
    }

    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        if (empty($item)) { //якщо ід не знайдено
            abon(404);
        }

        $data = $request->all(); //отримаємо масив даних, які надійшли з форми

        $result = $item->update($data);  //оновлюємо дані об'єкта і зберігаємо в БД

        return $result;
    }
}
