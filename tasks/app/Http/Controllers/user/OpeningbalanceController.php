<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\CmClass;
use App\CmClients;
use App\CmFields;
use App\CmPaper;
use App\Week;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class OpeningbalanceController extends Controller {

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
	public function __construct(cmclass $cmclass, cmclients $cmclients, cmfields $cmfields, cmpaper $cmpaper, week $week)
	{
		$this->middleware('userauth');
		$this->cmclass = $cmclass;
		$this->cmclients = $cmclients;
		$this->cmfields = $cmfields;
		$this->cmpaper = $cmpaper;
		$this->week = $week;
		date_default_timezone_set("Europe/Dublin");
		require_once(app_path('Http/helpers.php'));
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function opening_balance_manager()
	{
		$client = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();
		
		$class = DB::table('cm_class')->where('status', 0)->get();	
		$user = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('firstname','asc')->get();
		return view('user/opening_balance/opening_balance_manager', array('title' => 'Opening Balance Manager', 'clientlist' => $client, 'classlist' => $class, 'userlist' => $user));
	}
	public function client_opening_balance_manager()
	{
		$client_id = Input::get('client_id');
		$client = DB::table('cm_clients')->where('client_id',$client_id)->first();
		$check_client = DB::table('opening_balance')->where('client_id',$client_id)->first();
		if(!count($check_client))
		{
			if($client->client_added != "")
			{
				$client_added_slash = explode('/',$client->client_added);
				$client_added_minus = explode('-',$client->client_added);
				if(count($client_added_slash) > 1)
				{
					$date_added = $client_added_slash[2].'-'.$client_added_slash[1].'-'.$client_added_slash[0];
				}
				elseif(count($client_added_minus) > 1)
				{
					$date_added = $client_added_minus[2].'-'.$client_added_minus[1].'-'.$client_added_minus[0];
				}
				else{
					$date_added = date('Y-m-d', strtotime($client->updatetime));
				}
			}
			else{
				$date_added = date('Y-m-d', strtotime($client->updatetime));
			}
			$data['client_id'] = $client_id;
			$data['opening_balance'] = 0;
			$data['opening_date'] = $date_added;
			DB::table('opening_balance')->insert($data);
		}
		
		return view('user/opening_balance/client_opening_balance_manager', array('title' => 'Client Opening Balance Manager', 'client_details' => $client, 'client_id' => $client_id));
	}
	public function import_opening_balance_manager()
	{
		return view('user/opening_balance/import_opening_balance_manager', array('title' => 'Import Opening Balance Manager'));
	}
	public function change_opening_balance()
	{
		$client_id = Input::get('client_id');
		$balance = Input::get('balance');

		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);

		$data['opening_balance'] = $balance;
		$check_client = DB::table('opening_balance')->where('client_id',$client_id)->first();
		if(count($check_client))
		{
			DB::table('opening_balance')->where('client_id',$client_id)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			DB::table('opening_balance')->insert($data);
		}
	}
	public function change_opening_balance_date()
	{
		$client_id = Input::get('client_id');
		$date = explode('-',Input::get('dateval'));
		if($date[1] == "Jan") { $month = '01'; }
		if($date[1] == "Feb") { $month = '02'; }
		if($date[1] == "Mar") { $month = '03'; }
		if($date[1] == "Apr") { $month = '04'; }
		if($date[1] == "May") { $month = '05'; }
		if($date[1] == "Jun") { $month = '06'; }
		if($date[1] == "Jul") { $month = '07'; }
		if($date[1] == "Aug") { $month = '08'; }
		if($date[1] == "Sep") { $month = '09'; }
		if($date[1] == "Oct") { $month = '10'; }
		if($date[1] == "Nov") { $month = '11'; }
		if($date[1] == "Dec") { $month = '12'; }

		$data['opening_date'] = $date[2].'-'.$month.'-'.$date[0];
		$check_client = DB::table('opening_balance')->where('client_id',$client_id)->first();
		if(count($check_client))
		{
			DB::table('opening_balance')->where('client_id',$client_id)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			DB::table('opening_balance')->insert($data);
		}
	}
	public function auto_allocate_opening_balance()
	{
		$client_id = Input::get('client_id');
		$opening_balance = Input::get('opening_balance');
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);
		$opening_balance = str_replace(",","",$opening_balance);

		$balance = Input::get('opening_balance');
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);
		$balance = str_replace(",","",$balance);

		$dataval['balance_remaining'] = '';
		DB::table('invoice_system')->where('client_id',$client_id)->update($dataval);

		$get_invoices_update = DB::table('invoice_system')->where('client_id',$client_id)->orderBy('invoice_date','desc')->get();
		if(count($get_invoices_update))
		{
			foreach($get_invoices_update as $invoice)
			{
				$gross = $invoice->gross;
				if($opening_balance != 0)
				{
					if($gross > 0)
					{
						if($gross < $opening_balance)
						{
							$data['balance_remaining'] = $gross;
							$opening_balance = $opening_balance - $gross;
						}
						else{
							$data['balance_remaining'] = $opening_balance;
							$opening_balance = $opening_balance - $opening_balance;
						}
						DB::table('invoice_system')->where('id',$invoice->id)->update($data);
					}
				}
			}
		}
		$output = '';
		$total_remaining = 0;
          $get_invoices = DB::table('invoice_system')->where('client_id',$client_id)->orderBy('invoice_date','desc')->get();
          if(count($get_invoices))
          {
            foreach($get_invoices as $invoice)
            {
              if($invoice->balance_remaining != "") { $balance_remaining = number_format_invoice($invoice->balance_remaining); } else { $balance_remaining = '-'; }
              $output.='<tr>
                <td>'.$invoice->invoice_number.'</td>
                <td>'.date("d-M-Y", strtotime($invoice->invoice_date)).'</td>
                <td style="text-align: right">'.number_format_invoice($invoice->gross).'</td>
                <td style="text-align: right">'.$balance_remaining.'</td>
              </tr>';
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);

              $total_remaining = $total_remaining + $balance_remaining;
            }
          }
          else{
            $output.='<tr><td colspan="4">No Invoice Found</td></tr>';
          }
          $unallocated = $balance - $total_remaining;
          $output.='<tr>
            <td colspan="3" style="font-weight:700">Total Balance Remaining</td>
            <td style="background: #ddd;text-align: right">'.number_format_invoice($total_remaining).'</td>
          </tr>
          <tr>
            <td colspan="3" style="font-weight:700">Unallocated Balance</td>
            <td style="background: #ddd;text-align: right">'.number_format_invoice($unallocated).'</td>
          </tr>';
          echo $output;
	}
	public function import_opening_balance()
	{
		$filename = $_FILES['balance_file']['name'];
		$tmp_name = $_FILES['balance_file']['tmp_name'];
		$import_type = Input::get('import_balance');

		$upload_dir = 'uploads/opening_balance';
		if(!is_dir($upload_dir))
		{
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.time();
		if(!is_dir($upload_dir))
		{
			mkdir($upload_dir);
		}

		if($import_type == "1")
		{
			$output = '';
			if(move_uploaded_file($tmp_name, $upload_dir.'/'.$filename)){
				$filepath = $upload_dir.'/'.$filename;
				$objPHPExcel = PHPExcel_IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					$nrColumns = ord($highestColumn) - 64;
					$height = $highestRow;

					$client_label = $worksheet->getCellByColumnAndRow(0, 1); $client_label = trim($client_label->getValue());
					$balance_label = $worksheet->getCellByColumnAndRow(1, 1); $balance_label = trim($balance_label->getValue());

					if($client_label != "Code" || $balance_label != "Balance")
					{
						echo json_encode(array("error" => "1", "message" => 'You have tried to upload a wrong csv file.', "upload_dir" => $upload_dir.'/'.$filename, "output" => ""));
						exit;
					}
					else{
						for ($row = 2; $row <= $height; ++ $row) {
							$client_id = $worksheet->getCellByColumnAndRow(0, $row); $client_id = trim($client_id->getValue());
							$balance = $worksheet->getCellByColumnAndRow(1, $row); $balance = trim($balance->getValue());

							$bal = str_replace(',',"",$balance);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);

							if(is_numeric($bal) == 1) { $bal_status = '<td style="color:green">Pass</td>'; } else { $bal_status = '<td class="error_import" style="color:#f00">Value Fail</td>'; }
							$check_client = DB::table('cm_clients')->where('client_id',$client_id)->first();
							if(count($check_client)) { $client_status = '<td style="color:green">Pass</td>'; } else { $client_status = '<td class="error_import" style="color:#f00">ID Fail</td>'; }

							$output.='<tr>
							<td>'.$client_id.'</td>
							<td>-</td>
							<td>'.$balance.'</td>
							'.$client_status.'
							'.$bal_status.'
							<td>N/A</td>
							</tr>';
						}
					}
				}
				echo json_encode(array("error" => "0", "message" => '', "upload_dir" => $upload_dir.'/'.$filename, "output" => $output));
				exit;
			}
			else{
				echo json_encode(array("error" => "1", "message" => 'File is not activated properly please try again once', "upload_dir" => $upload_dir.'/'.$filename, "output" => ''));
				exit;
			}
		}
		else
		{
			$output = '';
			if(move_uploaded_file($tmp_name, $upload_dir.'/'.$filename)){
				$filepath = $upload_dir.'/'.$filename;
				$objPHPExcel = PHPExcel_IOFactory::load($filepath);
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					$nrColumns = ord($highestColumn) - 64;
					$height = $highestRow;

					$inv_label = $worksheet->getCellByColumnAndRow(0, 1); $inv_label = trim($inv_label->getValue());
					$gross_label = $worksheet->getCellByColumnAndRow(1, 1); $gross_label = trim($gross_label->getValue());

					if($inv_label != "Inv No" || $gross_label != "Gross")
					{
						echo json_encode(array("error" => "1", "message" => 'You have tried to upload a wrong csv file.', "upload_dir" => $upload_dir.'/'.$filename, "output" => ""));
						exit;
					}
					else{
						for ($row = 2; $row <= $height; ++ $row) {
							$inv_no = $worksheet->getCellByColumnAndRow(0, $row); $inv_no = trim($inv_no->getValue());
							$gross = $worksheet->getCellByColumnAndRow(1, $row); $gross = trim($gross->getValue());

							$bal = str_replace(',',"",$gross);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);

							if(is_numeric($bal) == 1) { $bal_status = '<td style="color:green">Pass</td>'; } else { $bal_status = '<td class="error_import" style="color:#f00">Inv Val Fail</td>'; }
							$check_inv = DB::table('invoice_system')->where('invoice_number',$inv_no)->first();
							if(count($check_inv)) { $inv_status = '<td style="color:green">Pass</td>'; $client_id = $check_inv->client_id; } else { $inv_status = '<td class="error_import" style="color:#f00">Inv No Fail</td>'; $client_id= ''; }

							$output.='<tr>
							<td>'.$client_id.'</td>
							<td>'.$inv_no.'</td>
							<td>'.$gross.'</td>
							<td>N/A</td>
							'.$bal_status.'
							'.$inv_status.'
							</tr>';
						}
					}
				}
				echo json_encode(array("error" => "0", "message" => '', "upload_dir" => $upload_dir.'/'.$filename, "output" => $output));
				exit;
			}
			else{
				echo json_encode(array("error" => "1", "message" => 'File is not activated properly please try again once', "upload_dir" => $upload_dir.'/'.$filename, "output" => ''));
				exit;
			}
		}
	}
	public function import_opening_balance_to_clients()
	{
		$filepath = Input::get('filename');
		$bal_date = Input::get('bal_date');
		$import_type = Input::get('import_type');

		$date = explode('-',Input::get('bal_date'));
		if($date[1] == "Jan") { $month = '01'; }
		if($date[1] == "Feb") { $month = '02'; }
		if($date[1] == "Mar") { $month = '03'; }
		if($date[1] == "Apr") { $month = '04'; }
		if($date[1] == "May") { $month = '05'; }
		if($date[1] == "Jun") { $month = '06'; }
		if($date[1] == "Jul") { $month = '07'; }
		if($date[1] == "Aug") { $month = '08'; }
		if($date[1] == "Sep") { $month = '09'; }
		if($date[1] == "Oct") { $month = '10'; }
		if($date[1] == "Nov") { $month = '11'; }
		if($date[1] == "Dec") { $month = '12'; }

		$get_locked_client_ids = DB::table('opening_balance')->select('client_id')->where('locked',1)->get();
		$locked_clients = array();
		if(count($get_locked_client_ids))
		{
			foreach($get_locked_client_ids as $clientid)
			{
				array_push($locked_clients,$clientid->client_id);
			}
		}

		if($import_type == "1")
		{
			$output = '';
			$databal = array();
			$objPHPExcel = PHPExcel_IOFactory::load($filepath);
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$nrColumns = ord($highestColumn) - 64;
				$height = $highestRow;

				
				for ($row = 2; $row <= $height; ++ $row) {
					$client_id = $worksheet->getCellByColumnAndRow(0, $row); $client_id = trim($client_id->getValue());
					$balance = $worksheet->getCellByColumnAndRow(1, $row); $balance = trim($balance->getValue());

					if(!in_array($client_id, $locked_clients))
					{
						$bal = str_replace(',',"",$balance);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);
						$bal = str_replace(',',"",$bal);

						$check_client = DB::table('cm_clients')->where('client_id',$client_id)->first();
						if(count($check_client)) { 
							if(is_numeric($bal) == 1) {
								if(isset($databal[$client_id]))
								{
									$databal[$client_id] = number_format_invoice_without_comma($databal[$client_id] + $bal);
								}
								else{
									$databal[$client_id] = number_format_invoice_without_comma($bal);
								}
							}
						} 
					}
				}
			}
			if(count($databal))
			{
				foreach($databal as $key => $data)
				{
					$dataupdate['opening_balance'] = $data;
					$dataupdate['opening_date'] = $date[2].'-'.$month.'-'.$date[0];
					$dataupdate['client_id'] = $key;
					$check_client_bal = DB::table('opening_balance')->where('client_id',$key)->first();
					if(count($check_client_bal))
					{
						DB::table('opening_balance')->where('client_id',$key)->update($dataupdate);
					}
					else{
						DB::table('opening_balance')->insert($dataupdate);
					}
				}
			}
		}
		else
		{
			$output = '';
			$databal = array();
			$objPHPExcel = PHPExcel_IOFactory::load($filepath);
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$nrColumns = ord($highestColumn) - 64;
				$height = $highestRow;

				
				for ($row = 2; $row <= $height; ++ $row) {
					$inv_no = $worksheet->getCellByColumnAndRow(0, $row); $inv_no = trim($inv_no->getValue());
					$gross = $worksheet->getCellByColumnAndRow(1, $row); $gross = trim($gross->getValue());
					$check_inv = DB::table('invoice_system')->where('invoice_number',$inv_no)->first();
					$client_id = $check_inv->client_id;
					if($client_id != "")
					{
						if(!in_array($client_id, $locked_clients))
						{
							$bal = str_replace(',',"",$gross);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							$bal = str_replace(',',"",$bal);
							
							if(count($check_inv)) { 
								$client_id = $check_inv->client_id; 
								if(is_numeric($bal) == 1) {
									if(isset($databal[$client_id]))
									{
										$databal[$client_id] = number_format_invoice_without_comma($databal[$client_id] + $bal);
									}
									else{
										$databal[$client_id] = number_format_invoice_without_comma($bal);
									}
								}
							}
						}
					}
				}
			}	
			if(count($databal))
			{
				foreach($databal as $key => $data)
				{
					$dataupdate['opening_balance'] = $data;
					$dataupdate['opening_date'] = $date[2].'-'.$month.'-'.$date[0];
					$dataupdate['client_id'] = $key;
					$check_client_bal = DB::table('opening_balance')->where('client_id',$key)->first();
					if(count($check_client_bal))
					{
						DB::table('opening_balance')->where('client_id',$key)->update($dataupdate);
					}
					else{
						DB::table('opening_balance')->insert($dataupdate);
					}
				}
			}		
		}
	}
	public function lock_client_opening_balance()
	{
		$client_id = Input::get('client_id');
		$locked = Input::get('locked');

		$client = DB::table('cm_clients')->where('client_id',$client_id)->first();
		$check_client = DB::table('opening_balance')->where('client_id',$client_id)->first();
		if(!count($check_client))
		{
			if($client->client_added != "")
			{
				$client_added_slash = explode('/',$client->client_added);
				$client_added_minus = explode('-',$client->client_added);
				if(count($client_added_slash) > 1)
				{
					$date_added = $client_added_slash[2].'-'.$client_added_slash[1].'-'.$client_added_slash[0];
				}
				elseif(count($client_added_minus) > 1)
				{
					$date_added = $client_added_minus[2].'-'.$client_added_minus[1].'-'.$client_added_minus[0];
				}
				else{
					$date_added = date('Y-m-d', strtotime($client->updatetime));
				}
			}
			else{
				$date_added = date('Y-m-d', strtotime($client->updatetime));
			}
			$data['client_id'] = $client_id;
			$data['opening_balance'] = 0;
			$data['opening_date'] = $date_added;
			$data['locked'] = $locked;

			DB::table('opening_balance')->insert($data);
		}
		else{
			$data['locked'] = $locked;
			DB::table('opening_balance')->where('client_id',$client_id)->update($data);
		}
		return Redirect::back();
	}
}