<?php
/**
 * Created by PhpStorm.
 * User: josefelipetto
 * Date: 06/11/18
 * Time: 11:27
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class CartaoDeCredito extends Model
{
    protected $table = 'cartoes_de_credito';

    protected $fillable = [
        'numero',
        'validade',
        'cvv',
        'bandeira',
        'user_id'
    ];

    protected $hidden = [
        'cvv'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movimentos(){
        return $this->hasMany(Movimento::class);
    }

}