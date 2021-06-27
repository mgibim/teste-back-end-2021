<?php

namespace App\Actions\Products;

use App\Models\Product;

class updateProductAction
{
    
    public function execute($id, $request)
    {        
        
        $product = Product::findOrFail($id);
        $input   = $request->all();

        // Refatorar para dentro de um Observer
        // if ($request->hasFile('image')) {
        //     // Exclui a imagem do produto anterior
        //     File::delete(storage_path('app/public/uploads/') . $product->image);
        //     $filename = $request->image->hashName();
        //     $request->file('image')->storeAs('public/uploads', $filename);
        //     $input['image'] = $filename;
        // }

        $product->fill($input);
        $product->save();

        return $product;
        
    }

}