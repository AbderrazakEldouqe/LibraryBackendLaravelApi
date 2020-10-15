<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use JWTAuth;

class CategoryController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $categories = Category::all();

        return CategoryResource::collection($categories);
    }

    public function store(Request $request)
    {
        $category = new Category();
        $category->name = $request->name;
        if ($category->save()){
            return new CategoryResource($category);
        }
        else
            return AppHelper::storeError('category');
    }

    public function show($id)
    {
        $category = Category::where('category_id_public', '=', $id)->first();


        if (!$category) {
            return AppHelper::notFoundError($id, 'category');
        }
        return new CategoryResource($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::where('category_id_public', '=', $id)->first();

        if (!$category) {
            return AppHelper::notFoundError($id, 'category');
        }

        $updated = $category->fill($request->all())->save();

        if ($updated) {
            return new CategoryResource($category);
        } else {
            return AppHelper::updateError($id, 'category');
        }
    }

    public function destroy($id)
    {
        $category = Category::where('category_id_public', '=', $id)->first();

        if (!$category) {
            return AppHelper::notFoundError($id, 'category');
        }

        if ($category->delete()) {
            return AppHelper::deleteSuccess($id, 'category');
        } else {
            return AppHelper::deleteError($id, 'category');
        }
    }

}
