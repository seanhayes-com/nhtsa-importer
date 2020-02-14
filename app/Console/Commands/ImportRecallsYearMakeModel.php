<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
#use App\MakeModel;
use App\YearMakeModel;
use App\Recall;

//use Illuminate\Foundation\Inspiring;

class ImportRecallsYearMakeModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:rymm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import recalls for year/make/model from NHTSA API';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //dd(extract_date('/Date(14356800000-0400)/')); 
            ///

        $YearMakeModels = YearMakeModel::where('ModelYear', '!=', '')->orderBy('ModelYear', 'desc')->get();
        // Recall:   'ModelYear', 'Make', 'Model', 'Manufacturer', 'NHTSACampaignNumber', 'ReportReceivedDate', 'Component', 'Summary'

        foreach ($YearMakeModels as $ymm) {
            $myear = (isset($ymm->ModelYear) && !empty($ymm->ModelYear)) ? $ymm->ModelYear : '';
            $mmake = (isset($ymm->Make) && !empty($ymm->Make)) ? $ymm->Make : '';
            $mmodel = (isset($ymm->Model) && !empty($ymm->Model)) ? $ymm->Model : '';

            $info_string = $myear.' '.$mmake.' '.$mmodel;

            $this->info($info_string);
			
            $api_url = 'https://one.nhtsa.gov/webapi/api/Recalls/vehicle/modelyear/'.$myear.'/make/'.query_fix($mmake).'/model/'.query_fix($mmodel).'?format=json';
			
			$ch = curl_init($api_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$api_json = '';
			if( ($api_json = curl_exec($ch) ) === false)
			{
 			   $this->error('Curl error: ' . curl_error($ch));
			   $recalls_array = array();
 			   sleep(10);
			} else {
			    $recalls_array = json_decode($api_json, true);
			}

			// Close handle
			curl_close($ch);
			

         /*  try {
               $api_url = 'https://one.nhtsa.gov/webapi/api/Recalls/vehicle/modelyear/'.$myear.'/make/'.query_fix($mmake).'/model/'.query_fix($mmodel).'?format=json';

              // $this->info('$api_url: '.$api_url);
			  
              $api_json = file_get_contents($api_url);
				
               if ($api_json === false) {
                   $recalls_array = array();//empty
               } else {
                   $recalls_array = json_decode($api_json, true);
               }
           } catch (Exception $e) {
               // Handle exception
			   $this->error(var_export($e,true));
			   sleep(10);
           }
			 */
            
            $counted_results = (isset($recalls_array['Count']) && !empty($recalls_array['Count'])) ? intval($recalls_array['Count']) : 0;

            if ($counted_results < 1) {
                $this->error('No results for Year Make Model: '.$info_string);
            }
			
			//dd($recalls_array);

            $api_up = (isset($recalls_array['Message']) && $recalls_array['Message'] == 'Results returned successfully') ? true : false;
            if ($counted_results > 0 && $api_up && isset($recalls_array['Results']) && (is_array($recalls_array['Results']) || is_object($recalls_array['Results']))) { //got to have something to work with

                foreach ($recalls_array['Results'] as $recall) {
                    $recall_year = (isset($recall['ModelYear']) && !empty($recall['ModelYear'])) ? $recall['ModelYear'] : '';
                    $recall_make = (isset($recall['Make']) && !empty($recall['Make'])) ? title_case($recall['Make']) : '';
                    $recall_model = (isset($recall['Model']) && !empty($recall['Model'])) ? title_case($recall['Model']) : '';
                    $recall_manufacturer = (isset($recall['Manufacturer']) && !empty($recall['Manufacturer'])) ? title_case($recall['Manufacturer']) : '';
                    $recall_nhtsacampaignnumber = (isset($recall['NHTSACampaignNumber']) && !empty($recall['NHTSACampaignNumber'])) ? title_case($recall['NHTSACampaignNumber']) : '';
                    $recall_reportreceiveddate = (isset($recall['ReportReceivedDate']) && !empty($recall['ReportReceivedDate'])) ? extract_date($recall['ReportReceivedDate']) : '';

                    $recall_component = (isset($recall['Component']) && !empty($recall['Component'])) ? title_case($recall['Component']) : '';
                    $recall_summary = (isset($recall['Summary']) && !empty($recall['Summary'])) ? sentence_case($recall['Summary']) : '';
					
					if($recall_summary == '') {
						$recall_summary = (isset($recall['Notes']) && !empty($recall['Notes'])) ? sentence_case($recall['Notes']) : '';
					}
					
					if($recall_summary == '') {
						//dd($recalls_array);
					}

                    if ($recall_year != '' && $recall_make != '' && $recall_model != '' && $recall_component != '' && $recall_summary != '') {
                       /* $this->error('Records:'.var_export($recall, true));
                        $this->info('$recall_year:'.$recall_year);
                        $this->info('$recall_make:'.$recall_make);
                        $this->info('$recall_model:'.$recall_model);
                        $this->info('$recall_manufacturer:'.$recall_manufacturer);
                        $this->info('$recall_nhtsacampaignnumber:'.$recall_nhtsacampaignnumber);
                        $this->info('$recall_reportreceiveddate:'.$recall_reportreceiveddate);
                        $this->info('$recall_component:'.$recall_component);
                        $this->info('$recall_summary:'.$recall_summary);*/

                        $recall_exists = Recall::where('ModelYear', '=', $recall_year)->where('Make', '=', $recall_make)->where('Model', '=', $recall_model)->where('NHTSACampaignNumber', '=', $recall_nhtsacampaignnumber)->where('ReportReceivedDate', '=', $recall_reportreceiveddate)->where('Component', '=', $recall_component)->first();

                        if (is_null($recall_exists)) {
                            //$this->info('is_null($recall_exists)');
                            $newr = new Recall();
                            $newr->ModelYear = $recall_year;
                            $newr->Make = $recall_make;
                            $newr->Model = $recall_model;
                            $newr->Manufacturer = $recall_manufacturer;
                            $newr->NHTSACampaignNumber = $recall_nhtsacampaignnumber;
                            $newr->ReportReceivedDate = $recall_reportreceiveddate;
                            $newr->Component = $recall_component;
                            $newr->Summary = $recall_summary;

                            if ($newr->save()) {
                                //$this->info('$newr->save()');
                                $this->info($recall_year.' '.$recall_make.' '.$recall_model.' '.$recall_component.' '.$recall_summary);
                            } else {
                                //$this->info('No save');
                            }
                        }
                        //dd('Test done.');
                    }
                }

               // $this->info('API works:'.$api_url);
            }
			sleep(5);
        } //foreach
    }
}
