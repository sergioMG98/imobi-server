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
            ->where('status', 'sell')
            ->orWhere('status', 'rent')
            ->get();
        
        $imageProducts = DB::table('pictures')
            ->get();

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
        // recupere le produit
        $details = DB::table('products')
            ->where('products.id', $request->status)
            ->get();
        // recupere les images du produits
        $imageProducts = DB::table('pictures')
            ->where('product_id', $details[0]->id)
            ->get();
        
        $productOwner = Product::find($details[0]->id)->client;
        /* dd($productOwner); */
        $imageUrl = array();

        foreach ($imageProducts as $key => $value) {
            array_push($imageUrl, asset('/storage/' . $value->picture));
        }

        
        return response()->json([
            'product' => $details[0],
            'imageProduct' => $imageUrl,
            'productOwner' => $productOwner[0]
        ]);
    }

}
