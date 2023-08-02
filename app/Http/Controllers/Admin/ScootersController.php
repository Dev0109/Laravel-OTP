<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use App\Price;
use App\Pricetype;
use App\LogHistory;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PriceImport;
use App\Services\SMSGatewayService;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Session;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;


class ScootersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('scooter_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $res = Price::whereNull('deleted_at')
            ->get();

        $pricetypes_user = DB::table('pricetypes_user')
            ->select('pricetypes')
            ->where('userId', auth()->user()->id)
            ->first();
        $results = DB::table('pricetypes')
            ->whereNull('deleted_at')
            ->get();
        $pricetypes = array();
        foreach ($results as $row) {
            $pricetypes[$row->id] = $row->name;
        }
        $pricetypes_multiplier = array();
        $pricetypes_user_array = array();
        $prices = array();
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
                    $row->name = $pricetypes[$row->pricetype_id];
                    array_push($prices, $row);
                }
            }
        }

        $multiplier = auth()->user()->multiplier;
        if ($multiplier) {
            $multiplier = floatval(explode('_', $multiplier)[1]);
        } else {
            $multiplier = 1;
        }
        
        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Price List View";
        $loghistory->save();        

        return view('admin.prices.index', compact('prices', 'multiplier'));
    }

    public function indexTest($uid)
    {
        $res = Price::whereNull('deleted_at')
            ->get();

        $pricetypes_user = DB::table('pricetypes_user')
            ->select('pricetypes')
            ->where('userId', $uid)
            ->first();
        $results = DB::table('pricetypes')
            ->whereNull('deleted_at')
            ->get();
        $pricetypes = array();
        foreach ($results as $row) {
            $pricetypes[$row->id] = $row->name;
        }
        $pricetypes_multiplier = array();
        $pricetypes_user_array = array();
        $prices = array();
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
                    $row->name = $pricetypes[$row->pricetype_id];
                    array_push($prices, $row);
                }
            }
        }

        $user = DB::table('users')
            ->where('id', $uid)
            ->first();
        $multiplier = $user->multiplier;
        if ($multiplier) {
            $multiplier = floatval(explode('_', $multiplier)[1]);
        } else {
            $multiplier = 1;
        }

        $query = "SELECT permissions.title FROM (SELECT permission_role.permission_id FROM role_user LEFT JOIN permission_role ON role_user.role_id = permission_role.role_id WHERE role_user.user_id = {$uid}) AS A LEFT JOIN permissions ON A.permission_id = permissions.id WHERE permissions.deleted_at IS NULL";
        $results = DB::select(DB::raw($query));
        $role = array();
        foreach ($results as $row) {
            array_push($role, $row->title);
        }

        return view('admin.prices.index-test', compact('prices', 'multiplier', 'role'));
    }
    
    public function filterList($id) {
        $prices = DB::table('prices')->leftJoin('pricetypes', 'prices.pricetype_id', '=', 'pricetypes.id')->whereNull('prices.deleted_at')->where('prices.pricetype_id', $id)->select('prices.*', 'pricetypes.name', 'pricetypes.id as ptid')->get();
        $price_type = PriceType::findOrFail($id);
        $pricetypes_user = DB::table('pricetypes_user')
            ->select('pricetypes')
            ->where('userId', auth()->user()->id)
            ->first();
        $temps = explode(',', $pricetypes_user->pricetypes);
        $price_multiplier = 1;
        foreach ($temps as $row) {
            $temp = explode('_', $row);
            if ($temp[0] === $id) {
                $price_multiplier = $temp[1];
            }
        }

        $multiplier = auth()->user()->multiplier;
        if ($multiplier) {
            $multiplier = floatval(explode('_', $multiplier)[1]);
        } else {
            $multiplier = 1;
        }

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Price List by type (".$id.") View";
        $loghistory->save();

        return view('admin.prices.index', compact('prices', 'id', 'price_type', 'price_multiplier', 'multiplier'));
    }

    public function filterListTest($uid, $id) {
        $prices = DB::table('prices')->leftJoin('pricetypes', 'prices.pricetype_id', '=', 'pricetypes.id')->whereNull('prices.deleted_at')->where('prices.pricetype_id', $id)->select('prices.*', 'pricetypes.name', 'pricetypes.id as ptid')->get();
        $price_type = PriceType::findOrFail($id);
        $pricetypes_user = DB::table('pricetypes_user')
            ->select('pricetypes')
            ->where('userId', $uid)
            ->first();
        $temps = explode(',', $pricetypes_user->pricetypes);
        $price_multiplier = 1;
        foreach ($temps as $row) {
            $temp = explode('_', $row);
            if ($temp[0] === $id) {
                $price_multiplier = $temp[1];
            }
        }
        $user = DB::table('users')
            ->where('id', $uid)
            ->first();

        $multiplier = $user->multiplier;
        if ($multiplier) {
            $multiplier = floatval(explode('_', $multiplier)[1]);
        } else {
            $multiplier = 1;
        }

        $query = "SELECT permissions.title FROM (SELECT permission_role.permission_id FROM role_user LEFT JOIN permission_role ON role_user.role_id = permission_role.role_id WHERE role_user.user_id = {$uid}) AS A LEFT JOIN permissions ON A.permission_id = permissions.id WHERE permissions.deleted_at IS NULL";
        $results = DB::select(DB::raw($query));
        $role = array();
        foreach ($results as $row) {
            array_push($role, $row->title);
        }

        return view('admin.prices.index-test', compact('prices', 'id', 'price_type', 'price_multiplier', 'multiplier', 'role'));
    }

    public function create()
    {
        abort_if(Gate::denies('scooter_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "New Item Creating Page";
        $loghistory->save();

        return view('admin.prices.create', compact('pricetypes'));
    }

    public function store(StorePriceRequest $request, Price $price)
    {
        $check = $request->validate([
            'itemcode' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'pricetype_id' => ['required', 'numeric'],
        ]);

        // $price = Price::create($request->all());
        $price = new Price();
        $price->itemcode = $request->itemcode;
        $price->description = $request->description;
        $price->description2 = $request->description2;
        $price->price = $request->price;
        $price->pricetype_id = $request->pricetype_id;

        if($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $price_dir_path = $this->get_price_dir_path();
            $request->image->move($price_dir_path, $imageName);
            $price->image = $imageName;
        }

        $price->save();

        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "New Item Saved";
        $loghistory->save();

        return view('admin.prices.show', compact('price', 'pricetypes'));
    }

    public function edit(Price $price, $id)
    {
        abort_if(Gate::denies('scooter_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $price = Price::findOrFail($id);

        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Existing Item Editing Page";
        $loghistory->save();

        return view('admin.prices.edit', compact('price', 'pricetypes'));
    }

    public function update(UpdatePriceRequest $request, Price $price, SMSGatewayService $SMSGateway, $id)
    {
        $check = $request->validate([
            'itemcode' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'pricetype_id' => ['required', 'numeric'],
        ]);

        $price = Price::findOrFail($id);
        $price->itemcode = $request->itemcode;
        $price->description = $request->description;
        $price->description2 = $request->description2;
        $price->price = $request->price;
        $price->pricetype_id = $request->pricetype_id;
        $price->id_model = $request->id_model;

        
        if($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();       
            $price_dir_path = $this->get_price_dir_path();
            $request->image->move($price_dir_path, $imageName);
            $price->image = $imageName;
        }

        $price->save();

        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Existing Item Updated";
        $loghistory->save();
        
        return view('admin.prices.show', compact('price', 'pricetypes'));

    }

    public function show(Price $price, $id)
    {
        abort_if(Gate::denies('scooter_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $price = Price::findOrFail($id);

        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Existing Item Showing Page";
        $loghistory->save();

        return view('admin.prices.show', compact('price', 'pricetypes'));
    }

    public function destroy(Price $price, $id)
    {
        abort_if(Gate::denies('scooter_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $price = Price::findOrFail($id);

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Existing Item Deleted";
        $loghistory->save();

        $price->delete();

        return back();
    }

    public function excelimport()
    {
        $pricetypes = Pricetype::all();

        $loghistory = new LogHistory();
        $loghistory->user_id = auth()->user()->id;
        $loghistory->table_name = "prices";
        $loghistory->action = "Excel Import Showing Page";
        $loghistory->save();

        return view('admin.prices.import', compact('pricetypes'));
    }

    public function import1(Request $request)
    {
        if($request->hasFile('file')) {
            try {
                $arr_images = array();
                $spreadsheet = IOFactory::load(request()->file('file'));
                $i = 0;
                foreach ($spreadsheet->getActiveSheet()->getDrawingCollection() as $drawing) {                    
                    if ($drawing instanceof MemoryDrawing) {                        
                        ob_start();
                        call_user_func(
                            $drawing->getRenderingFunction(),
                            $drawing->getImageResource()
                        );
                        $imageContents = ob_get_contents();
                        ob_end_clean();
                        switch ($drawing->getMimeType()) {
                            case MemoryDrawing::MIMETYPE_PNG :
                                $extension = 'png';
                                break;
                            case MemoryDrawing::MIMETYPE_GIF:
                                $extension = 'gif';
                                break;
                            case MemoryDrawing::MIMETYPE_JPEG :
                                $extension = 'jpg';
                                break;
                        }
                    } else {                        
                        $zipReader = fopen($drawing->getPath(), 'r');
                        $imageContents = '';
                        while (!feof($zipReader)) {
                            $imageContents .= fread($zipReader, 1024);
                        }
                        fclose($zipReader);
                        $extension = $drawing->getExtension();
                    }

                    $myFileName = time() .++$i. '.' . $extension;
                    file_put_contents("uploads/" . $myFileName, $imageContents);
                    array_push($arr_images, $myFileName);
                }
                Session::put('pricetype_images', implode(",", $arr_images));
                Session::put('pricetype_id', $request->pricetype_id);
                Excel::import(new PriceImport, $request->file);
                return back();
            } catch (\Exception $exp) {
                return back();
            }
        }
    }

    public function import(Request $request) 
    {
        // validate the request
        // $request->validate([
        //     'file' => 'required|mimes:xls,xlsx'
        // ]);

        Session::put('pricetype_id', $request->pricetype_id);

        Excel::import(new PriceImport, $request->file);
        // return back();
        return redirect()->route('admin.scooters.index');
    }

    public function deleteAll(Request $req) {
        $type = $req->id;
        $res = null;
        if($type > 0) {
            $res = Price::where('pricetype_id', $type)->delete();
        } else {
            $res = Price::truncate();
        }
        return response()->json(['result' => $res]);        
    }

    private function get_price_dir_path() {
        if(!file_exists(public_path('uploads/price')) || !is_dir(public_path('uploads/price'))) {
            mkdir(public_path('uploads/price'));
        }

        return public_path('uploads/price');
    }

}
