<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataRekananPICFileBerkas extends Model
{
    protected $table = "itbl_apps_data_rekanan_pic_file_berkas";
    protected $primaryKey = "id_data_rekanan_pic_file_berkas";
    const CREATED_AT = 'insert_time';
    const UPDATED_AT = 'update_time';
}
