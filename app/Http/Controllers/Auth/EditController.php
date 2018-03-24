<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditController extends Controller
{
    
    protected function edit(Request $request){
        $user = User::findOrFail($request->get('id'));
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->email = $request->get('sex');
        $user->email = $request->get('cpf_cnpj');
        $user->email = $request->get('email');
        $user->email = $request->get('email');
        $user->save();
        dd($request->all());
    }

}
