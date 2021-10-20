<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class STUser extends Model
{
    use HasFactory;
    protected $table="mdlub_user";
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;
}
