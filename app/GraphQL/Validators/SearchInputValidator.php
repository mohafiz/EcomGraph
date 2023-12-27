<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Nuwave\Lighthouse\Validation\Validator;

final class SearchInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'term' => ['string', 'min:3', 'max:100']
        ];
    }

    public function attributes(): array
    {
        $user = User::find(auth('sanctum')->id());

        if ($user->language_id)
            $language = Language::find($user->language_id)->code;
        else
            $language = 'en';

        Lang::setLocale($language);
        
        return [
            "currency" => Lang::get("attributes.term")
        ];
    }
}
