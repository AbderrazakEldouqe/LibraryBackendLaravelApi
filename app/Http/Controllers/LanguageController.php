<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use JWTAuth;

class LanguageController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $languages = Language::all();
        return LanguageResource::collection($languages);
    }

    public function store(Request $request)
    {
        $language = new Language();
        $language->name = $request->name;

        if ($language->save())
            return new LanguageResource($language);
        else
            return AppHelper::storeError('language');
    }

    public function show($id)
    {
        $language = Language::where('language_id_public', '=', $id)->first();

        if (!$language) {
            return AppHelper::notFoundError($id, 'language');
        }
        return new LanguageResource($language);
    }

    public function update(Request $request, $id)
    {
        $language = Language::where('language_id_public', '=', $id)->first();

        if (!$language) {
            return AppHelper::notFoundError($id, 'language');
        }

        $updated = $language->fill($request->all())->save();

        if ($updated) {
            return new LanguageResource($language);
        } else {
            return AppHelper::updateError($id, 'language');
        }
    }

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
