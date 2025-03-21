<?php
// Last Modified Date: 20-03-2025
// Developed By: Innovative Solution Pvt. Ltd. (ISPL)  
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\LanguageRequest;
use Illuminate\Http\Request;
use App\Models\Language\Language;
use App\Models\Language\Translate;
use App\Models\Language\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;
use DB;
use DataTables;
use Auth;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Validator;
use App\Imports\TranslateImport;

class LanguageController extends Controller
{
   
    /**

     * Display a list of the translation.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = Language::where('status',true)->pluck( 'name','code');

        // Load translation
        if ($languages) {
            return response()->json([
                'translation' => $languages
            ]);
        }

        return response()->json([
            'error' => 'Language not found.'
        ], 404);
    }
    
    public function getTranslations(Request $request)
    {
        // Get the requested language, default to English ('en')
        $lang = $request->lang;

        // Check if the language exists in the resources/lang folder
        $langPath = resource_path("lang/api/{$lang}.json");

        if (!File::exists($langPath)) {
            return response()->json(['error' => 'Language not supported.'], 404);
        }

        // Load the translations
        $translations = json_decode(File::get($langPath), true);

        return response()->json([
            'language' => $lang,
            'translations' => $translations,
        ]);
    }
}