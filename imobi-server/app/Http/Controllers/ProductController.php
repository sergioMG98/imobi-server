<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Caracteristique;
use App\Models\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    // pour affichage ( home )
    public function getProduct(){
        $product = DB::table('products')
            ->get();
        
        $imageProducts = DB::table('pictures')
            ->get();
        
        // $img = array();

        // foreach ($imageProducts as $key => $imageProduct) {
        //     array_push($img, $imageProduct);
        // }

        // $imageUrl = array();

        foreach ($imageProducts as $key => $value) {
            $value->picture = asset('/storage/' . $value->picture);
        }

        if(count($product) != 0){
            return response()->json([
                'status' => 'true',
                'message' => 'obtention des product reussi',
                'product' => $product,
                'imageProduct' => $imageProducts,
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'une erreu est survenue lors de la recuperation des product',
            ]);
        }

    }
    // pour affichage ( filtre vendre / louer ) 
    public function getProductSpecific(Request $request){
        
        $product = DB::table('products')
            ->where('status', $request->status)
            ->get();
        
        $imageProducts = DB::table('pictures')
            ->get();
        
        // $img = array();

        // foreach ($imageProducts as $key => $imageProduct) {
        //     array_push($img, $imageProduct);
        // }

        $imageUrl = array();

        foreach ($imageProducts as $key => $value) {
            $value->picture = asset('/storage/' . $value->picture);
        }

        return response()->json([
            'product' => $product,
            'imageProduct' => $imageProducts,
        ]);

    }
    // pour affichage des details ( detailsPage )
    public function getProductById(Request $request){

        $details = DB::table('products')
            ->where('products.id', $request->status)
            ->get();

        $imageProducts = DB::table('pictures')
            ->where('product_id', $details[0]->id)
            ->get();
        
        $imageUrl = array();

        foreach ($imageProducts as $key => $value) {
            array_push($imageUrl, asset('/storage/' . $value->picture));
        }

       /*  $imageUrl = asset('/storage/' . $imageProduct[0]->picture); */
        
        return response()->json([
            'product' => $details[0],
            'imageProduct' => $imageUrl,
        ]);
    }

}
