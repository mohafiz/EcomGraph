<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Nuwave\Lighthouse\Validation\Validator;

final class UpdateUserInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            "name"                  => ["nullable", "String"],
            "email"                 => ["nullable", "String"],
            "password"              => ["nullable", "String", "min:6", "confirmed"],
            "password_confirmation" => ["nullable", "String"]
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
            "name" => Lang::get("attributes.Username"),
            "email" => Lang::get("attributes.Email"),
            "password" => Lang::get("attributes.Password"),
            "password_confirmation" => Lang::get("attributes.Password_confirmation")
        ];
    }
}