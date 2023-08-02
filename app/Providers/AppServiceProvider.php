<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Pricetype;
use App\Language;
use Illuminate\Support\Facades\DB;
use Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $viewShare['languagelists'] = Language::all();

        view()->share($viewShare);

        view()->composer('partials.menu', function($view) {
            $user_id = auth()->user()->id;        

            $pricetypes = DB::table('pricetypes')
                ->select('id', 'name')
                ->whereNull('deleted_at')
                ->get();
            $pricetypes_array = array();
            foreach ($pricetypes as $row) {
                $pricetypes_array[$row->id] = $row->name;
            }
            $pricetypes_user = DB::table('pricetypes_user')
                ->select('pricetypes')
                ->where('userId', auth()->user()->id)
                ->first();
            $pricetypes_user_array = array();
            $role = DB::table('role_user')
                ->select('role_id')
                ->where('user_id', auth()->user()->id)
                ->first();
            $multiplier = auth()->user()->multiplier;
            if ($multiplier) {
                $multiplier = floatval(explode('_', $multiplier)[1]);
            } else {
                $multiplier = 1;
            }
            if ($pricetypes_user) {
                $temps = explode(',', $pricetypes_user->pricetypes);
                foreach ($temps as $key => $row) {
                    $temp = explode('_', $row);
                    $pricetypes_user_array[$key]['id'] = $temp[0];
                    if ($role->role_id === 1) {
                        $pricetypes_user_array[$key]['name'] = $pricetypes_array[$temp[0]] . ' - ' . (floatval($temp[1]) * $multiplier) ;
                    } else {
                        $pricetypes_user_array[$key]['name'] = $pricetypes_array[$temp[0]];
                    }
                }
            }

            $view->with([
                'pricetypelist_count' => Pricetype::all()->count(),
                'menu_pricetypelist' => $pricetypes_user_array,
            ]);
        });
    }
}
