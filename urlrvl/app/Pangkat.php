<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pangkat extends Model
{
    protected $table = "m_pangkat_pegawai";
    protected $primaryKey = "id";
    public $timestamps = false;
}
