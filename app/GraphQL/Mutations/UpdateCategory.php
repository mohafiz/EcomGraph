<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Category;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class UpdateCategory
{
    use ResponseTrait;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $category = Category::find($args['categoryId']);

            $category->update([
                'name' => array_key_exists('name', $args) ? $args['name'] : $category->name,
            ]);

            if (array_key_exists('photo', $args))
                $this->updateCategoryPhoto($category, $args['photo']);

            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError('Result');
        }
    }

    function updateCategoryPhoto($category, $photo)
    {
        if ($category->photo)
            Storage::delete(Str::replaceFirst('storage', 'public', $category->photo));

        $photoPath = CreateCategory::uploadCategoryPhoto($photo);
        $category->update(['photo' => $photoPath]);
    }
}
