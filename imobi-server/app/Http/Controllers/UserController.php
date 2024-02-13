<?php
//https://educrak.com/chapitre-content/64cfc0bf1ec60-authentification-des-api-laravel-avec-sanctum
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //creation de compte
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
            return response()->json([
                'status' => 'false',
                'data' => $validator -> Errors($validator),
                'message' => 'valeur manquante ou incorrect',
            ]);

        } else {
            // si elle reussi

            // verifie si l'email entrée existe déjà
            $alreadyExist = DB::table('users')
                ->where('email', $request->email)
                ->get();

            if(count($alreadyExist) == 0 ){

                $user = User::create([
                    'lastname' => $request->lastname,
                    'firstname' => $request->firstname,
                    'email' => $request->email,
                    'password' => $request->password,
                ]);

                $token = $user->createToken('TaskApp')->plainTextToken;

                return response()->json([
                    'status' => 'true',
                    'message' => 'inscription reussi',
                    'token' => $token,
                    "user" => $user,
                ]);

            } else {
                return response()->json([
                    'status' => 'false',
                    'message' => "l'adresse mail existe déjà",
                ]);
                
            }
        }


    }


    public function login(Request $request){
        
        /* dd($request); */
        // condition des data 
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required',
        ]);
        
        if($validator->fails()){
            // si la validation des data echoue
            return response()->json([
                'status' => 'false',
                'data' => $validator -> Errors($validator),
                'message' => 'valeur manquante ou incorrect',
            ]);
            
        } else {
            
            $user = DB::table('users')
                ->where('email', $request->email)
                ->get();
                
            if(count($user) != 0 ){

                if(Hash::check($request->password, $user[0]->password)){
                    
                    $us = User::find($user[0]->id);
                    $token = $us->createToken('token-name')->plainTextToken;

                    return response()->json([
                        'status' => 'true',
                        'message' => 'connexion reussi',
                        'token' => $token,
                    ]);

                } else {
                    return response()->json([
                        'status' => 'false',
                        'message' => 'mot de passe incorrecte',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'false',
                    'message' => "l'adresse mail entrée n'existe pas",
                ]);

            }
        }
    }

    public function someInformationSeller(){

        $dataSellerFiltred = array();

        try {
            $dataSelet = DB::table('users')
                ->get();
        
            foreach ($dataSelet as $key => $value) {
                array_push($dataSellerFiltred, (object) [
                    "seller_id" => $value->id,
                    "lastname" => $value->lastname, 
                    "firstname" => $value->firstname,
                    "phone" => $value->phone,
                    "latitude" => $value->latitude,
                    "longitude" => $value->longitude,
                ]);
                
            }
            return response()->json([
                "status" => "true",
                "data" => $dataSellerFiltred,
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                "status" => "false",
                "message" => "erreur lors de la recuperation",
            ]);
        }

    }

    // deconnexion
    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "déconnecté"
        ]);
    }
}
