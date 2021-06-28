<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request_App;
use App\Models\Status;
use DB;

class Request_AppController extends Controller
{
    //
    public function index() {
        return view('request_app');
    }

    public function getrecords()
    {
        $request_apps = Request_App::leftJoin('Statuses', 'Request_Apps.status_id', '=', 'Statuses.status_id')
        ->select('Request_Apps.*', 'Statuses.name')
        ->get(); 

        $statuses = DB::table('statuses')
			->select('Statuses.*')
			->get(); 
             
        return response()->json(['request_apps' => $request_apps, 'statuses' => $statuses],
            200);       
    }


}
