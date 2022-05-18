<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccidentVictimt extends Model
{
    public function kondisi()
	{
		return $this->hasOne('App\KondisiKorban', 'id', 'kondisi_id');
	}
}
