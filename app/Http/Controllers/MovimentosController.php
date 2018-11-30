<?php

namespace App\Http\Controllers;

use App\Movimento;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MovimentosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // left blank
    }

    public function show($user_id)
    {
        try
        {
            $user = User::with('movimentos')->find($user_id);

            return response()->json([
                'data' => $user->movimentos,
                'message' => null
            ],200);
        }
        catch (ModelNotFoundException $exception)
        {
            return response()->json([
                'data' => [],
                'message' => $exception->getMessage()
            ],404);
        }
    }

    public function store(Request $request, $user_id)
    {

        $validator = Validator::make($request->all(),[
           'cartao_de_credito_id' => 'required|exists:cartoes_de_credito,id',
            'valor' => 'required|numeric'
        ],$this->messages);

        if ($validator->fails())
        {
            return response()->json([
                'data' => [],
                'message' => $validator->errors()->all()
            ],400);
        }

        try
        {
            $valor = $request->input('valor');

            $tipo = $valor >= 0 ? 'E' : 'S' ;

            if($valor <= 0 && $tipo !== "S" )
            {
                return response()->json([
                    'data' => [],
                    'message' => 'Valores negativos só podem ser do tipo S'
                ],400);
            }

            if($this->getBalance($user_id) + $valor <= 0)
            {
                return response()->json([
                    'data' => [],
                    'message' => 'Saldo nao poderá ficar negativo. '
                ],400);
            }

            $movimento = Movimento::create([
                'cartao_de_credito_id' => $request->input('cartao_de_credito_id'),
                'valor' => $valor,
                'tipo' => $tipo
            ]);

            return response()->json([
                'data' => $movimento,
                'message' => 'Created'
            ],201);

        }
        catch (\Exception $exception)
        {
            return response()->json([
                'data' => [],
                'message' => $exception->getMessage()
            ],500);
        }

    }


    public function spend($user_id,$cartao_id,$valor)
    {

        $valor *= -1;

        try
        {
            if($this->getBalance($user_id) + $valor <= 0)
            {
                return response()->json([
                    'data' => [],
                    'message' => 'Saldo nao poderá ficar negativo. '
                ],400);
            }

            $movimento = Movimento::create([
                'cartao_de_credito_id' => $cartao_id,
                'valor' => $valor,
                'tipo' => 'S'
            ]);

            return response()->json([
                'data' => $movimento,
                'message' => 'Created'
            ],201);

        }
        catch (\Exception $exception)
        {
            return response()->json([
                'data' => [],
                'message' => $exception->getMessage()
            ],500);
        }

    }

    public function balance($user_id)
    {
        try
        {
            return response()->json([
                'data' => $this->getBalance($user_id),
                'message' => null
            ],200);

        }
        catch (\Exception $exception)
        {
            return response()->json([
                'data' => [],
                'message' => $exception->getMessage()
            ],$exception instanceof ModelNotFoundException ? 404 : 500);
        }
    }

    private function getBalance($user_id)
    {
        return User::with('movimentos')->findOrFail($user_id)->movimentos->sum('valor');
    }
}
