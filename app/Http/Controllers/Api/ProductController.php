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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:35',
            'precio' => 'required|decimal:0,2',
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
        if (!$product) {
            $data = [
                'message' => 'Error al crear el producto',
                'status' => 500
            ];

            return response()->json($data, 201);
        }
        $data = [
            'product' => $product,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $data = [
            'product' => $product,
            'status' => 200
        ];
        return response()->json($data, 404);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $product->delete();
        $data = [
            'message' => 'Producto eliminado',
            'status' => 200
        ];
        return response()->json($data, 404);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:35',
            'precio' => 'required|decimal:0,2',
            'categoria' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $product->nombre = $request->nombre;
        $product->precio = $request->precio;
        $product->categoria = $request->categoria;

        $product->save();
        $data = [
            'product' => $product,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'string|max:35',
            'precio' => 'decimal:0,2',
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
        if ($request->has('nombre')) {
            $product->nombre = $request->nombre;
        }
        if ($request->has('precio')) {
            $product->precio = $request->precio;
        }
        if ($request->has('categoria')) {
            $product->categoria = $request->categoria;
        }

        $product->save();
        $data = [
            'product' => $product,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
