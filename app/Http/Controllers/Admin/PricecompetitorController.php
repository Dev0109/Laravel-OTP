<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePricecompetitorRequest;
use App\Http\Requests\UpdatePricecompetitorRequest;
use App\Pricecompetitor;
use App\LogHistory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class PricecompetitorController extends Controller
{
    public function index()
    {
        $pricetypes = Pricecompetitor::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "Price Type List View";
        $loghistory->save();

        return view('admin.pricecompetitor.index', compact('pricetypes'));
    }

    public function indexTest($uid)
    {
        $pricetypes = Pricecompetitor::all();

        $query = "SELECT permissions.title FROM (SELECT permission_role.permission_id FROM role_user LEFT JOIN permission_role ON role_user.role_id = permission_role.role_id WHERE role_user.user_id = {$uid}) AS A LEFT JOIN permissions ON A.permission_id = permissions.id WHERE permissions.deleted_at IS NULL";
        $results = DB::select(DB::raw($query));
        $role = array();
        foreach ($results as $row) {
            array_push($role, $row->title);
        }

        return view('admin.pricecompetitor.index-test', compact('pricetypes', 'role'));
    }

    public function create()
    {
        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "New Item Create Page";
        $loghistory->save();

        return view('admin.pricecompetitor.create');
    }

    public function store(StorePricecompetitorRequest $request)
    {
        $check = $request->validate([
            'name' => ['required', 'string']
        ]);

        if ($request->hasfile('pricelist')) {
            $request->pricelist->move(public_path('uploads'), $request->file('pricelist')->getClientOriginalName());            
        }
        if ($request->hasfile('datasheet')) {
            $request->datasheet->move(public_path('uploads'), $request->file('datasheet')->getClientOriginalName());            
        }

        $pricetype = Pricecompetitor::create(array(
            'name' => $request->name,
            'pricelink' => $request->pricelink,
            'website' => $request->website,
            'username' => $request->username,
            'userpwd' => $request->userpwd,
            'pricelist' => $request->has('pricelist') ? $request->file('pricelist')->getClientOriginalName() : null,
            'datasheet' => $request->has('datasheet') ? $request->file('datasheet')->getClientOriginalName() : null,
        ));

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "New Item Saved";
        $loghistory->save();

        return view('admin.pricecompetitor.show', compact('pricetype'));
    }

    public function edit($id)
    {
        $pricetype = Pricecompetitor::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "Existing Item Edit Page";
        $loghistory->save();

        return view('admin.pricecompetitor.edit', compact('pricetype'));
    }

    public function update(UpdatePricecompetitorRequest $request, $id)
    {
        $check = $request->validate([
            'name' => ['required', 'string']
        ]);

        $pricetype = Pricecompetitor::findOrFail($id);        

        if ($request->has('pricelist')) {
            if($pricetype->pricelist) {
                $file_path = public_path('uploads/' . $pricetype->pricelist);
                if(file_exists($file_path)) {
                    unlink($file_path);                    
                }
            }
            $request->pricelist->move(public_path('uploads'), $request->file('pricelist')->getClientOriginalName());
            $pricetype->pricelist = $request->file('pricelist')->getClientOriginalName();
        }
        if ($request->has('datasheet')) {
            if($pricetype->datasheet) {
                $file_path = public_path('uploads/' . $pricetype->datasheet);
                if(file_exists($file_path)) {
                    unlink($file_path);                    
                }
            }
            $request->datasheet->move(public_path('uploads'), $request->file('datasheet')->getClientOriginalName());
            $pricetype->datasheet = $request->file('datasheet')->getClientOriginalName();
        }

        $pricetype->name = $request->name;
        $pricetype->pricelink = $request->pricelink;
        $pricetype->website = $request->website;
        $pricetype->username = $request->username;
        $pricetype->userpwd = $request->userpwd;
        $pricetype->save();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "Existing Item Updated";
        $loghistory->save();
        
        return view('admin.pricecompetitor.show', compact('pricetype'));
    }

    public function show($id)
    {
        $pricetype = Pricecompetitor::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "Existing Item View";
        $loghistory->save();

        return view('admin.pricecompetitor.show', compact('pricetype'));
    }

    public function destroy($id)
    {
        $pricetypes = Pricecompetitor::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricecompetitors";
        $loghistory->action = "Existing Item deleted";
        $loghistory->save();

        $pricetypes->delete();

        return back();
    }
}
