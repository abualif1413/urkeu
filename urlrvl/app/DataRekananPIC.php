<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataRekananPIC extends Model
{
    protected $table = "itbl_apps_data_rekanan_pic";
    protected $primaryKey = "id_data_rekanan_pic";
    const CREATED_AT = 'insert_time';
    const UPDATED_AT = 'update_time';
}
