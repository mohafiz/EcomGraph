<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class UploadProductPhoto
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $product = Product::find($args['productId']);
            $photo = $args['photo'];

            $extension = $photo->getClientOriginalExtension();
            $name = $photo->getClientOriginalName();

            $name = Str::replaceLast(".$extension", '', $name);
            $uploadOn = "public/products";

            $baseName = $product->id;

            if (File::exists($uploadOn . '/' . "$baseName.$extension"))
                Storage::delete($uploadOn . '/' . "$baseName.$extension");

            Storage::putFileAs($uploadOn, $photo, "$baseName.$extension");
            $path = Storage::url("$uploadOn/$baseName.$extension");

            $product->update(['photo' => $path]);

            return [
                '__typename' => 'ProductData',
                'success' => true,
                'product' => $product
            ];
            
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError('Result');
        }
        
    }
}
