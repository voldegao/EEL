<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mad extends Model
{
    use HasFactory;

    function famille() {
        return $this->belongsTo('App\Models\Famille',"famille_id");
    }

    function article() {
        return $this->belongsTo('App\Models\Article',"article_id");
    }

    function prevision() {
        return $this->belongsTo('App\Models\Prevision',"prevision_id");
    }
}
