<?php

namespace App\Actions\Products;

use App\Models\Product;

class destroyProductAction
{
    
    public function execute($id)
    {        
        
        $product = Product::findorFail($id);
        $result  = $product->delete();

        //File::delete(storage_path('app/public/uploads/') . $product->image);
        // Implementar Observer para Eliminar a Imagem
        
        return $result;
        
    }

}