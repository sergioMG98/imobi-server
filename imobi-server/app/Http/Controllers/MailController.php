<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\MyEmail;
/* use App\Models\Order; */
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\View\View;

class MailController extends Controller
{
    // envoie de mail pour réinitialiser le mot de passe 
    public function store(Request $request)/* : RedirectResponse */
    {
        
        $userExist = User::where('email', $request->email)
            ->get();

        
        if(count($userExist) != 0){

            // random_bytes: cree une valeur
            // bin2hex : Convertit des données binaires en représentation hexadécimale

            //mot de passe pour authotification du reset
            $tokenReset = bin2hex(random_bytes(rand(30, 50)));

            // numero random pour rendre identification des données plus difficile
            $randomValueBeforeMail = bin2hex(random_bytes(4));
            $randomValueAfterMail = bin2hex(random_bytes(4));

            $randomValueBeforeKey = bin2hex(random_bytes(4));
            $randomValueAfterKey = bin2hex(random_bytes(4));
            
            // insert le mot de passe pour authotification
            $userExist[0] ->tokenPasswordReset = $tokenReset;
            $userExist[0] ->save();

            // change le vusuel de l'email 
            $emailConverted = bin2hex($userExist[0]->email);

            // affichage des infos a envoyer
            $key = "{$randomValueBeforeKey}{$tokenReset}{$randomValueAfterKey}";
            $mail ="{$randomValueBeforeMail}{$emailConverted}{$randomValueAfterMail}";

            // valeurs pour la view "message d'actualisation"
            $order = [
                'name'=>$userExist[0]->firstname,
                'email'=>$mail,
                'key'=> $key,
            ];

            try {
    
                Mail::to($userExist[0]->email)->send(new MyEmail($order));
                
                return response()->json([
                    'message' => 'email de réinitialisation envoyé',
                    $request->email,
                ]);
    
            } catch (\Throwable $th) {
    
                return response()->json([
                    'error' => $th,
                    'message' => "erreur lors de l'envoie de l'email de réinitialisation",
                    $request->email
                ]);
            }
    
        } else {
            return response()->json([
                'message' => "l'adresse mail entée n'est pas presente dans la base de donnée",
                $request->email
            ]);
        }


        
    }

    // reinitialise mot de passe
    public function resetPassword(Request $request){
        
        // condition des data 
        $validator = Validator::make($request->all(), [
            'idReset' => 'required',
            'password' => 'required|regex:/^.*(?=.{8,16})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!@#$%^&*_=+-]).*$/',
        ]);
        
        
        if($validator->fails()){
            // si la validation des data echoue
            return response()->json([
                'status' => 'false',
                'data' => $validator -> Errors($validator),
                'message' => 'valeur manquante ou incorrect',
            ]);

        } else {
            
            // separation de l'email de la cle 
            $separation = explode("?", $request->idReset);

            // supprime les valeurs inutile puis decrypte l'email
            $email = hex2bin(substr($separation[0], 8, -8));
            // supprime les element inutile
            $key = substr($separation[1], 8, -8);
          

            //cherche l'utilisateur en rapport avec l'adresse mail
            try {
                $user = User::where('email', $email)->first();

                if ($user->tokenPasswordReset == $key) {
                    
                    $user->password = $request->password;
                    $user->save();

                } else {
                    return response()->json([
                        'status' => 'false',
                        'message' => 'changement de mot de passe echoué, la clé est incorrect',
                    ]);
                }

                $user->tokenPasswordReset = null;
                $user->save();

                return response()->json([
                    'status' => 'true',
                    'message' => 'changement de mot de passe reussi',
                ]);
                
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'changement de mot de passe echoué',
                ]);
            }
        }


    } 
}
