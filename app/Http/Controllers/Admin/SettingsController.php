<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Settings;
use Illuminate\Support\Facades\DB;

class SettingsController
{
    public function index()
    {
        $settings = Settings::where('user', auth()->user()->id)->first();
        $version = "v1.00";
        if(file_exists(public_path('settings.json'))) {
            $setting_info = file_get_contents(public_path('settings.json'));
            $setting_info = json_decode($setting_info, true);
            $version = $setting_info['version'];
        } else {
            $setting_info = ['version' => $version];
            file_put_contents(public_path('settings.json'), json_encode($setting_info));
        }
        
        return view('admin.settings.index', ['_page_title' => "Settings", 'settings' => $settings,'version' => $version]);
    }

    public function indexTest($uid)
    {
        $settings = Settings::where('user', $uid)->first();
        $version = "v1.00";
        if(file_exists(public_path('settings.json'))) {
            $setting_info = file_get_contents(public_path('settings.json'));
            $setting_info = json_decode($setting_info, true);
            $version = $setting_info['version'];
        } else {
            $setting_info = ['version' => $version];
            file_put_contents(public_path('settings.json'), json_encode($setting_info));
        }

        $query = "SELECT permissions.title FROM (SELECT permission_role.permission_id FROM role_user LEFT JOIN permission_role ON role_user.role_id = permission_role.role_id WHERE role_user.user_id = {$uid}) AS A LEFT JOIN permissions ON A.permission_id = permissions.id WHERE permissions.deleted_at IS NULL";
        $results = DB::select(DB::raw($query));
        $role = array();
        foreach ($results as $row) {
            array_push($role, $row->title);
        }
        
        return view('admin.settings.index-test', ['_page_title' => "Settings", 'settings' => $settings,'version' => $version, 'role' => $role]);
    }

    public function logo_update(Request $request)
    {       
        if($request->hasFile('logoimage')) {
            $settings = Settings::where('user', auth()->user()->id)->first();
            if(isset($settings)){
                $settings = $settings->first();
                $old_file_path = public_path('uploads/' . $settings->image);
                if (file_exists($old_file_path))
                    unlink($old_file_path);                
            } else {
                $settings = new Settings();
            }
            $filename = auth()->user()->id . "logo.png";
            $request->logoimage->move(public_path('uploads'), $filename);

            $settings->user = auth()->user()->id;
            $settings->image = $filename;
            $settings->save();
        }

        return back();
    }

    public function save_seller(Request $req) 
    {
        $settings = Settings::where('user', auth()->user()->id)->first();
        if(!isset($settings)){
            $settings = new Settings();
        }
        
        $settings->user = auth()->user()->id;
        $settings->comname =  $req->company_name;
        $settings->comaddr = $req->company_addr;
        $settings->comtel = $req->company_tel;
        $settings->comfax = $req->company_fax;            
        $settings->conname = $req->contact_name;
        $settings->contel =  $req->contact_tel;
        $settings->conmobile =  $req->contact_mobile;
        $settings->conemail = $req->contact_email;
        $settings->save();
    }

    public function save_version(Request $req) {        
        $setting_info = ['version' => $req->version];
        file_put_contents(public_path('settings.json'), json_encode($setting_info));
    }
}
