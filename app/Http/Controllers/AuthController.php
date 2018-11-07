<?php

namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create a new token.
     *
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60*60*60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User   $user
     * @return mixed
     */
    public function authenticate()
    {
        $validator = Validator::make($this->request->all(),[
            'email'     => 'required|email',
            'senha'  => 'required'
        ],$this->messages);

        if($validator->fails())
        {
            return response()->json([
                'data' => [],
                'message' => $validator->errors()->all()
            ],400);
        }
        $user = User::where('email', $this->request->input('email'))->first();

        if ( ! $user )
        {
            return response()->json([
                'data' => [],
                'message' => 'Email ou senha incorretos'
            ], 400);

        }

        if (Hash::check($this->request->input('senha'), $user->senha))
        {
            return response()->json([
                'data' => [
                    'token' => $this->jwt($user)
                ],
                'message' => null

            ], 200);
        }

        return response()->json([
            'data' => [],
            'message' => 'Email ou senha incorretos'
        ],400);

    }

    //
}
