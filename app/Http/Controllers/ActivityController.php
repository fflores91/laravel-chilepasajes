<?php

namespace App\Http\Controllers;

use App\Contracts\NasaDonkiServiceInterface;

class ActivityController extends Controller
{
    protected $nasaService;

    public function __construct(NasaDonkiServiceInterface $nasaService)
    {
        $this->nasaService = $nasaService;
    }

    public function index()
    {
        $activityIds = $this->nasaService->getActivityIds();
        return response()->json(['activityIds' => $activityIds]);
    }
}
