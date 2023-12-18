<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Illuminate\Support\Facades\Lang;
use Nuwave\Lighthouse\Validation\Validator;

final class VerifyAccountInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            "email" => ["required", "email"],
            "code"  => ["required", "string", "min:4", "max:4"]
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => Lang::get("attributes.Email"),
            'code'  => Lang::get("attributes.Verification_code")
        ];
    }
}
