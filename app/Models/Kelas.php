<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    public $timestamps = false;

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id');
    }

    public function spp()
    {
        return $this->belongsTo(SPP::class, 'id_spp', 'id');
    }

    protected function kelas(): Attribute
    {
        return Attribute::make(
        	get: function($value) {
        		$grade = angkatanSekarang() - $this->tahun_angkatan + 1;
        		return ( $grade == 1 ? "X" : ($grade == 2 ? "XI" : "XII") ) . " $value";
        	},
        );
    }
}
