<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller
{
      //get all zones
      public function getZones()
      {
          $zones = Zone::paginate(15);
          return $zones;
      }
  
      //get zone info
      public function zoneInfo($id)
      {
          try{
              $zone = Zone::where('id',$id)->first();
              return $zone;
  
          }catch (\Throwable $th) {
              return ['data'=>$th,'errors'=>null];
          }
      }
  
      //create zone
      public function createZone(Request $request)
      {
          try{
              $validator = Validator::make($request->all(), [
                  'zone' => 'required',
              ]);
  
              if ($validator->fails()) {
                  return ['data'=>null,'errors'=>$validator->errors()];
              }
              
              $zone = new Zone;
              $zone->zone = $request->zone;
              $zone->save();
              return $zone;
  
          }catch (\Throwable $th) {
              return ['data'=>null,'errors'=>null];
          }
      }
      
      //edit zone
      public function editZone(Request $request,$id)
      {
          try{
              $validator = Validator::make($request->all(), [
                 'zone' => 'required',
              ]);
  
              if ($validator->fails()) {
                  return ['data'=>null,'errors'=>$validator->errors()];
              }
              
              $zone = Zone::where('id',$id)->first();
              $zone->zone = $request->zone;
              $zone->save();
              return $zone;
  
          }catch (\Throwable $th) {
              return ['data'=>null,'errors'=>null];
          }
      }
  
      //delete zone
      public function deleteZone($id)
      {   
          try{
              $zone = Zone::where('id',$id)->first();
              $zone->delete();
              return $zone;
  
          }catch (\Throwable $th) {
              return ['data'=>null,'errors'=>null];
          }
      }
}
