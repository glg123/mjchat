<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait NameTrait
{
    /**
     * @return string
     */

    public function getNameAttribute() : string
    {
        $local = (app('request')->hasHeader('language')) ? app('request')->header('language') : 'ar';
        $name = 'name' . '_'.$local;
        return $this->$name;

    }
}
