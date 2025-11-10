<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {
        $product = Product::all();

        if ($product->isEmpty()) {
            $data = [
                'message' => 'No hay productos registrados',
                'status' => 200
            ];
            return response()->json($data);
        }

        $data = [
            'products' => $product,
            'status' => 200
        ];
        return response()->json($data);
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nombre' => 'required|string|max:35',
            'precio' => 'required|decimal',
            'categoria' => 'string|max:20'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $product = Product::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'categoria' => $request->categoria
        ]);
        if (!$product) 
        {
            $data = [
                'message' => 'Error al crear el producto',
                'status' => 500
            ];

            return response()->json($data, 201);
        }

    }
}
