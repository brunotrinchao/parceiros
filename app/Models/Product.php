<?php

namespace App\Models;
use App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public static function list(){
        $products = Product::get();
        return $products;
    }
}
