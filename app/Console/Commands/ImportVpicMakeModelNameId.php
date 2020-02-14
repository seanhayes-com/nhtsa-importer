<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use App\MakeModel;
//use App\YearMakeModel;
use App\VpicMake;
use App\VpicMakeModelNameId;

//use Illuminate\Foundation\Inspiring;

class ImportVpicMakeModelNameId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:vpic-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import GetModelsForMakeId vPIC NHTSA API';

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

            $vpic_api_url = 'https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeId/'.$make_id.'?format=json';
            $this->info(__LINE__.' $vpic_api_url: '.$vpic_api_url);
            $json_results = file_get_contents($vpic_api_url);
            $getallmodels = json_decode($json_results, true);

            $counted_results = (isset($getallmodels['Count']) && !empty($getallmodels['Count'])) ? intval($getallmodels['Count']) : 0;
            $api_up = (isset($getallmodels['Message']) && $getallmodels['Message'] == 'Response returned successfully') ? true : false;

            $this->info(__LINE__.' $counted_results: '.$counted_results);
            $this->info(__LINE__.' $api_up: '.$api_up);

            if ($counted_results > 0 && $api_up && isset($getallmodels['Results']) && (is_array($getallmodels['Results']) || is_object($getallmodels['Results']))) { //got to have something to work with

                 foreach ($getallmodels['Results'] as $model) {

                     //'Make_ID', 'Make_Name', 'Model_ID', 'Model_Name'

                     $Make_ID = (isset($model['Make_ID']) && !empty($model['Make_ID'])) ? $model['Make_ID'] : '';
                     $Make_Name = (isset($model['Make_Name']) && !empty($model['Make_Name'])) ? $model['Make_Name'] : '';
                     $Model_ID = (isset($model['Model_ID']) && !empty($model['Model_ID'])) ? $model['Model_ID'] : '';
                     $Model_Name = (isset($model['Model_Name']) && !empty($model['Model_Name'])) ? $model['Model_Name'] : '';

                     if ($Make_ID != '' && $Make_Name != '' && $Model_ID != '' && $Model_Name != '') {
                         $it_exists = VpicMakeModelNameId::where('Make_ID', '=', $Make_ID)->where('Make_Name', '=', $Make_Name)->where('Model_ID', '=', $Model_ID)->where('Model_Name', '=', $Model_Name)->first();

                         if (is_null($it_exists)) {
                             $newr = new VpicMakeModelNameId();
                             $newr->Make_ID = $Make_ID;
                             $newr->Make_Name = $Make_Name;
                             $newr->Model_ID = $Model_ID;
                             $newr->Model_Name = $Model_Name;

                             if ($newr->save()) {
                                 //$this->info('$newr->save()');
                                 $this->info($Make_ID.' '.$Make_Name.' '.$Model_ID.' '.$Model_Name);
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
