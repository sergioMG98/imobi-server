<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function getAllCustomer(){
        $user_id = 1;

        $customers = DB::table('clients')
            ->where('clients.user_id', $user_id)
            ->get();

        
        return response()->json([
            'customers' => $customers,
        ]);
    }

    public function getProductOfCustomer(Request $request){

        try {

            $productCustomer = Client::find($request->customer_id)
                ->customerProduct()
                ->get();

            $caraProduct = array() ;

            foreach ($productCustomer as $key => $value) {
                $c = DB::table('products')
                    ->where('products.id', $value->id)
                    ->join('caracteristiques', 'products.id', 'caracteristiques.product_id')
                    ->get();
                
                $caraProduct[] = $c[0];
                
            }
            
            return response()->json([
                'status' => 'true',
                'customerProduct' => $caraProduct
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => 'false',
                'message' => 'aucune données trouver'
            ]);
        }

    }
}
