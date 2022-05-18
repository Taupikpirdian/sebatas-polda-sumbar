<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterAnggaran extends Model
{
    public function satker()
    {
    	return $this->belongsTo('App\KategoriBagian', 'satker_id', 'id');
    }
}
