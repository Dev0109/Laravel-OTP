<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePricingmanageRequest;
use App\Http\Requests\UpdatePricemanageRequest;
use App\Price;
use App\Pricing;
use App\Pricemanage;
use App\LogHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PricemanageController extends Controller
{
    public function index()
    {
        $pricelist = Pricing::all();

        $pricemanas = DB::table('pricemanages')->leftJoin('prices', 'pricemanages.price_id', '=', 'prices.id')->whereNull('pricemanages.deleted_at')->select('pricemanages.id as pid', 'pricemanages.price_id', 'prices.*')->get();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricemanages";
        $loghistory->action = "Price management View";
        $loghistory->save();

        return view('admin.pricemanage.index', compact('pricelist', 'pricemanas'));
    }

    public function indexTest($uid)
    {
        $pricelist = Pricing::all();

        $pricemanas = DB::table('pricemanages')->leftJoin('prices', 'pricemanages.price_id', '=', 'prices.id')->whereNull('pricemanages.deleted_at')->select('pricemanages.id as pid', 'pricemanages.price_id', 'prices.*')->get();

        $query = "SELECT permissions.title FROM (SELECT permission_role.permission_id FROM role_user LEFT JOIN permission_role ON role_user.role_id = permission_role.role_id WHERE role_user.user_id = {$uid}) AS A LEFT JOIN permissions ON A.permission_id = permissions.id WHERE permissions.deleted_at IS NULL";
        $results = DB::select(DB::raw($query));
        $role = array();
        foreach ($results as $row) {
            array_push($role, $row->title);
        }
        
        return view('admin.pricemanage.index-test', compact('pricelist', 'pricemanas', 'role'));
    }

    public function create()
    {
        $prices = Price::all();
        
        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricemanages";
        $loghistory->action = "New Item Create Page";
        $loghistory->save();

        return view('admin.pricemanage.create', compact('prices'));
    }

    public function store(StorePricingmanageRequest $request)
    {
        $check = $request->validate([
            'price_id' => ['required', 'numeric']
        ]);

        $pricemanage = Pricemanage::create($request->all());
        $prices = Price::all();
        $pricings = Pricing::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricemanages";
        $loghistory->action = "New Item Saved";
        $loghistory->save();

        return view('admin.pricemanage.show', compact('pricemanage', 'prices', 'pricings'));
    }

    public function edit($id)
    {
        $pricemanage = Pricemanage::findOrFail($id);
        $prices = Price::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricemanages";
        $loghistory->action = "Existing Item Edit Page";
        $loghistory->save();

        return view('admin.pricemanage.edit', compact('pricemanage', 'prices'));
    }

    public function update(UpdatePricemanageRequest $request, $id)
    {
        $check = $request->validate([
            'price_id' => ['required', 'numeric']
        ]);

        $pricemanage = Pricemanage::findOrFail($id);
        $pricemanage->price_id = $request->price_id;
        $pricemanage->save();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricemanages";
        $loghistory->action = "Existing Item Updated";
        $loghistory->save();

        $prices = Price::all();
        $pricings = Pricing::all();
        
        return view('admin.pricemanage.show', compact('pricemanage', 'prices', 'pricings'));
    }

    public function show($id)
    {
        $pricemanage = Pricemanage::findOrFail($id);

        $prices = Price::all();
        $pricings = Pricing::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricemanages";
        $loghistory->action = "Existing Item View";
        $loghistory->save();

        return view('admin.pricemanage.show', compact('pricemanage', 'prices', 'pricings'));
    }

    public function destroy($id)
    {
        $pricemanage = Pricemanage::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "pricemanages";
        $loghistory->action = "Existing Item deleted";
        $loghistory->save();

        $pricemanage->delete();

        return back();
    }
}
