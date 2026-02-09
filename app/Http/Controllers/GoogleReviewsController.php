<?php

namespace App\Http\Controllers;

use App\Services\GoogleReviewsService;

class GoogleReviewsController extends Controller
{
    public function index(GoogleReviewsService $service)
    {
        return response()->json($service->get());
    }
}
