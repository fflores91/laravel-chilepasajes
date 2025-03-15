<?php

namespace App\Contracts;

interface NasaDonkiServiceInterface
{
    public function getAllData();
    public function getInstruments();
    public function getActivityIds();
    public function getInstrumentUsage();
    public function getUsageForInstrument($instrument);
}
