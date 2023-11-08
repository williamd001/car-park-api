<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteBookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customerId' => [
                'integer',
                'numeric',
            ],
            'bookingId' => [
                'integer',
                'numeric',
            ],
        ];
    }

    public function validationData(): array
    {
        return array_merge(
            parent::validationData(),
            [
                'customerId' => $this->route('customerId'),
                'bookingId' => $this->route('bookingId'),
            ]
        );
    }

    public function getCustomerId(): int
    {
        return (int) $this->route('customerId');
    }

    public function getBookingId(): int
    {
        return (int) $this->route('bookingId');
    }
}
