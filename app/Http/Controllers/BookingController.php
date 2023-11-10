<?php

namespace App\Http\Controllers;

use App\Exceptions\BookingNotFoundException;
use App\Http\Requests\DeleteBookingRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Repositories\BookingRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function __construct(
        private BookingRepository $bookingRepository
    )
    {
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingRepository
            ->storeBooking(
                $request->getCustomerId(),
                $request->getParkingSpaceId(),
                $request->getDateFrom(),
                $request->getDateTo(),
                $request->getPriceGbp()
            );

        return new JsonResponse(
            $booking->toArray(),
            status: Response::HTTP_CREATED
        );
    }

    public function destroy(DeleteBookingRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingRepository->getBooking($request->getBookingId());

            if ($booking->getCustomerId() !== $request->getCustomerId()) {
                return new JsonResponse(status: Response::HTTP_NOT_FOUND);
            }
        } catch (BookingNotFoundException) {
            return new JsonResponse(status: Response::HTTP_NOT_FOUND);
        }

        $this->bookingRepository->deleteBooking($request->getBookingId());

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
