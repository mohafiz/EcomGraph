<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class CheckoutInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            "details"             => ["required", "array"],
            "details.*"           => ["required", "array"],
            "details.*.productId" => ["required", "exists:products,id"],
            "details.*.quantity"  => ["required", "integer"],
            "promoCode"           => ["nullable", "string"]
        ];
    }
}