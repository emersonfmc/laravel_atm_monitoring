<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AreaSeeder extends Seeder
{
    public function run()
    {
            $data = [
                        [
                            'area_no' => 'A1',
                            'area_supervisor' => 'Josephine Julio',
                            'email' => 'jrjulio@everfirstloans.com',
                            'district_id' => 1,
                            'status' => 'Active',
                            'company_id' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'area_no' => 'A2',
                            'area_supervisor' => 'Cecillia Ibarra',
                            'email' => 'ceborgonos@everfirstloans.com',
                            'district_id' => 1,
                            'status' => 'Active',
                            'company_id' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'area_no' => 'A1',
                            'area_supervisor' => 'Maricar Yacat',
                            'email' => 'mdyacat@everfirstloans.com',
                            'district_id' => 2,
                                'status' => 'Active',
                            'company_id' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'area_no' => 'A2',
                            'area_supervisor' => 'Hyazel Cajipe',
                            'email' => 'hscajipe@everfirstloans.com',
                            'district_id' => 2,
                                'status' => 'Active',
                            'company_id' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'area_no' => 'A1',
                            'area_supervisor' => 'Minerva Piga',
                            'email' => 'mnpiga@everfirstloans.com',
                            'district_id' => 3,
                            'status' => 'Active',
                            'company_id' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'area_no' => 'A2',
                            'area_supervisor' => 'Imelda Estipona',
                            'email' => 'imestipona@everfirstloans.com',
                            'district_id' => 3,
                            'status' => 'Active',
                            'company_id' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'area_no' => 'A1',
                            'area_supervisor' => 'Hobert M Santiago',
                            'email' => 'hmsantiago@everfirstloans.com',
                            'district_id' => 4,
                                'status' => 'Active',
                            'company_id' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'area_no' => 'A2',
                            'area_supervisor' => 'Boots Bajaro',
                            'email' => 'bbbajaro@everfirstloans.com',
                            'district_id' => 4,
                            'status' => 'Active',
                            'company_id' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'area_no' => 'A1',
                            'area_supervisor' => 'Jocelyn Dela Cruz',
                            'email' => 'jmdelacruz@everfirstloans.com',
                            'district_id' => 5,
                                'status' => 'Active',
                            'company_id' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'area_no' => 'A2',
                            'area_supervisor' => 'Carlo Angelo Ramos',
                            'email' => 'caramos@everfirstloans.com',
                            'district_id' => 5,
                            'status' => 'Active',
                            'company_id' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'area_no' => 'A1',
                            'area_supervisor' => 'Marcelito Selda',
                            'email' => 'mtselda@everfirstloans.com',
                            'district_id' => 6,
                            'status' => 'Active',
                            'company_id' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],
                        [
                            'area_no' => 'A2',
                            'area_supervisor' => 'Christian Rodelas',
                            'email' => 'cmrodelas@everfirstloans.com',
                            'district_id' => 6,
                            'status' => 'Active',
                            'company_id' => 2,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ],

                    ];
            DB::table('data_areas')->insert($data);
    }
}
