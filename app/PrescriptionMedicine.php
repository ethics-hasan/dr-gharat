<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrescriptionMedicine extends Model
{

	    protected $table = 'prescription_medicines';

     public function Medicine(){
    	        return $this->hasOne('App\Medicine', 'id', 'medicine_id');
    }
}
