<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;


class Cliente extends Model
{
    use HasFactory;
    protected $with = ['enderecos'];

    protected $fillable = [
        'cnpj',
        'razao_social',
        'nome_contato',
        'telefone',
    ];

    public function enderecos()
    {
        return $this->hasMany(Endereco::class, 'cliente_id');
    }
    public function validate()
    {
        return Validator::make($this->attributes, [
            'cnpj' => 'required',
            'razao_social' => 'required',
            'nome_contato' => 'required',
            'telefone' => 'required',
        ])->validate();
    }

}
