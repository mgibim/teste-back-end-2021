<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

    public function show($id)
    {
        
        $product = Product::find($id);
        
        if (!$product) {
            return responder()->error(404, 'Produto não encontrado')->respond();
        }
        
        return responder()->success([
            'message' => 'Produto encontrado',
            'product' => $product,
        ])->respond();
        
    }
    
    public function store(Request $request)
    {
        
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'name'   => 'required',
            'price'  => 'required|numeric',
            'weight' => 'sometimes|numeric',
            'image'  => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
            
        if($validator->fails()){
            
            return responder()
                ->error(422, 'Ocorreu um erro de validação')
                ->data(['fields' => $validator->errors()])
                ->respond();
        }

        if ($request->hasFile('image')) {
            $path  = $request->file('image')->store('public/uploads');
            $input['image'] = $path;
        }
        
        $product = Product::create($input);
        
        if ($product) {
            return responder()->success([
                'success' => true,
                'message' => "Produto criado com sucesso",
            ])->respond();
        }

        return responder()
            ->error(500, 'Ocorreu um erro ao inserir o produto')
            ->respond();
        
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if(!$product) {
            return responder()->error(404, 'Produto não encontrado')->respond();
        }

        $input = $request->all();
        
        $validator = Validator::make($input, [
            'name'   => 'required',
            'price'  => 'required|numeric',
            'weight' => 'sometimes|numeric',
            'image'  => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Exclui a imagem do produto atual
            File::delete(storage_path('app/') . $product->image);

            $path  = $request->file('image')->store('public/uploads');
            $input['image'] = $path;
        }

        if($validator->fails()){
            
            return responder()
                ->error(422, 'Ocorreu um erro de validação')
                ->data(['fields' => $validator->errors()])
                ->respond();
        }

        $product->fill($input);
        $product->save();

        return responder()->success([
            'success' => true,
            'message' => "Produto atualizado com sucesso",
        ])->respond();
    }

    public function destroy($id)
    {
        
        $product = Product::find($id);
        
        if (!$product) {
            return responder()->error(404, 'Produto não encontrado')->respond();
        }

        // Exclui a imagem do produto
        File::delete(storage_path('app/') . $product->image);

        $result = $product->delete();

        if ($result) {
            return responder()->success([
                'success' => true,
                'message' => "Produto excluído com sucesso",
            ])->respond();
        }

        return responder()
            ->error(404, 'Ocorreu um erro ao excluir o produto')
            ->respond();
    }

}
