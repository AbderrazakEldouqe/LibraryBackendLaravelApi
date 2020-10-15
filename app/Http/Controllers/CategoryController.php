<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return TaskResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category();
        $category->name = $request->name;


        if ($category->save())
            return new TaskResource($category);
        else
            return AppHelper::storeError('category');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category =  Category::where('category_id_public','=',$id)->first();


        if (!$category) {
            return AppHelper::notFoundError($id, 'category
            ');
        }
        return new TaskResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::where('category_id_public','=',$id)->first();

        if (!$category) {
            return AppHelper::notFoundError($id, 'category');
        }

        $updated = $category->fill($request->all())->save();

        if ($updated) {
            return new TaskResource($category);
        } else {
            return AppHelper::updateError($id, 'category');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::where('category_id_public','=',$id)->first();

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
