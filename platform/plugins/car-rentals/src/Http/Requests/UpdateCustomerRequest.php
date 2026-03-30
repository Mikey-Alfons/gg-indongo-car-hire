<?php

namespace Botble\CarRentals\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Rules\EmailRule;
use Botble\CarRentals\Models\Customer;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends Request
{
    public function rules(): array
    {
        $ignoreId = $this->route('customer.id') ?: $this->route('vendor.id');

        $rules = [
            'name' => ['required', 'string', 'max:120', 'min:2'],
            'email' => [
                'required',
                new EmailRule(),
                Rule::unique((new Customer())->getTable(), 'email')->ignore($ignoreId),
            ],
            'phone' => ['nullable', 'string', ...BaseHelper::getPhoneValidationRule(true)],
            'avatar' => ['nullable', 'string'],
            'dob' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(BaseStatusEnum::values())],
            'whatsapp' => ['nullable', 'string', 'max:25'],
        ];

        if ($this->boolean('is_change_password')) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        return $rules;
    }
}
