<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use App\MakeModel;
//use App\YearMakeModel;
use App\VpicMake;

//use Illuminate\Foundation\Inspiring;

class ImportVpicMakes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:vpic-makes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import makes vPIC NHTSA API';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		
		$json_getallmakes_url = 'https://vpic.nhtsa.dot.gov/api/vehicles/getallmakes?format=json';
    	$json_results = file_get_contents($json_getallmakes_url);
	
		$this->info(__LINE__.' $json_getallmakes_url: '.$json_getallmakes_url);

        $getallmakes = json_decode($json_results, true);
		

        $counted_results = (isset($getallmakes['Count']) && !empty($getallmakes['Count'])) ? intval($getallmakes['Count']) : 0;
        $api_up = (isset($getallmakes['Message']) && $getallmakes['Message'] == 'Response returned successfully') ? true : false;
		
		$this->info(__LINE__.' $counted_results: '.$counted_results);
		$this->info(__LINE__.' $api_up: '.$api_up);

        if ($counted_results > 0 && $api_up && isset($getallmakes['Results']) && (is_array($getallmakes['Results']) || is_object($getallmakes['Results']))) { //got to have something to work with
			
			 foreach ($getallmakes['Results'] as $vmake) {
				 
				 $Make_ID = (isset($vmake['Make_ID']) && !empty($vmake['Make_ID'])) ? $vmake['Make_ID'] : '';
				 $Make_Name = (isset($vmake['Make_Name']) && !empty($vmake['Make_Name'])) ? $vmake['Make_Name'] : '';
				 
                 if ($Make_ID != '' && $Make_Name != '') {

                     $it_exists = VpicMake::where('Make_ID', '=', $Make_ID)->where('Make_Name', '=', $Make_Name)->first();

                     if (is_null($it_exists)) {
                         $newr = new VpicMake();
                         $newr->Make_ID = $Make_ID;
                         $newr->Make_Name = $Make_Name;
              
                         if ($newr->save()) {
                             //$this->info('$newr->save()');
                             $this->info($Make_ID.' '.$Make_Name);
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
