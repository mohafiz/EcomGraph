<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class UpdateOrderStatusInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            "orderId"  => ["required", "exists:orders,id"],
            "statusId" => ["required", "exists:statuses,id"]
        ];
    }
}
