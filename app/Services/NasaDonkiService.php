<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Contracts\NasaDonkiServiceInterface;

class NasaDonkiService implements NasaDonkiServiceInterface
{
    protected $apiKey;
    protected $baseUrl = 'https://api.nasa.gov/DONKI/';

    protected $endpoints = [
        'CME'                => 'CME',
        'CMEAnalysis'        => 'CMEAnalysis',
        'GST'                => 'GST',
        'IPS'                => 'IPS',
        'FLR'                => 'FLR',
        'SEP'                => 'SEP',
        'MPC'                => 'MPC',
        'RBE'                => 'RBE',
        'HSS'                => 'HSS',
        'WSAEnlilSimulations'=> 'WSAEnlilSimulations',
        'notifications'      => 'notifications',
    ];

    protected $defaultStartDate = '2016-03-01';
    protected $defaultEndDate   = '2016-03-31';

    public function __construct()
    {
        $this->apiKey = config('services.nasa.api_key');
    }

    public function getAllData()
    {
        $data = [];

        foreach ($this->endpoints as $key => $endpoint) {
            $url = $this->baseUrl . $endpoint;
            $params = [
                'startDate' => $this->defaultStartDate,
                'endDate'   => $this->defaultEndDate,
                'api_key'   => $this->apiKey,
            ];

            if ($key === 'CMEAnalysis') {
                $params['mostAccurateOnly'] = 'true';
                $params['speed']            = '500';
                $params['halfAngle']        = '30';
                $params['catalog']          = 'ALL';
            }
            if ($key === 'IPS') {
                $params['location'] = '1';
                $params['catalog']  = 'ALL';
            }
            if ($key === 'notifications') {
                $params['type'] = 'all';
            }

            $response = Http::get($url, $params);

            if ($response->successful()) {
                $json = $response->json();
                if (is_array($json)) {
                    $data = array_merge($data, $json);
                }
            }
        }

        return $data;
    }

    public function getInstruments()
    {
        $data = $this->getAllData();
        $instruments = [];

        foreach ($data as $item) {
            if (isset($item['instruments']) && is_array($item['instruments'])) {
                foreach ($item['instruments'] as $inst) {
                    $name = isset($inst['displayName']) ? $inst['displayName'] : $inst;
                    $instruments[] = $name;
                }
            }
        }

        return array_values(array_unique($instruments));
    }

    public function getActivityIds()
    {
        $data = $this->getAllData();
        $activityIds = [];

        foreach ($data as $item) {
            if (isset($item['activityID'])) {
                $cleanId = $this->removeDateFromActivityId($item['activityID']);
                $activityIds[] = $cleanId;
            }
            if (isset($item['linkedEvents']) && is_array($item['linkedEvents'])) {
                foreach ($item['linkedEvents'] as $linked) {
                    if (isset($linked['activityID'])) {
                        $cleanLinkedId = $this->removeDateFromActivityId($linked['activityID']);
                        $activityIds[] = $cleanLinkedId;
                    }
                }
            }
        }

        return array_values(array_unique($activityIds));
    }


    public function getInstrumentUsage()
    {
        $data = $this->getAllData();
        $instrumentCounts = [];
        $totalCount = 0;

        foreach ($data as $item) {
            if (isset($item['instruments']) && is_array($item['instruments'])) {
                foreach ($item['instruments'] as $inst) {
                    $name = isset($inst['displayName']) ? $inst['displayName'] : $inst;
                    $instrumentCounts[$name] = ($instrumentCounts[$name] ?? 0) + 1;
                    $totalCount++;
                }
            }
        }

        if ($totalCount === 0) {
            return [];
        }

        $fractions = [];
        foreach ($instrumentCounts as $name => $count) {
            $fractions[$name] = $count / $totalCount; 
        }
        $usage = $this->roundFractionsToOne($fractions, 2);

        return $usage;
    }

    public function getUsageForInstrument($instrument)
    {
        $usage = $this->getInstrumentUsage();
        return $usage[$instrument] ?? 0;
    }

    private function removeDateFromActivityId(string $fullId): string
    {
        $parts = explode('-', $fullId);

        if (count($parts) >= 2) {
            return $parts[count($parts) - 2] . '-' . $parts[count($parts) - 1];
        }
        return $fullId;
    }

    private function roundFractionsToOne(array $fractions, int $decimals = 2): array
    {
        $rounded = [];
        foreach ($fractions as $name => $fraction) {
            $rounded[$name] = round($fraction, $decimals);
        }

        $sum = array_sum($rounded);
        $difference = 1 - $sum;
        if (abs($difference) < 0.001) {
            return $rounded;
        }

        $maxInstrument = array_keys($rounded, max($rounded))[0];
        $rounded[$maxInstrument] = round($rounded[$maxInstrument] + $difference, $decimals);

        return $rounded;
    }
}
