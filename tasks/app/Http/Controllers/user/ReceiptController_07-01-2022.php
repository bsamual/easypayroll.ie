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
class ReceiptController extends Controller {
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
	public function receipt_management()
	{
		$receipts = DB::table('receipts')->get();
		foreach($receipts as $receipt)
		{
			$code = explode('-',$receipt->client_code);
			$client_id = trim(end($code));
			$dataa['client_code'] = $client_id;
			DB::table('receipts')->where('id',$receipt->id)->update($dataa);
		}
		$receipt_nominal_codes_arr = DB::table('receipt_nominal_codes')->get();
		$code_array = array();
		if(count($receipt_nominal_codes_arr))
		{
			foreach($receipt_nominal_codes_arr as $code)
			{
				array_push($code_array,$code->code);
			}
		}
		$nominal_codes = DB::table('nominal_codes')->whereNotin('code',$code_array)->orderBy('code','asc')->get();
		$receipt_nominal_codes = DB::table('receipt_nominal_codes')->orderBy('code','asc')->get();
		return view('user/receipts/receipt', array('title' => 'Receipt Management System','nominal_codes' => $nominal_codes,'receipt_nominal_codes' => $receipt_nominal_codes));
	}
	public function receipt_settings()
	{
		$receipt_nominal_codes_arr = DB::table('receipt_nominal_codes')->get();
		$code_array = array();
		if(count($receipt_nominal_codes_arr))
		{
			foreach($receipt_nominal_codes_arr as $code)
			{
				array_push($code_array,$code->code);
			}
		}
		$nominal_codes = DB::table('nominal_codes')->whereNotin('code',$code_array)->get();
		$receipt_nominal_codes = DB::table('receipt_nominal_codes')->get();
		return view('user/receipts/receipt_settings', array('title' => 'Receipt Management System','nominal_codes' => $nominal_codes,'receipt_nominal_codes' => $receipt_nominal_codes));
	}
	public function move_to_allowable_list()
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
				DB::table('receipt_nominal_codes')->insert($data);

				$output.='<tr>
					<td>'.$details->code.'</td>
					<td>'.$details->description.'</td>
				</tr>';
			}
		}
		echo $output;
	}
	public function get_nominal_code_description()
	{
		$code = Input::get('code');
		$get_details = DB::table('nominal_codes')->where('code',$code)->first();
		if(count($get_details))
		{
			echo $get_details->description;
		}
	}
	public function receipt_commonclient_search()
	{
		$value = Input::get('term');
		$details = DB::table('cm_clients')->Where('status', 0)->Where('client_id','like','%'.$value.'%')->orWhere('company','like','%'.$value.'%')->get();

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
	public function save_receipt_details()
	{
		$date = explode("/",Input::get('date'));
		$debit = Input::get('debit');
		$credit = Input::get('credit');
		$client_code = Input::get('client_code');
		$des = Input::get('des');
		$comment = Input::get('comment');
		$amount = Input::get('amount');

		$data['receipt_date'] = $date[2].'-'.$date[1].'-'.$date[0];
		$data['debit_nominal'] = $debit;
		$data['credit_nominal'] = $credit;
		$data['client_code'] = $client_code;
		$data['credit_description'] = $des;
		$data['comment'] = $comment;
		$data['amount'] = $amount;

		$id = DB::table('receipts')->insertGetid($data);
		echo $id;
	}
	public function update_receipt_details()
	{
		$date = explode("/",Input::get('date'));
		$debit = Input::get('debit');
		$credit = Input::get('credit');
		$client_code = Input::get('client_code');
		$des = Input::get('des');
		$amount = Input::get('amount');
		$comment = Input::get('comment');
		$receipt_id = Input::get('receipt_id');

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

		$data['receipt_date'] = $date[2].'-'.$date[1].'-'.$date[0];
		$data['debit_nominal'] = $debit;
		$data['credit_nominal'] = $credit;
		$data['client_code'] = $client_code;
		$data['credit_description'] = $des;
		$data['comment'] = $comment;
		$data['amount'] = $amount;

		DB::table('receipts')->where('id',$receipt_id)->update($data);
	}
	public function import_new_receipts()
	{
		$get_imported_receipts = DB::table('receipts')->where('imported',1)->get();
		if(count($get_imported_receipts))
		{
			foreach($get_imported_receipts as $receipt)
			{
				$daata['imported'] = 0;
				DB::table('receipts')->where('id',$receipt->id)->update($daata);
			}
		  	return redirect('user/receipt_management')->with('message_import', 'Receipt Imported successfully.');
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
		// 			return redirect('user/receipt_management')->with('message_import', 'Receipt Imported successfully.');
		// 		}
		// 		else{
		// 			return redirect('user/receipt_management?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type_new=1');
		// 		}
		// 	}
		// }
	}
	public function import_new_receipts_one()
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

				$data['receipt_date'] = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0];
				$data['debit_nominal'] = $debit;
				$data['credit_nominal'] = $credit;
				$data['client_code'] = $client;
				$data['credit_description'] = $description;
				$data['comment'] = $comment;
				$data['amount'] = $amount;
				$data['imported'] = 1;

				DB::table('receipts')->insert($data);
			}
		}
		if($height >= $highestRow)
		{
			return redirect('user/receipt_management')->with('message_import', 'Receipt Imported successfully.');
		}
		else{
			return redirect('user/receipt_management?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&import_type_new=1');
		}
	}
	public function change_receipt_status()
	{
		$receipt_id = Input::get('receipt_id');
		$data['status'] = 1;
		DB::table('receipts')->where('id',$receipt_id)->update($data);
	}
	public function load_receipt()
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
			$get_receipts = DB::table('receipts')->where('receipt_date','like',$current_year.'%')->where('imported',0)->get();
		}
		elseif($filter == "2")
		{
			$get_receipts = DB::table('receipts')->where('receipt_date','>=',$from)->where('receipt_date','<=',$to)->where('imported',0)->get();
		}
		elseif($filter == "3")
		{
			$get_receipts = DB::table('receipts')->where('client_code','like','%'.$client.'%')->where('imported',0)->get();
		}
		elseif($filter == "4")
		{
			$get_receipts = DB::table('receipts')->where('debit_nominal',$debit)->where('imported',0)->get();
		}
		elseif($filter == "5")
		{
			$get_receipts = DB::table('receipts')->where('credit_nominal',$credit)->where('imported',0)->get();
		}
		elseif($filter == "6")
		{
			$get_receipts = DB::table('receipts')->where('imported',0)->orderBy('id','desc')->limit(300)->get();
		}
		$output = '';
		if(count($get_receipts))
		{
			foreach($get_receipts as $receipt)
			{
				if($receipt->status == 1)
				{
					$font_color = 'color:#f00;font-weight:600';
				}
				elseif($receipt->hold_status == 0)
				{
					$font_color = 'color:blue;font-weight:600';
				}
				else{
					$font_color = '';
				}
				$get_details = DB::table('receipt_nominal_codes')->where('code',$receipt->debit_nominal)->first();
				$debit_nominal = '';
				if(count($get_details))
				{
					$debit_nominal = $get_details->code.' - '.$get_details->description;
				}
				if($receipt->hold_status == 0)
				{
					$hold_status = '<a href="javascript:" class="change_to_unhold" data-element="'.$receipt->id.'" data-nominal="'.$get_details->code.'">Outstanding</a>';
				}
				elseif($receipt->hold_status == 2)
				{
					$hold_status = '<a href="javascript:" class="change_to_unhold" data-element="'.$receipt->id.'" data-nominal="'.$get_details->code.'">Reconciled</a>';
				}
				else{
					$hold_status = '<a href="javascript:" class="unhold_receipt" data-element="'.$receipt->id.'">Cleared</a>';
				}
				$output.='<tr>
					<td style="'.$font_color.'"><spam class="date_sort_val" style="display:none">'.strtotime($receipt->receipt_date).'</spam>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
					<td class="debit_sort_val" style="'.$font_color.'">'.$debit_nominal.'</td>
					<td class="credit_sort_val" style="'.$font_color.'">'.$receipt->credit_nominal.'</td>
					<td class="client_sort_val" style="'.$font_color.'">'.$receipt->client_code.'</td>	
					<td class="des_sort_val" style="'.$font_color.'">'.$receipt->credit_description.'</td>
					<td class="comment_sort_val" style="'.$font_color.'">'.$receipt->comment.'</td>	
					<td style="'.$font_color.';text-align:right"><spam class="amount_sort_val" style="display:none">'.$receipt->amount.'</spam>'.number_format_invoice_empty($receipt->amount).'</td>	
					<td style="'.$font_color.';text-align:right"><a href="javascript:" class="journal_id_viewer" data-element="'.$receipt->journal_id.'">'.$receipt->journal_id.'</a></td>	
					<td>'.$hold_status.'</td>	
					<td>
						<a href="javascript:" class="fa fa-edit edit_receipt" title="Edit Receipt"></a>&nbsp;&nbsp;
						<a href="javascript:" class="fa fa-trash delete_receipt" title="Delete Receipt"></a>
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
	public function change_to_unhold()
	{
		$id = Input::get('id');
		$data['hold_status'] = 1;
		DB::table('receipts')->where('id',$id)->update($data);
	}
	public function add_receipt_export_csv()
	{
		$ids = Input::get('ids');
		$expids = explode(',',$ids);
		$columns = array('Date', 'Debit Nominal', 'Credit Nominal', 'Client Code', 'Credit Nominal Description', 'Comment', 'Amount','Journal Id','Status');
		$filename = time().'_Export_add_receipt_list.csv';
		$file = fopen('papers/'.$filename, 'w');
		fputcsv($file, $columns);
		if($ids != "")
		{
			foreach($expids as $id)
			{
				$get_details = DB::table('receipts')->where('id',$id)->first();
				$get_debit_details = DB::table('receipt_nominal_codes')->where('code',$get_details->debit_nominal)->first();
				$debit_nominal = '';
				if(count($get_debit_details))
				{
					$debit_nominal = $get_debit_details->code;
				}
				$columns_2 = array(date('d/m/Y', strtotime($get_details->receipt_date)),$debit_nominal,$get_details->credit_nominal,$get_details->client_code,$get_details->credit_description,$get_details->comment,number_format_invoice($get_details->amount),'','Outstanding');
				fputcsv($file, $columns_2);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function export_load_receipt()
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
			$get_receipts = DB::table('receipts')->where('receipt_date','like',$current_year.'%')->get();
		}
		elseif($filter == "2")
		{
			$get_receipts = DB::table('receipts')->where('receipt_date','>=',$from)->where('receipt_date','<=',$to)->get();
		}
		elseif($filter == "3")
		{
			$get_receipts = DB::table('receipts')->where('client_code','like','%'.$client.'%')->get();
		}
		elseif($filter == "4")
		{
			$get_receipts = DB::table('receipts')->where('debit_nominal',$debit)->get();
		}
		elseif($filter == "5")
		{
			$get_receipts = DB::table('receipts')->where('credit_nominal',$credit)->get();
		}
		elseif($filter == "6")
		{
			$get_receipts = DB::table('receipts')->orderBy('id','desc')->limit(300)->get();
		}

		$columns = array('Date', 'Debit Nominal', 'Credit Nominal', 'Client Code', 'Credit Nominal Description', 'Comment', 'Amount','Journal Id','Status');
		$filename = time().'_Export_receipt_list.csv';
		$file = fopen('papers/'.$filename, 'w');
		fputcsv($file, $columns);

		$output = '';
		if(count($get_receipts))
		{
			foreach($get_receipts as $receipt)
			{
				$get_details = DB::table('receipt_nominal_codes')->where('code',$receipt->debit_nominal)->first();
				$debit_nominal = '';
				if(count($get_details))
				{
					$debit_nominal = $get_details->code;
				}
				if($receipt->hold_status == 0)
				{
					$hold_status = 'Outstanding';
				}
				elseif($receipt->hold_status == 2)
				{
					$hold_status = 'Reconciled';
				}
				else{
					$hold_status = 'Cleared';
				}

				$columns_2 = array(date('d/m/Y', strtotime($receipt->receipt_date)),$debit_nominal,$receipt->credit_nominal,$receipt->client_code,$receipt->credit_description,$receipt->comment,number_format_invoice($receipt->amount),$receipt->journal_id,$hold_status);
				fputcsv($file, $columns_2);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function check_import_csv()
	{
		$get_imported_receipts = DB::table('receipts')->where('imported',1)->get();
		if(count($get_imported_receipts))
		{
		  DB::table('receipts')->where('imported',1)->delete();
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
					if($date_header == "Date" && $debit_header == "Debit Nominal" && $credit_header == "Credit Nominal" && $client_header == "Client Code" && $comment_header == "Comment" && $amount_header == "Amount")
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
							if($credit_description != "")
							{
								$description = $credit_description;
							}
							else{
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
							}

							$data['receipt_date'] = $dateval;
							$data['debit_nominal'] = $debit;
							$data['credit_nominal'] = $credit;
							if($credit == "712" || $credit == "813A")
					        {
								$data['client_code'] = $client;
							}else{
								$data['client_code'] = '';
							}
							$data['credit_description'] = $description;
							$data['comment'] = $comment;
							$data['amount'] = $amount;
							$data['imported'] = 1;

							$get_details = DB::table('receipt_nominal_codes')->where('code',$debit)->first();
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
					        else{
					        	if($client != ""){
					        		$i = $i + 1;
					        	}
					        }
					        if($amount == "")
					        {
					          $i = $i + 1;
					        }

							DB::table('receipts')->insert($data);

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
								$font_color = 'font-weight:600';
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
	public function check_import_csv_one()
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
				if($credit_description != "")
				{
					$description = $credit_description;
				}
				else{
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
				}

				$data['receipt_date'] = $dateval;
				$data['debit_nominal'] = $debit;
				$data['credit_nominal'] = $credit;
				if($credit == "712" || $credit == "813A")
		        {
					$data['client_code'] = $client;
				}else{
					$data['client_code'] = '';
				}
				$data['credit_description'] = $description;
				$data['comment'] = $comment;
				$data['amount'] = $amount;
				$data['imported'] = 1;

				$get_details = DB::table('receipt_nominal_codes')->where('code',$debit)->first();
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
		        else{
		        	if($client != ""){
		        		$i = $i + 1;
		        	}
		        }
		        if($amount == "")
		        {
		          $i = $i + 1;
		        }

				DB::table('receipts')->insert($data);

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
					$font_color = 'font-weight:600';
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