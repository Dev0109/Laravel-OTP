<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Company;
use App\ContactPeople;
use App\Project;
use App\Settings;
use App\JobPosition;
use App\Price;
use App\Unit;
use App\User;
use App\DeliveryAddress;
use App\DeliveryCondition;

class ProjectsController extends Controller
{    
    public function index()
    {        
        $query = "SELECT P.id, P.company, P.contact, P.reference, C.`name` AS `customer`, CP.firstname AS `contact_name`, P.`name` AS `project_name`, P.description, P.updated_at, P.`status` FROM `project` AS `P` LEFT JOIN `company` AS `C` ON P.company = C.id LEFT JOIN `contact_people` AS `CP` ON P.contact = CP.id WHERE ISNULL(P.deleted_at) AND P.user=" . auth()->user()->id;

        $result = DB::select($query);
        
        return view('admin.projects.index', [
            '_page_title' => trans('global.project.project_list'),
            'project_list' => $result
        ]);
    }

    public function profile($pid=0, $cid=0, $uid=0)
    {
        // $company_list = Company::where('user', auth()->user()->id)->get();
        $company_list = Company::all();
        $job_list = JobPosition::all();
        return view('admin.projects.profile',[
            '_page_title' => trans('global.project.project_profile'), 
            'company_list' => $company_list,
            'job_list' => $job_list,
            'pid' => $pid,
            'cid' => $cid,
            'uid' => $uid,
        ]);
    }

    public function detail($pid=0, $cid=0, $uid=0)
    {
        $option = $_GET['o'] ?? "";
        $user = User::findorFail(auth()->user()->id);
        $delivery_address = DeliveryAddress::where('id', $user->delivery_address)
            ->where('uid', auth()->user()->id)
            ->first();
        $delivery_condition = DeliveryCondition::where('id', $user->delivery_condition)
            ->where('uid', auth()->user()->id)
            ->first();
        $company = Company::findOrFail($cid);
        $contact = ContactPeople::findOrFail($uid);
        $project = null;
        $units = null;
        if($pid > 0) {
            $project = Project::findOrFail($pid);
            $units = Unit::where('pid', $pid)
                ->whereNull('deleted_at')
                ->get();
        }

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

        return view('admin.projects.detail',[
            '_page_title' => trans('global.project.project_profile'),
            'user' => $user,
            'company' => $company,
            'contact' => $contact,
            'pid' => $pid,
            'cid' => $cid,
            'uid' => $uid,
            'project' => $project,
            'settings' => $settings,
            'version' => $version,
            'option' => $option,
            'units' => json_encode($units),
            'delivery_address' => $delivery_address,
            'delivery_condition' => $delivery_condition,
        ]);
    }

    public function get_models(Request $request)
    {
        $url = 'https://acer.avensys-srl.com/api/models_json.php';
        $data = $request->all();
        
        $ch = curl_init($url . '?' . http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        
        curl_close($ch);
        return response()->json(['result' => json_decode($response)]);
    }

    public function get_completedata(Request $request)
    {
        $url = 'https://acer.avensys-srl.com/api/completedata_json.php';
        $data = $request->all();
        
        $ch = curl_init($url . '?' . http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        
        curl_close($ch);
        
        return response()->json(['result' => json_decode($response)]);
    }

    public function get_contact_list(Request $request) 
    {
        $id = $request->id;
        $list = DB::table('contact_people')
            // ->where('user', auth()->user()->id)
            ->where('company_id', $id)
            ->get();

        return response()->json(['result' => json_decode($list)]);
    }

    public function store_contact(Request $req) {
        $com_id = $req->company_id;
        $con_id = $req->contact_id;
        $first_name = $req->first_name;
        $second_name = $req->last_name;
        $tel_no = $req->tel_no;
        $mobile_no = $req->mobile_no;
        $email = $req->email;
        $job_position = $req->job_position;

        $contact = null;

        if($con_id > 0) 
            $contact = ContactPeople::findOrFail($con_id);           
        else 
            $contact = new ContactPeople();

        if(!$contact) 
            return response()->json(['result' => 'failed']); 
            
        $contact->user = auth()->user()->id;
        $contact->company_id = $com_id;
        $contact->firstname = $first_name;
        $contact->secondname = $second_name;
        $contact->phone = $tel_no;
        $contact->mobile = $mobile_no;
        $contact->email = $email;
        $contact->job_position = $job_position;
        $res = $contact->save();

        return response()->json(['result' => $res]);
    }

    public function delete_contact(Request $rea, $id = 0) {
        $contact = ContactPeople::findOrFail($id);
        $res = $contact->delete();
        return response()->json(['result' => $res]);
    }

    public function save_project(Request $request) {
        $p_id = $request->id;
    
        $project = null;

        if($p_id > 0) {
            $project = Project::findOrFail($p_id);
        } else
            $project = new Project();

        $project->user = auth()->user()->id;
        $project->company = $request->company;
        $project->contact = $request->contact;
        $project->name = $request->name;
        $project->description = $request->description;        
        $project->reference = $request->reference;
        if($p_id == 0) {
            $project->status = 0;
        }
        
        if($request->hasFile('pdf')) {
            if($p_id > 0) {
                $old_file_path = $this->get_project_dir_path() . '/' . $project->pdf;
                if (file_exists($old_file_path))
                    unlink($old_file_path);
            }
            $filename = $request->pdf->getClientOriginalName();
            $request->pdf->move($this->get_project_dir_path(), $filename);            
            $project->pdf = $filename;
        }

        // $project->layout = $request->layout;
        // $project->indoor = $request->indoor;
        // $project->ex1 = $request->ex1;
        // $project->ex2 = $request->ex2;
        // $project->airflow = $request->airflow;
        // $project->pressure = $request->pressure;
        // $project->Tfin = $request->Tfin;
        // $project->Trin = $request->Trin;
        // $project->Hfin = $request->Hfin;
        // $project->Hrin = $request->Hrin;
        
        // $project->modelId = $request->modelId;
        // $project->priceId = $request->priceId;
        $project->save();
        $pid = $project->id;

        Unit::where('pid', $pid)->delete();
        $units = json_decode($request->units);
        $n = count($units);
        for ($i=0; $i < $n; $i++) {
            $unit = new Unit();
            $unit->pid =        $pid;
            $unit->name =       $units[$i]->name;
            $unit->layout =     $units[$i]->layout;
            $unit->indoor =     $units[$i]->indoor;
            $unit->ex1 =        $units[$i]->ex1;
            $unit->ex2 =        $units[$i]->ex2;
            $unit->airflow =    $units[$i]->airflow;
            $unit->pressure =   $units[$i]->pressure;
            $unit->Tfin =       $units[$i]->Tfin;
            $unit->Trin =       $units[$i]->Trin;
            $unit->Hfin =       $units[$i]->Hfin;
            $unit->Hrin =       $units[$i]->Hrin;
            $unit->modelId =    $units[$i]->modelId;
            $unit->priceId =    $units[$i]->priceId;
            $unit->price =      $units[$i]->price;
            $unit->delivery_time =      $units[$i]->delivery_time;
            $unit->save();
        }

    }

    public function delete_project(Request $req) {
        $id = $req->id;
        $project = Project::findOrFail($id);
        $res = $project->delete();
        return response()->json(['result' => $res]);
    }

    public function duplicate_project(Request $req) {
        $id = $req->id;
        $project = Project::findOrFail($id);
        $new_project = new Project();
        
        $new_project->user = auth()->user()->id;
        $new_project->company = $project->company;
        $new_project->contact = $project->contact;
        $new_project->name = $project->name;
        $new_project->description = $project->description;        
        $new_project->reference = $project->reference;
        $new_project->layout = $project->layout;
        $new_project->indoor = $project->indoor;
        $new_project->ex1 = $project->ex1;
        $new_project->ex2 = $project->ex2;
        $new_project->airflow = $project->airflow;
        $new_project->pressure = $project->pressure;
        $new_project->Tfin = $project->Tfin;
        $new_project->Trin = $project->Trin;
        $new_project->Hfin = $project->Hfin;
        $new_project->Hrin = $project->Hrin;
        $new_project->status = $project->status;
        $new_project->modelId = $project->modelId;
        
        $new_pdf = 'REPORT_' . time() . '.pdf';
        $s_path = $this->get_project_dir_path() . '/' .  $project->pdf;
        $n_path = $this->get_project_dir_path() . '/' .  $new_pdf;
        // Copy the file using the copy() function
        if (file_exists($s_path)) {
            if (copy($s_path, $n_path)) {
                $new_project->pdf = $new_pdf;
            }
        }        

        $res = $new_project->save();
        return response()->json(['result' => $new_project->id]);
    }

    public function save_company(Request $req ) {

        $com_id = $req->company_id;
        $company = null;

        if($com_id > 0) 
            $company = Company::findOrFail($com_id);
        else 
            $company = new Company();

        if(!$company) 
            return response()->json(['result' => 'failed']); 
            
        $company->user = auth()->user()->id;
        $company->name = $req->company_name;
        $company->address = $req->company_address;
        $company->phone = $req->company_phone;
        $company->VAT = $req->company_VAT;
        $company->description = $req->company_desc;
        $company->save();

        return response()->json(['result' => $company]);
    }

    public function delete_company(Request $req ) {
        $com_id = $req->id;
        $company = Company::findOrFail($com_id);
        $res = $company->delete();
        return response()->json(['result' => $res]);
    }

    public function status_change(Request $req)
    {
        $project = Project::findOrFail($req->id);
        $project->status = $req->status;
        $res = $project->save();
        return response()->json(['result' => $res]);
    }

    public function job(Request $req) {
        $job_list = JobPosition::all();
        return view('admin.projects.job', [
            // '_page_title' => trans('global.project.job_position'),
            'job_list' => $job_list
        ]);        
    }
    
    public function jobTest($uid) {
        $job_list = JobPosition::all();
        
        return view('admin.projects.job-test', [
            'job_list' => $job_list,
        ]);        
    }

    public function store_job(Request $req) {
        $job = null;
        if($req->id < 1) {
            $job = new JobPosition();
        } else {
            $job = JobPosition::findOrFail($req->id);
        }
        $job->name = $req->name;
        $res = $job->save();
        return response()->json(['result' => $job]);
    }

    public function delete_job(Request $req) {
        $job = JobPosition::findOrFail($req->id);
        $res = $job->delete();
        return response()->json(['result' => $res]);
    }
    
    public function get_model_price(Request $req){
        $id_model = $req->get('id');
        $res = Price::whereNull('deleted_at')
            ->where('id_model', $id_model)
            ->get();

        $pricetypes_user = DB::table('pricetypes_user')
            ->select('pricetypes')
            ->where('userId', auth()->user()->id)
            ->first();
        $pricetypes_multiplier = array();
        $pricetypes_user_array = array();
        $result = array();
        if ($pricetypes_user) {
            $temps = explode(',', $pricetypes_user->pricetypes);
            foreach ($temps as $key => $row) {
                $temp = explode('_', $row);
                array_push($pricetypes_user_array, $temp[0]);
                $pricetypes_multiplier[$temp[0]] = $temp[1];
            }
            foreach ($res as $row) {
                if (in_array($row->pricetype_id, $pricetypes_user_array)) {
                    $row->multiplier = floatval($pricetypes_multiplier[$row->pricetype_id]);
                    array_push($result, $row);
                }
            }
        }
        echo json_encode($result);
    }

    private function get_project_dir_path() {
        if(!file_exists(public_path('uploads/project')) || !is_dir(public_path('uploads/project'))) {
            mkdir(public_path('uploads/project'));
        }

        return public_path('uploads/project');
    }
}

