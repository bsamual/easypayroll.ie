<?php
function number_format_invoice($value)
{
    if(is_numeric($value)){
        $expvalue = explode(".",$value);
    	if(count($expvalue) > 1)
    	{
    		$value = number_format($value,2,".","");
    		$explode = explode(".",$value);
    	}
    	else{
    		$explode = explode(".",$value);
    	}
    	if(count($explode) > 1)
    	{
    		$after_decimal = substr($explode[1], 0, 2);
    		if($after_decimal < 10)
    		{
    			$checkval = substr($after_decimal, 0, 1);
    			$checkval2 = substr($after_decimal, 1, 2);
    			if($checkval == 0 && $checkval2 < 10)
    			{
    				$after_decimal = $after_decimal;
    			}
    			else{
    				$after_decimal = $after_decimal.'0';
    			}
    		}
    	}
    	else{
    		$after_decimal = '00';
    	}
    	$first = add_commas((int)$explode[0]);
    	$check_minus = substr($explode[0],0,1);
    	if($check_minus == "-" && $first == "0")
    	{
    		if($after_decimal == '00')
    		{
    			$first = $first;
    		}
    		else{
    			$first = '-'.$first;
    		}
    	}
    	return $first.'.'.$after_decimal;   
    }
    else{
        return '';
    }
}
function number_format_invoice_empty($value)
{
	if($value == "")
	{
		return '';
	}
	else{
	    if(is_numeric($value)){
    		$expvalue = explode(".",$value);
    		if(count($expvalue) > 1)
    		{
    			$value = number_format($value,2,".","");
    			$explode = explode(".",$value);
    		}
    		else{
    			$explode = explode(".",$value);
    		}
    		if(count($explode) > 1)
    		{
    			$after_decimal = substr($explode[1], 0, 2);
    			if($after_decimal < 10)
    			{
    				$checkval = substr($after_decimal, 0, 1);
    				$checkval2 = substr($after_decimal, 1, 2);
    				if($checkval == 0 && $checkval2 < 10)
    				{
    					$after_decimal = $after_decimal;
    				}
    				else{
    					$after_decimal = $after_decimal.'0';
    				}
    			}
    		}
    		else{
    			$after_decimal = '00';
    		}
    		$first = add_commas((int)$explode[0]);
    		$check_minus = substr($explode[0],0,1);
    		if($check_minus == "-" && $first == "0")
    		{
    			if($after_decimal == '00')
    			{
    				$first = $first;
    			}
    			else{
    				$first = '-'.$first;
    			}
    		}
    		return $first.'.'.$after_decimal;
	    }
	    else{
	        return '';
	    }
	}
}
function number_format_invoice_without_decimal($value)
{
	$expvalue = explode(".",$value);
	if(count($expvalue) > 1)
	{
		$value = number_format($value,2,".","");
		$explode = explode(".",$value);
	}
	else{
		$explode = explode(".",$value);
	}
	if(count($explode) > 1)
	{
		$after_decimal = substr($explode[1], 0, 2);
		if($after_decimal < 10)
		{
			$checkval = substr($after_decimal, 0, 1);
			$checkval2 = substr($after_decimal, 1, 2);
			if($checkval == 0 && $checkval2 < 10)
			{
				$after_decimal = $after_decimal;
			}
			else{
				$after_decimal = $after_decimal.'0';
			}
		}
	}
	else{
		$after_decimal = '';
	}
	$first = add_commas((int)$explode[0]);
	$check_minus = substr($explode[0],0,1);
	if($check_minus == "-" && $first == "0")
	{
		if($after_decimal == '')
		{
			$first = $first;
		}
		else{
			$first = '-'.$first;
		}
	}

	if($after_decimal == "")
	{
		return $first.'.00';
	}
	else{
		return $first.'.'.$after_decimal;
	}
}
function add_commas($number)
{
	return number_format($number);
}
function number_format_invoice_without_comma($value)
{
	if($value == "")
	{
		return '';
	}
	else{
		$expvalue = explode(".",$value);
		if(count($expvalue) > 1)
		{
			$value = number_format($value,2,".","");
			$explode = explode(".",$value);
		}
		else{
			$explode = explode(".",$value);
		}
		if(count($explode) > 1)
		{
			$after_decimal = substr($explode[1], 0, 2);
			if($after_decimal < 10)
			{
				$checkval = substr($after_decimal, 0, 1);
				$checkval2 = substr($after_decimal, 1, 2);
				if($checkval == 0 && $checkval2 < 10)
				{
					$after_decimal = $after_decimal;
				}
				else{
					$after_decimal = $after_decimal.'0';
				}
			}
		}
		else{
			$after_decimal = '00';
		}
		$first = (int)$explode[0];
		$check_minus = substr($explode[0],0,1);
		if($check_minus == "-" && $first == "0")
		{
			if($after_decimal == '00')
			{
				$first = $first;
			}
			else{
				$first = '-'.$first;
			}
		}
		return $first.'.'.$after_decimal;
	}
}
function getDirContents($dir,$missing_files,&$results = array(),&$filess = array())
{
	$files = explode("||",$missing_files);
	$ffs = opendir($dir);
	print_r($ffs);
	exit;
    foreach($ffs as $ff)
    {
        $ff = str_replace("'","''",$ff);
        if($ff!='.' && $ff!='..')
        {
            if(is_dir($dir.'/'.$ff))
            {
                $file = $dir.'/'.$ff;
                $file = str_replace('//', '/', $file);
                getDirContents($file,$missing_files,$results,$filess);
            }
            else{
            	if(in_array(strtolower($ff), $files))
            	{
            		$results[] = $dir.'/'.$ff;
            		$filess[] = strtolower($ff);
            	}
            }
        }
    }
    return json_encode(array("urls" => $results,"files" => $filess));
}
function time_task_review_all_helper()
{
	$task_ids = DB::table('time_task')->where('task_type', 2)->get();
	$array = array();
	if(count($task_ids)){
		foreach ($task_ids as $key => $singletask) {
			$taskid = $singletask->id;
			$client_ids = DB::table("cm_clients")->where("client_id","!=","")->get();

			$update_id ='';	
			$commo = '';

			if(count($client_ids)){
				foreach ($client_ids as $key => $clienid) {
					if($commo == ''){
						$commo = $clienid->client_id;
					}
					else{
						$commo =  $commo.','. $clienid->client_id;
					}
				}
			}
			$data['clients'] = $commo;
			DB::table('time_task')->where('id', $taskid)->update($data);
		}
	}
}
function user_rating($id= '',$specifics=0){
	$rating = 0;
	if($id != ""){
		$details = DB::table('taskmanager')->where('id',$id)->first();
		if(count($details)){
			$rating = $details->user_rating;
		}
	}

	if($specifics == 1){
		$cls_5 = 'taskmanagerstar_spec_'.$id.'_5';
		$cls_4 = 'taskmanagerstar_spec_'.$id.'_4';
		$cls_3 = 'taskmanagerstar_spec_'.$id.'_3';
		$cls_2 = 'taskmanagerstar_spec_'.$id.'_2';
		$cls_1 = 'taskmanagerstar_spec_'.$id.'_1';
	}
	else{
		$cls_5 = 'taskmanagerstar_'.$id.'_5';
		$cls_4 = 'taskmanagerstar_'.$id.'_4';
		$cls_3 = 'taskmanagerstar_'.$id.'_3';
		$cls_2 = 'taskmanagerstar_'.$id.'_2';
		$cls_1 = 'taskmanagerstar_'.$id.'_1';
	}

	$output = '<div class="taskmanager_rate">
	    <input type="radio" id="'.$cls_5.'" name="taskmanager_rate_input" class="taskmanager_rate_input taskmanager_star_red '; if($rating == 5) { $output.='checked_input'; } $output.='" value="5"'; if($rating == 5) { $output.=' checked'; } $output.=' data-element="'.$id.'" />
	    <label for="'.$cls_5.'" title="High">5 stars</label>
	    <input type="radio" id="'.$cls_4.'" name="taskmanager_rate_input" class="taskmanager_rate_input taskmanager_star_orange '; if($rating >= 4) { $output.='checked_input'; } $output.='" value="4"'; if($rating >= 4) { $output.=' checked'; } $output.=' data-element="'.$id.'"/>
	    <label for="'.$cls_4.'" title="High">4 stars</label>
	    <input type="radio" id="'.$cls_3.'" name="taskmanager_rate_input" class="taskmanager_rate_input taskmanager_star_yellow '; if($rating >= 3) { $output.='checked_input'; } $output.='" value="3"'; if($rating >= 3) { $output.=' checked'; } $output.=' data-element="'.$id.'"/>
	    <label for="'.$cls_3.'" title="Medium">3 stars</label>
	    <input type="radio" id="'.$cls_2.'" name="taskmanager_rate_input" class="taskmanager_rate_input taskmanager_star_chartreuse '; if($rating >= 2) { $output.='checked_input'; } $output.='" value="2"'; if($rating >= 2) { $output.=' checked'; } $output.=' data-element="'.$id.'"/>
	    <label for="'.$cls_2.'" title="Low">2 stars</label>
	    <input type="radio" id="'.$cls_1.'" name="taskmanager_rate_input" class="taskmanager_rate_input taskmanager_star_green '; if($rating >= 1) { $output.='checked_input'; } $output.='" value="1"'; if($rating >= 1) { $output.=' checked'; } $output.=' data-element="'.$id.'"/>
	    <label for="'.$cls_1.'" title="Low">1 star</label>
	    <input type="hidden" name="hidden_star_rating_taskmanager" id="hidden_star_rating_taskmanager" class="hidden_star_rating_taskmanager" value="'.$rating.'">
	</div>
	';
	
	return $output;
}
function ExtractTextFromPdf ($pdfdata) {
    if (strlen ($pdfdata) < 1000 && file_exists ($pdfdata)) $pdfdata = file_get_contents ($pdfdata); //get the data from file
    if (!trim ($pdfdata)) echo "Error: there is no PDF data or file to process.";
    $result = ''; //this will store the results
    //Find all the streams in FlateDecode format (not sure what this is), and then loop through each of them
    if (preg_match_all ('/\s*stream(.+)endstream/Uis', $pdfdata, $m)) foreach ($m[1] as $chunk) {

    	// $chunk = preg_replace("/[^a-zA-Z0-9., ]/", "", mb_convert_encoding($chunk,'UTF-8'));
        try {
        	$chunk = gzuncompress_crc32 (ltrim ($chunk)); //uncompress the data using the PHP gzuncompress function
        } catch (MyGzException $e) {
		    $chunk = $e;
		}
        //If there are [] in the data, then extract all stuff within (), or just extract () from the data directly
        $a = preg_match_all ('/\[([^\]]+)\]/', $chunk, $m2) ? $m2[1] : array ($chunk); //get all the stuff within []
        
        foreach ($a as $subchunk) if (preg_match_all ('/\(([^\)]+)\)/', $subchunk, $m3)) $result .= join (' ', $m3[1]); //within ()
    }
    else echo "Error: there is no FlateDecode text in this PDF file that I can process.";
    return $result; //return what was found
}
function gzuncompress_crc32($data) {
     $f = tempnam('/tmp', 'gz_fix');
     file_put_contents($f, "\x1f\x8b\x08\x00\x00\x00\x00\x00" . $data);
     return file_get_contents('compress.zlib://' . $f);
}
function dateformat_string($array_values) {
	$output_string = '';
    if(!empty($array_values)){
		foreach($array_values as $arr)
		{
			$output_string.=date('d M Y @ H:i', strtotime($arr)).'<br/>';
		}
	}
	
	return $output_string;
}
?>
