<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Caracteristique;
use App\Models\Client;
use App\Models\User;
use App\Models\Picture;

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
            "description" => 'required', 
            "surface" => 'required',
            "dpe" => 'required', 
            "ges" => 'required',
            "prix" => 'required',
            "type" => 'required',
            "status" => 'required',
            "label" => 'required',
            "lastname" => 'required',
            "firstname" => 'required',
            "email" => 'required',
            "phone" => 'required',
            "image" => 'required|image',
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
            $image = $request->image;
            
            $imagePath = $request->file('image')->store('blog', 'public');
            
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
                //chemin de l'image
                $imageTable = Picture::create([
                    "picture" => $imagePath,
                    "product_id" => $test->id,
                ]);

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
            "prix" => 'required',
            "description" => 'required',
            "surface" => 'required',
            "ges" => 'required', 
            "dpe" => 'required', 
            /* "type" => 'required', */
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
                $product = Product::find($request->id_product);
            
                $product ->prix = $request->prix;
                $product ->status = $request->status;
                $product ->description = $request->description;
                $product ->piece = $request->piece;
                $product ->surfaceTerrain = $request->surfaceTerrain;
                $product ->surface = $request->surface;
                $product ->salleDeBain = $request->salleDeBain;
                $product ->chambre = $request->chambre;
                $product ->terrasse = $request->terrasse;
                $product ->cave = $request->cave;
                $product ->ges = $request->ges;
                $product ->dpe = $request->dpe;

                $product ->type = $request->type;
                $product ->balcon = $request->balcon;
                $product ->garage = $request->garage;
                $product ->piscine = $request->piscine;
                $product ->ascenseur = $request->ascenseur;
    
                $product ->save();
                // suppression d'image
                if (  $request->deleteImage != null) {
                    
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
                if ($request->newImage != "undefined") {
                    // ajout image dans le storage
                    $imagePath = $request->file('newImage')->store('blog', 'public');
                    
                    //chemin de l'image
                    $imageTable = Picture::create([
                        "picture" => $imagePath,
                        "product_id" => $request->id_product,
                    ]); 
                    /* return response()->json([
                        'status' => 'false',
                        'message' => "erreur lors de l'ajour d'image",
                        'error' => $request->newImage
                    ]); */
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
            "lastname" => 'required',
            "firstname" => 'required',
            "email" => 'required',
            "phone" => 'required',
            "latitude" => 'required',
            "longitude"=> 'required',
            "city" => 'required',
            "label" => 'required'
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
                $profil -> latitude = $request->latitude;
                $profil -> longitude = $request->longitude;
                $profil -> city = $request->city;
                $profil -> label = $request->label;
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


        return response()->json([
            "status" => "true",
            "lastname" => $request->lastname,
            "firstname" => $request->firstname,
            "email" => $request->email,
            "phone" => $request->phone,
            "password" => $request->password,
            "latitude" => $request->latitude,
            "longitude"=> $request->longitude,
            "city" => $request->city,
            "label" => $request->label
        ]);
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

}
