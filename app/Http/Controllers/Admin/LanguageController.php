<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Language;
use App\LogHistory;
use Illuminate\Http\Request;
use App;
use File;
use Symfony\Component\HttpFoundation\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LangImport;
use Illuminate\Support\Facades\DB;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "languages";
        $loghistory->action = "Language List View";
        $loghistory->save();

        return view('admin.language.index', compact('languages'));
    }

    public function indexTest($uid)
    {
        $languages = Language::all();

        $query = "SELECT permissions.title FROM (SELECT permission_role.permission_id FROM role_user LEFT JOIN permission_role ON role_user.role_id = permission_role.role_id WHERE role_user.user_id = {$uid}) AS A LEFT JOIN permissions ON A.permission_id = permissions.id WHERE permissions.deleted_at IS NULL";
        $results = DB::select(DB::raw($query));
        $role = array();
        foreach ($results as $row) {
            array_push($role, $row->title);
        }

        return view('admin.language.index-test', compact('languages', 'role'));
    }

    public function create()
    {
        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "languages";
        $loghistory->action = "New Language Create Page";
        $loghistory->save();

        return view('admin.language.create');
    }

    public function store(StoreLanguageRequest $request)
    {
        $check = $request->validate([
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
            'is_default' => ['required'],
        ]);

        $language = Language::create($request->all());

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "languages";
        $loghistory->action = "New Item Saved";
        $loghistory->save();

        $existdata = array (
            'Home' => '',
            'Name' => '',
            'User management' => '',
            'Permissions' => '',
            'Roles' => '',
            'Users' => '',
            'Logout' => '',
            'Dashboard' => '',
            'Price List' => '',
            'Type' => '',
            'Price management' => '',
            'Price Type' => '',
            'Pricing Class' => '',
            'Price Management' => '',
            'Competitor Management' => '',
            'All Comparation' => '',
            'History' => '',
            'Language Setting' => '',
            'Change password' => '',
            'Create New Language' => '',
            'Language Name' => '',
            'Language Code' => '',
            'Save' => '',
            'Code' => '',
            'Add New Language' => '',
            'Default' => '',
        );

        File::put(resource_path('lang/'.$request->code.'.json'), json_encode($existdata));

        return view('admin.language.show', compact('language'));
    }

    public function edit($id)
    {
        $language = Language::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "languages";
        $loghistory->action = "Existing Item Edit Page";
        $loghistory->save();

        return view('admin.language.edit', compact('language'));
    }

    public function update(UpdateLanguageRequest $request, $id)
    {
        $check = $request->validate([
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
            'is_default' => ['required'],
        ]);

        $language = Language::findOrFail($id);
        $language->name = $request->name;
        $language->code = $request->code;
        $language->is_default = $request->is_default;
        $language->save();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "languages";
        $loghistory->action = "Existing Item Updated";
        $loghistory->save();
        
        return view('admin.language.show', compact('language'));
    }

    public function show($id)
    {
        $language = Language::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "languages";
        $loghistory->action = "Existing Item View";
        $loghistory->save();

        return view('admin.language.show', compact('language'));
    }

    public function destroy($id)
    {
        $language = Language::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "languages";
        $loghistory->action = "Existing Item deleted";
        $loghistory->save();

        $language->delete();

        return back();
    }

    public function langedit($id) 
    {
        $lang = Language::findOrFail($id);
        $json = file_get_contents(resource_path('lang/').$lang->code.'.json');
        $list_lang = Language::all();
        if(empty($json)) {
            return back();
        }
        $json = json_decode($json);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "languages";
        $loghistory->action = "Language Detail View Page";
        $loghistory->save();

        return view('admin.language.lang_edit', compact('json', 'list_lang', 'lang'));
    }

    public function storeLanguageJson(Request $request, $id) {
        $lang = Language::findOrFail($id);
        $check = $request->validate([
            'key' => ['required'],
            'value' => ['required'],
        ]);

        $items = file_get_contents(resource_path('lang/').$lang->code.'.json');
        $reqKey = trim($request->key);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "languages";
        $loghistory->action = "New Language Key Saved";
        $loghistory->save();

        if(array_key_exists($reqKey, json_decode($items, true))) {
            return back();
        } else {
            $newArr[$reqKey] = trim($request->value);
            $itemsss = json_decode($items, true);
            $result = array_merge($itemsss, $newArr);
            file_put_contents(resource_path('lang/').$lang->code.'.json', json_encode($result));
            return back();
        }
    }

    public function deleteLanguageJson(Request $request, $id) {
        $check = $request->validate([
            'key' => ['required'],
            'value' => ['required'],
        ]);

        $reqkey = $request->key;
        $lang = Language::findOrFail($id);
        $data = file_get_contents(resource_path('lang/').$lang->code.'.json');

        $json_arr = json_decode($data, true);
        unset($json_arr[$reqkey]);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "languages";
        $loghistory->action = "New Language Key Deleted";
        $loghistory->save();

        file_put_contents(resource_path('lang/').$lang->code.'.json', json_encode($json_arr));
        return back();
    }

    public function updateLanguageJson(Request $request, $id)
    {
        $check = $request->validate([
            'key' => ['required'],
            'value' => ['required'],
        ]);

        $reqkey = trim($request->key);
        $reqValue = $request->value;
        $lang = Language::find($id);

        $data = file_get_contents(resource_path('lang/').$lang->code.'.json');

        $json_arr = json_decode($data, true);

        $json_arr[$reqkey] = $reqValue;

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "languages";
        $loghistory->action = "New Language Key Updated";
        $loghistory->save();

        file_put_contents(resource_path('lang/').$lang->code.'.json', json_encode($json_arr));

        return back();
    }

    public function importLanguageJson(Request $request, $id) {
        $lang = Language::find($id);

        // Read the Excel file into an array
        $data = Excel::toArray(new LangImport(), $request->file);

        if(isset($data[0]) && is_array($data[0]) && count($data[0]) > 0) {
            $items = file_get_contents(resource_path('lang/').$lang->code.'.json');

            $loghistory = new LogHistory();
            $loghistory->user_id = auth()->user()->id;
            $loghistory->table_name = "languages";
            $loghistory->action = "New Language Key Saved";
            $loghistory->save();

            $old_array = json_decode($items, true);            

            foreach($data[0] as $d) {
                $old_array[$d['key']] = $d['translation'];
            }
            file_put_contents(resource_path('lang/').$lang->code.'.json', json_encode($old_array));
        }        
    }

    public function changeLanguage($lang = null) {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "languages";
        $loghistory->action = "Changed WebSite Language as ".$language->name;
        $loghistory->save();

        App::setLocale($lang);

        return back();
    }
}
