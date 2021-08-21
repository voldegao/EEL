<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Famille;
use App\Models\Zone;
use App\Models\Prevision;
use App\Models\Taux;
use App\Models\Article;
use App\Models\Alpha;
use DB;

use Illuminate\Http\Request;

class GenerationController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // public function getPrevision(Request $request)
    // {
    //     $previsions = Article::with(['previsions' => function ($q) {
    //         $q->selectRaw(DB::raw('article_id,prevision,extract(MONTH from date) AS Mois,Extract(YEAR FROM date) as Year'));
    //     }])
    //         ->with(['stocks' => function ($q) use ($request) {
    //             $q->selectRaw(DB::raw('article_id,quantite,extract(MONTH from date) AS Mois,Extract(YEAR FROM date) as Year'))
    //                 ->whereBetween('date', [$request->dateDebut, $request->dateFin]);
    //         }])
    //         ->get();
    //     return $previsions;
    // }


    public function getArticleData(Request $request)
    {
        $data = Article::when($request->code, function($q) use ($request){    //Search by Nom Article
                            $q->where('code', 'like', '%'.$request->code.'%');
                        })
                        ->when($request->article, function($q) use ($request){    //Search by Nom Article
                            $q->where('designation', 'like', '%'.$request->article.'%');
                        })
                        ->when($request->famille, function($query) use ($request){                   //Search by Famille
                            $query->whereHas('famille', function($q) use ($request) {
                                $q->where('famille', 'like', '%'.$request->famille.'%');
                            });
                        }) 
                        ->with(['previsions' => function ($q) use($request) {
                            $q->selectRaw(DB::raw('article_id,prevision,extract(WEEK from date) as Week,extract(MONTH from date) AS Mois,Extract(YEAR FROM date) as Year'))
                             ->whereBetween('date', [$request->datePrevDebut, $request->datePrevFin])
                             ->groupByRaw('article_id,extract(WEEK FROM date),extract(MONTH from date),extract(YEAR FROM date)')
                             ->orderBy('Year','asc')
                             ->orderBy('Mois','asc')
                             ->orderBy('Week','asc');
                         }])
                        ->with(['stocks' => function ($q) use ($request) {
                             $q->selectRaw(DB::raw('article_id,quantite,extract(MONTH from date) AS Mois,Extract(YEAR FROM date) as Year,Extract(WEEK FROM date) as Week'))
                             ->whereBetween('date', [$request->dateStockDebut, $request->dateStockFin])
                             ->groupByRaw('article_id,extract(MONTH from date),extract(WEEK FROM date)')
                             ->orderBy('Year','asc')
                             ->orderBy('Mois','asc')
                             ->orderBy('Week','asc');
                            }])
                        ->with(['commandes' => function ($q) use ($request) {
                             $q->selectRaw(DB::raw('article_id,quantite,extract(MONTH from date_vente) AS Mois,Extract(YEAR FROM date_vente) as Year,Extract(WEEK FROM date_vente) as Week'))
                             ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                             ->whereBetween('date_vente', [$request->dateCmdDebut, $request->dateCmdFin])
                             ->groupByRaw('article_id,extract(MONTH from date_vente),extract(WEEK FROM date_vente)')
                             ->orderBy('Year','asc')
                             ->orderBy('Mois','asc')
                             ->orderBy('Week','asc');
                            }])
                        ->with(['previsionsS' => function ($q) use($request) {
                                $q->selectRaw(DB::raw('article_id,prevision,extract(MONTH from date) AS Mois,Extract(YEAR FROM date) as Year'))
                                ->selectRaw(DB::raw('sum(prevision) as Sumprev'))
                                 ->whereBetween('date', [$request->datePrevDebut, $request->datePrevFin])
                                 ->groupByRaw('article_id,extract(MONTH from date),extract(YEAR FROM date)')
                                 ->orderBy('Year','asc')
                                 ->orderBy('Mois','asc');
                             }])
                        ->with(['stocksM' => function ($q) use ($request) {
                             $q->selectRaw(DB::raw('article_id,quantite,extract(MONTH from date) AS Mois,Extract(YEAR FROM date) as Year'))
                             
                             ->whereBetween('date', [$request->dateStockDebut, $request->dateStockFin])
                             ->latest('date')->first();
                            }])
                        ->with(['commandesM' => function ($q) use ($request) {
                             $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS Mois,Extract(YEAR FROM date_vente) as Year'))
                             ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                             ->whereBetween('date_vente', [$request->dateCmdDebut, $request->dateCmdFin])
                             ->groupByRaw('article_id,extract(MONTH from date_vente)')
                             ->orderBy('Year','asc')
                             ->orderBy('Mois','asc');
                            }])
                        ->get();
        return $data;
    }
}
