<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriBagian extends Model
{
	public function kategori()
	{
		return $this->belongsTo('App\Kategori', 'kategori_id', 'id');
	}

	public function masterAnggaran()
	{
			return $this->belongsTo('App\MasterAnggaran', 'id', 'satker_id');
	}

	public function anggaran()
	{
			return $this->hasMany('App\Anggaran', 'master_sakter_id', 'id');
	}

	public function Turunan()
	{
		return $this->belongsTo('App\TurunanSatuan', 'satker_turunan_id', 'id');
	}
}
