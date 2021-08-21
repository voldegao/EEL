<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Methode extends Model
{
    use HasFactory;
    protected $table = 'methode_previsions';
 
    public function previsions(){
       return $this->hasMany('App\Models\Prevision','methode_id');
    }
}
