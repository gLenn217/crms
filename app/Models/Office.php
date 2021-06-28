<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    public $timestamp = false;

    protected $table = 'offices';
    protected $primaryKey =  'office_id';

    public function contact_person() {
        return $this->belongsTo('App\Models\Contact_Person');
    }
}
