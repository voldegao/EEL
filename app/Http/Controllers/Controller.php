<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Famille;
use App\Models\Zone;
use App\Models\Client;
use App\Models\Taux;
use App\Models\Alpha;
use DB;

use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function getAll()
    {
        $clients = Client::all();
        $familles = Famille::all();
        $zones = Zone::all();
        $taux = Taux::all();
        $data = [
            "familles"=>$familles,
            "zones"=>$zones,
            "clients"=>$clients,
            "taux"=>$taux
        ];

        return $data;
    }

    public function getAlpha()
    {
        
        $alpha = Alpha::where('active',1)->get();

        return $alpha;
    }

     public function editAlpha(Request $request)
    {
        $a = Alpha::where('id',1)->first();
        $a->alpha = $request->alpha;
        $a->save();
        return $request->alpha;
    }



    public function ListeFamilles()
    {
       $Familles = DB::connection('sqlsrv')->select('SELECT DISTINCT ARTCOLLECTION FROM dbo.ARTICLES');
       return $Familles;
    } 
    public function ListeArticles()
    {
       $Articles = DB::connection('sqlsrv')->select('SELECT ARTID,ARTCODE,ARTDESIGNATION,ARTCOLLECTION FROM dbo.ARTICLES');
       return $Articles;
    }

    public function ListeZones()
    {
       $Zones = DB::connection('sqlsrv')->select('SELECT DISTINCT CLIVILLE FROM dbo.V_STATISTIQUE_VENTE');
       return $Zones;
    }
    
    public function ListeVentes()
    {
        $Ventes = DB::connection('sqlsrv')->select("SELECT TOP 4 PLVID,ARTID,PCVNUM,DATELIGNE,PLVQTEUS,CLIVILLE,MNTNETHT FROM dbo.V_STATISTIQUE_VENTE WHERE (PCVNUM LIKE '%BC%')");
        return $Ventes;
    }

     public function ListeStocks()
    {
        $Stocks = DB::connection('sqlsrv')->select("SELECT TOP 4 ARTID,STOCKREEL FROM dbo.V_STOCK_ARTICLES");
        return $Stocks;
        // return $start = new Carbon('last day of last month');
        // return Carbon::now();
    }

    //Ajout données au DB Reception

    public function TransfertFamille()
    {
       $Familles = $this->ListeFamilles();
       $sum = 0;
       foreach ($Familles as $item) {
           $checkFamille = Famille::where('famille',$item->ARTCOLLECTION)->first(); 
           if(!$checkFamille){
                $famille = new Famille;
                $famille->famille = $item->ARTCOLLECTION;
                $famille->tauxsecurite_id = 1;
                $famille->save();
                $sum = $sum + 1;
           }
       }
       return $sum." Famille(s) Ajoutée(s) à la base de donnée.";
    } 

     public function TransfertZone()
    {
       $Zones = $this->ListeZones();
       $sum = 0;
       foreach ($Zones as $item) {
           $checkZone = Zone::where('zone',$item->CLIVILLE)->first(); 
           if(!$checkZone){
                $zone = new Zone;
                $zone->zone = $item->CLIVILLE;
                $zone->save();
                $sum = $sum + 1;
           }
       }
       return $sum." Zone(s) Ajouté(s) à la base de donnée.";
    } 

    public function TransfertArticle()
    {
       $Articles = $this->ListeArticles();
       $sum = 0;
       foreach ($Articles as $item) {
           $checkArticle = Article::where('code',$item->ARTCODE)->first(); 
           if(!$checkArticle){
                $famille_id = Famille::where('famille',$item->ARTCOLLECTION)->first();
                $article = new Article;
                $article->ARTID = $item->ARTID;
                $article->code = $item->ARTCODE;
                $article->designation = $item->ARTDESIGNATION;
                $article->famille_id = $famille_id->id;
                $article->classe = "A";
                $article->strategie = "MTS";
                $article->tauxsecurite_id = 1;
                $article->save();
                $sum = $sum + 1;
           }
       }
       return $sum." Article(s) Ajouté(s) à la base de donnée.";
    } 

      public function TransfertCommande()
    {
       $Ventes = $this->ListeVentes();
       $sum = 0;
       foreach ($Ventes as $item) {
           $checkVente = Commande::where('CMDID',$item->PLVID)->first(); 
           if(!$checkVente){

                $Article = Article::where('ARTID',$item->ARTID)->first();
                $Zone = Zone::where('zone',$item->CLIVILLE)->first();

                $vente = new Commande;
                $vente->article_id = $Article->id;
                $vente->famille_id = $Article->famille_id;
                $vente->CMDID = $item->PLVID;
                $vente->zone_id = $Zone->id;
                $vente->quantite = $item->PLVQTEUS;
                $vente->montant = $item->MNTNETHT;
                $vente->date_vente = $item->DATELIGNE;
                $vente->save();
                $sum = $sum + 1;
           }
       }
       return $sum." Vente(s) Ajoutée(s) à la base de donnée.";
    } 


     public function TransfertStock()
    {
       $Stocks = $this->ListeStocks();
       $sum = 0;
       foreach ($Stocks as $item) {
           $Article = Article::where('ARTID',$item->ARTID)->first();
           if($Article){
                $stock = new Stock;
                $stock->article_id = $Article->id;
                $stock->quantite = $item->STOCKREEL;
                $stock->date = Carbon::now();
                $stock->save();
                $sum = $sum + 1;
           }
       }
       return $sum." Stock(s) Ajouté(s) à la base de donnée.";
    } 

}
