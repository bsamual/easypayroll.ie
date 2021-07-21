<?php
function number_format_invoice($value)
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
function number_format_invoice_empty($value)
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
?>
