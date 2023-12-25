<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Category;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class CreateCategory
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $args = $args['input'];
            $photoPath = $this->uploadCategoryPhoto($args['photo']);

            Category::create([
                'name'  => $args['name'],
                'photo' => $photoPath
            ]);

            return $this->success();
            
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }

    static function uploadCategoryPhoto($photo)
    {
        $extension = $photo->getClientOriginalExtension();
        $name = $photo->getClientOriginalName();

        $name = Str::replaceLast(".$extension", '', $name);
        $uploadOn = "public/products";

        $baseName = Str::replaceLast(".$extension", '', $name);

        if (File::exists($uploadOn . '/' . "$baseName.$extension"))
            Storage::delete($uploadOn . '/' . "$baseName.$extension");

        Storage::putFileAs($uploadOn, $photo, "$baseName.$extension");
        return Storage::url("$uploadOn/$baseName.$extension");
    }
}
