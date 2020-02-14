<?

/*
 * Global helpers file with misc functions
 */

if (!function_exists('title_case')) {

    /**
     * Helper to make sentences Title Case.
     *
     * @return mixed
     */
    function title_case($string)
    {
        $string = preg_replace("/([\'\"]+)/", '', $string);
        $len = strlen($string);
        $i = 0;
        $last = '';
        $new = '';
        $string = strtoupper($string);
        while ($i < $len):
                $char = substr($string, $i, 1);
        if (preg_match('/([A-Z]+)$/', $last)):
                        $new .= strtolower($char); else:
                        $new .= strtoupper($char);
        endif;
        $last = $char;
        ++$i;
        endwhile;

        return $new;
    } // end function
}

if (!function_exists('query_fix')) {

    /**
     * Helper to Replace character ampresand (&) value in the parameter with underscore (_) and space with %20
     *
     * @return mixed
     */
    function query_fix($string)
    {
		$string = urlencode($string);
		//$string = str_replace(' ', '%20',$string);
		//$string = str_replace('&', '_',$string);
		//$string = str_replace('.', '',$string);
		//$string = str_replace('"', '%22',$string);
		
        return $string;
    } // end function
}

if (!function_exists('sentence_case')) {
function sentence_case($string) {
    $sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
    $new_string = '';
    foreach ($sentences as $key => $sentence) {
        $new_string .= ($key & 1) == 0?
            ucfirst(strtolower(trim($sentence))) :
            $sentence.' ';
    }
    return trim($new_string);
} // end function
}

if (!function_exists('extract_date')) {
function extract_date($string) {
    // /date(1241064000000-0400)/
	//echo $string ."\n";
	preg_match('@\/date\(([0-9]+)\-([0-9]+)\)\/@ism', $string, $output_array);
	
	if(isset($output_array[1]) && !empty($output_array[1])) {
		//echo '$output_array:'.$output_array[1] ."\n";
		$remove_end_zeroes = preg_replace('/([0]+)$/is', '', $output_array[1]);
		$date_out = date("Y-m-d H:i:s", $remove_end_zeroes);
	} else {
		$date_out = date("Y-m-d H:i:s");
	}
	
	//dd($date_out);
	
	/*array(3
0	=>	/date(1241064000000-0400)/
1	=>	1241064000000
2	=>	0400
)*/
	
	//dd($output_array);
	
	//$date = (isset($output_array[1]) && !empty($output_array[1])) ? date("Y-m-d H:i:s", $output_array[1]) : date("Y-m-d H:i:s");
	
	return $date_out;
	
} // end function
}


?>