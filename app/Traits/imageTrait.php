<?php
namespace App\Traits;

trait imageTrait{

    function storeImages($photo,$folder){
        $file_extintion=$photo->getClientOriginalExtension();
        //$name=$photo->getClientOriginalName();
        $file_name=time().rand(15,100).'.'.$file_extintion;
        $path=$folder;
        $photo->move($path,$file_name);
        return $file_name;
    }



}
