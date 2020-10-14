<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class LanguageController extends Controller
{

    protected $user;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $languages = Language::all();
        return TaskResource::collection($languages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $language = new Language();
        $language->name = $request->name;

        if ($language->save())
            return new TaskResource($language);
        else
            return AppHelper::storeError('language');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $language = Language::where('language_id_public', '=', $id)->first();

        if (!$language) {
            return AppHelper::notFoundError($id, 'language');
        }
        return new TaskResource($language);
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
        $language = Language::where('language_id_public', '=', $id)->first();

        if (!$language) {
            return AppHelper::notFoundError($id, 'language');
        }

        $updated = $language->fill($request->all())->save();

        if ($updated) {
            return new TaskResource($language);
        } else {
            return AppHelper::updateError($id, 'language');
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
        $language = Language::where('language_id_public', '=', $id)->first();

        if (!$language) {
            return AppHelper::notFoundError($id, 'language');
        }

        if ($language->delete()) {
            return AppHelper::deleteSuccess($id, 'language');
        } else {
            return AppHelper::deleteError($id, 'language');
        }
    }
}
