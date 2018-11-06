<?php

namespace App\Http\Controllers;

use App\CartaoDeCredito;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardExpirationDate;
use LVR\CreditCard\CardNumber;

class CreditCardController extends Controller
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

    public function store(Request $request, $user_id)
    {

        $validator = Validator::make($request->all(),[
            'numero' => ['required',new CardNumber],
            'validade' => ['required',new CardExpirationDate('m/y')],
            'cvv' => ['required', new CardCvc($request->input('numero'))],
            'bandeira' => 'required|string'
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
            /* @var \App\User $user */
            $user_id = User::findOrFail($user_id)->id;

            $cartao = CartaoDeCredito::create([
                'numero' => $request->input('numero'),
                'validade' => Carbon::createFromFormat('m/y',$request->input('validade')),
                'cvv' => $request->input('cvv'),
                'bandeira' => $request->input('bandeira'),
                'user_id' => $user_id
            ]);

            return response()->json([
                'data' => $cartao,
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

    public function destroy($user_id,$card_id)
    {
        try
        {
            /* @var \App\User $user */
            $user = User::findOrFail($user_id);

            $user->cartaoDeCredito()->find($card_id)->delete();

            return response()->json([
                'data' => [],
                'message' => 'Deleted'
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

}
