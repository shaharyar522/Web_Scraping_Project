<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoScrape extends Model
{
    protected $table = "demo_scrapes";


    protected $fillable = ['product_star_rating','product_title','product_price', 'product_stock'];
}
