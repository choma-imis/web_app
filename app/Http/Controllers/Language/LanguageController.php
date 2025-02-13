<?php

namespace App\Http\Controllers\Language;


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
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:List Languages', ['only' => ['index']]);
        $this->middleware('permission:View Language', ['only' => ['show']]);
        $this->middleware('permission:Add Language', ['only' => ['create', 'store', 'import_translates']]);
        $this->middleware('permission:Edit Language', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Delete Language', ['only' => ['destroy']]);
        $this->middleware('permission:Export Language', ['only' => ['export']]);
        $this->middleware('permission:View Language', ['only' => ['history']]);
        $this->middleware('permission:Generate Translation', ['only' => ['generate_translate']]);
        $this->middleware('permission:Export Translation CSV', ['only' => ['export_csv_format']]);
        $this->middleware('permission:Add Language', ['only' => ['create', 'store','import_translates']]);


    }
    /**

     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = Language::all();
        $page_title = 'Languages';
        return view("language.index", compact('languages', 'page_title'));
    }


    public function getData(Request $request)
    {
        $languages = Language::whereNull('deleted_at')->orderBY('id', 'asc');
        return Datatables::of($languages)
            ->filter(function ($query) use ($request) {})
            ->addColumn('action', function ($model) {
                if ($model->id == 1) {
                    return '';
                }
                if (Auth::user()->can('Add Translation')) {
                    $content = '<a title="Import Translations" href="' . action("Language\LanguageController@create_import", [$model->code]) . '"class="btn btn-info btn-sm mb-1"><i class="fa fa-upload"></i></a> ';

                    $content .= '<a title="Add Translations" href="' . action("Language\LanguageController@add_translation", [$model->code]) . '"class="btn btn-info btn-sm mb-1"><i class="fa fa-language" aria-hidden="true"></i></a> ';
                }
                // Generate translation button
                if (Auth::user()->can('Generate Translation')) {
                    // Add the embedded form for "Generate Language"
                    $content .= \Form::open(['method' => 'POST', 'route' => ['lang.generate', $model->code], 'class' => 'd-inline']);
                    $content .= '<button type="submit"class="btn btn-info btn-sm mb-1 mr-1" title="Generate Language"><i class="fa fa-cogs"></i></button>';
                    $content .= \Form::close();
                }
                $content .= \Form::open(['method' => 'DELETE', 'route' => ['setup.destroy', $model->id], 'class' => 'd-inline']);


                if (Auth::user()->can('Edit Language')) {
                    $content .= '<a title="Edit" href="' . action("Language\LanguageController@edit", [$model->id]) . '"  class="btn btn-info btn-sm mb-1"><i class="fa fa-edit"></i></a> ';
                }
                if (Auth::user()->can('View Language')) {
                    $content .= '<a title="Detail" href="' . action("Language\LanguageController@show", [$model->id]) . '"class="btn btn-info btn-sm mb-1"><i class="fa fa-list"></i></a> ';
                }
                if (Auth::user()->can('Delete Language')) {
                    $content .= '<a title="Delete" class="delete btn btn-danger btn-sm mb-1">&nbsp;<i class="fa fa-trash"></i>&nbsp;</a> ';
                }
                $content .= \Form::close();
                return $content;
            })
            ->editColumn('status', function ($model) {
                return $model->status ? 'Active' : 'Disabled';
            })
            ->make(true);
    }



    public function create()
    {
        $page_title = "Add Language";
        return view('language.create', compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(LanguageRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $languageId = $this->storeOrUpdate(null, $data); // Store Language
            // Fetch existing records from Translates (1 to 1263)
            $existingTranslates = Translate::whereBetween('id', [1, 1263])->get();
            // Insert new records in Translates table
            foreach ($existingTranslates as $translate) {
                Translate::create([
                    'key'   => $translate->key,      // Keep existing key
                    'name'  => $data['code'] ?? null, // Use new language code
                    'pages' => $translate->pages,   // Keep existing pages
                    'group' => $translate->group,   // Keep existing group
                    'panel' => $translate->panel,   // Keep existing panel
                    'load'  => $translate->load,    // Keep existing load

                ]);
            }
            DB::commit();
            return redirect('language/setup')->with('success', 'Language Added successfully');
        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Error in storing Language and Translates: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $language = Language::find($id);
        if ($language) {
            $page_title = "Language Details";
            return view('language.show', compact('page_title',  'language'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = "Edit Language";
        $language = Language::find($id);
        return view('language.edit', compact('page_title', 'language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LanguageRequest $request, $id)
    {
        $language = Language::find($id);
        if ($language) {
            $data = $request->all();
            $this->storeOrUpdate($language->id, $data);
            return redirect('language/setup')->with('success', 'Language updated successfully');
        } else {
            return redirect('language/setup')->with('error', 'Failed to update Language');
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
        $language = Language::find($id);
        if ($language) {
            if ($language->code == 'en') {
                return redirect('language/setup')->with('error', 'English is defauly language and cannot be deleted');
            }
            language::destroy($id);
            return redirect('language/setup')->with('success', 'Language deleted successfully');
        } else {
            return redirect('language/setup')->with('error', 'Failed to delete Language');
        }
    }


    public function storeOrUpdate($id, $data)
    {
        if (is_null($id)) {
            $language = new Language();
            $language->name = $data['name'] ? $data['name'] : null;
            $language->label = $data['label'] ? $data['label'] : null;
            $language->short = $data['short'] ? $data['short'] : null;
            $language->code = $data['code'] ? $data['code'] : null;
            $language->status = $data['status'] ? $data['status'] : 0;
            $language->save();
            return $language->id;
        } else {
            $language = Language::find($id);
            $language->name = $data['name'] ? $data['name'] : null;
            $language->label = $data['label'] ? $data['label'] : null;
            $language->short = $data['short'] ? $data['short'] : null;
            $language->code = $data['code'] ? $data['code'] : null;
            $language->status = $data['status'] ? $data['status'] : 0;
            $language->save();
        }
    }


}
