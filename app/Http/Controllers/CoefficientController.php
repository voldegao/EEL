<?php

namespace App\Http\Controllers;

use App\Models\Coefficient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoefficientController extends Controller
{
    //   //get all coefficients
    //   public function getCoefficients()
    //   {
    //       $coefficients = Coefficient::paginate(15);
    //       return $coefficients;
    //   }
  
    //   //get coefficient info
    //   public function coefficientInfo($id)
    //   {
    //       try{
    //           $coefficient = Coefficient::where('id',$id)->first();
    //           return $coefficient;
  
    //       }catch (\Throwable $th) {
    //           return ['data'=>null,'errors'=>null];
    //       }
    //   }
  
    //   //create coefficient
    //   public function createCoefficient(Request $request)
    //   {
    //       try{
    //           $validator = Validator::make($request->all(), [
    //             'famille' => 'required',
    //             'article' => 'required',
    //             'prevision' => 'required',
    //             'client' => 'required',
    //             'date' => 'required'
    //           ]);
  
    //           if ($validator->fails()) {
    //               return ['data'=>null,'errors'=>$validator->errors()];
    //           }
              
    //           $coefficient = new Coefficient;
    //           $coefficient->famille = $request->famille;
    //           $coefficient->article = $request->article;
    //           $coefficient->prevision = $request->prevision;
    //           $coefficient->client = $request->client;
    //           $coefficient->date = $request->date;
    //           $coefficient->save();
    //           return $coefficient;
  
    //       }catch (\Throwable $th) {
    //           return ['data'=>null,'errors'=>null];
    //       }
    //   }
      
    //   //edit coefficient
    //   public function editCoefficient(Request $request,$id)
    //   {
    //       try{
    //           $validator = Validator::make($request->all(), [
    //             'famille' => 'required',
    //             'article' => 'required',
    //             'prevision' => 'required',
    //             'client' => 'required',
    //             'date' => 'required'

    //           ]);
  
    //           if ($validator->fails()) {
    //               return ['data'=>null,'errors'=>$validator->errors()];
    //           }
              
    //           $coefficient = Coefficient::where('id',$id)->first();
    //           $coefficient->famille = $request->famille;
    //           $coefficient->article = $request->article;
    //           $coefficient->prevision = $request->prevision;
    //           $coefficient->client = $request->client;
    //           $coefficient->date = $request->date;

    //           $coefficient->save();
    //           return $coefficient;
  
    //       }catch (\Throwable $th) {
    //           return ['data'=>null,'errors'=>null];
    //       }
    //   }
  
    //   //delete coefficient
    //   public function deleteCoefficient($id)
    //   {   
    //       try{
    //           $coefficient = Coefficient::where('id',$id)->first();
    //           $coefficient->delete();
    //           return $coefficient;
  
    //       }catch (\Throwable $th) {
    //           return ['data'=>null,'errors'=>null];
    //       }
    //   }
}
