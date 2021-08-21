<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Famille extends Model
{
    use HasFactory;
   
    
    public function articles(){
        return $this->hasMany('App\Models\Article','famille_id');
    }

    public function commandeG()
    {
        return $this->hasMany('App\Models\Commande','famille_id');
    }
    public function commandeC()
    {
        return $this->hasMany('App\Models\Commande','famille_id');
    }

     public function commandeD()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }

    public function commandes()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }

    public function commandeM()
    {
        return $this->hasMany('App\Models\Commande','famille_id');
    }
    public function commandeZone()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }
     public function commandeZd()
    {
        return $this->hasMany('App\Models\Commande','article_id');
    }
    public function previsions()
    {
        return $this->hasMany('App\Models\Prevision','famille_id');
    }
    public function previsionsm()
    {
        return $this->hasMany('App\Models\Prevision','famille_id');
    }
    public function previsionsC()
    {
        return $this->hasMany('App\Models\Prevision','famille_id');
    }
    public function commandeone()
    {
        return $this->hasMany('App\Models\Commande','famille_id');
    }
    public function commandetwo()
    {
        return $this->hasMany('App\Models\Commande','famille_id');
    }
    public function commandethree()
    {
        return $this->hasMany('App\Models\Commande','famille_id');
    }
}
