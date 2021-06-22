<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact_Person extends Model
{
    use HasFactory;

    public $timestamp = false;

    protected $table = 'contact_persons';
    protected $primaryKey =  'contact_id';
}
