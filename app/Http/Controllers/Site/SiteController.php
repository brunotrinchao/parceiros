<?php

namespace App\Http\Controllers\Site;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    public function index(Request $request){
        $user = false;
        if(Auth::check()){
            $user = auth()->user();
        }
        return view('site.index', ['user' => $user]);
    }
    public function login(Request $request){
        $user = false;
        if(Auth::check()){
            $user = auth()->user();
        }
        return redirect()->route('site.index', ['user' => $user]);
    }
}
