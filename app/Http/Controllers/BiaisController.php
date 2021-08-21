<?php

namespace App\Http\Controllers;

use App\Models\Biais;
use App\Models\Commande;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;


class BiaisController extends Controller
{

       //get all biais
       public function getBiais()
       {
           $biais = Biais::paginate(15);
           return $biais;
       }
   
       //get biais info
       public function biaisInfo($id)
       {
           try{
               $biais = Biais::where('id',$id)->first();
               return $biais;
   
           }catch (\Throwable $th) {
               return ['data'=>null,'errors'=>null];
           }
       }
   
       //create biais
       public function createBiais(Request $request)
       {
           try{
               $validator = Validator::make($request->all(), [
                   'famille' => 'required',
                   'article' => 'required',
                   'zone' => 'required',
                   'prevision' => 'required',
                   'cause' => 'required',
                   'action' => 'required'
               ]);
   
               if ($validator->fails()) {
                   return ['data'=>null,'errors'=>$validator->errors()];
               }
               
               $biais = new Biais;
               $biais->famille = $request->famille;
               $biais->article = $request->article;
               $biais->zone = $request->zone;
               $biais->prevision = $request->prevision;
               $biais->cause = $request->designation;
               $biais->action = $request->action;
               $biais->save();
               return $biais;
   
           }catch (\Throwable $th) {
               return ['data'=>null,'errors'=>null];
           }
       }
       
       //edit biais
       public function editBiais(Request $request,$id)
       {
           try{
               $validator = Validator::make($request->all(), [
                    'famille' => 'required',
                    'article' => 'required',
                    'zone' => 'required',
                    'prevision' => 'required',
                    'cause' => 'required',
                    'action' => 'required'
               ]);
   
               if ($validator->fails()) {
                   return ['data'=>null,'errors'=>$validator->errors()];
               }
               
               $biais = Biais::where('id',$id)->first();
               $biais->famille = $request->famille;
               $biais->article = $request->article;
               $biais->zone = $request->zone;
               $biais->prevision = $request->prevision;
               $biais->cause = $request->cause;
               $biais->action = $request->action;
               
               $biais->save();
               return $biais;
   
           }catch (\Throwable $th) {
               return ['data'=>null,'errors'=>null];
           }
       }
   
       //delete biais
       public function deleteBiais($id)
       {   
           try{
               $biais = Biais::where('id',$id)->first();
               $biais->delete();
               return $biais;
   
           }catch (\Throwable $th) {
               return ['data'=>null,'errors'=>null];
           }
       }
}
