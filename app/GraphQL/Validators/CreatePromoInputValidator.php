<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Nuwave\Lighthouse\Validation\Validator;

final class CreatePromoInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            "discountType" => ["required", "string", "in:fixed,percentage"],
            "discount"     => ["required", "string"],
            "minimumTotal" => ["required", "string"],
            "startDate"    => ["required", "date_format:d-m-Y"],
            "endDate"      => ["required", "date_format:d-m-Y"]
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
            "discountType" => Lang::get("attributes.Type_of_discount"),
            "discount" => Lang::get("attributes.Discount"),
            "minimumTotal" => Lang::get("attributes.Minimum_total_amount"),
            "startDate" => Lang::get("attributes.Start_date"),
            "endDate" => Lang::get("attributes.End_date")
        ];
    }
}