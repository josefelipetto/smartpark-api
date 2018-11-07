<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $messages = [
        'required' => ':attribute é obrigatório',
        'size'     => ':attribute deve ter exatamente :size caracteres.',
        'email'    => ':attribute deve ser um e-mail válido',
        'min'      => ':attribute deve ter no mínimo :min caracteres',
        'max'      => ':attribute deve ter no máximo :max caracteres',
        'in'       => ':attribute deve ser um dos seguintes valores: :values',
        'numeric'  => ':attribute deve ser um número'
    ];
}
