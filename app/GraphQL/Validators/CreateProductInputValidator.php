<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Nuwave\Lighthouse\Validation\Validator;

final class CreateProductInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            "category_id"    => ["required", "exists:categories,id"],
            "name"           => ["required", "string"],
            "name_ar"        => ["nullable", "string"],
            "name_es"        => ["nullable", "string"],
            "name_fr"        => ["nullable", "string"],
            "price"          => ["required", "string"],
            "stock"          => ["required", "Integer"],
            "description"    => ["required" ,"string"],
            "description_ar" => ["nullable", "string"],
            "description_es" => ["nullable", "string"],
            "description_fr" => ["nullable", "string"]
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
            "name" => Lang::get("attributes.English_name"),
            "name_ar" => Lang::get("attributes.Arabic_name"),
            "name_es" => Lang::get("attributes.Spanish_name"),
            "name_fr" => Lang::get("attributes.French_name"),
            "price" => Lang::get("attributes.Price"),
            "stock" => Lang::get("attributes.Stock"),
            "description" => Lang::get("attributes.English_description"),
            "description_ar" => Lang::get("attributes.Arabic_description"),
            "description_es" => Lang::get("attributes.Spanish_description"),
            "description_fr" => Lang::get("attributes.French_description")
        ];
    }
}
