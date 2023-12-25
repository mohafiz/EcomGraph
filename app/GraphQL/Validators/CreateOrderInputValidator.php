<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Nuwave\Lighthouse\Validation\Validator;

final class CreateOrderInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            "orderID"                  => ["required", "exists:orders,id"],
            "shipping"                 => ["required", "array"],
            "shipping.fullname"        => ["required", "string"],
            "shipping.shippingAddress" => ["required", "string"],
            "shipping.email"           => ["required", "string"],
            "shipping.phone"           => ["required", "string"],
            "billing"                  => ["required", "array"],
            "billing.cardHolder"       => ["required", "string"],
            "billing.cardNumber"       => ["required", "string"],
            "billing.expMonth"         => ["required", "string"],
            "billing.expYear"          => ["required", "string"],
            "billing.CVV"              => ["required", "string"]
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
            "shipping.fullname" => Lang::get("attributes.Fullname"),
            "shipping.shippingAddress" => Lang::get("attributes.Shipping_address"),
            "shipping.email" => Lang::get("attributes.Email"),
            "shipping.phone" => Lang::get("attributes.Phone"),
            "billing.cardHolder" => Lang::get("attributes.Card_holder"),
            "billing.cardNumber" => Lang::get("attributes.Card_number"),
            "billing.expMonth" => Lang::get("attributes.Expiration_month"),
            "billing.expYear" => Lang::get("attributes.Expiration_year"),
            "billing.CVV" => Lang::get("attributes.CVV")
        ];
    }
}