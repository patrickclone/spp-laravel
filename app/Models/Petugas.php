<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Petugas extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'petugas';

    protected $fillable = [
        'username',
        'level_user',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_petugas', 'id');
    }
}
