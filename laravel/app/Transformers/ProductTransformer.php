<?php

namespace App\Transformers;

use Carbon\Carbon;
use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  \App\Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'id'     => (int) $product->id,
            'name'   => (string) $product->name,
            'price'  => (float) $product->price,
            'weight' => (float) $product->weight,
            'image'  => $product->image == null 
                ? null 
                : (string) asset('storage/uploads' . '/' . $product->image),
            'created_at' => Carbon::parse($product->created_at)->format('d/m/Y H:m:i'),
            'updated_at' => Carbon::parse($product->updated_at)->format('d/m/Y H:m:i')
        ];
    }
}
