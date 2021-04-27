<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProductRequest;
use App\Transformers\ProductTransformer;

class ProductController extends Controller
{

    public function index()
    {
        
        return responder()->success(Product::all(), ProductTransformer::class)->respond(200);

    }
    
    public function show($id)
    {
        
        $product = Product::find($id);
        
        if (!$product) {
            return responder()->error(404, 'Produto não encontrado')->respond(404);
        }
        
        return responder()->success([
            'message' => 'Produto encontrado',
            'product' => (new ProductTransformer)->transform($product)
        ])->respond(200);
        
    }
    
    public function store(ProductRequest $request)
    {
        
        $input = $request->all();
        
        if ($request->hasFile('image')) {
            
            $filename = $request->image->hashName();
            $request->file('image')->storeAs('public/uploads', $filename);
            
            $input['image'] = $filename;
        }
        
        $product = Product::create($input);
        
        if ($product) {
            return responder(201)->success([
                'success' => true,
                'message' => "Produto criado com sucesso",
                'product' => (new ProductTransformer)->transform($product)
            ])->respond(201);
        }

        return responder()
            ->error(401, 'Ocorreu um erro ao inserir o produto')
            ->respond(401);
        
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::find($id);

        if(!$product) {
            return responder()->error(404, 'Produto não encontrado')->respond(404);
        }

        $input = $request->all();

        if ($request->hasFile('image')) {
            // Exclui a imagem do produto anterior
            File::delete(storage_path('app/public/uploads/') . $product->image);

            $filename = $request->image->hashName();
            $request->file('image')->storeAs('public/uploads', $filename);
            
            $input['image'] = $filename;
        }

        $product->fill($input);
        $product->save();

        return responder()->success([
            'success' => true,
            'message' => "Produto atualizado com sucesso",
            'product' => (new ProductTransformer)->transform($product)
        ])->respond(200);
    }

    public function destroy($id)
    {
        
        $product = Product::find($id);
        
        if (!$product) {
            return responder()->error(404, 'Produto não encontrado')->respond(404);
        }

        // Exclui a imagem do produto
        File::delete(storage_path('app/public/uploads/') . $product->image);

        $result = $product->delete();

        if ($result) {
            return responder()->success([
                'success' => true,
                'message' => "Produto excluído com sucesso",
            ])->respond(200);
        }

        return responder()
            ->error(404, 'Ocorreu um erro ao excluir o produto')
            ->respond(404);
    }

}