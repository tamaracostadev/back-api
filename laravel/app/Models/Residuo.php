<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Residuo extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nome',
        'tipo',
        'categoria',
        'tratamento',
        'classe',
        'medida',
        'peso'
    ];
}
