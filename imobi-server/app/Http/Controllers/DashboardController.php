<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Caracteristique;
use App\Models\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    // ajout de lieux ( dashboard )
    public function addProduct(Request $request){
        
        /* dd($request); */

/* 		"description" : "de", 
		"piece" : 1, 
		"surfaceTerrain" : 1,
		"surface" : 1,
		"salleDeBain"  : 1, 
		"chambre" : 1, 
		"terrasse"  : 1, 
		"cave"  : 1, 
		"bilanEnergetique"  : 1,
		"prix"  : 1,
		"status" : "rent",
		"lastname": "de",
		"firstname" : "de",
		"email" : "de",
		"phone"  : 1
 */

        // condition des data 
        $validator = Validator::make($request->all(), [
            "description" => 'required', 
            "piece" => 'required', 
            "surfaceTerrain" => 'required',
            "surface" => 'required',
            "salleDeBain" => 'required', 
            "chambre" => 'required', 
            "terrasse" => 'required', 
            "cave" => 'required', 
            "bilanEnergetique" => 'required',
            "prix" => 'required',
            "status" => 'required',
            "lastname" => 'required',
            "firstname" => 'required',
            "email" => 'required',
            "phone" => 'required',
        ]);
        
        if($validator->fails()){
            // si la validation des data echoue
            return response()->json([
                'status' => 'false',
                'data' => $validator -> Errors($validator),
                'message' => 'valeur manquante ou incorrect',
            ]);
        } else {

            $user_id = 1;

            try {
                $test = Product::create([
                    "prix" =>$request->prix,
                    "status" => $request->status,
                    "description" => $request->description, 
                    "piece" => $request->piece, 
                    "surfaceTerrain" => $request->surfaceTerrain,
                    "surface" => $request->surface,
                    "salleDeBain" => $request->salleDeBain, 
                    "chambre" => $request->chambre, 
                    "terrasse" => $request->terrasse, 
                    "cave" => $request->cave, 
                    "bilanEnergetique" => $request->bilanEnergetique,
                    "type" => "appartement",
                    "user_id" => $user_id,
                ]);
                
                return response()->json([
                    'status' => 'true',
                    'message' => 'création de product reussi',
                ]);
            } catch (\Throwable $th) {
                
                return response()->json([
                    'status' => 'false',
                    'message' => 'création de product echoué',
                    'error' => $th,
                    'ver' => $request->description
                ]);
            }


        }
    }
    // affichage de lieux ( dashboard )
    public function getProductSeller(){

        $user_id = 1;   

        $product = DB::table('products')
            ->where('user_id', $user_id )
            ->join('caracteristiques', 'products.id', 'caracteristiques.product_id')
            ->get();
      

        return response()->json([
            'status' => 'true',
            'product' => $product,
        ]);
        
    }

    public function updateProduct(Request $request){
        // condition des data 
        $validator = Validator::make($request->all(), [
            "id_product" => "required",
            "description" => 'required', 
            "piece" => 'required', 
            "surfaceTerrain" => 'required',
            "surface" => 'required',
            "salleDeBain" => 'required', 
            "chambre" => 'required', 
            "terrasse" => 'required', 
            "cave" => 'required', 
            "bilanEnergetique" => 'required',
            "prix" => 'required',
            "status" => 'required',
/*                     "lastname" => 'required',
            "firstname" => 'required',
            "email" => 'required',
            "phone" => 'required', */
        ]);

        if($validator->fails()){
            // si la validation des data echoue
            return response()->json([
                'status' => 'false',
                'data' => $validator -> Errors($validator),
                'message' => 'valeur manquante ou incorrect',
            ]);
        } else {
            
            
            $product = Product::find($request->id_product);
            $productCara = Product::find($request->id_product)->caracteristique;

            
            $product ->prix = $request->prix;
            $product ->status = $request->status;


            $productCara ->description = $request->description;
            $productCara ->piece = $request->piece;
            $productCara ->surfaceTerrain = $request->surfaceTerrain;
            $productCara ->surface = $request->surface;
            $productCara ->salleDeBain = $request->salleDeBain;
            $productCara ->chambre = $request->chambre;
            $productCara ->terrasse = $request->terrasse;
            $productCara ->cave = $request->cave;
            $productCara ->bilanEnergetique = $request->bilanEnergetique;

            $product ->save();
            $productCara ->save();

            return (['data' => $request]);
        }

        

    }
}
