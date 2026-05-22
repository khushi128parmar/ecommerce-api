<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $notifications = auth()->user()

            ->notifications()

            ->latest()

            ->paginate(10);

        return $this->successResponse(
            'Notifications fetched successfully',
            $notifications
        );
    }
}