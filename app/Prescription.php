<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $table = 'prescriptions';

    public function Patient()
    {
        return $this->hasOne('App\Patient', 'id', 'patient_id');
    }

    public function Medicine()
    {
        return $this->belongsToMany('App\Medicine', 'prescription_medicines');
    }

    public function Treatment()
    {
        return $this->belongsToMany('App\Treatment', 'prescription_treatments');
    }
}
