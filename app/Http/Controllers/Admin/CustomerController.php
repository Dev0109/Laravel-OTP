<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company;
use App\ContactPeople;
use App\JobPosition;

class CustomerController extends Controller
{
    public function index()
    {        
        $company_list = Company::all();
        $job_list = JobPosition::all();
        return view('admin.customer.index',[
            'company_list' => $company_list,
            'job_list' => $job_list,
        ]);
    }
    
    public function get_contact_list(Request $request) 
    {
        $id = $request->id;
        $list = ContactPeople::where('company_id', $id)
            ->get();
        return response()->json(['result' => json_decode($list)]);
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
}
