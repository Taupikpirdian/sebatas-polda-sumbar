<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perkara extends Model
{
	protected $table = "perkaras";
 
    protected $fillable = ['user_id','no_lp','date_no_lp','kategori_id','kategori_bagian_id','uraian','nama_petugas','pangkat','no_tlp','tkp','lat','long','pin','date','time','divisi','jenis_pidana','status_id'];

  public function korban()
	{
		return $this->belongsTo('App\Korban', 'no_lp', 'no_lp');
	}

	public function status()
	{
		return $this->belongsTo('App\Status', 'status_id', 'id');
	}

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

	public function jenis_narkoba()
	{
		return $this->belongsTo('App\TypeNarkoba', 'material', 'id');
	}
}
