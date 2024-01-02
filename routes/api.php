<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/telegram-bot-updates', [TelegramController::class, 'handleTelegramUpdates']);

use Elastic\Elasticsearch\ClientBuilder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

// Route::get('/test', function() {

    $client = ClientBuilder::create()
        ->setHosts(['https://127.0.0.1:9200'])
        ->setSSLVerification(false)
        ->setBasicAuthentication(config('elasticsearch.user'), config('elasticsearch.pass'))
        ->build();

//     // products
//     echo "indexing products ...\n";
//     $params = [
//         'index' => 'products'
//     ];

//     $client->indices()->create($params);

//     foreach (Product::all() as $product) {
//         $params = [
//             'index' => 'products',
//             'id'    => $product->id,
//             'body'  => [
//                 'id'             => $product->id,
//                 'name'           => $product->name,
//                 'name_ar'        => $product->name_ar,
//                 'name_es'        => $product->name_es,
//                 'name_fr'        => $product->name_fr,
//                 'price'          => $product->price,
//                 'description'    => $product->description,
//                 'description_ar' => $product->description_ar,
//                 'description_es' => $product->description_es,
//                 'description_fr' => $product->description_fr
//             ]
//         ];

//         $client->index($params);
//     }

//     // categories
//     echo "indexing categories ...\n";
//     $params = [
//         'index' => 'categories'
//     ];

//     $client->indices()->create($params);

//     foreach (Category::all() as $category) {
//         $params = [
//             'index' => 'categories',
//             'id'    => $category->id,
//             'body'  => [
//                 'id'      => $category->id,
//                 'name'    => $category->name,
//                 'name_ar' => $category->name_ar,
//                 'name_es' => $category->name_es,
//                 'name_fr' => $category->name_fr
//             ]
//         ];

//         $client->index($params);
//     }

//     // orders
    echo "indexing orders ...\n";
    $params = [
        'index' => 'orders'
    ];

    $client->indices()->create($params);

    foreach (Order::all() as $order) {
        $params = [
            'index' => 'orders',
            'id'    => $order->id,
            'body'  => [
                'id'         => $order->id,
                'products'   => $order->products,
                'totalPrice' => $order->totalPrice,
                'status'     => $order->status->name,
                'user'       => $order->user->name,
                'created_at' => $order->created_at
            ]
        ];

        $client->index($params);
    }

    echo "done :)";
// });