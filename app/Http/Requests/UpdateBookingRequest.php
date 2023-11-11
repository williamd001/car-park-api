<?php

namespace App\Http\Requests;

use App\Rules\ParkingSpaceAvailable;
use App\Rules\ValidPrice;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
            'booking_id' => [
                'integer',
                'numeric',
                'exists:parking_space_bookings,id'
            ],
            'parking_space_id' => [
                'required',
                'integer',
                'numeric',
                'exists:parking_spaces,id',
                new ParkingSpaceAvailable
            ],
            'price_gbp' => [
                'required',
                'min:0',
                'max:99999',
                'numeric',
                new ValidPrice
            ],
        ];
    }

    public function validationData(): array
    {
        return array_merge(
            parent::validationData(),
            [
                'customer_id' => $this->route('customerId'),
                'booking_id' => $this->route('bookingId'),
            ]
        );
    }

    public function getCustomerId(): int
    {
        return (int) $this->route('customerId');
    }

    public function getParkingSpaceId(): int
    {
        return (int) $this->json('parking_space_id');
    }

    public function getDateFrom(): Carbon
    {
        return new Carbon($this->json('date_from'));
    }

    public function getDateTo(): Carbon
    {
        return new Carbon($this->json('date_to'));
    }

    public function getPriceGbp(): float
    {
        return (float) $this->json('price_gbp');
    }

    public function getBookingId(): int
    {
        return (int) $this->route('bookingId');
    }
}
