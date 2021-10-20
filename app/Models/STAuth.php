<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class STAuth extends Model
{
    use HasFactory;
    protected $table="smart_teller_auth";
    public $timestamps = false;
}
