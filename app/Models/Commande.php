<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Commande extends Model
{
    use HasFactory;

    public function article()
    {
        return $this->belongsTo('App\Models\Article','article_id');
    }

    public function famille()
    {
        return $this->belongsTo('App\Models\Famille','article_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }
    public function zone()
    {
        return $this->belongsTo('App\Models\Zone','zone_id');
    }

    public function f(){
          return $Commandes = Commande::selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS mois'))
           ->selectRaw(DB::raw('avg(quantite) AS moyenne'))
           ->selectRaw(DB::raw('sum(quantite) as ventes'))
           ->groupByRaw('article_id,EXTRACT(MONTH from date_vente)')
           ->with(['article','famille','client','zone'])
           ->get();
           
    }
}
