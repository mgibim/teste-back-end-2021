<?php

namespace App\Http\Controllers;

use App\Actions\Products\destroyProductAction;
use App\Actions\Products\storeProductAction;
use App\Actions\Products\updateProductAction;
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
    
    public function store(ProductRequest $request, storeProductAction $action)
    {
        try {

            $product = $action->execute($request);

            return responder(201)->success([
                'success' => true,
                'message' => "Produto criado com sucesso",
                'product' => (new ProductTransformer)->transform($product)
            ])->respond(201);

        } catch (\Throwable $th) {

            return responder()->error(401, 'Ocorreu um erro ao inserir o produto')->respond(401);

        }
        
    }

    public function update(ProductRequest $request, $id, updateProductAction $action)
    {
        
        try {

            $product = $action->execute($id, $request);

            return responder()->success([
                'success' => true,
                'message' => "Produto atualizado com sucesso",
                'product' => (new ProductTransformer)->transform($product)
            ])->respond(200);

        } catch (\Throwable $th) {

            return responder()->error(404, 'Produto não encontrado')->respond(404);

        }

    }

    public function destroy($id, destroyProductAction $action)
    {
        
        try {
            $action->execute($id);

            return responder()->success([
                'success' => true,
                'message' => "Produto excluído com sucesso",
            ])->respond(200);

        } catch (\Throwable $th) {
            return responder()->error(404, 'Produto não encontrado')->respond(404);
        }

    }

}