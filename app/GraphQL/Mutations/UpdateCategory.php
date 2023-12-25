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
            $args = $args['input'];
            $category = Category::find($args['categoryId']);

            $category->update([
                'name' => array_key_exists('name', $args) ? $args['name'] : $category->name,
                'name_ar' => array_key_exists('name_ar', $args) ? $args['name_ar'] : $category->name_ar,
                'name_es' => array_key_exists('name_es', $args) ? $args['name_es'] : $category->name_es,
                'name_fr' => array_key_exists('name_rf', $args) ? $args['name_fr'] : $category->name_fr,
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
