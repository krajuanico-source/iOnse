<?php

// app/Http/Controllers/LocationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Province;
use App\Models\Municipality;
use App\Models\Barangay;

class LocationController extends Controller
{
    public function getRegions()
    {
        return response()->json(Region::orderBy('name')->get());
    }

    public function getProvinces($region_code)
    {
        return response()->json(
            Province::where('region_code', $region_code)->orderBy('name')->get()
        );
    }

    public function getMunicipalities($province_code)
    {
        return response()->json(
            Municipality::where('province_code', $province_code)->orderBy('name')->get()
        );
    }

    public function getBarangays($municipality_code)
    {
        return response()->json(
            Barangay::where('municipality_code', $municipality_code)->orderBy('name')->get()
        );
    }
}
