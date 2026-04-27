<?php

namespace App\Http\Controllers;

use App\Models\User;  //Importamos el modelo User para poder crear y buscar usuarias
use Illuminate\Http\Request; //Para recibir los datos que manda el formulario
use Illuminate\Support\Facades\Hash; //para encriptar las contraseñas, nunca las guardamos en un texto plano

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user', //en tonces por defecto toda cuenta nueva sera usuaria
        ]);

        $token = $user->createToken('alerta-rosa')->plainTextToken;

        return response()->json([
            'message' => 'Registro exitoso',
            'token' => $token,
            'user' => $user,

        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);
        $user = User::where('phone', $request->phone)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas',

            ], 401);
        }
        $token = $user->createToken('alerta-rosa')->plainTextToken;
        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
            'user' => $user,
        
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); 
        //Obtiene la usuaria que esta haciendo la peticion,
                         //obtiene el token activo en el inicio de sesino
                                                //elimina ese token, asi la sesion queda cerrada y el token ya no sirve
        return response()->json([
            'message' => 'Sesion cerrada exitosamente',
        ]);
    }
}
