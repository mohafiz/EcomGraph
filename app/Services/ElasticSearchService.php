<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticSearchService {

    public $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(['https://127.0.0.1:9200'])
            ->setSSLVerification(false)
            ->setBasicAuthentication(config('elasticsearch.user'), config('elasticsearch.pass'))
            ->build();
    }

    public function search($index, $query)
    {
        if ($index != 'products' && $index != 'categories')
            return;

        $params = [
            'scroll' => '30s',
            'size' => 10,
            'index' => $index,
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['name', 'name_ar', 'name_es', 'name_fr']
                    ],
                ],
            ],
        ];

        $response = $this->client->search($params)->asArray();
        return $this->constructSearchResult($response);
    }

    public function scroll($scroll_id)
    {
        $params = [
            'body' => [
                'scroll_id' => $scroll_id,
                'scroll' => '30s'
            ]
        ];

        $response = $this->client->scroll($params)->asArray();
        return $this->constructSearchResult($response);
    }

    public function constructSearchResult($response)
    {
        $result = ['total' => $response['hits']['total']['value'], 'scroll_id' => $response['_scroll_id']];
        $data = [];

        foreach ($response['hits']['hits'] as $resp)
            array_push($data, $resp['_source']);
        
        $result['data'] = $data;
        return $result;   
    }

    public function index($index, $instnace)
    {
        switch ($index) {
            case 'products':
                $indexData = $this->buildProductIndexData($instnace);
                break;
            
            case 'categories':
                $indexData = $this->buildCategoryIndexData($instnace);
                break;

            case 'orders':
                $indexData = $this->buildOrderIndexData($instnace);
                break;
            
            default:
                return;
        }

        $params = [
            'index' => $index,
            'id'    => $instnace->id,
            'body'  => $indexData
        ];

        $this->client->index($params);
    }

    public function buildProductIndexData($product)
    {
        return [
            'id'             => $product->id,
            'name'           => $product->name,
            'name_ar'        => $product->name_ar,
            'name_es'        => $product->name_es,
            'name_fr'        => $product->name_fr,
            'price'          => $product->price,
            'description'    => $product->description,
            'description_ar' => $product->description_ar,
            'description_es' => $product->description_es,
            'description_fr' => $product->description_fr
        ];
    }

    public function buildCategoryIndexData($category)
    {
        return [
            'id'       => $category->id,
            'name'     => $category->name,
            'name_ar'  => $category->name_ar,
            'name_es'  => $category->name_es
        ];
    }

    public function buildOrderIndexData($order)
    {
        return [
            'id'         => $order->id,
            'products'   => $order->products,
            'totalPrice' => $order->totalPrice,
            'status'     => $order->status->name,
            'user'       => $order->user->name,
            'created_at' => $order->created_at
        ];
    }

    public function delete($index, $id)
    {
        $params = [
            'index' => $index,
            'id'    => $id
        ];

        $this->client->delete($params);
    }

    public function update($index, $id, $data)
    {
        $params = [
            'index' => $index,
            'id'    => $id,
            'body'  => [
                'doc' => $data
            ]
        ];
    }
}