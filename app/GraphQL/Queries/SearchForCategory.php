<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Category;
use App\Services\ElasticSearchService;
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
            $input = $args['input'];
            $elasticSearch = new ElasticSearchService();

            if (isset($input['scroll_id']))
                $searchResult = $elasticSearch->scroll($input['scroll_id']);
            else
                $searchResult = $elasticSearch->search('products', $input['term']);

                return [
                    '__typename' => 'SearchCategories',
                    'success'    => true,
                    'total'      => $searchResult['total'],
                    'scroll_id'  => $searchResult['scroll_id'],
                    'categories' => $searchResult['data'],
                ];

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError('Result');
        }
    }
}
