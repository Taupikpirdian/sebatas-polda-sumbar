<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lapor extends Model
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

	public function pidana()
	{
		return $this->belongsTo('App\JenisPidana', 'jenis_pidana', 'id');
	}

}
