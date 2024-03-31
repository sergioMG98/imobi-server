<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Caracteristique;
use App\Models\Client;
use App\Models\User;
use App\Models\Picture;
use App\Models\Event;
use App\Models\Message;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{

    // ajout de lieux ( dashboard )
    public function addProduct(Request $request){
        
        /* dd($request); */

        // "description" : "de", 
		// "piece" : 1, 
		// "surfaceTerrain" : 1,
		// "surface" : 1,
		// "salleDeBain"  : 1, 
		// "chambre" : 1, 
		// "terrasse"  : 1, 
		// "cave"  : 1, 
		// "bilanEnergetique"  : 1,
		// "prix"  : 1,
		// "status" : "rent",
		// "lastname": "de",
		// "firstname" : "de",
		// "email" : "de",
		// "phone"  : 1


        // condition des data 
        $validator = Validator::make($request->all(), [
            "description" => 'required|string', 
            "surface" => 'required|numeric',
            "dpe" => 'required|alpha:ascii', 
            "ges" => 'required|alpha:ascii',
            "prix" => 'required|numeric',
            "type" => 'required|string',

            // "piece" => 'nullable|numeric',
            // "sulleDeBain" => 'nullable|numeric',
            // "chambre" => 'nullable|numeric',
            // "terrasse" => 'nullable|numeric',
            // "balcon" => 'nullable|numeric',
            // "garage" => 'nullable|numeric',
            // "piscine"=> 'nullable|numeric',
            // "ascenseur" => 'nullable|numeric',
            // "cave" => 'nullable|numeric',

            "status" => 'required|string',
            "label" => 'required',
            "lastname" => 'required|string',
            "firstname" => 'required|string',
            "email" => 'required|email',
            "phone" => 'required|numeric',
            /* "image" => 'required', */
        ]);
        
        if($validator->fails()){
            // si la validation des data echoue
            return response()->json([
                'status' => 'false',
                'data' => $validator -> Errors($validator),
                'message' => 'valeur manquante ou incorrect',
            ]);

        } else {

            /* image */
            /** @var UploadedFile $image */
            
 
            
            try {
                try {
                    $test = Product::create([
                        "status" => $request->status,
                        "prix" =>$request->prix,
                        "description" => $request->description, 
                        "surface" => $request->surface,
                        "ges" => $request->ges,
                        "dpe" => $request->dpe,
                        "type" => $request->type,
                        "piece" => $request->piece != 'undefined' ? $request->piece : null ,
                        "surfaceTerrain" => $request->surfaceTerrain != 'undefined' ? $request->surfaceTerrain : null ,
                        "salleDeBain" => $request->salleDeBain != 'undefined' ? $request->salleDeBain : null,
                        "chambre" => $request->chambre != 'undefined' ? $request->chambre : null , 
                        "terrasse" => $request->terrasse != 'undefined' ? $request->terrasse : null ,
                        "balcon" => $request->balcon != 'undefined' ? $request->balcon : null ,
                        "garage" => $request->garage != 'undefined' ? $request->garage : null ,
                        "piscine" => $request->piscine != 'undefined' ? $request->piscine : null ,
                        "ascenseur" => $request->ascenseur != 'undefined' ? $request->ascenseur : null ,
                        "cave" => $request->cave != 'undefined' ? $request->cave : null ,
                        "longitude" => $request->longitude != 'undefined' ? $request->longitude : null ,
                        "latitude" => $request->latitude != 'undefined' ? $request->latitude : null ,
                        
    
                        "ville" => $request->ville,
                        "label" => $request->label,
                        "user_id" => Auth::id(),
                    ]);
                } catch (\Throwable $th) {
                    return response()->json([
                        'status' => 'false',
                        'message' => 'création de product échoué, creation du produit ',
                        'test' => $th,
                    ]);
                }

                //image
                try {
                    //chemin de l'image
                    $imagePath = $request->file('image')->store('blog', 'public');
           
                    $imageTable = Picture::create([
                        "picture" => $imagePath,
                        "product_id" => $test->id,
                    ]);

                } catch (\Throwable $th) {
                    return response()->json([
                        'status' => 'false',
                        'message' => 'création de product échoué, affectation des images ',
                        'test' => $request->image,
                    ]);
                }


                // affectation des clients
                try {
                    if ($request->idCustomer == null || $request->idCustomer == "undefined" ) {

                        $customers = Client::create([
                            "lastname" => $request->lastname,
                            "firstname" => $request->firstname,
                            "email" => $request->email,
                            "phone" => $request->phone,
                            "user_id" => Auth::id(),
                        ]); 
    
                        $pivot = $customers->customerProduct()->attach($test->id);
                        
                    } else {
    
                        $customers = Client::find($request->idCustomer);
    
                        $pivot = $customers->customerProduct()->attach($test->id);
    
                    }
                } catch (\Throwable $th) {

                    return response()->json([
                        'status' => 'false',
                        'message' => 'création de product échoué, affectation des clients ',
                        'test' => $th,
                    ]);
    
                }


                return response()->json([
                    'status' => 'true',
                    'message' => 'création de product reussi',
                    'test' => $test->id,
                ]);

            } catch (\Throwable $th) {
                
                return response()->json([
                    'status' => 'false',
                    'message' => 'création de product echoué',
                    'error' => $th,
                    'r' => Auth::id(),
                    "lastname" => $request->lastname,
                    "firstname" => $request->firstname,
                    "email" => $request->email,
                    "phone" => $request->phone,
                    "id" => $request->idCustomer,
                ]);
            }


        }
    }
    // affichage de lieux ( dashboard )
    public function getProductSeller(){   

        $product = DB::table('products')
            ->where('user_id', Auth::id() )
            ->get();
      

        return response()->json([
            'status' => 'true',
            'product' => $product,
            "id" => Auth::id(),
        ]);
        
    }
    // modification de produit
    public function updateProduct(Request $request){

        // condition des data 
        $validator = Validator::make($request->all(), [
            "id_product" => "required",
            "status" => 'required',
            "prix" => 'required|numeric',
            "description" => 'required|string',
            "surface" => 'required|numeric',
            "ges" => 'required|alpha:ascii', 
            "dpe" => 'required|alpha:ascii', 
            "type" => 'required|string',

            "piece" => 'nullable|numeric',
            "sulleDeBain" => 'nullable|numeric',
            "chambre" => 'nullable|numeric',
            "terrasse" => 'nullable|numeric',
            "balcon" => 'nullable|numeric',
            "garage" => 'nullable|numeric',
            "piscine"=> 'nullable|numeric',
            "ascenseur" => 'nullable|numeric',
            "cave" => 'nullable|numeric',

            "lastname" => 'required|string',
            "firstname" => 'required|string',
            "email" => 'required|email',
            "phone" => 'required|numeric',
        ]);

        if($validator->fails()){
            // si la validation des data echoue
            return response()->json([
                'status' => 'false',
                'data' => $validator -> Errors($validator),
                'message' => 'valeur manquante ou incorrect',
            ]);
        } else {
            
            /* return response()->json([
                'test' => $request->piece,
            ]); */
            try {
                $product = Product::find($request->id_product);
            
                $product ->status = $request->status;
                $product ->prix = $request->prix;
                $product ->description = $request->description;
                $product ->surface = $request->surface;
                $product ->ges = $request->ges;
                $product ->dpe = $request->dpe;
                $product ->type = $request->type;

                $request->piece ? $product ->piece = $request->piece : null;
                $request->surfaceTerrain ? $product ->surfaceTerrain = $request->surfaceTerrain : null;
                $request->salleDeBain ? $product ->salleDeBain = $request->salleDeBain : null;
                $request->chambre ? $product ->chambre = $request->chambre : null;
                $request->terrasse ? $product ->terrasse = $request->terrasse : null;
                $request->balcon ? $product ->balcon = $request->balcon : null;
                $request->garage ? $product ->garage = $request->garage : null;
                $request->piscine ? $product ->piscine = $request->piscine : null;
                $request->ascenseur ? $product ->ascenseur = $request->ascenseur : null;
                $request->cave ? $product ->cave = $request->cave : null;
    
                $product ->save();
                

                // suppression d'image
                if ( $request->deleteImage != null) {
                    
                    try {
                        foreach (explode(",", $request->deleteImage) as $key => $value) {
                            // supprime dans la bdd
                            $imageDelete = Picture::where('picture', explode("http://127.0.0.1:8000/storage/", $request->deleteImage)[1])
                                ->delete();
                            // supprime dans le storage
                            if(Storage::disk('public')->exists(explode("http://127.0.0.1:8000/storage/", $request->deleteImage)[1])){
                                Storage::disk('public')->delete(explode("http://127.0.0.1:8000/storage/", $request->deleteImage)[1]);
                                
                            }else {
                                return response()->json([
                                    'status' => 'false',
                                    'message' => "n'existe pas",
                                ]);
                            }


                        }
                    } catch (\Throwable $th) {
                        return response()->json([
                            'status' => 'false',
                            'message' => "erreur lors supression d'image",
                            'error' => $th
                        ]);
                    }

                }

                // ajout image
                if ($request->newImage != null) {
                    
                    // ajout image dans le storage
                    $imagePath = $request->file('newImage')->store('blog', 'public');
                    
                    //chemin de l'image
                    $imageTable = Picture::create([
                        "picture" => $imagePath,
                        "product_id" => $request->id_product,
                    ]); 
                }

                // affectation du client au produit
                if ($request->lastname) {
                    
                    try {
                        
                        $ownerExist = DB::table('clients')
                            ->where('lastname', $request->lastname)
                            ->where('firstname', $request->firstname)
                            ->where('phone', $request->phone)
                            ->where('email', $request->email)
                            ->get();
                            /* return response()->json([
                                'status' => 'true',
                                'message' => 'entre try',
                                $request->lastname
                            ]);  */
                            
                        if(count($ownerExist) == 0 ){
    
                            $user = Client::create([
                                'lastname' => $request->lastname,
                                'firstname' => $request->firstname,
                                'email' => $request->email,
                                'phone' => $request->phone,
                                'user_id' => Auth::id(),
                            ]);
                            
                            $pivot = $user->customerProduct()->attach($product->id);
                        } else {
                            // supprime l'ancien lien product -> client
                            $product->client()->detach();
                            // ajoute le nouveau lien client -> product

                            $customers = Client::find($ownerExist[0]->id);

                            $pivot = $customers->customerProduct()->attach($product->id);
                            
                            
                            return response()->json([
                                'status' => 'true',
                                'message' => 'modification client reussi',
                                'test' => $customers
                            ]);
                        }
    
                    } catch (\Throwable $th) {
    
                        return response()->json([
                            'status' => 'false',
                            'message' => 'erreur changement client',
                            'er' => $ownerExist[0]->id,
                        ]);
                    }
    
                }

                return response()->json([
                    'status' => 'true',
                    'message' => 'modification reussi',
                    'er' => $request->newImage
                ]);

            } catch (\Throwable $th) {
                
                return response()->json([
                    'status' => 'false',
                    'message' => 'modification échoué',
                    "erreur" => $th,
                ]);
            }



           /*  return (['data' => $request]); */
        }

        

    }
    //obtention des message
    public function getMessage(){

        $message = DB::table('messages')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json([
            'status' => "true",
            'data' => $message,
        ]);
    }

    // obtention du profil
    public function getProfil(){
        
        $id = Auth::id();
        

        $user = DB::table('users')
            ->where('id', $id)
            ->get();

        $userFiltered = [
            "lastname" => $user[0]->lastname,
            "firstname" => $user[0]->firstname,
            "email" => $user[0]->email,
            "phone" => $user[0]->phone,
            "label" => $user[0]->label,
        ];

        return response()->json([
            'status' => "true",
            'data' => $userFiltered,
        ]);
    }
    // modification profile
    public function updateProfil(Request $request){

        $validator = Validator::make($request->all(), [
            "lastname" => 'required|string',
            "firstname" => 'required|string',
            "email" => 'required|email',
            "phone" => 'required|numeric',
            // "latitude" => 'required',
            // "longitude"=> 'required',
            // "city" => 'required',
            "label" => 'required',
            "password" => 'nullable|regex:/^.*(?=.{8,16})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!@#$%^&*_=+-]).*$/',
        ]);

        if($validator->fails()){
            // si la validation des data echoue
            return response()->json([
                'status' => 'false',
                'data' => $validator -> Errors($validator),
                'message' => 'valeur manquante ou incorrect',
            ]);
        } else {

            try {
                
                $profil = User::find(Auth::id());

                $profil -> lastname = $request->lastname;
                $profil -> firstname = $request->firstname;
                $profil -> email = $request->email;
                $profil -> phone = $request->phone;
                if ($request -> password != null) {
                    $profil -> password = $request->password;
                }
                $request->latitude ? $profil -> latitude = $request->latitude : null;
                $request->longitude ? $profil -> longitude = $request->longitude : null;
                $request->city ? $profil -> city = $request->city : null;
                $request->label ? $profil -> label = $request->label : null;
                $profil ->save();

                return response()->json([
                    'status' => 'true',
                    'message' => 'la modification du profil reussi',
                ]);

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'false',
                    'data' => $th,
                    'message' => 'erreur lors la modification du profil',
                ]);
            }
        }
    }

    // recuperation des clients
    public function getAllCustomer(){

        try {
            $customers = DB::table('clients')
            ->where('user_id', Auth::id())
            ->get();

        
            return response()->json([
                'status' => 'true',
                'customers' => $customers,
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'false',
                'error' => $th,
            ]);
        }

    }

    // delete user 
    public function deleteUser(){
        $user_id = Auth::id();
/*         return response()->json([
            'status' => 'false'
        ]);
 */
        try {
            $user = User::find($user_id);

            $product = Product::where('user_id', $user_id)->get();
            foreach ($product as $key => $value) {
               
                // suppression d'image
                $image = Picture::where('product_id', $value->id)->get();
                
                
                if (count($image) != 0) {
                    
                    try {
                        // supprime dans la bdd
                        $imageDelete = Picture::where('id', $image[0]->id)
                            ->delete();

                        // supprime dans le storage
                        if(Storage::disk('public')->exists($image[0]->picture)){
                            Storage::disk('public')->delete($image[0]->picture);
                    
                        }else {
                            return response()->json([
                                'status' => 'false',
                                'message' => "n'existe pas",
                            ]);
                        }
                        
                    } catch (\Throwable $th) {
                        return response()->json([
                            'status' => 'false',
                            'message' => "erreur lors supression d'image",
                            'error' => $th
                        ]);
                    }
                }
                
                if (count($product) != 0) {
                    try {
                        // suppression produit
                        $product[$key]->delete();
                    } catch (\Throwable $th) {
                        return response()->json([
                            'status' => 'true',
                            'erreur' => 'product'
                        ]);
                    }
                }
                
            }


            try {
                // supprime les evenement
                $events = Event::where('user_id', $user->id);
                $events -> delete();
            } catch (\Throwable $th) {

                return response()->json([
                    'status' => 'true',
                    'erreur' => 'event'
                ]);
            }

            try {
                // supprime les clients
                $client = Client::where('user_id', $user->id);
                $client -> delete();
            } catch (\Throwable $th) {
                
                return response()->json([
                    'status' => 'true',
                    'erreur' => 'client'
                ]);
            }
            
            try {
                // supprime les messages
                $message = Message::where('user_id', $user->id);
                $message -> delete();
            } catch (\Throwable $th) {
                
                return response()->json([
                    'status' => 'true',
                    'erreur' => 'message'
                ]);
            }

            try {
                // supprime le token
                auth()->user()->tokens()->delete();
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'true',
                    'erreur' => 'token'
                ]);
            }

            
            try {
                /* return response()->json([
                'status' => 't',
                'erreur' => $user
            ]); */
                //supprime user 
                $user->delete();
            } catch (\Throwable $th) {
                
                return response()->json([
                    'status' => 'true',
                    'erreur' => 'user'
                ]);
            }
            

            return response()->json([
                'status' => 'true'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'false',
                'error' => $th
            ]);
        }

    }
}
