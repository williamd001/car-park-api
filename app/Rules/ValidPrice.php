<?php

namespace App\Rules;

use App\Repositories\ParkingSpaceRepository;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPrice implements DataAwareRule, ValidationRule
{
    protected array $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var ParkingSpaceRepository $parkingSpaceRepository */
        $parkingSpaceRepository = app(ParkingSpaceRepository::class);

        $parkingSpaceId = (int) $this->data['parking_space_id'];

        $dateFrom = new Carbon($this->data['date_from']);

        $dateTo = new Carbon($this->data['date_to']);

        $validPrice = $parkingSpaceRepository->calculatePriceGbp(
            $parkingSpaceId,
            $dateFrom,
            $dateTo
        );

        if ((float) $this->data['price_gbp'] !== $validPrice) {
            $fail(
                sprintf("Invalid price for Parking space %s. From %s to %s. Price in GBP should be %s",
                    $parkingSpaceId,
                    $dateFrom->toDateString(),
                    $dateTo->toDateString(),
                    number_format($validPrice, 2)
                )
            );
        }
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
