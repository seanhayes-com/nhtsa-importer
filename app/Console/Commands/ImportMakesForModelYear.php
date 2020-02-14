<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MakeModel;
use App\YearMakeModel;

//use Illuminate\Foundation\Inspiring;

class ImportMakesForModelYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:mfmy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import makes for model year from NHTSA API';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        /*
array:3 [
  "Count" => 378
  "Message" => "Results returned successfully"
  "Results" => array:378 [
    0 => array:2 [
      "ModelYear" => "2000"
      "Make" => "ACCUBUILT"
    ]
        
        ...
        */

        foreach (range(1950, date('Y')) as $year) {
            //echo $year;
		
			$json_recalls_url = 'https://one.nhtsa.gov/webapi/api/Recalls/vehicle/modelyear/'.$year.'?format=json';
        	$jsonString = file_get_contents($json_recalls_url);
		
			$this->info(__LINE__.' $json_recalls_url: '.$json_recalls_url);

            $makemodels = json_decode($jsonString, true);

            $counted_results = (isset($makemodels['Count']) && !empty($makemodels['Count'])) ? intval($makemodels['Count']) : 0;
            $api_up = (isset($makemodels['Message']) && $makemodels['Message'] == 'Results returned successfully') ? true : false;

            if ($counted_results > 0 && $api_up && isset($makemodels['Results']) && (is_array($makemodels['Results']) || is_object($makemodels['Results']))) { //got to have something to work with

            foreach ($makemodels['Results'] as $makemodel) {
                $makemodelyear = (isset($makemodel['ModelYear']) && !empty($makemodel['ModelYear'])) ? $makemodel['ModelYear'] : '';
                $makemodelmake = (isset($makemodel['Make']) && !empty($makemodel['Make'])) ? title_case($makemodel['Make']) : '';
				
				$this->info(__LINE__.' '.$makemodelyear.' '.$makemodelmake);

                if ($makemodelyear != '' && $makemodelmake != '') {
                    $mmexists = MakeModel::where('ModelYear', '=', $makemodelyear)->where('Make', '=', $makemodelmake)->first();

                    if (is_null($mmexists)) {
                        $newmm = new MakeModel();
                        $newmm->ModelYear = $makemodelyear;
                        $newmm->Make = $makemodelmake;

                        if ($newmm->save()) {
                            $this->info($makemodelyear.' '.$makemodelmake);
                        }
                    }
					
					/*// Jump APIs to gather more data:
					// https://vpic.nhtsa.dot.gov/api/vehicles/getmodelsformakeyear/make/honda/modelyear/2015?format=json
					
					$json_vpic_url = 'https://vpic.nhtsa.dot.gov/api/vehicles/getmodelsformakeyear/make/'.query_fix($makemodelmake).'/modelyear/'.$makemodelyear.'?format=json';
					$this->info(__LINE__.' $json_vpic_url: '.$json_vpic_url);
					
					$json_string_vpic = file_get_contents($json_vpic_url);
					
					$this->info(__LINE__.' $json_string_vpic: '.$json_string_vpic);
					
					$yearmakemodels = json_decode($json_string_vpic, true);
					
                    $counted_results = (isset($yearmakemodels['Count']) && !empty($yearmakemodels['Count'])) ? intval($yearmakemodels['Count']) : 0;
                    $api_up = (isset($yearmakemodels['Message']) && $yearmakemodels['Message'] == 'Results returned successfully') ? true : false;
                    if ($counted_results > 0 && $api_up && isset($yearmakemodels['Results']) && (is_array($yearmakemodels['Results']) || is_object($yearmakemodels['Results']))) { //got to have something to work with
						dd($yearmakemodels);
					}*/
					
                    $jsonString2 = file_get_contents('https://one.nhtsa.gov/webapi/api/Recalls/vehicle/modelyear/'.$makemodelyear.'/make/'.query_fix($makemodelmake).'?format=json');

                    $yearmakemodels = json_decode($jsonString2, true);

                    $counted_results = (isset($yearmakemodels['Count']) && !empty($yearmakemodels['Count'])) ? intval($yearmakemodels['Count']) : 0;
                    $api_up = (isset($yearmakemodels['Message']) && $yearmakemodels['Message'] == 'Results returned successfully') ? true : false;
                    if ($counted_results > 0 && $api_up && isset($yearmakemodels['Results']) && (is_array($yearmakemodels['Results']) || is_object($yearmakemodels['Results']))) { //got to have something to work with

                        foreach ($yearmakemodels['Results'] as $yearmakemodel) {
                            $makemodelyear2 = (isset($yearmakemodel['ModelYear']) && !empty($yearmakemodel['ModelYear'])) ? $yearmakemodel['ModelYear'] : '';
                            $makemodelmake2 = (isset($yearmakemodel['Make']) && !empty($yearmakemodel['Make'])) ? title_case($yearmakemodel['Make']) : '';
                            $makemodel2 = (isset($yearmakemodel['Model']) && !empty($yearmakemodel['Model'])) ? $yearmakemodel['Model'] : '';

                            if ($makemodelyear2 != '' && $makemodelmake2 != '' && $makemodel2 != '') {
                                $ymmexists = YearMakeModel::where('ModelYear', '=', $makemodelyear)->where('Make', '=', $makemodelmake)->where('Model', '=', $makemodel2)->first();

                                if (is_null($ymmexists)) {
                                    $newymm = new YearMakeModel();
                                    $newymm->ModelYear = $makemodelyear2;
                                    $newymm->Make = $makemodelmake2;
                                    $newymm->Model = $makemodel2;

                                    if ($newymm->save()) {
                                        $this->info($makemodelyear2.' '.$makemodelmake2.' '.$makemodel2);
                                    }
                                }
                            }
                        }
                    } //if
					
                }
            }
            }
            sleep(5);
        }
    }
}
