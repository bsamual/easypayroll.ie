<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use Hash;
class PaymentController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('userauth');
		date_default_timezone_set("Europe/Dublin");
		require_once(app_path('Http/helpers.php'));
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function payment_management()
	{
		$payment_nominal_codes_arr = DB::table('payment_nominal_codes')->get();
		$code_array = array();
		if(count($payment_nominal_codes_arr))
		{
			foreach($payment_nominal_codes_arr as $code)
			{
				array_push($code_array,$code->code);
			}
		}
		$nominal_codes = DB::table('nominal_codes')->whereNotin('code',$code_array)->orderBy('code','asc')->get();
		$payment_nominal_codes = DB::table('payment_nominal_codes')->orderBy('code','asc')->get();
		return view('user/payment/payment', array('title' => 'Payment Management System','nominal_codes' => $nominal_codes,'payment_nominal_codes' => $payment_nominal_codes));
	}
	public function payment_move_to_allowable_list()
	{
		$code = explode(',',Input::get('code'));
		$get_details = DB::table('nominal_codes')->whereIn('id',$code)->get();
		$output = '';
		if(count($get_details))
		{
			foreach($get_details as $details)
			{
				$data['code'] = $details->code;
				$data['description'] = $details->description;
				DB::table('payment_nominal_codes')->insert($data);

				$output.='<tr>
					<td>'.$details->code.'</td>
					<td>'.$details->description.'</td>
				</tr>';
			}
		}
		echo $output;
	}
	public function payment_save_details()
	{
		$date = explode("/",Input::get('date'));
		$debit = Input::get('debit');
		$credit = Input::get('credit');
		$client_code = Input::get('client_code');
		$des = Input::get('des');
		$comment = Input::get('comment');
		$amount = Input::get('amount');

		$data['payment_date'] = $date[2].'-'.$date[1].'-'.$date[0];
		$data['debit_nominal'] = $debit;
		$data['credit_nominal'] = $credit;
		$data['client_code'] = $client_code;
		$data['credit_description'] = $des;
		$data['comment'] = $comment;
		$data['amount'] = $amount;

		$id = DB::table('payments')->insertGetid($data);
		echo $id;
	}

	public function payment_update_details()
	{
		$date = explode("/",Input::get('date'));
		$debit = Input::get('debit');
		$credit = Input::get('credit');
		$client_code = Input::get('client_code');
		$des = Input::get('des');
		$amount = Input::get('amount');
		$comment = Input::get('comment');
		$payment_id = Input::get('payment_id');

		if($date == "") { $data['status'] = 1; }
		elseif($debit == "") { $data['status'] = 1; }
		elseif($credit == "") { $data['status'] = 1; }
		elseif($des == "") { $data['status'] = 1; }
		elseif($amount == "") { $data['status'] = 1; }
		elseif($credit == "712" || $credit == "813A") { 
			if($client_code == "") { $data['status'] =  1; }
		}
		else{
			$data['status'] =  0;
		}

		$data['payment_date'] = $date[2].'-'.$date[1].'-'.$date[0];
		$data['debit_nominal'] = $debit;
		$data['credit_nominal'] = $credit;
		$data['client_code'] = $client_code;
		$data['credit_description'] = $des;
		$data['comment'] = $comment;
		$data['amount'] = $amount;

		DB::table('payments')->where('payments_id',$payment_id)->update($data);
	}
	public function payment_commonclient_search()
	{
		$value = Input::get('term');
		$details = DB::table('cm_clients')->Where('client_id','like','%'.$value.'%')->Where('status', 0)->get();

		$data=array();
		foreach ($details as $single) {
			if($single->company != "")
			{
				$company = $single->company;
			}
			else{
				$company = $single->firstname.' '.$single->surname;
			}
            $data[]=array('value'=>$company.' - '.$single->client_id,'id'=>$single->client_id,'company' => $company.' - '.$single->client_id);

        }
         if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>'','company' => ''];

	}

	public function load_payment()
	{
		$filter = Input::get('filter');
		$expfrom = explode('/',Input::get('from'));
		$expto = explode('/',Input::get('to'));
		$client = Input::get('client');
		$debit = Input::get('debit');
		$credit = Input::get('credit');

		if(count($expfrom) > 2)
		{
			$from = $expfrom[2].'-'.$expfrom[1].'-'.$expfrom[0];
		}
		if(count($expto) > 2)
		{
			$to = $expto[2].'-'.$expto[1].'-'.$expto[0];
		}

		if($filter == "1")
		{
			$current_year = date('Y');
			$get_payments = DB::table('payments')->where('payment_date','like',$current_year.'%')->where('imported',0)->get();
		}
		elseif($filter == "2")
		{
			$get_payments = DB::table('payments')->where('payment_date','>=',$from)->where('payment_date','<=',$to)->where('imported',0)->get();
		}
		elseif($filter == "3")
		{
			$get_payments = DB::table('payments')->where('client_code','like','%'.$client.'%')->where('imported',0)->get();
		}
		elseif($filter == "4")
		{
			$get_payments = DB::table('payments')->where('debit_nominal',$debit)->where('imported',0)->get();
		}
		elseif($filter == "5")
		{
			$get_payments = DB::table('payments')->where('credit_nominal',$credit)->where('imported',0)->get();
		}
		elseif($filter == "6")
		{
			$get_payments = DB::table('payments')->where('imported',0)->orderBy('id','desc')->limit(300)->get();
		}
		$output = '';
		if(count($get_payments))
		{
			foreach($get_payments as $payment)
			{
				if($payment->status == 1)
				{
					$font_color = 'color:#f00;font-weight:600';
				}
				elseif($payment->hold_status == 0)
				{
					$font_color = 'color:blue;font-weight:600';
				}
				else{
					$font_color = '';
				}
				$get_details = DB::table('payment_nominal_codes')->where('code',$payment->debit_nominal)->first();
				$debit_nominal = '';
				if(count($get_details))
				{
					$debit_nominal = $get_details->code.' - '.$get_details->description;
				}
				if($payment->hold_status == 0)
				{
					$hold_status = '<a href="javascript:" class="common_black_button change_to_unhold" data-element="'.$payment->payments_id.'">Hold</a>';
				}
				else{
					$hold_status = '<a href="javascript:" class="common_black_button unhold_payment" data-element="'.$payment->payments_id.'">Unhold</a>';
				}
				$output.='<tr>
					<td style="'.$font_color.'"><spam class="date_sort_val" style="display:none">'.strtotime($payment->payment_date).'</spam>'.date('d/m/Y', strtotime($payment->payment_date)).'</td>
					<td class="debit_sort_val" style="'.$font_color.'">'.$debit_nominal.'</td>
					<td class="credit_sort_val" style="'.$font_color.'">'.$payment->credit_nominal.'</td>
					<td class="client_sort_val" style="'.$font_color.'">'.$payment->client_code.'</td>	
					<td class="des_sort_val" style="'.$font_color.'">'.$payment->credit_description.'</td>
					<td class="comment_sort_val" style="'.$font_color.'">'.$payment->comment.'</td>	
					<td style="'.$font_color.';text-align:right"><spam class="amount_sort_val" style="display:none">'.$payment->amount.'</spam>'.number_format_invoice_empty($payment->amount).'</td>	
					<td style="'.$font_color.';text-align:right"></td>	
					<td>'.$hold_status.'</td>	
					<td>
						<a href="javascript:" class="fa fa-edit edit_payment" title="Edit Payment"></a>&nbsp;&nbsp;
						<a href="javascript:" class="fa fa-trash delete_payment" title="Delete Payment"></a>
					</td>	
				</tr>';
			}
		}
		else
		{
			$output.='<tr>
                  <td colspan="10" style="text-align: center">No Records Found</td>
                </tr>';
		}
		echo $output;
	}

	public function export_load_payment()
	{
		$filter = Input::get('filter');
		$expfrom = explode('/',Input::get('from'));
		$expto = explode('/',Input::get('to'));
		$client = Input::get('client');
		$debit = Input::get('debit');
		$credit = Input::get('credit');

		if(count($expfrom) > 2)
		{
			$from = $expfrom[2].'-'.$expfrom[1].'-'.$expfrom[0];
		}
		if(count($expto) > 2)
		{
			$to = $expto[2].'-'.$expto[1].'-'.$expto[0];
		}

		if($filter == "1")
		{
			$current_year = date('Y');
			$get_payments = DB::table('payments')->where('payment_date','like',$current_year.'%')->get();
		}
		elseif($filter == "2")
		{
			$get_payments = DB::table('payments')->where('payment_date','>=',$from)->where('payment_date','<=',$to)->get();
		}
		elseif($filter == "3")
		{
			$get_payments = DB::table('payments')->where('client_code','like','%'.$client.'%')->get();
		}
		elseif($filter == "4")
		{
			$get_payments = DB::table('payments')->where('debit_nominal',$debit)->get();
		}
		elseif($filter == "5")
		{
			$get_payments = DB::table('payments')->where('credit_nominal',$credit)->get();
		}
		elseif($filter == "6")
		{
			$get_payments = DB::table('payments')->orderBy('id','desc')->limit(300)->get();
		}

		$columns = array('Date', 'Debit Nominal', 'Credit Nominal', 'Client Code', 'Credit Nominal Description', 'Comment', 'Amount','Journal Id','Status');
		$filename = time().'_Export_payment_list.csv';
		$file = fopen('papers/'.$filename, 'w');
		fputcsv($file, $columns);

		$output = '';
		if(count($get_payments))
		{
			foreach($get_payments as $payment)
			{
				$get_details = DB::table('payment_nominal_codes')->where('code',$payment->debit_nominal)->first();
				$debit_nominal = '';
				if(count($get_details))
				{
					$debit_nominal = $get_details->code;
				}
				if($payment->hold_status == 0)
				{
					$hold_status = 'Hold';
				}
				else{
					$hold_status = 'Unhold';
				}

				$columns_2 = array(date('d/m/Y', strtotime($payment->payment_date)),$debit_nominal,$payment->credit_nominal,$payment->client_code,$payment->credit_description,$payment->comment,number_format_invoice($payment->amount),'',$hold_status);
				fputcsv($file, $columns_2);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function payment_change_to_unhold()
	{
		$id = Input::get('id');
		$data['hold_status'] = 1;
		DB::table('payments')->where('payments_id',$id)->update($data);
	}

	public function import_new_payment()
	{
		$get_imported_payment = DB::table('payments')->where('imported',1)->get();
		if(count($get_imported_payment))
		{
			foreach($get_imported_payment as $payment)
			{
				$daata['imported'] = 0;
				$client_name = '';
				if($payment->client_code != "")
				{
					$get_client_name = DB::table('cm_clients')->where('client_id',strtoupper($payment->client_code))->first();
					if(count($get_client_name))
					{
						$client_name = $get_client_name->company.' - '.$get_client_name->client_id;
					}
				}
				$daata['client_code'] = $client_name;
				DB::table('payments')->where('payments_id',$payment->payments_id)->update($daata);
			}
		  	return redirect('user/payment_management')->with('message_import', 'Payments Imported successfully.');
		}
		// if($_FILES['new_file']['name']!='')
		// {
		// 	$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		// 	$tmp_name = $_FILES['new_file']['tmp_name'];
		// 	$name=time().'_'.$_FILES['new_file']['name'];
		// 	$errorlist = array();
		// 	if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){

		// 		$filepath = $uploads_dir.'/'.$name;

		// 		$objPHPExcel = PHPExcel_IOFactory::load($filepath);

		// 		foreach ($objPHPExcel->getWorksheetIterator() as $keyval => $worksheet) {

		// 			$worksheetTitle     = $worksheet->getTitle();
		// 			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
		// 			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
		// 			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		// 			$nrColumns = ord($highestColumn) - 64;
		// 			if($highestRow > 500)
		// 			{
		// 				$height = 500;
		// 			}
		// 			else{
		// 				$height = $highestRow;
		// 			}
		// 			$date_header = $worksheet->getCellByColumnAndRow(0, 1); $date_header = trim($date_header->getValue());
		// 			$debit_header = $worksheet->getCellByColumnAndRow(1, 1); $debit_header = trim($debit_header->getValue());
		// 			$credit_header = $worksheet->getCellByColumnAndRow(2, 1); $credit_header = trim($credit_header->getValue());
		// 			$client_header = $worksheet->getCellByColumnAndRow(3, 1); $client_header = trim($client_header->getValue());
		// 			$description_header = $worksheet->getCellByColumnAndRow(4, 1); $description_header = trim($description_header->getValue());
		// 			$comment_header = $worksheet->getCellByColumnAndRow(5, 1); $comment_header = trim($comment_header->getValue());
		// 			$amount_header = $worksheet->getCellByColumnAndRow(6, 1); $amount_header = trim($amount_header->getValue());

					
		// 			if($date_header == "Date" && $debit_header == "Debit Nominal" && $credit_header == "Credit Nominal" && $client_header == "Client Code" && $comment_header == "Comment" && $amount_header == "Amount")
		// 			{
		// 				for ($row = 2; $row <= $height; $row++) {
		// 					$date = $worksheet->getCellByColumnAndRow(0, $row); $date = trim($date->getValue());
		// 					$debit = $worksheet->getCellByColumnAndRow(1, $row); $debit = trim($debit->getValue());
		// 					$credit = $worksheet->getCellByColumnAndRow(2, $row); $credit = trim($credit->getValue());
		// 					$client = $worksheet->getCellByColumnAndRow(3, $row); $client = trim($client->getValue());
		// 					$description = $worksheet->getCellByColumnAndRow(4, $row); $description = trim($description->getValue());
		// 					$comment = $worksheet->getCellByColumnAndRow(5, $row); $comment = trim($comment->getValue());
		// 					$amount = $worksheet->getCellByColumnAndRow(6, $row); $amount = trim($amount->getValue());

		// 					$exp_date = explode('/',$date);

		// 					$data['receipt_date'] = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0];
		// 					$data['debit_nominal'] = $debit;
		// 					$data['credit_nominal'] = $credit;
		// 					$data['client_code'] = $client;
		// 					$data['credit_description'] = $description;
		// 					$data['comment'] = $comment;
		// 					$data['amount'] = $amount;
		// 					$data['imported'] = 1;

		// 					DB::table('receipts')->insert($data);
		// 				}
		// 			}
		// 			else{
		// 				return redirect('user/invoice_management')->with('message_import', 'Import Failed! Invalid Import File');
		// 			}
		// 		}
				
		// 		if($height >= $highestRow)
		// 		{
		// 			return redirect('user/payment_management')->with('message_import', 'Receipt Imported successfully.');
		// 		}
		// 		else{
		// 			return redirect('user/payment_management?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type_new=1');
		// 		}
		// 	}
		// }
	}
	public function import_new_payment_one()
	{
		$name = Input::get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			$round = Input::get('round');
			$last_height = Input::get('height');
			$offset = $round - 1;
			$offsetcount = $last_height + 1;
			$roundcount = $round * 500;
			$nextround = $round + 1;
			if($highestRow > $roundcount)
			{
				$height = $roundcount;
			}
			else{
				$height = $highestRow;
			}
			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$date = $worksheet->getCellByColumnAndRow(0, $row); $date = trim($date->getValue());
				$debit = $worksheet->getCellByColumnAndRow(1, $row); $debit = trim($debit->getValue());
				$credit = $worksheet->getCellByColumnAndRow(2, $row); $credit = trim($credit->getValue());
				$client = $worksheet->getCellByColumnAndRow(3, $row); $client = trim($client->getValue());
				$description = $worksheet->getCellByColumnAndRow(4, $row); $description = trim($description->getValue());
				$comment = $worksheet->getCellByColumnAndRow(5, $row); $comment = trim($comment->getValue());
				$amount = $worksheet->getCellByColumnAndRow(6, $row); $amount = trim($amount->getValue());

				$exp_date = explode('/',$date);

				$data['payment_date'] = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0];
				$data['debit_nominal'] = $debit;
				$data['credit_nominal'] = $credit;
				$data['client_code'] = $client;
				$data['credit_description'] = $description;
				$data['comment'] = $comment;
				$data['amount'] = $amount;
				$data['imported'] = 1;

				DB::table('payments')->insert($data);
			}
		}
		if($height >= $highestRow)
		{
			return redirect('user/payment_management')->with('message_import', 'Payment Imported successfully.');
		}
		else{
			return redirect('user/payment_management?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&import_type_new=1');
		}
	}

	public function add_payment_export_csv()
	{
		$ids = Input::get('ids');		
		$expids = explode(',',$ids);
		$columns = array('Date', 'Debit Nominal', 'Credit Nominal', 'Client Code', 'Credit Nominal Description', 'Comment', 'Amount','Journal Id','Status');
		$filename = time().'_Export_add_payment_list.csv';
		$file = fopen('papers/'.$filename, 'w');
		fputcsv($file, $columns);
		if($ids != "")
		{
			foreach($expids as $id)
			{				
				$get_details = DB::table('payments')->where('payments_id',$id)->first();				
				$get_debit_details = DB::table('payment_nominal_codes')->where('code',$get_details->debit_nominal)->first();
				$debit_nominal = '';
				if(count($get_debit_details))
				{
					$debit_nominal = $get_debit_details->code;
				}
				$columns_2 = array(date('d/m/Y', strtotime($get_details->payment_date)),$debit_nominal,$get_details->credit_nominal,$get_details->client_code,$get_details->credit_description,$get_details->comment,number_format_invoice($get_details->amount),'','On Hold');
				fputcsv($file, $columns_2);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function check_import_csv_payment()
	{
		$get_imported_payment = DB::table('payments')->where('imported',1)->get();
		if(count($get_imported_payment))
		{
		  DB::table('payments')->where('imported',1)->delete();
		}
		if($_FILES['new_file']['name']!='')
		{
			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
			$tmp_name = $_FILES['new_file']['tmp_name'];
			$name=time().'_'.$_FILES['new_file']['name'];
			$errorlist = array();
			$output = '';
			$error_code = "";
			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
				$filepath = $uploads_dir.'/'.$name;
				$objPHPExcel = PHPExcel_IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $keyval => $worksheet) {
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					$nrColumns = ord($highestColumn) - 64;
					if($highestRow > 100)
					{
						$height = 100;
					}
					else{
						$height = $highestRow;
					}

					$date_header = $worksheet->getCellByColumnAndRow(0, 1); $date_header = trim($date_header->getValue());
					$debit_header = $worksheet->getCellByColumnAndRow(1, 1); $debit_header = trim($debit_header->getValue());
					$credit_header = $worksheet->getCellByColumnAndRow(2, 1); $credit_header = trim($credit_header->getValue());
					$client_header = $worksheet->getCellByColumnAndRow(3, 1); $client_header = trim($client_header->getValue());
					$comment_header = $worksheet->getCellByColumnAndRow(5, 1); $comment_header = trim($comment_header->getValue());
					$amount_header = $worksheet->getCellByColumnAndRow(6, 1); $amount_header = trim($amount_header->getValue());
					if($date_header == "Date" && $debit_header == "Debit Nominal" && $credit_header == "Credit Nominal" && $client_header == "Client Code" && $comment_header == "Comment")
					{
						for ($row = 2; $row <= $height; $row++) {
							$date = $worksheet->getCellByColumnAndRow(0, $row); $date = trim($date->getValue());
							$debit = $worksheet->getCellByColumnAndRow(1, $row); $debit = trim($debit->getValue());
							$credit = $worksheet->getCellByColumnAndRow(2, $row); $credit = trim($credit->getValue());
							$client = $worksheet->getCellByColumnAndRow(3, $row); $client = trim($client->getValue());
							$credit_description = $worksheet->getCellByColumnAndRow(4, $row); $credit_description = trim($credit_description->getValue());
							$comment = $worksheet->getCellByColumnAndRow(5, $row); $comment = trim($comment->getValue());
							$amount = $worksheet->getCellByColumnAndRow(6, $row); $amount = trim($amount->getValue());

							$amount = str_replace(",","",$amount);
							$amount = str_replace(",","",$amount);
							$amount = str_replace(",","",$amount);
							$amount = str_replace(",","",$amount);
							$amount = str_replace(",","",$amount);
							$amount = str_replace(",","",$amount);
							$amount = str_replace(",","",$amount);
							$amount = str_replace(",","",$amount);

							$exp_date = explode('/',$date);
							if($date == "")
							{
								$dateval = '';
							}
							else{
								$dateval = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0];
							}
							$description = '';
							if($credit == "712" || $credit == "813A")
							{
								$client_details = DB::table('cm_clients')->where('client_id',$client)->first();
								if(count($client_details))
								{
									$description = $client_details->company.' - '.$client_details->client_id;
								}
							}
							else{
								$get_nominal_code_description = DB::table('nominal_codes')->where('code',$credit)->first();
								if(count($get_nominal_code_description))
								{
									$description = $get_nominal_code_description->description;
								}
							}

							$data['payment_date'] = $dateval;
							$data['debit_nominal'] = $debit;
							$data['credit_nominal'] = $credit;
							$data['client_code'] = $client;
							$data['credit_description'] = $credit_description;
							$data['comment'] = $comment;
							$data['amount'] = $amount;
							$data['imported'] = 1;

							$get_details = DB::table('payment_nominal_codes')->where('code',$debit)->first();
							$debit_nominal = '';
							if(count($get_details))
							{
								$debit_nominal = $get_details->code;
							}

							$get_details = DB::table('nominal_codes')->where('code',$credit)->first();
							$credit_nominal = '';
							if(count($get_details))
							{
								$credit_nominal = $get_details->code;
							}

							$get_details = DB::table('cm_clients')->where('client_id',$client)->first();
							$client_name = '';
							if(count($get_details))
							{
								$client_name = $get_details->client_id;
							}

							$i = 0;
							if($date == "")
					        {
					          $i = $i + 1;
					        }
					        if($debit_nominal == "")
					        {
					          $i = $i + 1;
					        }
					        if($credit_nominal == "")
					        {
					          $i = $i + 1;
					        }
					        if($description == "")
					        {
					          $i = $i + 1;
					        }
					        if($credit == "712" || $credit == "813A")
					        {
					          if($client_name == "")
					          {
					            $i = $i + 1;
					          }
					        }
					        if($amount == "")
					        {
					          $i = $i + 1;
					        }

							DB::table('payments')->insert($data);

							if($i > 0)
							{
								$data['status'] = 1;
								$font_color = 'color:#f00;font-weight:600';
								$error_code = "3";
								$error_cls = "error_tr";
							}
							else
							{
								$data['status'] = 0;
								$font_color = 'color:blue;font-weight:600';
								$error_code = "0";
								$error_cls = "";
							}

							$output.='<tr class="'.$error_cls.'">
								<td style="'.$font_color.'">'.$date.'</td>
								<td style="'.$font_color.'">'.$debit.'</td>
								<td style="'.$font_color.'">'.$credit.'</td>
								<td style="'.$font_color.'">'.$client.'</td>  
								<td style="'.$font_color.'">'.$credit_description.'</td>
								<td style="'.$font_color.'">'.$comment.'</td>  
								<td style="'.$font_color.'">'.number_format_invoice_empty($amount).'</td>
							</tr>';
						}
					}
					else{
						$error_code = "1";
					}
				}
				if($height >= $highestRow)
				{
					echo json_encode(array("error_code" => $error_code,"output" => $output, "import_type_new" => "0"));
				}
				else{
					echo json_encode(array("error_code" => $error_code,"output" => $output,"filename" => $name, "height" => $height, "round" => "2","highestrow" => $highestRow, "import_type_new" => "1"));
				}
			}
			else{
				$error_code = "2";
				echo json_encode(array("error_code" => $error_code, "output" =>$output, "import_type_new" => "0"));
			}
		}
	}
	public function check_import_csv_one_payment()
	{
		$name = Input::get('filename');
		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';
		$filepath = $uploads_dir.'/'.$name;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);
		$output = '';
		$error_code = "";
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			$round = Input::get('round');
			$last_height = Input::get('height');
			$offset = $round - 1;
			$offsetcount = $last_height + 1;
			$roundcount = $round * 100;
			$nextround = $round + 1;
			if($highestRow > $roundcount)
			{
				$height = $roundcount;
			}
			else{
				$height = $highestRow;
			}
			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$date = $worksheet->getCellByColumnAndRow(0, $row); $date = trim($date->getValue());
				$debit = $worksheet->getCellByColumnAndRow(1, $row); $debit = trim($debit->getValue());
				$credit = $worksheet->getCellByColumnAndRow(2, $row); $credit = trim($credit->getValue());
				$client = $worksheet->getCellByColumnAndRow(3, $row); $client = trim($client->getValue());
				$credit_description = $worksheet->getCellByColumnAndRow(4, $row); $credit_description = trim($credit_description->getValue());
				$comment = $worksheet->getCellByColumnAndRow(5, $row); $comment = trim($comment->getValue());
				$amount = $worksheet->getCellByColumnAndRow(6, $row); $amount = trim($amount->getValue());

				$amount = str_replace(",","",$amount);
				$amount = str_replace(",","",$amount);
				$amount = str_replace(",","",$amount);
				$amount = str_replace(",","",$amount);
				$amount = str_replace(",","",$amount);
				$amount = str_replace(",","",$amount);
				$amount = str_replace(",","",$amount);
				$amount = str_replace(",","",$amount);

				$exp_date = explode('/',$date);
				if($date == "")
				{
					$dateval = '';
				}
				else{
					$dateval = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0];
				}

				$description = '';
				if($credit == "712" || $credit == "813A")
				{
					$client_details = DB::table('cm_clients')->where('client_id',$client)->first();
					if(count($client_details))
					{
						$description = $client_details->company.' - '.$client_details->client_id;
					}
				}
				else{
					$get_nominal_code_description = DB::table('nominal_codes')->where('code',$credit)->first();
					if(count($get_nominal_code_description))
					{
						$description = $get_nominal_code_description->description;
					}
				}

				$data['payment_date'] = $dateval;
				$data['debit_nominal'] = $debit;
				$data['credit_nominal'] = $credit;
				$data['client_code'] = $client;
				$data['credit_description'] = $credit_description;
				$data['comment'] = $comment;
				$data['amount'] = $amount;
				$data['imported'] = 1;

				$get_details = DB::table('payment_nominal_codes')->where('code',$debit)->first();
				$debit_nominal = '';
				if(count($get_details))
				{
					$debit_nominal = $get_details->code;
				}

				$get_details = DB::table('nominal_codes')->where('code',$credit)->first();
				$credit_nominal = '';
				if(count($get_details))
				{
					$credit_nominal = $get_details->code;
				}

				$get_details = DB::table('cm_clients')->where('client_id',$client)->first();
				$client_name = '';
				if(count($get_details))
				{
					$client_name = $get_details->client_id;
				}

				$i = 0;
				if($date == "")
		        {
		          $i = $i + 1;
		        }
		        if($debit_nominal == "")
		        {
		          $i = $i + 1;
		        }
		        if($credit_nominal == "")
		        {
		          
		          $i = $i + 1;
		        }
		        if($description == "")
		        {
		          $i = $i + 1;
		        }
		        if($credit == "712" || $credit == "813A")
		        {
		          if($client_name == "")
		          {
		            $i = $i + 1;
		          }
		        }
		        if($amount == "")
		        {
		          $i = $i + 1;
		        }

				DB::table('payments')->insert($data);

				if($i > 0)
				{
					$data['status'] = 1;
					$font_color = 'color:#f00;font-weight:600';
					$error_code = "3";
					$error_cls = "error_tr";
				}
				else
				{
					$data['status'] = 0;
					$font_color = 'color:blue;font-weight:600';
					$error_code = "0";
					$error_cls = "";
				}

				$output.='<tr class="'.$error_cls.'">
					<td style="'.$font_color.'">'.$date.'</td>
					<td style="'.$font_color.'">'.$debit.'</td>
					<td style="'.$font_color.'">'.$credit.'</td>
					<td style="'.$font_color.'">'.$client.'</td>  
					<td style="'.$font_color.'">'.$credit_description.'</td>
					<td style="'.$font_color.'">'.$comment.'</td>  
					<td style="'.$font_color.'">'.number_format_invoice_empty($amount).'</td>
				</tr>';
			}
		}
		if($height >= $highestRow)
		{
			echo json_encode(array("error_code" => $error_code,"output" => $output, "import_type_new" => "0"));
		}
		else{
			echo json_encode(array("error_code" => $error_code,"output" => $output,"filename" => $name, "height" => $height, "round" => $nextround,"highestrow" => $highestRow, "import_type_new" => "1"));
		}
	}
}