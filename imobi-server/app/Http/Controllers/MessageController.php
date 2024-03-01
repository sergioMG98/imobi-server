<?php

namespace App\Http\Controllers;

use App\Models\Message;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    // cree un message
    public function setMessage(Request $request){
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'lastnameOfCustomer' => 'required', 
            'firstnameOfCustomer' => 'required', 
            'phoneOfCustomer' => 'required',
            'seller_id' => 'required',  
        ]);
        
        if($validator->fails()){
            
            return response()->json([
                'status' => 'false',
                'data' => $validator -> Errors($validator),
            ]);

        } else {

            $user_id = Auth::id();
        
           
            try {
                $setMessage = Message::create([
                    // expediteur
                    'seller_id' => $user_id, 
                    // destinataire
                    'user_id' => $request->seller_id, 
                    'message' => $request->message,
                    'lastnameSender' => $request->lastnameOfCustomer, 
                    'firstnameSender' => $request->firstnameOfCustomer, 
                    'phoneSender' => $request->phoneOfCustomer,
                    'mailSender' => $request->mailOfCustomer,
                    'referenceAnnonce' => $request->referenceAnnonce,
                ]);

                return response()->json([
                    'status' => "true",
                    'message' => "message envoyé"
                ]);

            } catch (\Throwable $th) {

                return response()->json([
                    'status' => "false",
                    'message' => "erreur lors de l'envoi du message",
                ]);
            }
            
        }
    }
}
