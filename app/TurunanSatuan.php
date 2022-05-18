<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TurunanSatuan extends Model
{
    protected $primaryKey = 'satker_turunan_id';
    public $incrementing = false;

    public function satkerTurunan()
	{
		return $this->belongsTo('App\KategoriBagian', 'satker_turunan_id', 'id');
	}
}
