<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Language;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

final class SetDefaultLanguage
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
            
            $user = User::find(auth('sanctum')->id());
            $language = Language::where('code', $args['lang'])->first();

            $user->update(['language_id' => $language->id]);
            return $this->success();

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->serverError();
        }
    }
}
