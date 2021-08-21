<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prevision extends Model
{
    use HasFactory;
    
    public function familles(){
       return $this->hasMany('App\Models\Familles','famille_id');
    }
    
    public function articles(){
       return $this->belongsTo('App\Models\Article','article_id');
    }
}
