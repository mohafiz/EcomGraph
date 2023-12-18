<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Illuminate\Support\Facades\Lang;
use Nuwave\Lighthouse\Validation\Validator;

final class UploadAvatarInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            "avatar" => ["require", "image"]
        ];
    }

    public function attributes(): array
    {
        return [
            "avatar" => Lang::get("attributes.Avatar")
        ];
    }
}