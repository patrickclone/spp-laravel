<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $casts = [
    	'tanggal' => 'datetime:Y-m-d',
    ];

    protected $guarded = ['id'];
    public $timestamps = false;

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis_siswa', 'nis');
    }
}
