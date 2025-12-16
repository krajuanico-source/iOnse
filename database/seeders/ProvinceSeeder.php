<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $provinces = [
      ['psgc' => '012800000', 'name' => 'ILOCOS NORTE'],
      ['psgc' => '012900000', 'name' => 'ILOCOS SUR'],
      ['psgc' => '013300000', 'name' => 'LA UNION'],
      ['psgc' => '015500000', 'name' => 'PANGASINAN'],
      ['psgc' => '020900000', 'name' => 'BATANES'],
      ['psgc' => '021500000', 'name' => 'CAGAYAN'],
      ['psgc' => '023100000', 'name' => 'ISABELA'],
      ['psgc' => '025000000', 'name' => 'NUEVA VIZCAYA'],
      ['psgc' => '025700000', 'name' => 'QUIRINO'],
      ['psgc' => '030800000', 'name' => 'BATAAN'],
      ['psgc' => '031400000', 'name' => 'BULACAN'],
      ['psgc' => '034900000', 'name' => 'NUEVA ECIJA'],
      ['psgc' => '035400000', 'name' => 'PAMPANGA'],
      ['psgc' => '036900000', 'name' => 'TARLAC'],
      ['psgc' => '037100000', 'name' => 'ZAMBALES'],
      ['psgc' => '037700000', 'name' => 'AURORA'],
      ['psgc' => '041000000', 'name' => 'BATANGAS'],
      ['psgc' => '042100000', 'name' => 'CAVITE'],
      ['psgc' => '043400000', 'name' => 'LAGUNA'],
      ['psgc' => '045600000', 'name' => 'QUEZON'],
      ['psgc' => '045800000', 'name' => 'RIZAL'],
      ['psgc' => '050500000', 'name' => 'ALBAY'],
      ['psgc' => '051600000', 'name' => 'CAMARINES NORTE'],
      ['psgc' => '051700000', 'name' => 'CAMARINES SUR'],
      ['psgc' => '052000000', 'name' => 'CATANDUANES'],
      ['psgc' => '054100000', 'name' => 'MASBATE'],
      ['psgc' => '056200000', 'name' => 'SORSOGON'],
      ['psgc' => '060400000', 'name' => 'AKLAN'],
      ['psgc' => '060600000', 'name' => 'ANTIQUE'],
      ['psgc' => '061900000', 'name' => 'CAPIZ'],
      ['psgc' => '063000000', 'name' => 'ILOILO'],
      ['psgc' => '064500000', 'name' => 'NEGROS OCCIDENTAL'],
      ['psgc' => '067900000', 'name' => 'GUIMARAS'],
      ['psgc' => '071200000', 'name' => 'BOHOL'],
      ['psgc' => '072200000', 'name' => 'CEBU'],
      ['psgc' => '074600000', 'name' => 'NEGROS ORIENTAL'],
      ['psgc' => '076100000', 'name' => 'SIQUIJOR'],
      ['psgc' => '082600000', 'name' => 'EASTERN SAMAR'],
      ['psgc' => '083700000', 'name' => 'LEYTE'],
      ['psgc' => '084800000', 'name' => 'NORTHERN SAMAR'],
      ['psgc' => '086000000', 'name' => 'SAMAR (WESTERN SAMAR)'],
      ['psgc' => '086400000', 'name' => 'SOUTHERN LEYTE'],
      ['psgc' => '087800000', 'name' => 'BILIRAN'],
      ['psgc' => '097200000', 'name' => 'ZAMBOANGA DEL NORTE'],
      ['psgc' => '097300000', 'name' => 'ZAMBOANGA DEL SUR'],
      ['psgc' => '098300000', 'name' => 'ZAMBOANGA SIBUGAY'],
      ['psgc' => '101300000', 'name' => 'BUKIDNON'],
      ['psgc' => '101800000', 'name' => 'CAMIGUIN'],
      ['psgc' => '103500000', 'name' => 'LANAO DEL NORTE'],
      ['psgc' => '104200000', 'name' => 'MISAMIS OCCIDENTAL'],
      ['psgc' => '104300000', 'name' => 'MISAMIS ORIENTAL'],
      ['psgc' => '112300000', 'name' => 'DAVAO DEL NORTE'],
      ['psgc' => '112400000', 'name' => 'DAVAO DEL SUR'],
      ['psgc' => '112500000', 'name' => 'DAVAO ORIENTAL'],
      ['psgc' => '118200000', 'name' => 'DAVAO DE ORO'],
      ['psgc' => '118600000', 'name' => 'DAVAO OCCIDENTAL'],
      ['psgc' => '124700000', 'name' => 'COTABATO (NORTH COTABATO)'],
      ['psgc' => '126300000', 'name' => 'SOUTH COTABATO'],
      ['psgc' => '126500000', 'name' => 'SULTAN KUDARAT'],
      ['psgc' => '128000000', 'name' => 'SARANGANI'],
      ['psgc' => '133900000', 'name' => 'MANILA CITY (NCR 1st DISTRICT)'],
      ['psgc' => '137400000', 'name' => 'NCR, SECOND DISTRICT'],
      ['psgc' => '137500000', 'name' => 'NCR, THIRD DISTRICT'],
      ['psgc' => '137600000', 'name' => 'NCR, FOURTH DISTRICT'],
      ['psgc' => '140100000', 'name' => 'ABRA'],
      ['psgc' => '141100000', 'name' => 'BENGUET'],
      ['psgc' => '142700000', 'name' => 'IFUGAO'],
      ['psgc' => '143200000', 'name' => 'KALINGA'],
      ['psgc' => '144400000', 'name' => 'MOUNTAIN PROVINCE'],
      ['psgc' => '148100000', 'name' => 'APAYAO'],
      ['psgc' => '150700000', 'name' => 'BASILAN'],
      ['psgc' => '153600000', 'name' => 'LANAO DEL SUR'],
      ['psgc' => '153800000', 'name' => 'MAGUINDANAO'],
      ['psgc' => '156600000', 'name' => 'SULU'],
      ['psgc' => '157000000', 'name' => 'TAWI-TAWI'],
      ['psgc' => '160200000', 'name' => 'AGUSAN DEL NORTE'],
      ['psgc' => '160300000', 'name' => 'AGUSAN DEL SUR'],
      ['psgc' => '166700000', 'name' => 'SURIGAO DEL NORTE'],
      ['psgc' => '166800000', 'name' => 'SURIGAO DEL SUR'],
      ['psgc' => '168500000', 'name' => 'DINAGAT ISLANDS'],
      ['psgc' => '174000000', 'name' => 'MARINDUQUE'],
      ['psgc' => '175100000', 'name' => 'OCCIDENTAL MINDORO'],
      ['psgc' => '175200000', 'name' => 'ORIENTAL MINDORO'],
      ['psgc' => '175300000', 'name' => 'PALAWAN'],
      ['psgc' => '175900000', 'name' => 'ROMBLON'],
    ];

    // Insert without failing on duplicates
    DB::table('provinces')->insertOrIgnore($provinces);
  }
}
