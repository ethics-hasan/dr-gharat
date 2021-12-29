<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrescriptionTreatment extends Model
{
    protected $table = 'prescription_treatments';


    public function Treatment()
    {
        return $this->hasOne('App\Treatment', 'id', 'treatment_id');
    }
}
