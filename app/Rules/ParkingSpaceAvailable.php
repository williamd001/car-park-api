<?php

namespace App\Rules;

use App\Repositories\ParkingSpaceRepository;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ParkingSpaceAvailable implements DataAwareRule, ValidationRule
{
    protected array $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var ParkingSpaceRepository $parkingSpaceRepository */
        $parkingSpaceRepository = app(ParkingSpaceRepository::class);

        if (! $parkingSpaceRepository->isParkingSpaceAvailable(
            (int) $this->data['parking_space_id'],
            new Carbon($this->data['date_from']),
            new Carbon($this->data['date_to']),
            $this->data['booking_id'] ?? null
        ))
        {
            $fail("Parking space {$value} is not available during the selected date range");
        }
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
