<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create_user(Request $request){

        // condition des data 
        $validator = Validator::make($request->all(), [
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'email' => 'required|string',
            'password' => 'required',
        ]);
        
        
        if($validator->fails()){
            // si la validation des data echoue
            return (
                $validator -> Errors($validator)
            );
            
        } else {
            // si elle reussi

            // verifie si l'email entrée existe déjà
            $alreadyExist = DB::table('users')
                ->where('email', $request->email)
                ->get();

            if(count($alreadyExist) == 0 ){

                $userChecked = [
                    'lastname' => $request->lastname,
                    'firstname' => $request->firstname,
                    'email' => $request->email,
                    'password' => $request->password,
                ];

                User::create($userChecked);
                return (
                    "inscription reussi"
                );
            } else {

                return (
                    "l'adresse mail existe déjà"
                );
            }
        }


    }

    public function login(Request $request){
        
        // condition des data 
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required',
        ]);
        
        if($validator->fails()){
            // si la validation des data echoue
            return (
                $validator -> Errors($validator)
            );
            
        } else {
            
            
            $user = DB::table('users')
                ->where('email', $request->email)
                ->get();
                
            if(count($user) != 0 ){
                if(Hash::check($request->password, $user[0]->password)){
                    return (
                        'connexion reussi'
                    );
                } else {
                    return (
                        "mot de passe incorrecte"
                    );
                }
            } else {
                return (
                    "l'adresse mail entrée n'existe pas"
                );
            }
        }
    }
}
