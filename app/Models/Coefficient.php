<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coefficient extends Model
{
    use HasFactory;

    function famille() {
        return $this->belongsTo('App\Models\Famille',"famille_id");
    }

    function article() {
        return $this->belongsTo('App\Models\Article',"article_id");
    }

    function zone() {
        return $this->belongsTo('App\Models\Zone',"zone_id");
    }

    function client() {
        return $this->belongsTo('App\Models\Client',"client_id");
    }


}
