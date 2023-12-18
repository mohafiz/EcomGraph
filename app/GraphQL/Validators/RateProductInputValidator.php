<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class RateProductInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            "productID" => ["required", "exists:products,id"],
            "rating"    => ["required", "float", "min:0.0", "max:5.0"]
        ];
    }
}