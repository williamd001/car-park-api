<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParkingSpaceIndexRequest;
use App\Repositories\ParkingSpaceRepository;
use Illuminate\Http\JsonResponse;

class ParkingSpaceController extends Controller
{
    public function __construct(private ParkingSpaceRepository $parkingSpaceRepository)
    {
    }

    public function index(ParkingSpaceIndexRequest $request): JsonResponse
    {
        return new JsonResponse(
            $this->parkingSpaceRepository
                ->getAvailableParkingSpaces(
                    $request->getDateFrom(),
                    $request->getDateTo()
                )
        );
    }
}
