<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class STSettings extends Model
{
    use HasFactory;
    protected $table="smart_teller_settings";
    public $timestamps = false;

}
