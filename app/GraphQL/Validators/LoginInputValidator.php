<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Nuwave\Lighthouse\Validation\Validator;

final class LoginInputValidator extends Validator
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
            "password" => ["required", "string", "min:6"]
        ];
    }

    public function attributes(): array
    {        
        return [
            'email' => Lang::get("attributes.Email"),
            'password' => Lang::get("attributes.Password")
        ];
    }
}
