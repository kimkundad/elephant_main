<?php

namespace App\Services;

use App\Models\PickupLocation;
use App\Models\Tour;
use Illuminate\Validation\ValidationException;

class BookingPickup
{
    /**
     * Work out the pickup fields for a new booking. A guest either travels by
     * themselves or picks an active pickup point in the tour's province; the
     * free-text note is kept only alongside a pickup point.
     *
     * @return array{pickup_location_id: ?int, self_drive: bool, pickup_source: string, pickup_note: ?string}
     *
     * @throws ValidationException
     */
    public function resolve(Tour $tour, bool $selfDrive, ?int $pickupLocationId, ?string $note): array
    {
        if ($selfDrive) {
            return [
                'pickup_location_id' => null,
                'self_drive' => true,
                'pickup_source' => 'self_drive',
                'pickup_note' => null,
            ];
        }

        if (!$pickupLocationId) {
            throw ValidationException::withMessages([
                'pickup_location_id' => __('booking.errors.pickup_required'),
            ]);
        }

        $available = PickupLocation::availableIn((int) $tour->province_id)
            ->whereKey($pickupLocationId)
            ->exists();

        if (!$available) {
            throw ValidationException::withMessages([
                'pickup_location_id' => __('booking.errors.pickup_invalid'),
            ]);
        }

        $note = trim((string) $note);

        return [
            'pickup_location_id' => $pickupLocationId,
            'self_drive' => false,
            'pickup_source' => 'list',
            'pickup_note' => $note !== '' ? $note : null,
        ];
    }
}
