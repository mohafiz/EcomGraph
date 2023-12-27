<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Category;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class SearchForCategory
{
    use ResponseTrait;
    
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        try {
            $term = $args['input']['term'];

            $categories = Category::where("name", "like", "%$term%")
                            ->orWhere("name_ar", "like", "%$term%")
                            ->orWhere("name_es", "like", "%$term%")
                            ->orWhere("name_fr", "like", "%$term%")
                            ->get();

            return [
                '__typename' => 'Categories',
                'success'  => true,
                'categories' => $categories,
            ];

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError('Result');
        }
    }
}
