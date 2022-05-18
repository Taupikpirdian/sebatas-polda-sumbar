<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrafficAccident extends Model
{
    public function user()
	{
		return $this->belongsTo('App\User', 'user_id', 'id');
	}

	public function satuan()
	{
		return $this->belongsTo('App\Kategori', 'kategori_id', 'id');
	}

	public function satker()
	{
		return $this->belongsTo('App\KategoriBagian', 'kategori_bagian_id', 'id');
	}

	public function korbans()
	{
			return $this->hasMany('App\AccidentVictimt', 'traffic_accident_id', 'id');
	}

	public function status()
	{
		return $this->belongsTo('App\Status', 'status_id', 'id');
	}

	public function faktor()
	{
		return $this->belongsTo('App\FaktorKecelakaan', 'faktor_id', 'id');
	}

	public function klasifikasi()
	{
		return $this->belongsTo('App\KlasfikasiKecelakaan', 'klasifikasi_id', 'id');
	}
	
}
