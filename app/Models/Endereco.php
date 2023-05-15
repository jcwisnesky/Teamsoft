<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $fillable = [

        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
    ];
    protected $hidden = [
        'cliente_id'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
}
