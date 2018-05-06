<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminAboutController extends AdminController
{
    public function index(){
    // $helps = DB::table('helps')
    //         ->select(
    //             'helps.id',
    //             'helps.category_id',
    //             'helps.product_id',
    //             'helps.name',
    //             'helps.description',
    //             'helps.status',
    //             'helps.created_at as date',
    //             'categories.name as name_category',
    //             'products.name as name_product',
    //             'products.slug as slug_product'
    //         )
    //         ->join('categories', 'categories.id', '=', 'helps.category_id')
    //         ->join('products', 'products.id', '=', 'helps.product_id')
    //         ->orderby('categories.name')
    //         ->orderby('helps.created_at')
    //         ->get();
            
            return view('admin.sobre.index');
    }
}
