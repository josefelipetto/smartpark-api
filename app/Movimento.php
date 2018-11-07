<?php
/**
 * Created by PhpStorm.
 * User: josefelipetto
 * Date: 06/11/18
 * Time: 11:27
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Movimento extends Model
{

    protected $fillable = [
        'cartao_de_credito_id',
        'valor',
        'tipo'
    ];

    public function cartaoDeCredito(){
        return $this->belongsTo(CartaoDeCredito::class,'cartao_de_credito_id');
    }
}