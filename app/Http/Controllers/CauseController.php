<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Causearticle;
use App\Models\Causefamille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
class CauseController extends Controller
{
    public function listCauseArticle(Request $request,$id)
    {
       $causes = Causearticle::where('article_id',$id)
       ->selectRaw(DB::raw('id,date,article_id,cause,action,extract(MONTH from date) AS mois,extract(YEAR FROM date) as Year'))
       ->whereBetween('date', [$request->datedebut, $request->datefin])
       ->get();
       return $causes;
    }

    public function articleCauseinfo($id)
    {
       $causes = Causearticle::where('id',$id)
       ->get();
       return $causes;
    }
    public function createCauseArticle(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'article_id' => 'required',
                'date' => 'required',
                'cause' => 'required',
                'action' => 'required'
            ]);

            if ($validator->fails()) {
                return ['data'=>null,'errors'=>$validator->errors()];
            }
            
            $cause = new Causearticle;
            $cause->article_id= $request->article_id;
            $cause->date = $request->date;
            $cause->cause = $request->cause;
            $cause->action = $request->action;
            $cause->save();
            return $cause;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>$th];
        }  
    }
    public function updateCauseArticle(Request $request,$id)
    {
        try{
            $validator = Validator::make($request->all(), [
                'date' => 'required',
                'cause' => 'required',
                'action' => 'required'
            ]);

            if ($validator->fails()) {
                return ['data'=>null,'errors'=>$validator->errors()];
            }
            
            $cause = Causearticle::where('id',$request->id)->first();
            $cause->date = $request->date;
            $cause->cause = $request->cause;
            $cause->action = $request->action;
            $cause->save();
            return $cause;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>$th];
        }  
    }
    public function deleteCauseArticle(Request $request)
    {
        try{
          
            $cause = Causearticle::where('id',$request->id);
            $cause->delete();
            return 1;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>null];
        }  
    }

    public function listeCauseFamille()
    {
        $causes = Causefamille::all();
        return $causes;
    }
    public function createCauseFamille(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'article_id' => 'required',
                'date' => 'required',
                'cause' => 'required',
                'action' => 'required'
            ]);

            if ($validator->fails()) {
                return ['data'=>null,'errors'=>$validator->errors()];
            }
            
            $cause = new Causefamille;
            $cause->article_id= $request->article_id;
            $cause->date = $request->date;
            $cause->cause = $request->cause;
            $cause->action = $request->action;
            $cause->save();
            return $cause;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>null];
        }  
    }
    public function updateCauseFamille(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'article_id' => 'required',
                'date' => 'required',
                'cause' => 'required',
                'action' => 'required'
            ]);

            if ($validator->fails()) {
                return ['data'=>null,'errors'=>$validator->errors()];
            }
            
            $cause = Causefamille::where('id',$request->id);
            $cause->article_id= $request->article_id;
            $cause->date = $request->date;
            $cause->cause = $request->cause;
            $cause->action = $request->action;
            $cause->save();
            return $cause;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>null];
        }  
    }
    public function deleteCauseFamille(Request $request)
    {
        try{
          
            $cause = Causefamille::where('id',$request->id);
            $cause->delete();
            return $cause;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>null];
        }  
    }
}
