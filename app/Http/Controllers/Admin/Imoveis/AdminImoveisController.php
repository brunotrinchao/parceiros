<?php

namespace App\Http\Controllers\Admin\Imoveis;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;

class AdminImoveisController extends AdminController
{
    public function index(){
        return view('admin.imoveis.home.index');
    }

}
