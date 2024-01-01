<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function addProduct(Request $request){

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

            Product::create([
                "description" => $request->description, 
                "piece" => $request->piece, 
                "surfaceTerrain" => $request->surfaceTerrain,
                "surface" => $request->surface,
                "salleDeBain" => $request->salleDeBain, 
                "chambre" => $request->chambre, 
                "terrasse" => $request->terrasse, 
                "cave" => $request->cave, 
                "bilanEnergenique" => $request->bilanEnergetique,
                "user_id" => $user_id,
            ]);

            return response()->json([
                'status' => 'true',
                'message' => 'création de product reussi',
            ]);
        }
    }

    public function getProduct(){
        $product = DB::table('products')
            ->get();

        if(count($product) != 0){
            return response()->json([
                'status' => 'true',
                'message' => 'obtention des product reussi',
                'product' => $product,
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'une erreu est survenue lors de la recuperation des product',
            ]);
        }

    }
    public function getProductSpecific(Request $request){
        
        $product = DB::table('products')
            ->where('status', $request->status)
            ->get();
       /*  dd($request); */
        
        return response()->json([
            'product' => $product,
        ]);

    }
}
