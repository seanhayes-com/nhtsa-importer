<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use App\MakeModel;
//use App\YearMakeModel;
use App\VpicMake;
use App\VpicVehicleTypesForMakeId;

//use Illuminate\Foundation\Inspiring;

class ImportVpicVehicleTypesForMakeId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:vpic-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import GetVehicleTypesForMakeId vPIC NHTSA API';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $VpicMakes = VpicMake::where('Make_ID', '!=', '')->get();

        foreach ($VpicMakes as $vm) {
            $make_id = (isset($vm->Make_ID) && !empty($vm->Make_ID)) ? $vm->Make_ID : '';

            $vpic_api_url = 'https://vpic.nhtsa.dot.gov/api/vehicles/GetVehicleTypesForMakeId/'.$make_id.'?format=json';
            $this->info(__LINE__.' $vpic_api_url: '.$vpic_api_url);
            $json_results = file_get_contents($vpic_api_url);
            $getalltypes = json_decode($json_results, true);

            $counted_results = (isset($getalltypes['Count']) && !empty($getalltypes['Count'])) ? intval($getalltypes['Count']) : 0;
            $api_up = (isset($getalltypes['Message']) && $getalltypes['Message'] == 'Response returned successfully') ? true : false;

            $this->info(__LINE__.' $counted_results: '.$counted_results);
            $this->info(__LINE__.' $api_up: '.$api_up);

            if ($counted_results > 0 && $api_up && isset($getalltypes['Results']) && (is_array($getalltypes['Results']) || is_object($getalltypes['Results']))) { //got to have something to work with

                 foreach ($getalltypes['Results'] as $type) {
					 
					 //dd($type);
					 
		             //$this->info(__LINE__.' $type[Make_ID]: '.$type['Make_ID']);
		             $this->info(__LINE__.' $type[VehicleTypeId]: '.$type['VehicleTypeId']);
					 $this->info(__LINE__.' $type[VehicleTypeName]: '.$type['VehicleTypeName']);

                     //'Make_ID', 'VehicleTypeId', 'VehicleTypeName'

                     $Make_ID = $make_id;//(isset($type['Make_ID']) && !empty($type['Make_ID'])) ? $type['Make_ID'] : '';
                     $VehicleTypeId = (isset($type['VehicleTypeId']) && !empty($type['VehicleTypeId'])) ? $type['VehicleTypeId'] : '';
                     $VehicleTypeName = (isset($type['VehicleTypeName']) && !empty($type['VehicleTypeName'])) ? $type['VehicleTypeName'] : '';

                     if ($Make_ID != '' && $VehicleTypeId != '' && $VehicleTypeName != '') {
                         $it_exists = VpicVehicleTypesForMakeId::where('Make_ID', '=', $Make_ID)->where('VehicleTypeId', '=', $VehicleTypeId)->where('VehicleTypeName', '=', $VehicleTypeName)->first();

                         if (is_null($it_exists)) {
                             $newr = new VpicVehicleTypesForMakeId();
                             $newr->Make_ID = $Make_ID;
                             $newr->VehicleTypeId = $VehicleTypeId;
                             $newr->VehicleTypeName = $VehicleTypeName;

                             if ($newr->save()) {
                                 //$this->info('$newr->save()');
                                 $this->info($Make_ID.' '.$VehicleTypeId.' '.$VehicleTypeName);
                             } else {
                                 //$this->info('No save');
                             }
                         }
                         //dd('Test done.');
                     }
                 }
            }
        }
    }
}
