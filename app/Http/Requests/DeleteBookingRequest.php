<?php

namespace App\Http\Requests;

use App\Repositories\BookingRepository;
use Illuminate\Foundation\Http\FormRequest;

class DeleteBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // prevent invalid booking id
        $this->getValidatorInstance()->validate();

        /* @var BookingRepository $bookingRepository */
        $bookingRepository = app(BookingRepository::class);

        $booking = $bookingRepository->getBooking($this->getBookingId());

        return $this->user()->id === $booking->getUserId();
}

    public function rules(): array
    {
        return [
            'user_id' => [
                'integer',
                'numeric',
            ],
            'booking_id' => [
                'integer',
                'numeric',
                'exists:bookings,id'
            ],
        ];
    }

    public function validationData(): array
    {
        return array_merge(
            parent::validationData(),
            [
                'user_id' => $this->route('userId'),
                'booking_id' => $this->route('bookingId'),
            ]
        );
    }

    public function getBookingId(): int
    {
        return (int) $this->route('bookingId');
    }
}
