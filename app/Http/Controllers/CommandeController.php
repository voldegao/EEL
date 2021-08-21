<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Article;
use App\Models\Famille;
use App\Models\Prevision;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;

class CommandeController extends Controller
{

    //test coefficient

    public function test()
       {
           $Commandes = Commande::selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS mois'))
           ->selectRaw(DB::raw('avg(quantite) AS moyenne'))
           ->selectRaw(DB::raw('sum(quantite) as ventes'))
           ->groupByRaw('article_id,EXTRACT(MONTH from date_vente)')
           ->with(['article','famille','client','zone'])
           ->get();
           return $Commandes;
       }


    public function cv(Request $request)
       {
           $Commandes = Article::when($request->article, function($q) use ($request){    //Search by Nom Article
                    $q->where('designation', 'like', '%'.$request->article.'%');
                })
            ->when($request->code, function($q) use ($request){                       //Search by Code Article
                    $q->where('code', 'like', '%'.$request->code.'%');
                })
            ->when($request->classe, function($q) use ($request){                       //Search by Code Article
                    $q->where('classe', 'like', '%'.$request->classe.'%');
                })
            ->when($request->famille, function($query) use ($request){                   //Search by Famille
                        $query->whereHas('famille', function($q) use ($request) {
                            $q->where('famille', 'like', '%'.$request->famille.'%');
                        });
                }) 
            ->when($request->client, function($query) use ($request){                    //Search by Nom Client
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereHas('client', function($qi) use ($request){
                            $qi->where('designation', 'like', '%'.$request->client.'%');
                        });
                    });
                })
            ->when($request->zone, function($query) use ($request){                    //Search by Nom Zone
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        });
                    });
                })
            ->when(($request->dateDebut && $request->dateFin), function($query) use ($request){                    //Search by Nom Client
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereBetween('date_vente', [$request->dateDebut, $request->dateFin]);
                    });
                })           
            ->with(['commandeG'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('zone_id,article_id,extract(MONTH from date_vente) AS Mois'))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('article_id');
                    }])
            ->with(['commandeV'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id'))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('article_id');
                    }])
            ->with(['commandeZone'=> function($q) use ($request){
                        $q->selectRaw(DB::raw('zone_id,article_id,extract(MONTH from date_vente) AS Mois'))
                          ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                          ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                          ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                          ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                          ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                          ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                          ->whereHas('zone', function($qi) use ($request){
                                   $qi->where('zone', 'like', '%'.$request->zone.'%');
                               })
                          ->with('zone')
                          ->groupByRaw('article_id,zone_id');
                    }]) 
            ->with(['commandeZd'=> function($q) use ($request){
                        $q->selectRaw(DB::raw('zone_id,article_id,extract(MONTH from date_vente) AS Mois'))
                          ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                          ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                          ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                          ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                          ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                          ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                          ->whereHas('zone', function($qi) use ($request){
                                   $qi->where('zone', 'like', '%'.$request->zone.'%');
                               })
                          ->groupByRaw('article_id,zone_id,Extract( MONTH FROM date_vente )');
                    }])             
           ->with(['commandeD'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS Mois'))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('article_id,Extract( MONTH FROM date_vente )');
                    }])
           ->with(['commandeC'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS Mois,extract(YEAR FROM date_vente) as Year'))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('article_id,Extract( MONTH FROM date_vente ),Extract( YEAR FROM date_vente )')
                   ->orderBy('Year','asc')
                   ->orderBy('Mois','asc');
                    }])
            ->with(['commandes'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('*'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->orderBy('zone_id','asc');

                    }])
            ->with(['previsions'=> function($q) use ($request){
                $q->selectRaw(DB::raw('article_id,Extract( MONTH FROM date ) as mois,sum(prevision) as Sumprevision'))
                    ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                    ->groupByRaw('article_id,Extract( MONTH FROM date )');
            }])
            ->with(['previsionsC'=> function($q) use ($request){
                $q->selectRaw(DB::raw('article_id,Extract( MONTH FROM date ) as mois,Extract(YEAR FROM date) as Year,sum(prevision) as Sumprevision'))
                    ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                    ->groupByRaw('article_id,Extract( MONTH FROM date ),Extract(YEAR FROM date)')
                    ->orderBy('Year','asc')
                    ->orderBy('Mois','asc');
            }])
           ->with('famille')
           ->paginate(10);
           return $Commandes;
       }

       public function cvChart(Request $request)
       {
           $Commandes = Article::when($request->article, function($q) use ($request){    //Search by Nom Article
                    $q->where('designation', 'like', '%'.$request->article.'%');
                })
            ->when($request->code, function($q) use ($request){                       //Search by Code Article
                    $q->where('code', 'like', '%'.$request->code.'%');
                })
            ->when($request->famille, function($query) use ($request){                   //Search by Famille
                        $query->whereHas('famille', function($q) use ($request) {
                            $q->where('famille', 'like', '%'.$request->famille.'%');
                        });
                }) 
            ->when($request->client, function($query) use ($request){                    //Search by Nom Client
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereHas('client', function($qi) use ($request){
                            $qi->where('designation', 'like', '%'.$request->client.'%');
                        });
                    });
                })
            ->when($request->zone, function($query) use ($request){                    //Search by Nom Zone
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        });
                    });
                })
            ->when(($request->dateDebut && $request->dateFin), function($query) use ($request){                    //Search by Nom Client
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereBetween('date_vente', [$request->dateDebut, $request->dateFin]);
                    });
                })           
            ->with(['commandeG'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('zone_id,article_id,extract(MONTH from date_vente) AS Mois'))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('article_id');
                    }])
            ->with(['commandeZone'=> function($q) use ($request){
                        $q->selectRaw(DB::raw('zone_id,article_id,extract(MONTH from date_vente) AS Mois'))
                          ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                          ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                          ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                          ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                          ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                          ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                          ->whereHas('zone', function($qi) use ($request){
                                   $qi->where('zone', 'like', '%'.$request->zone.'%');
                               })
                          ->groupByRaw('article_id,zone_id');
                    }]) 
            ->with(['commandeZd'=> function($q) use ($request){
                        $q->selectRaw(DB::raw('zone_id,article_id,extract(MONTH from date_vente) AS Mois'))
                          ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                          ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                          ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                          ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                          ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                          ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                          ->whereHas('zone', function($qi) use ($request){
                                   $qi->where('zone', 'like', '%'.$request->zone.'%');
                               })
                          ->groupByRaw('article_id,zone_id,Extract( MONTH FROM date_vente )');
                    }])             
           ->with(['commandeD'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS Mois,extract(YEAR from date_vente) as Year'))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('article_id,Extract( MONTH FROM date_vente ),extract(YEAR from date_vente)')
                   ->orderBy('Year','asc')
                   ->orderBy('Mois','asc');
                    }])
            ->with(['commandes'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('*'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->orderBy('zone_id','asc');

                    }])
           ->with('famille')
           ->get();
           return $Commandes;
       }


       // bia
       public function biaisFamille(Request $request)
       {

           $Commandes = Famille::with('articles')
           ->when($request->famille, function($q) use ($request){    //Search by Nom Article
                    $q->where('famille', 'like', '%'.$request->famille.'%');
                })
           ->with(['commandeG'=>function($q) use ($request){    //Search by Nom Article
                $q->selectRaw(DB::raw('famille_id,extract(MONTH from date_vente) AS Mois,extract(YEAR FROM date_vente) as Year'))
                ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                ->groupByRaw('famille_id,Extract( MONTH FROM date_vente ),extract(YEAR FROM date_vente)')
                ->orderBy('Year','asc')
           ->orderBy('Mois','asc');
             }])
           ->with(['previsions'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('famille_id,Extract( MONTH FROM date ) as Mois,extract(YEAR FROM date) as Year,sum(prevision) as Sumprevision'))
                   ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                   ->groupByRaw('famille_id,Extract( MONTH FROM date ),extract(YEAR From date)')
                   ->orderBy('Year','asc')
                   ->orderBy('Mois','asc');
                    }])
           ->groupBy('id')
           ->paginate(20);
           return $Commandes;
       }


       public function biais(Request $request)
       {

           $Commandes = Article::when($request->article, function($q) use ($request){    //Search by Nom Article
                    $q->where('designation', 'like', '%'.$request->article.'%');
                })
            ->when($request->code, function($q) use ($request){                       //Search by Code Article
                    $q->where('code', 'like', '%'.$request->code.'%');
                })
            ->when($request->famille, function($query) use ($request){                   //Search by Famille
                        $query->whereHas('famille', function($q) use ($request) {
                            $q->where('famille', 'like', '%'.$request->famille.'%');
                        });
                }) 
            ->when($request->client, function($query) use ($request){                    //Search by Nom Client
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereHas('client', function($qi) use ($request){
                            $qi->where('designation', 'like', '%'.$request->client.'%');
                        });
                    });
                })
            ->when(($request->dateDebut && $request->dateFin), function($query) use ($request){                    //Search by Nom Client
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereBetween('date_vente', [$request->dateDebut, $request->dateFin]);
                    });
                })           
            ->with(['commandeG'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS Mois'))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                   ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->groupByRaw('article_id');
                    }])
            ->with(['commandeD'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS Mois,extract(YEAR FROM date_vente) as Year'))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('article_id,Extract( MONTH FROM date_vente ),extract(YEAR FROM date_vente)')
                   ->orderBy('Year','asc')
                   ->orderBy('Mois','asc');
                    }])
           ->with(['previsions'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,Extract( MONTH FROM date ) as mois,extract(YEAR FROM date) as Year,sum(prevision) as Sumprevision'))
                   ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                   ->groupByRaw('article_id,Extract( MONTH FROM date ),extract(YEAR FROM date)')
                   ->orderBy('Year','asc')
                   ->orderBy('Mois','asc');
                    }])
           ->with('famille')
           ->paginate(20);
           return $Commandes;
       }

        public function mad(Request $request)
       {

           $Commandes = Article::when($request->article, function($q) use ($request){    //Search by Nom Article
                    $q->where('designation', 'like', '%'.$request->article.'%');
                })
            ->when($request->code, function($q) use ($request){                       //Search by Code Article
                    $q->where('code', 'like', '%'.$request->code.'%');
                })
            ->when($request->famille, function($query) use ($request){                   //Search by Famille
                        $query->whereHas('famille', function($q) use ($request) {
                            $q->where('famille', 'like', '%'.$request->famille.'%');
                        });
                }) 
            ->when($request->client, function($query) use ($request){                    //Search by Nom Client
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereHas('client', function($qi) use ($request){
                            $qi->where('designation', 'like', '%'.$request->client.'%');
                        });
                    });
                })
            ->when(($request->dateDebut && $request->dateFin), function($query) use ($request){                    //Search by Nom Client
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereBetween('date_vente', [$request->dateDebut, $request->dateFin]);
                    });
                })           
            ->with(['commandeG'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS Mois'))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                   ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->groupByRaw('article_id');
                    }])
            ->with(['commandeD'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS Mois'))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('article_id,Extract( MONTH FROM date_vente )');
                    }])
            ->with(['commandeC'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS Mois,extract(YEAR from date_vente) as Year'))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('article_id,Extract( MONTH FROM date_vente ),extract(YEAR from date_vente)')
                   ->orderBy('Year','asc')
                   ->orderBy('Mois','asc');
                    }])
           ->with(['previsions'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,Extract( MONTH FROM date ) as mois,sum(prevision) as Sumprevision'))
                   ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                   ->groupByRaw('article_id,Extract( MONTH FROM date )');
                    }])
           ->with(['previsionsG'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,Extract( MONTH FROM date ) as mois,sum(prevision) as Sumprevision'))
                   ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                   ->groupByRaw('article_id');
                    }])
           ->with(['previsionsC'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,Extract( MONTH FROM date ) as mois,Extract(YEAR from date) as Year,sum(prevision) as Sumprevision'))
                   ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                   ->groupByRaw('article_id,Extract(MONTH from date),Extract(YEAR FROM date)')
                   ->orderBy('Year','asc')
                   ->orderBy('mois','asc');
                    }])
           ->with('famille')
           ->paginate(10);
           return $Commandes;
       }

       public function madFamille(Request $request)
       {

           $Commandes = Famille::with('articles')
           ->when($request->famille, function($q) use ($request){    //Search by Nom Article
                    $q->where('famille', 'like', '%'.$request->famille.'%');
                })
           ->with(['commandeG'=>function($q) use ($request){    //Search by Nom Article
                $q->selectRaw(DB::raw('famille_id,extract(MONTH from date_vente) AS Mois,extract(YEAR from date_vente) as Year'))
                ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                ->groupByRaw('famille_id,Extract( MONTH FROM date_vente ),extract(YEAR from date_vente)')
                ->orderBy('Year','asc')
                ->orderBy('Mois','asc');
             }])
           ->with(['commandeD'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS Mois'))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('article_id,Extract( MONTH FROM date_vente )');
                    }])
           ->with(['commandeC'=> function($q) use ($request){
                $q->selectRaw(DB::raw('famille_id,extract(MONTH from date_vente) AS Mois,extract(YEAR FROM date_vente) as Year'))
                ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                ->whereHas('zone', function($qi) use ($request){
                        $qi->where('zone', 'like', '%'.$request->zone.'%');
                    })
                ->groupByRaw('famille_id,Extract( MONTH FROM date_vente ),extract(YEAR FROM date_vente)')
                ->orderBy('Year','asc')
                ->orderBy('Mois','asc');
                }])
           ->with(['previsions'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('famille_id,Extract( MONTH FROM date ) as mois,sum(prevision) as Sumprevision'))
                   ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                   ->groupByRaw('famille_id,Extract( MONTH FROM date )');
                    }])
           ->with(['previsionsC'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('famille_id,Extract( MONTH FROM date ) as mois,Extract(YEAR FROM date) as Year,sum(prevision) as Sumprevision'))
                   ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                   ->groupByRaw('famille_id,Extract( MONTH FROM date ),Extract(YEAR from date)')
                   ->orderBy('Year','asc')
                   ->orderBy('mois','asc');
                    }])
           ->groupBy('id')
           ->paginate(20);
           return $Commandes;
       }



     public function moyenneMobile(Request $request)
       {

           $Commandes = Famille::with('articles')
           ->when($request->famille, function($q) use ($request){    //Search by Nom Article
                    $q->where('famille', 'like', '%'.$request->famille.'%');
                })
           ->when(($request->dateDebut && $request->dateFin), function($query) use ($request){                    //Search by Nom Client
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereBetween('date_vente', [$request->dateDebut, $request->dateFin]);
                    });
                })           
           ->with(['commandeG'=>function($q) use ($request){    //Search by Nom Article
                $q->selectRaw(DB::raw('famille_id,extract(WEEK from date_vente) AS semaine'))
                ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                ->selectRaw(DB::raw('COUNT(Extract( WEEK FROM date_vente )) AS NombreVente'))
                ->selectRaw(DB::raw('COUNT(DISTINCT Extract( WEEK FROM date_vente )) AS Nombresemaine'))
                ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( WEEK FROM date_vente ))) AS MoyennePeriode'))
                ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                ->groupByRaw('Extract( WEEK FROM date_vente )');
             }])
           ->with(['commandeD'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('article_id,extract(WEEK from date_vente) AS semaine' ))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( WEEK FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( WEEK FROM date_vente )) AS Nombresemaine'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('article_id,Extract( WEEK FROM date_vente )');
                    }])

            ->with(['commandeM'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('famille_id,extract(MONTH from date_vente) AS mois' ))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('famille_id,Extract( MONTH FROM date_vente )');
                    }])
                    
            ->with(['commandeone'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('famille_id,extract(MONTH from date_vente) AS mois' ))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->whereBetween('date_vente', [$request->dateDebut0, $request->dateFin0])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('famille_id,Extract( MONTH FROM date_vente )');
                    }])
                    
            ->with(['commandetwo'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('famille_id,extract(MONTH from date_vente) AS mois' ))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->whereBetween('date_vente', [$request->dateDebut1, $request->dateFin1])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('famille_id,Extract( MONTH FROM date_vente )');
                    }])
                    
            ->with(['commandethree'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('famille_id,extract(MONTH from date_vente) AS mois' ))
                   ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                   ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                   ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                   ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                   ->whereBetween('date_vente', [$request->dateDebut2, $request->dateFin2])
                   ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                   ->groupByRaw('famille_id,Extract( MONTH FROM date_vente )');
                    }])
                    
           ->with(['previsions'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('famille_id,Extract( WEEK FROM date ) as mois,sum(prevision) as Sumprevision'))
                   ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                   ->groupByRaw('Extract( WEEK FROM date )');
                    }])
           ->with(['previsionsm'=> function($q) use ($request){
                 $q->selectRaw(DB::raw('famille_id,Extract( MONTH FROM date ) as mois,sum(prevision) as Sumprevision'))
                   ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                   ->groupByRaw('Extract( MONTH FROM date )');
                    }])
           ->groupBy('id')
           ->paginate(20);
           return $Commandes;

       }

     public function moyenneMobilearticles(Request $request)
       {
            $Commandes = Article::when($request->article, function($q) use ($request){    //Search by Nom Article
                    $q->where('designation', 'like', '%'.$request->article.'%');
                })
            ->when($request->code, function($q) use ($request){                       //Search by Code Article
                    $q->where('code', 'like', '%'.$request->code.'%');
                })
            ->when($request->famille, function($query) use ($request){                   //Search by Famille
                        $query->whereHas('famille', function($q) use ($request) {
                            $q->where('famille', 'like', '%'.$request->famille.'%');
                        });
                }) 
            ->when($request->client, function($query) use ($request){                    //Search by Nom Client
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereHas('client', function($qi) use ($request){
                            $qi->where('designation', 'like', '%'.$request->client.'%');
                        });
                    });
                })
            ->when(($request->dateDebut && $request->dateFin), function($query) use ($request){                    //Search by Nom Client
                    $query->whereHas('commandes', function($q) use ($request) {
                        $q->whereBetween('date_vente', [$request->dateDebut, $request->dateFin]);
                    });
                })           
            ->with(['commandeG'=> function($q) use ($request){
                $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS semaine'))
                ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                ->selectRaw(DB::raw('COUNT(Extract( WEEK FROM date_vente )) AS NombreVente'))
                ->selectRaw(DB::raw('COUNT(DISTINCT Extract( WEEK FROM date_vente )) AS Nombresemaine'))
                ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( WEEK FROM date_vente ))) AS MoyennePeriode'))
                ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                ->groupByRaw('Extract( WEEK FROM date_vente )');
                    }])
            ->with(['commandeM'=> function($q) use ($request){
                $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS mois'))
                ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                ->selectRaw(DB::raw('(sum(quantite)/COUNT(DISTINCT Extract( MONTH FROM date_vente ))) AS MoyennePeriode'))
                ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                ->groupByRaw('article_id,Extract( MONTH FROM date_vente )');
                    }])
            ->with(['commandeD'=> function($q) use ($request){
                $q->selectRaw(DB::raw('article_id,extract(WEEK from date_vente) AS semaine'))
                ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                ->selectRaw(DB::raw('COUNT(Extract( WEEK FROM date_vente )) AS NombreVente'))
                ->selectRaw(DB::raw('COUNT(DISTINCT Extract( WEEK FROM date_vente )) AS Nombresemaine'))
                ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                ->whereBetween('date_vente', [$request->dateDebut, $request->dateFin])
                
                ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                ->groupByRaw('article_id,Extract( WEEK FROM date_vente )');
                    }])
            ->with(['commandeone'=> function($q) use ($request){
                $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS mois'))
                ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                ->whereBetween('date_vente', [$request->dateDebut0, $request->dateFin0])
                
                ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                ->groupByRaw('article_id,Extract( MONTH FROM date_vente )');
                    }])
            ->with(['commandetwo'=> function($q) use ($request){
                $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS mois'))
                ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                ->whereBetween('date_vente', [$request->dateDebut1, $request->dateFin1])
                
                ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                ->groupByRaw('article_id,Extract( MONTH FROM date_vente )');
                    }])
            ->with(['commandethree'=> function($q) use ($request){
                $q->selectRaw(DB::raw('article_id,extract(MONTH from date_vente) AS mois'))
                ->selectRaw(DB::raw('sum(quantite) as Sumventes'))
                ->selectRaw(DB::raw('COUNT(Extract( MONTH FROM date_vente )) AS NombreVente'))
                ->selectRaw(DB::raw('COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois'))
                ->selectRaw(DB::raw('avg(quantite) AS MoyenneVente'))
                ->whereBetween('date_vente', [$request->dateDebut2, $request->dateFin2])
                
                ->whereHas('zone', function($qi) use ($request){
                            $qi->where('zone', 'like', '%'.$request->zone.'%');
                        })
                ->groupByRaw('article_id,Extract( MONTH FROM date_vente )');
                    }])
            ->with(['previsions'=> function($q) use ($request){
                $q->selectRaw(DB::raw('article_id,Extract( WEEK FROM date ) as semaine,sum(prevision) as Sumprevision'))
                ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                ->groupByRaw('article_id,Extract( WEEK FROM date )');
                    }])
            ->with(['previsionsm'=> function($q) use ($request){
                $q->selectRaw(DB::raw('article_id,Extract( MONTH FROM date ) as mois,sum(prevision) as Sumprevision'))
                ->whereBetween('date', [$request->dateDebut, $request->dateFin])
                ->groupByRaw('article_id,Extract( MONTH FROM date )');
                    }])
        ->with('famille')
        ->paginate(20);
                    return $Commandes;
       }






     //get all Commandes
       public function getCommandes()
       {
           $Commandes = Commande::with(['article','famille','client','zone'])
           ->paginate(15);
           return $Commandes;
       }
   
       //get Commande info
       public function CommandeInfo($id)
       {
           try{
               $Commande = Commande::where('id',$id)
               ->with(['article','famille','client','zone'])
               ->first();
               return $Commande;
   
           }catch (\Throwable $th) {
               return ['data'=>null,'errors'=>null];
           }
       }
   
       //create Commande
       public function createCommande(Request $request)
       {
           try{
               $validator = Validator::make($request->all(), [
                   'article' => 'required',
                   'quantite' => 'required',
                   'datevente' => 'required',
                    'zone' => 'required',
                    'client' => 'required'

               ]);
   
               if ($validator->fails()) {
                   return ['data'=>null,'errors'=>$validator->errors()];
               }
               
               $Commande = new Commande;
               $Commande->article_id = $request->article;
               $Commande->quantite = $request->quantite;
               $Commande->date_vente = $request->datevente;
               $Commande->zone_id = $request->zone;
               $Commande->client_id = $request->client;

               $Commande->save();
               return $Commande;
   
           }catch (\Throwable $th) {
               return ['data'=>$th,'errors'=>null];
           }
       }
       
       //edit Commande
       public function editCommande(Request $request,$id)
       {
           try{
               $validator = Validator::make($request->all(), [
                'article' => 'required',
                'quantite' => 'required',
                'datevente' => 'required',
                'zone' => 'required',
                'client' => 'required',

               ]);
   
               if ($validator->fails()) {
                   return ['data'=>null,'errors'=>$validator->errors()];
               }
               
               $Commande = Commande::where('id',$id)->first();
               $Commande->article_id = $request->article;
               $Commande->quantite = $request->quantite;
               $Commande->date_vente = $request->datevente;
               $Commande->zone_id = $request->zone;
               $Commande->client_id = $request->client;
               $Commande->save();
               return $Commande;
   
           }catch (\Throwable $th) {
               return ['data'=>null,'errors'=>null];
           }
       }
   
       //delete Commande
       public function deleteCommande($id)
       {   
           try{
               $Commande = Commande::where('id',$id)->first();
               $Commande->delete();
               return $Commande;
   
           }catch (\Throwable $th) {
               return ['data'=>null,'errors'=>null];
           }
       }
}
