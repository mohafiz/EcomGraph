<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Nuwave\Lighthouse\Validation\Validator;

final class UpdateCategoryInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            "categoryId" => ["required", "exists:categories,id"],
            "name"       => ["nullable", "string"],
            "name_ar"    => ["nullable", "string"],
            "name_es"    => ["nullable", "string"],
            "name_fr"    => ["nullable", "string"],
            "photo"      => ["nullable", "image"]
        ];
    }

    public function attributes(): array
    {
        Lang::setLocale(User::find(auth('sanctum')->id())->default_language);
        
        return [
            "name" => Lang::get("attributes.English_name"),
            "name_ar" => Lang::get("attributes.Arabic_name"),
            "name_es" => Lang::get("attributes.Spanish_name"),
            "name_fr" => Lang::get("attributes.French_name"),
            "photo" => Lang::get("attributes.Photo")
        ];
    }
}
