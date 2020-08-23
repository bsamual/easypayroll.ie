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
		$first = '-'.$first;
	}
	return $first.'.'.$after_decimal;
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
		$first = '-'.$first;
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
		$first = '-'.$first;
	}
	return $first.'.'.$after_decimal;
}
?>
