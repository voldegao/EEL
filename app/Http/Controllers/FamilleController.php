<?php

namespace App\Http\Controllers;

use App\Models\Famille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FamilleController extends Controller
{
    //get all familles
    public function getFamilles()
    {
        $familles = Famille::all();
        return $familles;
    }

    //get famille info
    public function familleInfo($id)
    {
        try{
            $famille = Famille::where('id',$id)->first();
            return $famille;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>null];
        }
    }

    //create famille
    public function createFamille(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'famille' => 'required',
            ]);

            if ($validator->fails()) {
                return ['data'=>null,'errors'=>$validator->errors()];
            }
            
            $famille = new Famille;
            $famille->famille = $request->famille;
            $famille->save();
            return $famille;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>null];
        }
    }
    
    //edit famille
    public function editFamille(Request $request,$id)
    {
        try{
            $validator = Validator::make($request->all(), [
                'famille' => 'required',
            ]);

            if ($validator->fails()) {
                return ['data'=>null,'errors'=>$validator->errors()];
            }
            
            $famille = Famille::where('id',$id)->first();
            $famille->famille = $request->famille;
            $famille->save();
            return $famille;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>null];
        }
    }

    //delete famille
    public function deleteFamille($id)
    {   
        try{
            $famille = Famille::where('id',$id)->first();
            $famille->delete();
            return $famille;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>null];
        }
    }
    public function getArticles(Request $request)
        {
            $articles = Famille::where('famille',$request->famille)
            ->with('articles')
            ->get();
            return $articles;
        }
}
