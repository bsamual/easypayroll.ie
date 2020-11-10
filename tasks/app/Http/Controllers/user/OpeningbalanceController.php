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
			$data['opening_balance'] = "";
			$data['opening_date'] = $date_added;
			DB::table('opening_balance')->insert($data);
		}
		
		return view('user/opening_balance/client_opening_balance_manager', array('title' => 'Client Opening Balance Manager', 'client_details' => $client, 'client_id' => $client_id));
	}
	public function import_opening_balance_manager()
	{
		if(Session::has('databal'))
		{
			Session::forget('databal');
		}
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

		echo number_format_invoice($balance);
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

		$check_client = DB::table('opening_balance')->where('client_id',$client_id)->first();
        $get_invoices_update = DB::select('SELECT * from `invoice_system` WHERE `client_id` = "'.$client_id.'" AND `invoice_date` <= "'.$check_client->opening_date.'" ORDER BY `invoice_date` DESC');
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
							$opening_balance = number_format_invoice_without_comma(number_format_invoice_without_comma($opening_balance) - number_format_invoice_without_comma($gross));
						}
						else{
							$data['balance_remaining'] = $opening_balance;
							$opening_balance = number_format_invoice_without_comma(number_format_invoice_without_comma($opening_balance) - number_format_invoice_without_comma($opening_balance));
						}
						DB::table('invoice_system')->where('id',$invoice->id)->update($data);
					}
				}
			}
		}
		$output = '';
		$total_remaining = 0;
		$total_breakdown = 0;
          $get_invoices = DB::select('SELECT * from `invoice_system` WHERE `client_id` = "'.$client_id.'" AND `invoice_date` <= "'.$check_client->opening_date.'" ORDER BY `invoice_date` DESC');
          if(count($get_invoices))
          {
            foreach($get_invoices as $invoice)
            {
              if (strpos($invoice->gross, '-') !== false) { $breakdown = '-'; $balance_remaining = '-'; }
              else{
                if($invoice->balance_remaining != "") { 
                  $balance_remaining = number_format_invoice($invoice->balance_remaining); 
                  $breakdown = number_format_invoice(number_format_invoice_without_comma($invoice->gross) - number_format_invoice_without_comma($invoice->balance_remaining));
                } 
                else { 
                  $balance_remaining = '0.00'; 
                  $breakdown = number_format_invoice(number_format_invoice_without_comma($invoice->gross) - number_format_invoice_without_comma($invoice->balance_remaining));
                }
              }
              $output.='<tr>
                <td>'.$invoice->invoice_number.'</td>
                <td>'.date("d-M-Y", strtotime($invoice->invoice_date)).'</td>
                <td style="text-align: right">'.number_format_invoice($invoice->gross).'</td>
                <td style="text-align: right">'.($invoice->import_balance == "")?'-':number_format_invoice($invoice->import_balance).'</td>
                <td style="text-align: right">'.$balance_remaining.'</td>
                
              </tr>';
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);
              $balance_remaining = str_replace(",","",$balance_remaining);

              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              $breakdown = str_replace(",","",$breakdown);
              
              $total_remaining = $total_remaining + $balance_remaining;
              $total_breakdown = $total_breakdown + $breakdown;
            }
          }
          else{
            $output.='<tr><td colspan="5">No Invoice are available on or before the given opening balance date</td></tr>';
          }
          $unallocated = $balance - $total_remaining;
          $output.='<tr>
            <td colspan="4" style="font-weight:700">Total Balance Remaining</td>
            <td style="background: #ddd;text-align: right">'.number_format_invoice($total_remaining).'</td>
          </tr>
          <tr>
            <td colspan="4" style="font-weight:700">Unallocated Balance</td>
            <td style="background: #ddd;text-align: right">'.number_format_invoice($unallocated).'</td>
          </tr>';
          echo $output;
	}
	public function import_opening_balance()
	{
		$page = Input::get('page');
		if($page == "1")
		{
			$filename = $_FILES['balance_file']['name'];
			$tmp_name = $_FILES['balance_file']['tmp_name'];
			$import_type = Input::get('import_balance');
			$page = Input::get('page');

			$session_id = time();
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
			move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);

			$filepath = $upload_dir.'/'.$filename;
		}
		else{
			$filepath = Input::get('filename');
			$import_type = Input::get('import_type');
			$page = Input::get('page');
			$session_id = Input::get('session_id');
		}

		if($import_type == "1")
		{
			$output = '';
			$objPHPExcel = PHPExcel_IOFactory::load($filepath);
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$nrColumns = ord($highestColumn) - 64;
				
				$prevround = $page - 1;
				$last_height = $prevround * 100;
				$offsetcount = $last_height + 1;
				$roundcount = $page * 100;
				$nextpage = $page + 1;

				if($highestRow > $roundcount)
				{
					$height = $roundcount;
				}
				else{
					$height = $highestRow;
					$nextpage  = 0;
				}
				if($offsetcount == 1)
				{
					$offsetcount = 2;
				}

				$client_label = $worksheet->getCellByColumnAndRow(0, 1); $client_label = trim($client_label->getValue());
				$balance_label = $worksheet->getCellByColumnAndRow(1, 1); $balance_label = trim($balance_label->getValue());
				$date_label = $worksheet->getCellByColumnAndRow(2, 1); $date_label = trim($date_label->getValue());

				$client_label = strtolower($client_label);
				$balance_label = strtolower($balance_label);
				$date_label = strtolower($date_label);

				$client_label = str_replace(" ", "", $client_label);
				$client_label = str_replace(" ", "", $client_label);
				$client_label = str_replace(" ", "", $client_label);

				$balance_label = str_replace(" ", "", $balance_label);
				$balance_label = str_replace(" ", "", $balance_label);
				$balance_label = str_replace(" ", "", $balance_label);

				$date_label = str_replace(" ", "", $date_label);
				$date_label = str_replace(" ", "", $date_label);
				$date_label = str_replace(" ", "", $date_label);

				if($client_label != "code" || $balance_label != "balance" || $date_label != "date")
				{
					echo json_encode(array("error" => "1", "message" => 'You have tried to upload a wrong csv file.', "upload_dir" => $filepath, "output" => "",'page' => "0", 'session_id' => $session_id, 'import_type' =>$import_type,'highestRow' => $highestRow));
					exit;
				}
				else{
					for ($row = $offsetcount; $row <= $height; ++ $row) {
						$client_id = $worksheet->getCellByColumnAndRow(0, $row); $client_id = trim($client_id->getValue());
						$balance = $worksheet->getCellByColumnAndRow(1, $row); $balance = trim($balance->getValue());
						$import_date = $worksheet->getCellByColumnAndRow(2, $row); $import_date = trim($import_date->getValue());
						$import_date = trim($import_date);

						if($import_date == "")
						{
							$imp_date = '';
						}
						else{
							$explode_import_date = explode("/",$import_date);
							$explode_hyphen_import_date = explode("-",$import_date);

							if(count($explode_import_date) == 3)
							{
								$inc_date = $explode_import_date[2].'-'.$explode_import_date[1].'-'.$explode_import_date[0];
								$imp_date = date('Y-m-d',strtotime($inc_date));
							}
							elseif(count($explode_hyphen_import_date) == 3){
								$inc_date = $explode_hyphen_import_date[2].'-'.$explode_hyphen_import_date[1].'-'.$explode_hyphen_import_date[0];
								$imp_date = date('Y-m-d',strtotime($inc_date));
							}
							else{
								$unix_date = ($import_date - 25569) * 86400;
								$excel_date = 25569 + ($unix_date / 86400);
								$unix_date = ($excel_date - 25569) * 86400;
								$imp_date = gmdate("Y-m-d", $unix_date);
							}
						}
						

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

						$importdata['session_id'] = $session_id;
						$importdata['client_id'] = $client_id;
						$importdata['balance'] = $bal;
						$importdata['import_date'] = $imp_date;
						$importdata['import_type'] = $import_type;

						DB::table('opening_balance_import')->insert($importdata);

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
			echo json_encode(array("error" => "0", "message" => '', "upload_dir" => $filepath, "output" => $output,'page' => $nextpage, 'session_id' => $session_id, 'import_type' =>$import_type,'highestRow' => $highestRow));
			exit;
			
		}
		else
		{
			$output = '';
			$objPHPExcel = PHPExcel_IOFactory::load($filepath);
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$nrColumns = ord($highestColumn) - 64;
				
				$prevround = $page - 1;
				$last_height = $prevround * 100;
				$offsetcount = $last_height + 1;
				$roundcount = $page * 100;
				$nextpage = $page + 1;

				if($highestRow > $roundcount)
				{
					$height = $roundcount;
				}
				else{
					$height = $highestRow;
					$nextpage  = 0;
				}
				if($offsetcount == 1)
				{
					$offsetcount = 2;
				}

				$inv_label = $worksheet->getCellByColumnAndRow(0, 1); $inv_label = trim($inv_label->getValue());
				$gross_label = $worksheet->getCellByColumnAndRow(1, 1); $gross_label = trim($gross_label->getValue());
				$date_label = $worksheet->getCellByColumnAndRow(2, 1); $date_label = trim($date_label->getValue());

				$inv_label = strtolower($inv_label);
				$gross_label = strtolower($gross_label);
				$date_label = strtolower($date_label);

				$inv_label = str_replace(" ", "", $inv_label);
				$inv_label = str_replace(" ", "", $inv_label);
				$inv_label = str_replace(" ", "", $inv_label);

				$gross_label = str_replace(" ", "", $gross_label);
				$gross_label = str_replace(" ", "", $gross_label);
				$gross_label = str_replace(" ", "", $gross_label);

				$date_label = str_replace(" ", "", $date_label);
				$date_label = str_replace(" ", "", $date_label);
				$date_label = str_replace(" ", "", $date_label);

				if($inv_label != "invno" || $gross_label != "gross" || $date_label != "date")
				{
					echo json_encode(array("error" => "1", "message" => 'You have tried to upload a wrong csv file.', "upload_dir" => $filepath, "output" => "",'page' => "0", 'session_id' => $session_id, 'import_type' =>$import_type,'highestRow' => $highestRow));
					exit;
				}
				else{
					for ($row = $offsetcount; $row <= $height; ++ $row) {
						$inv_no = $worksheet->getCellByColumnAndRow(0, $row); $inv_no = trim($inv_no->getValue());
						$gross = $worksheet->getCellByColumnAndRow(1, $row); $gross = trim($gross->getValue());
						$import_date = $worksheet->getCellByColumnAndRow(2, $row); $import_date = trim($import_date->getValue());
						$import_date = trim($import_date);
						
						if($import_date == "")
						{
							$imp_date = '';
						}
						else{
							$explode_import_date = explode("/",$import_date);
							$explode_hyphen_import_date = explode("-",$import_date);

							if(count($explode_import_date) == 3)
							{
								$inc_date = $explode_import_date[2].'-'.$explode_import_date[1].'-'.$explode_import_date[0];
								$imp_date = date('Y-m-d',strtotime($inc_date));
							}
							elseif(count($explode_hyphen_import_date) == 3){
								$inc_date = $explode_hyphen_import_date[2].'-'.$explode_hyphen_import_date[1].'-'.$explode_hyphen_import_date[0];
								$imp_date = date('Y-m-d',strtotime($inc_date));
							}
							else{
								$unix_date = ($import_date - 25569) * 86400;
								$excel_date = 25569 + ($unix_date / 86400);
								$unix_date = ($excel_date - 25569) * 86400;
								$imp_date = gmdate("Y-m-d", $unix_date);
							}
						}

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

						$importdata['session_id'] = $session_id;
						$importdata['client_id'] = $client_id;
						$importdata['invoice_id'] = $inv_no;
						$importdata['balance'] = $bal;
						$importdata['import_date'] = $imp_date;
						$importdata['import_type'] = $import_type;

						DB::table('opening_balance_import')->insert($importdata);

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
			echo json_encode(array("error" => "0", "message" => '', "upload_dir" => $filepath, "output" => $output,'page' => $nextpage, 'session_id' => $session_id, 'import_type' =>$import_type,'highestRow' => $highestRow));
				exit;
			
		}
	}
	public function import_opening_balance_to_clients()
	{
		$filepath = Input::get('filename');
		$bal_date = Input::get('bal_date');
		$import_type = Input::get('import_type');
		$session_id = Input::get('session_id');
		$page = Input::get('page');
		$nextpage = $page + 1;

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
			$balance = DB::table('opening_balance_import')->whereNotIn('client_id',$locked_clients)->where('session_id',$session_id)->where('status',0)->first();
			if(count($balance))
			{
				$get_client_balance = DB::table('opening_balance_import')->where('client_id',$balance->client_id)->where('session_id',$session_id)->sum('balance');

				$dataupdate['opening_balance'] = $get_client_balance;
				$dataupdate['client_id'] = $balance->client_id;
				$dataupdate['opening_date'] = $date[2].'-'.$month.'-'.$date[0];

				$databal['status'] = 1;
				DB::table('opening_balance_import')->where('client_id',$balance->client_id)->where('session_id',$session_id)->update($databal);

				$check_client_bal = DB::table('opening_balance')->where('client_id',$balance->client_id)->first();
				if(count($check_client_bal))
				{
					DB::table('opening_balance')->where('client_id',$balance->client_id)->update($dataupdate);
				}
				else{
					DB::table('opening_balance')->insert($dataupdate);
				}
			}
			else{
				DB::table('opening_balance_import')->where('session_id',$session_id)->delete();
				echo json_encode(array("filename" => $filepath,"bal_date" => $bal_date, "import_type" => $import_type, "session_id" => $session_id, "page" => $nextpage, "status" => 'finish'));
				exit;
			}
		}
		else
		{
			$balance = DB::table('opening_balance_import')->whereNotIn('client_id',$locked_clients)->where('session_id',$session_id)->where('status',0)->first();
			if(count($balance))
			{
				$get_client_balance = DB::table('opening_balance_import')->where('client_id',$balance->client_id)->where('session_id',$session_id)->sum('balance');

				$dataupdate['opening_balance'] = number_format_invoice_without_comma($get_client_balance);
				$dataupdate['client_id'] = $balance->client_id;
				$dataupdate['opening_date'] = $date[2].'-'.$month.'-'.$date[0];

				$databal['status'] = 1;
				DB::table('opening_balance_import')->where('client_id',$balance->client_id)->where('session_id',$session_id)->update($databal);

				$check_client_bal = DB::table('opening_balance')->where('client_id',$balance->client_id)->first();
				if(count($check_client_bal))
				{
					DB::table('opening_balance')->where('client_id',$balance->client_id)->update($dataupdate);
				}
				else{
					DB::table('opening_balance')->insert($dataupdate);
				}
				$client_id = $balance->client_id;
				$opening_balance = $get_client_balance;
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);
				$opening_balance = str_replace(",","",$opening_balance);

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
			}
			else{
				$datainv_empty['import_balance'] = "";
				DB::table('invoice_system')->update($datainv_empty);

				$get_client_csv_balance = DB::table('opening_balance_import')->where('session_id',$session_id)->get();
				if(count($get_client_csv_balance))
				{
					foreach($get_client_csv_balance as $csv_bal)
					{
						$datainv['import_balance'] = $csv_bal->balance;
						DB::table('invoice_system')->where('invoice_number',$csv_bal->invoice_id)->update($datainv);
					}
				}

				DB::table('opening_balance_import')->where('session_id',$session_id)->delete();
				echo json_encode(array("filename" => $filepath,"bal_date" => $bal_date, "import_type" => $import_type, "session_id" => $session_id, "page" => $nextpage, "status" => 'finish'));
				exit;
			}
		}
		echo json_encode(array("filename" => $filepath,"bal_date" => $bal_date, "import_type" => $import_type, "session_id" => $session_id, "page" => $nextpage, "status" => 'start'));
		
	}
	public function clear_import_opening_balance()
	{
		$session_id = Input::get('session_id');
		DB::table('opening_balance_import')->where('session_id',$session_id)->delete();
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
			$data['opening_balance'] = "";
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
	public function get_client_counts_opening_balance()
	{
		$session_id = Input::get('session_id');

		$get_locked_client_ids = DB::table('opening_balance')->select('client_id')->where('locked',1)->get();
		$locked_clients = array();
		if(count($get_locked_client_ids))
		{
			foreach($get_locked_client_ids as $clientid)
			{
				array_push($locked_clients,$clientid->client_id);
			}
		}

		$balance = DB::table('opening_balance_import')->whereNotIn('client_id',$locked_clients)->where('session_id',$session_id)->groupBy('client_id')->get();
		echo count($balance);
	}
	public function set_global_opening_bal_date()
	{
		$date = explode('-',Input::get('global_date'));
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

		$dataupdate['opening_date'] = $date[2].'-'.$month.'-'.$date[0];

		DB::table('opening_balance')->where('locked',0)->update($dataupdate);
	}
}