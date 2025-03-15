<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\NasaDonkiServiceInterface;

class InstrumentController extends Controller
{
    protected $nasaService;

    public function __construct(NasaDonkiServiceInterface $nasaService)
    {
        $this->nasaService = $nasaService;
    }

    public function index()
    {
        $instruments = $this->nasaService->getInstruments();
        return response()->json(['instruments' => $instruments]);
    }

    public function usage()
    {
        $usage = $this->nasaService->getInstrumentUsage();
        return response()->json(['usage' => $usage]);
    }

    public function usageForInstrument(Request $request)
    {
        $request->validate([
            'instrument' => 'required|string'
        ]);

        $instrument = $request->input('instrument');
        $usage = $this->nasaService->getUsageForInstrument($instrument);
        return response()->json([
            'instrument' => $instrument,
            'usage'      => $usage
        ]);
    }
}
