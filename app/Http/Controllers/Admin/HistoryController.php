<?php

namespace App\Http\Controllers\Admin;

use App\LogHistory;
use Illuminate\Support\Facades\DB;


class HistoryController
{
    public function index()
    {
        if(auth()->user()->getIsAdminAttribute()) {
            $loghistories = DB::table('loghistories')
                ->select('users.email', 'users.name', 'loghistories.table_name', 'loghistories.action', 'loghistories.created_at', 'loghistories.id')
                ->leftJoin('users', 'loghistories.user_id', '=', 'users.id')                    
                ->get();
        } else {
            $loghistories = DB::table('loghistories')
                ->select('users.email', 'users.name', 'loghistories.table_name', 'loghistories.action', 'loghistories.created_at', 'loghistories.id')
                ->leftJoin('users', 'loghistories.user_id', '=', 'users.id')                    
                ->where('users.email', auth()->user()->email)
                ->get();
        }
        

        return view('admin.history', compact('loghistories'));
    }
}
