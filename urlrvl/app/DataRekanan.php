<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataRekanan extends Model
{
    protected $table = "itbl_apps_data_rekanan";
    protected $primaryKey = "id_data_rekanan";
    const CREATED_AT = 'insert_time';
    const UPDATED_AT = 'update_time';
}
