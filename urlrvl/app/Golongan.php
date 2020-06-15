<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    protected $table = "m_golongan";
    protected $primaryKey = "id";
    public $timestamps = false;
}
