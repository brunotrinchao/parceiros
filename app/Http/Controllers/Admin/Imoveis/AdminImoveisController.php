<?php

namespace App\Http\Controllers\Admin\Imoveis;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Imovel\Properties;
use App\Helpers\Helper;

class AdminImoveisController extends AdminController
{
    public function index(){
        return view('admin.imoveis.home.index');
    }

    

}
