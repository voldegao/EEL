<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Article extends Model
{
    use HasFactory;

    public function famille()
    {
        return $this->belongsTo('App\Models\Famille','famille_id');
    }
    //  public function stock()
    // {
    //     return $this->hasMany('App\Models\Stocl','article_id');
    // }

    public function stocks(){
        return $this->hasMany('App\Models\Stock','article_id');
     }

    public function stocksM(){
        return $this->hasMany('App\Models\Stock','article_id');
     }

    public function commandes()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }

    public function previsions()
    {
        return $this->hasMany('App\Models\Prevision','article_id');
    }
    public function previsionsm()
    {
        return $this->hasMany('App\Models\Prevision','article_id');
    }
     public function previsionsG()
    {
        return $this->hasMany('App\Models\Prevision','article_id');
    }
     public function previsionsS()
    {
        return $this->hasMany('App\Models\Prevision','article_id');
    }
     public function previsionsC()
    {
        return $this->hasMany('App\Models\Prevision','article_id');
    }

    public function coefficients()
    {
        return $this->hasMany('App\Models\Coefficient','article_id');
    }

    public function biais()
    {
        return $this->hasMany('App\Models\Biais','article_id');
    }

    public function mads()
    {
        return $this->hasMany('App\Models\Mad','article_id');
    }

     public function commandeG()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }
     public function commandeV()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }
     public function commandeC()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }
     public function commandeM()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }
     public function commandesM()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }
     public function commandeone()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }
     public function commandetwo()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }

    public function commandethree()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }

     public function commandeD()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }
    public function commandeZone()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }
     public function commandeZd()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }

}
