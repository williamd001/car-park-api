<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteBookingRequest;
use App\Repositories\BookingRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function __construct(private BookingRepository $bookingRepository)
    {
    }

    public function destroy(DeleteBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingRepository->getBooking($request->getBookingId());

        if ($booking === null || $booking->getCustomerId() !== $request->getCustomerId()) {
            return new JsonResponse(status: Response::HTTP_NOT_FOUND);
        }

        $this->bookingRepository->deleteBooking($request->getBookingId());

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
