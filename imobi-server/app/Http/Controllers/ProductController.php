<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Caracteristique;
use App\Models\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    // pour affichage ( home )
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
    // pour affichage ( filtre )
    public function getProductSpecific(Request $request){
        
        $product = DB::table('products')
            ->where('status', $request->status)
            ->get();
        
        return response()->json([
            'product' => $product,
        ]);

    }
    // pour affichage des details ( detailsPage )
    public function getProductById(Request $request){

        $details = DB::table('products')
            ->where('products.id', $request->status)
            ->get();
        $caracteristiques = DB::table('caracteristiques')
            ->where('product_id', $request->status)
            ->get();

        return response()->json([
            'product' => $details,
            'caracteristique_product' => $caracteristiques,
        ]);
    }

}
