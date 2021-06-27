<?php

namespace App\Actions\Products;

use App\Models\Product;

class storeProductAction
{
    
    public function execute($request)
    {        
        
        $input = $request->all();

        if ($request->hasFile('image')) {
            
            $filename = $request->image->hashName();
            $request->file('image')->storeAs('public/uploads', $filename);
            
            $input['image'] = $filename;
        }
        
        $product = Product::create($input);
        
        return $product;
    }

}