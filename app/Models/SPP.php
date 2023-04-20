<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPP extends Model
{
    use HasFactory;

    protected $table = 'spp';
    public $timestamps = false;

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_spp', 'id');
    }
}
