<?php
namespace App\Traits;
use Illuminate\Support\Facades\DB;

trait Helperfunctions
{

    public function getID($token,$tablename){
        $id=DB::table($tablename)->where('api_token',$token)->value('id');
        return $id;
    }

}
