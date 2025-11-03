<?php

namespace App\Exports;

use App\Models\KeyPosition;
use Maatwebsite\Excel\Concerns\FromCollection;

class DashboardExport implements FromCollection
{
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return KeyPosition::all();
  }
}
