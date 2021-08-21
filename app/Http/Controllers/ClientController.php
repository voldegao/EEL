<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
     //get all clients
     public function getClients()
     {
         $clients = Client::paginate(15);
         return $clients;
     }
 
     //get client info
     public function clientInfo($id)
     {
         try{
             $client = Client::where('id',$id)->first();
             return $client;
 
         }catch (\Throwable $th) {
             return ['data'=>null,'errors'=>null];
         }
     }
 
     //create client
     public function createClient(Request $request)
     {
         try{
             $validator = Validator::make($request->all(), [
                 'designation' => 'required',
                 'type' => 'required',
                 'phone' => 'required',
             ]);
 
             if ($validator->fails()) {
                 return ['data'=>null,'errors'=>$validator->errors()];
             }
             
             $client = new Client;
             $client->designation = $request->designation;
             $client->adresse = $request->adresse;
             $client->type = $request->type;
             $client->phone = $request->phone;
             $client->save();
             return $client;
 
         }catch (\Throwable $th) {
             return ['data'=>null,'errors'=>null];
         }
     }
     
     //edit client
     public function editClient(Request $request,$id)
     {
         try{
             $validator = Validator::make($request->all(), [
                'designation' => 'required',
                'type' => 'required',
                'phone' => 'required',
             ]);
 
             if ($validator->fails()) {
                 return ['data'=>null,'errors'=>$validator->errors()];
             }
             
             $client = Client::where('id',$id)->first();
             $client->designation = $request->designation;
             $client->adresse = $request->adresse;
             $client->type = $request->type;
             $client->phone = $request->phone;
             $client->save();
             return $client;
 
         }catch (\Throwable $th) {
             return ['data'=>null,'errors'=>null];
         }
     }
 
     //delete client
     public function deleteClient($id)
     {   
         try{
             $client = Client::where('id',$id)->first();
             $client->delete();
             return $client;
 
         }catch (\Throwable $th) {
             return ['data'=>null,'errors'=>null];
         }
     }
}
