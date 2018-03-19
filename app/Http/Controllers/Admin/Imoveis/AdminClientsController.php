<?php

namespace App\Http\Controllers\Admin\Imoveis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Properties;

class AdminClientsController extends Controller
{
    public function clients(){
        return $this->hasMany(Properties::class);
    }
}
