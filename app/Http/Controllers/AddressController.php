<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
  /**
   * Get all regions.
   */
  public function getRegions()
  {
    $regions = DB::table('regions')
      ->orderBy('name', 'asc')
      ->get();

    return response()->json($regions);
  }

  /**
   * Get provinces based on region PSGC code.
   */
  public function getProvinces($region_psgc)
  {
    $provinces = DB::table('provinces')
      ->where('psgc', 'like', substr($region_psgc, 0, 2) . '%')
      ->orderBy('name', 'asc')
      ->get();

    return response()->json($provinces);
  }

  /**
   * Get cities or municipalities based on province PSGC code.
   */
  public function getCities($province_psgc)
  {
    $cities = DB::table('cities')
      ->where('psgc', 'like', substr($province_psgc, 0, 4) . '%')
      ->orderBy('name', 'asc')
      ->get();

    return response()->json($cities);
  }

  /**
   * Get barangays based on city or municipality PSGC code.
   */
  public function getBarangays($city_psgc)
  {
    $barangays = DB::table('barangays')
      ->where('psgc', 'like', substr($city_psgc, 0, 6) . '%')
      ->orderBy('name', 'asc')
      ->get();

    return response()->json($barangays);
  }
}
