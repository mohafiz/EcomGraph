<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Nuwave\Lighthouse\Validation\Validator;

final class SetDefaultLanguageInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            "lang" => ["required", "String", "in:en,ar,es,fr"]
        ];
    }

    public function attributes(): array
    {
        Lang::setLocale(User::find(auth('sanctum')->id())->default_language);
        
        return [
            "lang" => Lang::get("attributes.Language")
        ];
    }
}