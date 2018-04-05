<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Archive extends Model
{
    
    public function product()
    {
        return $this->belongsTo(Product);
    }

    
}
