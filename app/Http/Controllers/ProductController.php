<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductInfoRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function createProduct(CreateProductRequest $request)
    {
        try {
            $photoPath = $this->uploadProductPhoto($request->file('photo'));

            $data = [
                'name' => $request->name,
                'price' => $request->price,
                'photo' => $photoPath,
                'stock' => $request->stock,
            ];

            $product = Product::create($data);
            return new ProductResource($product);
            
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
    
    public function uploadProductPhoto($photo)
    {    
        $name = time() . '_' . Str::random(10) . "." . $photo->getClientOriginalExtension();
        $uploadOn = "public/uploads/products";

        $baseName = $name;

        $duplicate = 0;
        while (File::exists($uploadOn . '/' . $name)) {
            $duplicate++;
            $name = $baseName . '(' . (string) $duplicate . ')';
        }

        Storage::putFileAs($uploadOn, $photo, $name);
        return Storage::url("$uploadOn/$name");
    }

    public function getProductsList()
    {
        try {
            $products = Product::get();
            return $this->success(ProductResource::collection($products));

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }

    public function getProduct($id)
    {
        try {
            $product = Product::find($id);

            if (!$product)
                return $this->badRequest('the product could not be found');

            return new ProductResource($product);

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }

    public function updateProductInfo(UpdateProductInfoRequest $request)
    {
        try {
            $product = Product::find($request->id);

            if (!$product)
                return $this->badRequest('the product could not be found');

            $photoPath = null;
            if ($request->has('photo'))
                $photoPath = $this->updateProductPhoto($product->photo, $request->photo);

            $product->update([
                'name' => $request->name ?? $product->name,
                'price' => $request->price ?? $product->price,
                'photo' => $photoPath ?? $product->photo,
                'stock' => $request->stock ?? $product->stock
            ]);

            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }

    public function updateProductPhoto($oldPhoto, $newPhoto)
    {
        $deletePath = str_replace('/storage/', '/public/', $oldPhoto);
        Storage::delete($deletePath);

        return $this->uploadProductPhoto($newPhoto);
    }

    public function deleteProdcut($id)
    {
        try {
            $product = Product::find($id);

            if (!$product)
                return $this->badRequest('the product could not be found');

            $product->delete();
            return $this->success();
            
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
