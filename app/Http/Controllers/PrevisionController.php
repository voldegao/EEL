<?php

namespace App\Http\Controllers;

use App\Models\Prevision;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use DB;

class PrevisionController extends Controller
{
    //get all previsions
    public function getPrevisions()
    {
        $previsions = Prevision::paginate(15);
        return $previsions;
    }

    //get prevision info
    public function previsionInfo($id)
    {
        try{
            $prevision = Prevision::where('id',$id)->first();
            return $prevision;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>null];
        }
    }

    //create prevision
    public function createPrevision(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'famille' => 'required',
                'article' => 'required',
                'date' => 'required',
                'prevision' => 'required'
            ]);

            if ($validator->fails()) {
                return ['data'=>null,'errors'=>$validator->errors()];
            }
            
            $prevision = new Prevision;
            $prevision->famille = $request->famille;
            $prevision->article = $request->article;
            $prevision->date = $request->date;
            $prevision->prevision = $request->prevision;
            $prevision->save();
            return $prevision;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>null];
        }
    }
    
    //edit prevision
    public function editPrevision(Request $request,$id)
    {
        try{
            $validator = Validator::make($request->all(), [
                'famille' => 'required',
                'article' => 'required',
                'date' => 'required',
                'prevision' => 'required'
            ]);

            if ($validator->fails()) {
                return ['data'=>null,'errors'=>$validator->errors()];
            }
            
            $prevision = Prevision::where('id',$id)->first();
            $prevision->famille = $request->famille;
            $prevision->article = $request->article;
            $prevision->date = $request->date;
            $prevision->prevision = $request->prevision;
            $prevision->save();
            return $prevision;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>null];
        }
    }

    //delete prevision
    public function deletePrevision($id)
    {   
        try{
            $prevision = Prevision::where('id',$id)->first();
            $prevision->delete();
            return $prevision;

        }catch (\Throwable $th) {
            return ['data'=>null,'errors'=>null];
        }
    }

    public function generatePrevision(Request $request)
    {
        // get the prevision from the request {date:..., prevision:....}
        $previsionSplitList = array();
        $data = json_decode($request->previsionData);

        if (is_array ($data)) {
            // Loop foreach Prevision
            foreach($data as $d){
                //// Split each Prevision to 4 data sing Foor and Modulo
                $previsionModulo = $d% 4;
                $previsionFloor = floor($d/4);
                //// Insert the splitted data in an Array
                for ($x = 0; $x < 4; $x++) {
                    if($x == 3){
                        array_push($previsionSplitList,($previsionFloor + $previsionModulo));
                    }else{
                        array_push($previsionSplitList,$previsionFloor);
                    }
                }
                //// Insert each splitted Prevision with a date 7-14-21-28 per Month
                
            }
        }
        return $previsionSplitList;
    }




    public function testFunc(Request $request)
    {
        $previsionSplitList = array();
        $dates =  json_decode($request->listeDates);
        $previsions =  json_decode($request->listePrev);
        $numWeeks =  json_decode($request->numWeeks);
        $articleID =  $request->articleID;
        $methode =  $request->methode;

        $article = Article::where('id',$articleID)->first();

        $previsionSplitList = array();
        if(is_array($numWeeks) && is_array($previsions)){
            for($i = 0;$i<Count($previsions);$i++){
                //// Split each Prevision to 4 data sing Foor and Modulo
                $previsionModulo = $previsions[$i]% $numWeeks[$i];
                $previsionFloor = floor($previsions[$i]/$numWeeks[$i]);
                //// Insert the splitted data in an Array
                for ($x = 0; $x < $numWeeks[$i]; $x++) {
                    if($x ==  ($numWeeks[$i]-1)){
                        array_push($previsionSplitList,($previsionFloor + $previsionModulo));
                    }else{
                        array_push($previsionSplitList,$previsionFloor);
                    }
                }
            }

            if(count($dates) == count($previsionSplitList)){
                for($i=0;$i<count($dates);$i++){
                    $date = $dates[$i];
                    $prevision = $previsionSplitList[$i];

                    $prev = new Prevision;
                    $prev->famille_id = $article->famille->id;
                    $prev->article_id = $article->id;
                    $prev->date = $date;
                    $prev->prevision = $prevision;
                    $prev->methode_id = $request->methode;
                    $prev->save();
                }
                return [
                    "msg"=>"all data saved",
                    "previsions"=>$previsions,
                    "numwees"=>$numWeeks,
                    "dates"=>$dates,
                    "listo"=>$previsionSplitList
                ];
            }
        }

     
    }

    public function getPrev()
    {
        $list = Prevision::whereBetween(DB::raw('DATE(date)'), array('2021-08-01','2021-11-30'))->get();
        return $list;
    }

    public function deletePrev()
    {
        $list = Prevision::whereBetween(DB::raw('DATE(date)'), array('2021-12-1','2022-07-31'))->get();
        $ids = array();
        foreach($list as $l){
            array_push($ids,$l->id);
        }

        return Prevision::destroy($ids);
    }

}
