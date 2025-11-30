<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoScrape extends Model
{
    protected $table = "demo_scrapes";

    protected $fillable = ['text'];
}
