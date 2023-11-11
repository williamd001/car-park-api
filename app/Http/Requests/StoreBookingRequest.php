<?php

namespace App\Http\Requests;

use App\Rules\ParkingSpaceAvailable;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'date_from' => [
                'required',
                'date_format:Y-m-d',
            ],
            'date_to' => [
                'required',
                'date_format:Y-m-d',
                'after:date_from',
            ],
            'customer_id' => [
                'integer',
                'numeric',
                'exists:customers,id'
            ],
            'price_gbp' => [
                'required',
                'min:0',
                'max:99999',
                'numeric'
            ],
            'parking_space_id' => [
                'required',
                'integer',
                'numeric',
                'exists:parking_spaces,id',
                new ParkingSpaceAvailable
            ]
        ];
    }

    public function validationData(): array
    {
        return array_merge(
            parent::validationData(),
            [
                'customer_id' => $this->route('customerId')
            ]
        );
    }

    public function getCustomerId(): int
    {
        return (int) $this->route('customerId');
    }

    public function getParkingSpaceId(): int
    {
        return (int) $this->post('parking_space_id');
    }

    public function getDateFrom(): Carbon
    {
        return new Carbon($this->post('date_from'));
    }

    public function getDateTo(): Carbon
    {
        return new Carbon($this->post('date_to'));
    }

    public function getPriceGbp(): float
    {
        return (float) $this->post('price_gbp');
    }
}