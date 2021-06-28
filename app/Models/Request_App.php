<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request_App extends Model
{
    use HasFactory;

    public $timestamp = false;

    protected $table = 'request_apps';
    protected $primaryKey =  'request_id';
}
