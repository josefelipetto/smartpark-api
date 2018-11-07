<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
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

    public function show($id)
    {
        try
        {
            $user = User::with('cartaoDeCredito')->findOrFail($id);

            return response()->json([
                'data' => $user,
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'required|string',
            'email' => 'required|email',
            'matricula' => 'required|string|min:10',
            'senha' => 'required|min:6|max:16|string',
            'tipo' => ['required', Rule::in(['A','P'])]
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
            $user = User::create([
                'nome' => $request->input('nome'),
                'email' => $request->input('email'),
                'matricula' => $request->input('matricula'),
                'senha' => Hash::make($request->input('senha')),
                'tipo' => $request->input('tipo')
            ]);

            $user->load('cartaoDeCredito');

            return response()->json([
                'data' => $user,
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

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'nome' => 'string',
            'email' => 'email',
            'matricula' => 'string|min:10',
            'senha' => 'min:6|max:16|string',
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
            $user = User::findOrFail($id);

            if($request->has('senha'))
            {
                $request->merge([
                    'senha' => Hash::make($request->input('senha'))
                ]);
            }

            $user->update($request->all());

            $user->load('cartaoDeCredito');

            return response()->json([
                'data' => $user,
                'message' => 'updated'
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

    public function destroy($id)
    {
        try
        {
            /* @var \App\User $user */
            $user = User::findOrFail($id);

            $user->delete();

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
