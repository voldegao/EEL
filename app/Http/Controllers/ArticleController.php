<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class ArticleController extends Controller
{
       //get all articles
       public function getArticles()
       {
           $articles = Article::with('famille')->paginate(15);
           return $articles;
       }

       public function getArticlesWithRelations()
       {
           $articles = Article::with(['famille','commandes','previsions'])
           ->paginate(15);
           return $articles;
       }
   
       //get article info
       public function articleInfo($id)
       {
           try{
               $article = Article::where('id',$id)
               ->with(['famille','commandes','previsions'])
               ->first();
               return $article;
   
           }catch (\Throwable $th) {
               return ['data'=>null,'errors'=>null];
           }
       }

        
   
   
       //create article
       public function createArticle(Request $request)
       {
           try{
               $validator = Validator::make($request->all(), [
                   'code' => 'required',
                   'designation' => 'required',
                   'famille' => 'required',
                   'classe'=>'required'
               ]);
   
               if ($validator->fails()) {
                   return ['data'=>null,'errors'=>$validator->errors()];
               }
               
               $article = new Article;
               $article->code = $request->code;
               $article->designation = $request->designation;
               $article->famille_id = $request->famille;
               $article->classe = $request->classe;
               $article->save();
               return $article;
   
           }catch (\Throwable $th) {
               return ['data'=>$th,'errors'=>null];
           }
       }
       
       //edit article
       public function editArticle(Request $request,$id)
       {
           try{
               $validator = Validator::make($request->all(), [
                    'code' => 'required',
                    'designation' => 'required',
                    'famille' => 'required',
                    'classe'=>'required'
               ]);
   
               if ($validator->fails()) {
                   return ['data'=>null,'errors'=>$validator->errors()];
               }
               
               $article = Article::where('id',$id)->first();
               $article->code = $request->code;
               $article->designation = $request->designation;
               $article->famille_id = $request->famille;
               $article->classe = $request->classe;
               $article->save();
               return $article;
   
           }catch (\Throwable $th) {
               return ['data'=>$th,'errors'=>null];
           }
       }
   
       //delete article
       public function deleteArticle($id)
       {   
           try{
               $article = Article::where('id',$id)->first();
               $article->delete();
               return $article;
   
           }catch (\Throwable $th) {
               return ['data'=>null,'errors'=>null];
           }
       }

       public function stocks(Request $request)
       {
           $stocks = Stock::selectRaw(DB::raw('article_id,quantite,extract(MONTH from date) AS Mois,extract(YEAR FROM date) as Year'))
                    ->when(($request->dateDebut && $request->dateFin), function($query) use ($request){                    //Search by Nom Client
                        $query->whereBetween('date', [$request->dateDebut, $request->dateFin]);
                    })
                    ->when($request->code, function($query) use ($request){                    //Search by Nom Client
                            $query->whereHas('articles', function($q) use ($request) {
                                $q->where('code', 'like', '%'.$request->code.'%');
                            });
                    })
                    ->groupByRaw('article_id,Extract( MONTH FROM date ),Extract(YEAR from date)')
                   ->orderBy('Year','asc')
                   ->orderBy('mois','asc')
                    ->paginate(100);
            
            return $stocks;
       }



}
