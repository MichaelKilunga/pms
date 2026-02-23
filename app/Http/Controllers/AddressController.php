<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AddressController extends Controller
{
    private $streets;
    private static $cachedStreets = null;

    public function __construct()
    {
        $this->loadStreets();
    }

    private function loadStreets()
    {
        if (self::$cachedStreets !== null) {
            $this->streets = self::$cachedStreets;
            return;
        }

        $path = storage_path('app/public/streets.json');
        if (file_exists($path)) {
            $this->streets = json_decode(file_get_contents($path), true);
            self::$cachedStreets = $this->streets;
        } else {
            $this->streets = [];
        }
    }

    public function getRegions()
    {
        return response()->json(array_keys($this->streets));
    }

    public function getDistricts($region)
    {
        if (isset($this->streets[$region])) {
            return response()->json(array_keys($this->streets[$region]['districts']));
        }
        return response()->json([]);
    }

    public function getWards($region, $district)
    {
        if (isset($this->streets[$region]['districts'][$district])) {
            return response()->json(array_keys($this->streets[$region]['districts'][$district]['wards']));
        }
        return response()->json([]);
    }

    public function getStreets($region, $district, $ward)
    {
        if (isset($this->streets[$region]['districts'][$district]['wards'][$ward])) {
            return response()->json(array_keys($this->streets[$region]['districts'][$district]['wards'][$ward]['streets']));
        }
        return response()->json([]);
    }
}
