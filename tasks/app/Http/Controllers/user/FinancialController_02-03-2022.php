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
use ZipArchive;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class FinancialController extends Controller {

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
	public function financials()
	{
		return view('user/financials/financials', array('title' => 'Financials'));
	}
	public function check_nominal_code()
	{
		$nominal_code = Input::get('nominal_code_add');
		$check_nominal_code = DB::table('nominal_codes')->where('code',$nominal_code)->first();
		if(count($check_nominal_code))
		{
			$valid = false;
		}
		else{
			$valid = true;
		}
		echo json_encode($valid);
		exit;
	}
	public function add_nominal_code_financial()
	{
		$code = Input::get('code');
		$description = Input::get('description');
		$primary = Input::get('primary');
		$debit = Input::get('debit');
		$credit = Input::get('credit');

		$data['code'] = $code;
		$data['description'] = $description;
		$data['primary_group'] = $primary;

		if($primary == "Profit & Loss")
		{
			$data['debit_group'] = $debit;
			$data['credit_group'] = $debit;
		}
		else{
			$data['debit_group'] = $debit;
			$data['credit_group'] = $credit;
		}
		$data['type'] = 1;

		$already = DB::table('nominal_codes')->where('code',$code)->first();
		if(count($already))
		{
			DB::table('nominal_codes')->where('id',$already->id)->update($data);
			$table_type = 1;
		}
		else{
			DB::table('nominal_codes')->insert($data);
			$table_type = 0;
		}

		echo json_encode(array("code" => $code,"description" => $description, "primary" => $primary,"debit" => $debit,"credit" => $credit,"table_type" => $table_type));
	}
	public function add_bank_financial()
	{
		$bank_name = Input::get('bank_name');
		$account_name = Input::get('account_name');
		$account_no = Input::get('account_no');
		$description = Input::get('description');
		$code = Input::get('code');

		$data['bank_name'] = $bank_name;
		$data['account_name'] = $account_name;
		$data['account_number'] = $account_no;
		$data['description'] = $description;
		$data['nominal_code'] = $code;
		
		$id = DB::table('financial_banks')->insertGetid($data);

		$dataval['description'] = $description;
		DB::table('nominal_codes')->where('code',$code)->update($dataval);

		$bank_count = DB::table('financial_banks')->get();

		echo json_encode(array("id" => $id,"bank_name" => $bank_name, "account_name" => $account_name,"account_no" => $account_no,"description" => $description, "code" => $code,"bank_counts" => count($bank_count)));
	}
	public function update_bank_financial()
	{
		$bank_name = Input::get('bank_name');
		$account_name = Input::get('account_name');
		$account_no = Input::get('account_no');
		$description = Input::get('description');
		$id = Input::get('bank_id');

		$data['bank_name'] = $bank_name;
		$data['account_name'] = $account_name;
		$data['account_number'] = $account_no;
		$data['description'] = $description;
		
		DB::table('financial_banks')->where('id',$id)->update($data);

		$bank_code = DB::table('financial_banks')->where('id',$id)->first();
		
		$dataval['description'] = $description;
		DB::table('nominal_codes')->where('code',$bank_code->nominal_code)->update($dataval);

		$bank_count = DB::table('financial_banks')->get();

		echo json_encode(array("id" => $id,"bank_name" => $bank_name, "account_name" => $account_name,"account_no" => $account_no,"description" => $description, "code" => $bank_code->nominal_code));
	}
	
	public function edit_nominal_code_finance()
	{
		$code = Input::get('code');
		$get_codes = DB::table('nominal_codes')->where('code',$code)->first();
		if(count($get_codes))
		{
			$data['code'] = $get_codes->code;
			$data['description'] = $get_codes->description;
			$data['primary'] = $get_codes->primary_group;
			$data['debit'] = $get_codes->debit_group;
			$data['credit'] = $get_codes->credit_group;

			echo json_encode($data);
		}
	}
	public function get_nominal_codes_for_bank()
	{
		$get_nominal_codes = DB::table('nominal_codes')->where('primary_group','Balance Sheet')->where('debit_group','Current Assets')->orderBy('code','asc')->get();
		$option = '<option value="">Select Nominal Code</option>';
		if(count($get_nominal_codes))
		{
			foreach($get_nominal_codes as $code)
			{
				$check_code = DB::table('financial_banks')->where('nominal_code',$code->code)->first();
				if(!count($check_code))
				{
					$option.='<option value="'.$code->code.'">'.$code->code.'</option>';
				}
			}
		}
		echo $option;
	}
	public function financial_opening_balance_show()
	{
		$id = Input::get('id');
		$date = DB::table('user_login')->where('id',1)->first();
		$bank_details = DB::table('financial_banks')->where('id',$id)->first();
		$data['description'] = $bank_details->description;
		$data['bank_name'] = $bank_details->bank_name;
		$data['account_name'] = $bank_details->account_name;
		$data['account_no'] = $bank_details->account_number;

		$data['debit_balance'] = ($bank_details->debit_balance != "") ? number_format_invoice($bank_details->debit_balance) : "";
		$data['credit_balance'] = ($bank_details->credit_balance != "") ? number_format_invoice($bank_details->credit_balance) : "";
		$data['opening_balance_date'] = date('d-M-Y', strtotime($date->opening_balance_date));

		echo json_encode($data);
	}
	public function save_opening_balance_values()
	{
		$id = Input::get('id');
		$debit_balance = Input::get('debit_balance');
		$credit_balance = Input::get('credit_balance');

		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);
		$debit_balance = str_replace(",", "", $debit_balance);

		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);
		$credit_balance = str_replace(",", "", $credit_balance);

		$bank_details = DB::table('financial_banks')->where('id',$id)->first();
		if(count($bank_details))
		{
			$journal_id = $bank_details->journal_id;
			$code = $bank_details->nominal_code;

			if($journal_id != 0)
			{
				if($debit_balance != "" && $debit_balance != "0.00" && $debit_balance != "0")
				{
					$dataval['journal_source'] = 'BOB';

					$dataval['nominal_code'] = $code;
					$dataval['dr_value'] = $debit_balance;
					$dataval['cr_value'] = '0.00';
					DB::table('journals')->where('connecting_journal_reference',$journal_id)->update($dataval);

					$dataval['nominal_code'] = '991';
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $debit_balance;
					DB::table('journals')->where('connecting_journal_reference',$journal_id.'.01')->update($dataval);
				}
				else{
					$dataval['journal_source'] = 'BOB';

					$dataval['nominal_code'] = '991';
					$dataval['dr_value'] = $credit_balance;
					$dataval['cr_value'] = '0.00';
					DB::table('journals')->where('connecting_journal_reference',$journal_id)->update($dataval);

					$dataval['nominal_code'] = $code;
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $credit_balance;
					DB::table('journals')->where('connecting_journal_reference',$journal_id.'.01')->update($dataval);
				}

				$data['debit_balance'] = $debit_balance;
				$data['credit_balance'] = $credit_balance;
				DB::table('financial_banks')->where('id',$id)->update($data);
				echo $journal_id;
			}
			else{
				$count_total_journals = DB::table('journals')->groupBy('reference')->get();
				$next_connecting_journal = count($count_total_journals) + 1;
				$date = DB::table('user_login')->where('id',1)->first();

				$dataval['journal_date'] = $date->opening_balance_date;
				$dataval['description'] = 'Bank Account Opening Balance';
				$dataval['reference'] = 'OB'.$id;
				$dataval['journal_source'] = 'BOB';

				if($debit_balance != "" && $debit_balance != "0.00" && $debit_balance != "0")
				{
					$dataval['connecting_journal_reference'] = $next_connecting_journal;
					$dataval['nominal_code'] = $bank_details->nominal_code;
					$dataval['dr_value'] = $debit_balance;
					$dataval['cr_value'] = '0.00';
					DB::table('journals')->insert($dataval);

					$dataval['connecting_journal_reference'] = $next_connecting_journal.'.01';
					$dataval['nominal_code'] = '991';
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $debit_balance;
					DB::table('journals')->insert($dataval);
				}
				else{
					$dataval['connecting_journal_reference'] = $next_connecting_journal;
					$dataval['nominal_code'] = '991';
					$dataval['dr_value'] = $credit_balance;
					$dataval['cr_value'] = '0.00';
					DB::table('journals')->insert($dataval);

					$dataval['connecting_journal_reference'] = $next_connecting_journal.'.01';
					$dataval['nominal_code'] = $bank_details->nominal_code;
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $credit_balance;
					DB::table('journals')->insert($dataval);
				}

				$data['debit_balance'] = $debit_balance;
				$data['credit_balance'] = $credit_balance;
				$data['journal_id'] = $next_connecting_journal;
				DB::table('financial_banks')->where('id',$id)->update($data);

				echo $next_connecting_journal;
			}
		}
	}
	public function save_opening_balance_date()
	{
		$date = Input::get('opening_balance_date');
		$data['opening_balance_date'] = date('Y-m-d', strtotime($date));
		DB::table('user_login')->where('id',1)->update($data);
	}
	public function load_journals_financials()
	{
		$selection = Input::get('selection');
		$fromdate = Input::get('from');
		$todate = Input::get('to');

		if($selection == "4")
		{
			$explodefrom = explode('/',$fromdate);
			$explodeto = explode('/',$todate);
			$from = $explodefrom[2].'-'.$explodefrom[1].'-'.$explodefrom[0];
			$to = $explodeto[2].'-'.$explodeto[1].'-'.$explodeto[0];

			$from_date = strtotime(date('Y-m-d',strtotime($from)));
			$to_date = strtotime(date('Y-m-d',strtotime($to)));

			$journals = DB::select('SELECT * from `journals` WHERE UNIX_TIMESTAMP(`journal_date`) >= "'.$from_date.'" AND UNIX_TIMESTAMP(`journal_date`) <= "'.$to_date.'" ORDER BY `id` ASC');
		}
		elseif($selection == "1")
		{
			$current_year = date('Y');
			$journals = DB::table('journals')->where('journal_date','like',$current_year.'%')->orderBy('id','asc')->get();
		}
		elseif($selection == "2")
		{
			$current_year = date('Y') - 1;
			$journals = DB::table('journals')->where('journal_date','like',$current_year.'%')->orderBy('id','asc')->get();
		}
		elseif($selection == "3")
		{
			$current_month = date('Y-m');
			$journals = DB::table('journals')->where('journal_date','like',$current_month.'-%')->orderBy('id','asc')->get();
		}
		$output = '<table class="table own_table_white" id="journal_table">
		<thead>
			<th style="text-align:left">Journal <br>ID <i class="fa fa-sort journal_id_sort" style="float: right"></i></th>
			<th style="text-align:left">Journal <br>Date <i class="fa fa-sort journal_date_sort" style="float: right"></i></th>
			<th style="text-align:left">Journal <br>Description <i class="fa fa-sort journal_des_sort" style="float: right"></i></th>
			<th style="text-align:left">Nominal <br>Code <i class="fa fa-sort nominal_code_sort" style="float: right"></i></th>
			<th style="text-align:left">Nominal Code <br>Description <i class="fa fa-sort nominal_des_sort" style="float: right"></i></th>
			<th style="text-align:left">Journal <br>Source <i class="fa fa-sort source_sort" style="float: right"></i></th>
			<th style="text-align:left">Debit <br>Value <i class="fa fa-sort debit_journal_sort" style="float: right"></i></th>
			<th style="text-align:left">Credit <br>Value <i class="fa fa-sort credit_journal_sort" style="float: right"></i></th>
		</thead>
		<tbody id="load_journals_tbody">';
		if(count($journals))
		{
			foreach($journals as $journal)
			{
				$get_nominal = DB::table('nominal_codes')->where('code',$journal->nominal_code)->first();
				$output.='<tr>
					<td><a href="javascript:" class="journal_id_viewer journal_id_sortval" data-element="'.$journal->connecting_journal_reference.'">'.$journal->connecting_journal_reference.'</a></td>
					<td><spam class="journal_date_sortval" style="display:none">'.strtotime($journal->journal_date).'</spam>'.date('d-M-Y',strtotime($journal->journal_date)).'</td>
					<td class="journal_des_sortval">'.$journal->description.'</td>
					<td class="nominal_code_sortval">'.$journal->nominal_code.'</td>
					<td class="nominal_des_sortval">'.$get_nominal->description.'</td>
					<td class="source_sortval">'.$journal->journal_source.'</td>
					<td style="text-align:right"><spam class="debit_journal_sortval" style="display:none">'.$journal->dr_value.'</spam>'.number_format_invoice($journal->dr_value).'</td>
					<td style="text-align:right"><spam class="credit_journal_sortval" style="display:none">'.$journal->cr_value.'</spam>'.number_format_invoice($journal->cr_value).'</td>
				</tr>';
			}
		}
		else{
			$output.='<tr>
				<td colspan="8" style="text-align:center">No Journals Found</td>
			</tr>';
		}
		$output.='</tbody>
		</table>';

		echo $output;
	}
	public function save_debit_credit_finance_client()
	{
		$debit = Input::get('debit');
		$credit = Input::get('credit');
		$debit = str_replace(",", "", $debit);
		$debit = str_replace(",", "", $debit);
		$debit = str_replace(",", "", $debit);
		$debit = str_replace(",", "", $debit);
		$debit = str_replace(",", "", $debit);
		$debit = str_replace(",", "", $debit);

		$credit = str_replace(",", "", $credit);
		$credit = str_replace(",", "", $credit);
		$credit = str_replace(",", "", $credit);
		$credit = str_replace(",", "", $credit);
		$credit = str_replace(",", "", $credit);
		$credit = str_replace(",", "", $credit);


		$debit = number_format_invoice_without_comma($debit);
		$credit = number_format_invoice_without_comma($credit);
		$client_id = Input::get('client_id');

		$bal_status = '0';
		$commit_status = '0';
		$owed_text = '';

		$data['debit'] = $debit;
		$data['credit'] = $credit;
		if($debit != "0.00" && $credit != "0.00")
		{
			$data['balance'] = 'ERROR';
			$bal_status = 1;
		}
		else{
			$sum = number_format_invoice_without_comma($debit - $credit);
			$data['balance'] = $sum;
			if($sum != "0.00")
			{
				$commit_status = '1';
				if($sum >= 0) { $owed_text = '<spam style="color:green;font-size:12px;font-weight:600">Client Owes Back</spam>'; }
				else { $owed_text = '<spam style="color:#f00;font-size:12px;font-weight:600">Client Is Owed</spam>'; }
			}
		}
		$check_client = DB::table('finance_clients')->where('client_id',$client_id)->first();
		if(count($check_client))
		{
			DB::table('finance_clients')->where('id',$check_client->id)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			DB::table('finance_clients')->insert($data);
		}
		echo json_encode(array("bal_status" => $bal_status, 'commit_status' => $commit_status, 'owed_text' => $owed_text, "balance" => number_format_invoice($data['balance'])));
	}
	public function export_csv_client_opening()
	{
		$filename = 'client_account_opening_balance_manager.csv';

		$columns = array('Client Code','Surname','Firstname','Company Name','Debit', 'Credit','Balance','Details');
		$file = fopen('papers/client_account_opening_balance_manager.csv', 'w');
		fputcsv($file, $columns);

		$clients = DB::table('cm_clients')->get();
		if(count($clients))
		{
			foreach($clients as $client)
			{
				$finance_client = DB::table('finance_clients')->where('client_id',$client->client_id)->first();
				$debit = '0.00';
				$credit = '0.00';
				$balance = '0.00';
				$bal_style = '';
				$owed_text = '';
				$commit_style="display:none";
				if(count($finance_client))
				{
					$debit = ($finance_client->debit != "")?$finance_client->debit:"0.00";
					$credit = ($finance_client->credit != "")?$finance_client->credit:"0.00";
					if($debit != "" && $debit != "0.00" && $debit != "0" && $credit != "" && $credit != "0.00" && $credit != "0")
					{
						$balance = 'ERROR';
					}
					else{
						$balance = ($finance_client->balance != "")?number_format_invoice_without_comma($finance_client->balance):"0.00";
						if($balance != "0.00" && $balance != "" && $balance != "0")
						{
							if($finance_client->balance >= 0) { $owed_text = 'Client Owes Back'; }
							else { $owed_text = 'Client Is Owed'; }
						}
					}
				}

				$columns1 = array($client->client_id,$client->surname,$client->firstname,$client->company,number_format_invoice_without_comma($debit),number_format_invoice_without_comma($credit),$balance,$owed_text);
				fputcsv($file, $columns1);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function commit_client_account_opening_balance()
	{
		$client_id = Input::get('client_id');
		$finance_client = DB::table('finance_clients')->where('client_id',$client_id)->first();
		$get_sets = DB::table('journals')->groupBy('reference')->get();
		$next_ref_id = count($get_sets) + 1;
		if(count($finance_client))
		{
			$client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
			$opening_dete_details = DB::table('user_login')->where('id',Session::get('userid'))->first();

			if($finance_client->debit > 0)
			{
				$data['journal_date'] = $opening_dete_details->opening_balance_date;
				$data['description'] = 'Client Account Open Bal '.$client_id.' '.$client_details->company;
				$data['journal_source'] = 'CFA';
				$data['nominal_code'] = '813A';
				$data['dr_value'] = $finance_client->debit;
				$data['cr_value'] = '0.00';
				$data['connecting_journal_reference'] = $next_ref_id;
				$data['reference'] = 'CFA'.$finance_client->id;

				DB::table('journals')->insert($data);

				$data['journal_date'] = $opening_dete_details->opening_balance_date;
				$data['description'] = 'Client Account Open Bal '.$client_id.' '.$client_details->company;
				$data['journal_source'] = 'CFA';
				$data['nominal_code'] = '991';
				$data['dr_value'] = '0.00';
				$data['cr_value'] = $finance_client->debit;
				$data['connecting_journal_reference'] = $next_ref_id.'.01';
				$data['reference'] = 'CFA'.$finance_client->id;

				DB::table('journals')->insert($data);

				$dataval['journal_id'] = $next_ref_id;
				DB::table('finance_clients')->where('id',$finance_client->id)->update($dataval);
				echo $next_ref_id;
			}
			elseif($finance_client->credit > 0)
			{
				$data['journal_date'] = $opening_dete_details->opening_balance_date;
				$data['description'] = 'Client Account Open Bal '.$client_id.' '.$client_details->company;
				$data['journal_source'] = 'CFA';
				$data['nominal_code'] = '991';
				$data['dr_value'] = $finance_client->credit;
				$data['cr_value'] = '0.00';
				$data['connecting_journal_reference'] = $next_ref_id;
				$data['reference'] = 'CFA'.$finance_client->id;

				DB::table('journals')->insert($data);

				$data['journal_date'] = $opening_dete_details->opening_balance_date;
				$data['description'] = 'Client Account Open Bal '.$client_id.' '.$client_details->company;
				$data['journal_source'] = 'CFA';
				$data['nominal_code'] = '813A';
				$data['dr_value'] = '0.00';
				$data['cr_value'] = $finance_client->credit;
				$data['connecting_journal_reference'] = $next_ref_id.'.01';
				$data['reference'] = 'CFA'.$finance_client->id;

				DB::table('journals')->insert($data);

				$dataval['journal_id'] = $next_ref_id;
				DB::table('finance_clients')->where('id',$finance_client->id)->update($dataval);
				echo $next_ref_id;
			}
		}
	}
	public function edit_bank_account_finance()
	{
		$bank_id = Input::get('id');
		$banks = DB::table('financial_banks')->where('id',$bank_id)->first();
		if(count($banks))
		{
			$data['bank_name'] = $banks->bank_name;
			$data['account_name'] = $banks->account_name;
			$data['account_no'] = $banks->account_number;
			$data['description'] = $banks->description;
			echo json_encode($data);
		}
	}
	public function summary_clients_list()
	{
		$clients = DB::table('cm_clients')->get();
		$output = '';
		if(count($clients))
		{
			foreach($clients as $client)
			{
				$output.='<tr class="summary_tr summary_tr_'.$client->client_id.'">
				  <td class="client_summary_sort_val"><a href="javascript:" class="open_client_review" data-element="'.$client->client_id.'">'.$client->client_id.'</a></td>
				  <td class="surname_summary_sort_val"><a href="javascript:" class="open_client_review" data-element="'.$client->client_id.'">'.$client->surname.'</a></td>
				  <td class="firstname_summary_sort_val"><a href="javascript:" class="open_client_review" data-element="'.$client->client_id.'">'.$client->firstname.'</a></td>
				  <td class="company_summary_sort_val"><a href="javascript:" class="open_client_review" data-element="'.$client->client_id.'">'.$client->company.'</a></td>
				  <td class="opening_bal_summary_sort_val" style="text-align:right"></td>
				  <td class="receipt_summary_sort_val" style="text-align:right"></td>
				  <td class="payment_summary_sort_val" style="text-align:right"></td>
				  <td class="balance_summary_sort_val" style="text-align:right"></td>
				</tr>';
			}
		}
	    echo $output;
	}
	public function summary_load_opening_balance()
	{
		$clients = DB::table('cm_clients')->get();
		$output_array= array();
		$total = 0;
		if(count($clients))
		{
			foreach($clients as $client)
			{
				$finance_client = DB::table('finance_clients')->where('client_id',$client->client_id)->first();
				$opening_bal = '0.0';
				if(count($finance_client))
				{
					if($finance_client->debit != "" && $finance_client->debit != "0.00" && $finance_client->debit != "0.0" && $finance_client->debit != "0")
					{
						$opening_bal = $finance_client->debit;
					}
					if($finance_client->credit != "" && $finance_client->credit != "0.00" && $finance_client->credit != "0.0" && $finance_client->credit != "0")
					{
						$opening_bal = '-'.$finance_client->credit;
					}
				}
				$total = $total + $opening_bal;
				array_push($output_array, number_format_invoice($opening_bal));
			}
		}
		$data['output'] = $output_array;
		$data['total'] = number_format_invoice($total);
		echo json_encode($data);
	}
	public function summary_load_receipts()
	{
		$clients = DB::table('cm_clients')->get();
		$output_array= array();
		$total = 0;
		if(count($clients))
		{
			foreach($clients as $client)
			{
				$client_receipt = DB::table('receipts')->where('client_code',$client->client_id)->where('credit_nominal','813A')->where('imported',0)->sum('amount');
				$total = $total + ($client_receipt * -1);
				array_push($output_array, number_format_invoice($client_receipt * -1));
			}
		}
		$data['output'] = $output_array;
		$data['total'] = number_format_invoice($total);
		echo json_encode($data);
	}
	public function summary_load_payments()
	{
		$clients = DB::table('cm_clients')->get();
		$output_array= array();
		$total = 0;
		if(count($clients))
		{
			foreach($clients as $client)
			{
				$client_payment = DB::table('payments')->where('client_code',$client->client_id)->where('imported',0)->sum('amount');
				$total = $total + $client_payment;
				array_push($output_array, number_format_invoice($client_payment));
			}
		}
		$data['output'] = $output_array;
		$data['total'] = number_format_invoice($total);
		echo json_encode($data);
	}
	public function summary_calculations()
	{
		$clients = DB::table('cm_clients')->get();
		$output_array= array();
		$total = 0;
		if(count($clients))
		{
			foreach($clients as $client)
			{
				$finance_client = DB::table('finance_clients')->where('client_id',$client->client_id)->first();
				$opening_bal = '0.0';
				if(count($finance_client))
				{
					if($finance_client->debit != "" && $finance_client->debit != "0.00" && $finance_client->debit != "0.0" && $finance_client->debit != "0")
					{
						$opening_bal = $finance_client->debit;
					}
					if($finance_client->credit != "" && $finance_client->credit != "0.00" && $finance_client->credit != "0.0" && $finance_client->credit != "0")
					{
						$opening_bal = '-'.$finance_client->credit;
					}
				}
				$client_receipt = DB::table('receipts')->where('client_code',$client->client_id)->where('credit_nominal','813A')->where('imported',0)->sum('amount');
				$client_payment = DB::table('payments')->where('client_code',$client->client_id)->where('imported',0)->sum('amount');

				$sumval = $opening_bal + ($client_receipt * -1);

				$sumval = $sumval + $client_payment;
				$total = $total + $sumval;
				array_push($output_array, number_format_invoice($sumval));
			}
		}
		$data['output'] = $output_array;
		$data['total'] = number_format_invoice($total);
		echo json_encode($data);
	}
	public function summary_export_csv()
	{
		$clients = DB::table('cm_clients')->get();

		$filename = 'client_account_summary.csv';
		$columns = array('Client Code','Surname','Firstname','Company Name','Opening Balance', 'Client money Received','Payments Made','Balance');
		$file = fopen('papers/client_account_summary.csv', 'w');
		fputcsv($file, $columns);

		if(count($clients))
		{
			foreach($clients as $client)
			{
				$finance_client = DB::table('finance_clients')->where('client_id',$client->client_id)->first();
				$opening_bal = '0.0';
				if(count($finance_client))
				{
					if($finance_client->debit != "" && $finance_client->debit != "0.00" && $finance_client->debit != "0.0" && $finance_client->debit != "0")
					{
						$opening_bal = $finance_client->debit;
					}
					if($finance_client->credit != "" && $finance_client->credit != "0.00" && $finance_client->credit != "0.0" && $finance_client->credit != "0")
					{
						$opening_bal = '-'.$finance_client->credit;
					}
				}
				$client_receipt = DB::table('receipts')->where('client_code',$client->client_id)->where('credit_nominal','813A')->where('imported',0)->sum('amount');
				$client_payment = DB::table('payments')->where('client_code',$client->client_id)->where('imported',0)->sum('amount');

				$sumval = $opening_bal + ($client_receipt * -1);

				$sumval = $sumval + $client_payment;

				$columns1 = array($client->client_id,$client->surname,$client->firstname,$client->company,$opening_bal, ($client_receipt * -1),$client_payment,$sumval);
				fputcsv($file, $columns1);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function load_trial_balance_nominals()
	{
		$selection = Input::get('selection');
		$fromdate = Input::get('from');
		$todate = Input::get('to');

		if($selection == "4")
		{
			$explodefrom = explode('/',$fromdate);
			$from = $explodefrom[2].'-'.$explodefrom[1].'-'.$explodefrom[0];

			$explodeto = explode('/',$todate);
			$to = $explodeto[2].'-'.$explodeto[1].'-'.$explodeto[0];

			$from_date = date('Y-m-d',strtotime($from));
			$to_date = date('Y-m-d',strtotime($to));
		}
		elseif($selection == "1")
		{
			$current_year = date('Y');
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "2")
		{
			$current_year = date('Y') - 1;
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "3")
		{
			$current_month = date('Y-m');
			$from_date = $current_month.'-01';
			$to_date = date('Y-m-d', strtotime('last day of this month'));
		}

		$nominals = DB::table('nominal_codes');

		$nominal_codes = DB::table('nominal_codes')->get();
		$nom_codes = '';
		$total_nominal_debit = 0;
		$total_nominal_credit = 0;
		if(count($nominal_codes))
		{
			foreach($nominal_codes as $codes)
			{
				$debits_open = DB::select('SELECT SUM(`dr_value`) as debits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` < "'.$from_date.'"');
				$credits_open = DB::select('SELECT SUM(`cr_value`) as credits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` < "'.$from_date.'"');

				$opening_bal = number_format_invoice_without_comma($debits_open[0]->debits - $credits_open[0]->credits);

				$debits = DB::select('SELECT SUM(`dr_value`) as debits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` >= "'.$from_date.'"  AND `journal_date` <= "'.$to_date.'"');
				$credits = DB::select('SELECT SUM(`cr_value`) as credits from `journals` WHERE `nominal_code` = "'.$codes->code.'" AND `journal_date` >= "'.$from_date.'"  AND `journal_date` <= "'.$to_date.'"');

				$closing_bal = number_format_invoice_without_comma(($opening_bal + $debits[0]->debits) - $credits[0]->credits);

				if($closing_bal == 0 || $closing_bal == 0.00 || $closing_bal == '' || $closing_bal == '0.00' || $closing_bal == '0')
				{
					$nominal_debit_value = '0.00';
					$nominal_credit_value = '0.00';
					$nil_bal = 'nil_balance_tr';
					$opening = $opening_bal;
				}
				elseif($closing_bal > 0)
				{
					$nominal_debit_value = number_format_invoice_without_comma($closing_bal);
					$nominal_credit_value = '0.00';
					$nil_bal = '';
					$opening = $opening_bal;
				}
				else{
					$nominal_debit_value = '0.00';
					$nominal_credit_value = number_format_invoice_without_comma($closing_bal);
					$nil_bal = '';
					$opening = $opening_bal;
				}

				$total_nominal_debit = $total_nominal_debit + $nominal_debit_value;
				$total_nominal_credit = $total_nominal_credit + $nominal_credit_value;

				$des_code = $codes->description;
				$nom_codes.='<tr class="des_tr_'.$codes->code.' code_'.$codes->code.' '.$nil_bal.'">
					  <td><a href="javascript:" class="get_nominal_code_journals code_trial_sort_val" data-element="'.$codes->code.'" data-opening="'.number_format_invoice($opening).'">'.$codes->code.'</a></td>
					  <td><a href="javascript:" class="get_nominal_code_journals des_trial_sort_val" data-element="'.$codes->code.'" data-opening="'.number_format_invoice($opening).'">'.$des_code.'</a></td>
					  <td><a href="javascript:" class="get_nominal_code_journals primary_trial_sort_val" data-element="'.$codes->code.'" data-opening="'.number_format_invoice($opening).'">'.$codes->primary_group.'</a></td>
					  <td style="text-align:right"><a href="javascript:" class="get_nominal_code_journals" data-element="'.$codes->code.'" data-opening="'.number_format_invoice($opening).'">'.number_format_invoice($nominal_debit_value).'</a> <spam class="debit_trial_sort_val" style="display:none">'.$nominal_debit_value.'</spam></td> 
					  <td style="text-align:right"><a href="javascript:" class="get_nominal_code_journals" data-element="'.$codes->code.'" data-opening="'.number_format_invoice($opening).'">'.number_format_invoice($nominal_credit_value).'</a> <spam class="credit_trial_sort_val" style="display:none">'.$nominal_credit_value.'</spam></td>
					</tr>';
			}
		}
		$data['output'] = $nom_codes;
		$data['total_nominal_debit'] = number_format_invoice($total_nominal_debit);
		$data['total_nominal_credit'] = number_format_invoice($total_nominal_credit);
		echo json_encode($data);
	}
	public function load_trial_balance_journals_for_nominal()
	{
		$code = Input::get('code');
		$selection = Input::get('selection');
		$from = Input::get('from');
		$to = Input::get('to');
		$opening = Input::get('opening');
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);
		$opening = str_replace(',','',$opening);


		if($opening > 0){
			$debit_bal = $opening;
			$credit_bal = '0.00';
		} elseif($opening < 0){
			$debit_bal = '0.00';
			$credit_bal = $opening;
		} else{
			$debit_bal = '0.00';
			$credit_bal = '0.00';
		}

		if($selection == "4")
		{
			$explodefrom = explode('/',$from);
			$from = $explodefrom[2].'-'.$explodefrom[1].'-'.$explodefrom[0];

			$explodeto = explode('/',$to);
			$to = $explodeto[2].'-'.$explodeto[1].'-'.$explodeto[0];

			$from_date = date('Y-m-d',strtotime($from));
			$to_date = date('Y-m-d',strtotime($to));
		}
		elseif($selection == "1")
		{
			$current_year = date('Y');
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "2")
		{
			$current_year = date('Y') - 1;
			$from_date = $current_year.'-01-01';
			$to_date = $current_year.'-12-31';
		}
		elseif($selection == "3")
		{
			$current_month = date('Y-m');
			$from_date = $current_month.'-01';
			$to_date = date('Y-m-d', strtotime('last day of this month'));
		}

		$nominal_code_details = DB::table('nominal_codes')->where('code',$code)->first();

		$journals = DB::select('SELECT * from `journals` WHERE `nominal_code` = "'.$code.'" AND `journal_date` >= "'.$from_date.'" AND `journal_date` <= "'.$to_date.'"');

		$output = '
		<table class="table own_table_white" style="margin-top:20px">
			<thead>
				<th style="text-align:left">Journal <br/>ID</th>
				<th style="text-align:left">Journal <br/>Date</th>
				<th style="text-align:left">Journal <br/>Description</th>
				<th style="text-align:left">Nominal <br/>Code</th>
				<th style="text-align:left">Nominal Code <br/>Description</th>
				<th style="text-align:left">Journal <br/>Source</th>
				<th style="text-align:right">Debit <br/>Value</th>
				<th style="text-align:right">Credit <br/>Value</th>
			</thead>
			<tbody>
			<tr>
				<td></td>
				<td></td>
				<td>Opening Balance</td>
				<td>'.$code.'</td>
				<td>'.$nominal_code_details->description.'</td>
				<td></td>
				<td style="text-align:right">'.number_format_invoice($debit_bal).'</td>
				<td style="text-align:right">'.number_format_invoice($credit_bal).'</td>
			</tr>';
			$total_debit_value = $debit_bal;
			$total_credit_value = $credit_bal;
			if(count($journals))
			{
				foreach($journals as $journal)
				{
					$get_nominal = DB::table('nominal_codes')->where('code',$journal->nominal_code)->first();
					$output.='<tr>
						<td><a href="javascript:" class="journal_id_viewer" data-element="'.$journal->connecting_journal_reference.'">'.$journal->connecting_journal_reference.'</a></td>
						<td>'.date('d-M-Y',strtotime($journal->journal_date)).'</td>
						<td>'.$journal->description.'</td>
						<td>'.$journal->nominal_code.'</td>
						<td>'.$get_nominal->description.'</td>
						<td>'.$journal->journal_source.'</td>
						<td style="text-align:right">'.number_format_invoice($journal->dr_value).'</td>
						<td style="text-align:right">'.number_format_invoice($journal->cr_value).'</td>
					</tr>';

					$total_debit_value = $total_debit_value + $journal->dr_value;
					$total_credit_value = $total_credit_value + $journal->cr_value;
				}
			}
			$output.='</tbody>
			<tr>
				<td colspan="6">Total</td>
				<td style="text-align:right">'.number_format_invoice($total_debit_value).'</td>
				<td style="text-align:right">'.number_format_invoice($total_credit_value).'</td>
			</tr>
		</table>';

		echo $output;
	}

	public function finance_get_bank_details(){
		$id = base64_decode(Input::get('id'));
		$bank_details = DB::table('financial_banks')->where('id', $id)->first();

		$data['bank_name'] = $bank_details->bank_name;
		$data['account_name'] = $bank_details->account_name;
		$data['account_number'] = $bank_details->account_number;
		$data['description'] = $bank_details->description;
		$data['nominal_code'] = $bank_details->nominal_code;

		echo json_encode($data);		
	}

	public function finance_reconcile_load(){
		$id = base64_decode(Input::get('id'));
		$bank_details = DB::table('financial_banks')->where('id', $id)->first();
		/*$receipts_details = DB::table('receipts')->where('debit_nominal', $bank_details->nominal_code)->get();*/
		if(count($bank_details)){
			$date = DB::table('user_login')->where('id',1)->first();

			if($bank_details->debit_balance != "")
            {
              $baln = number_format_invoice($bank_details->debit_balance);
              $opening_balace_text = 'Opening Balance';
            }
            elseif($bank_details->credit_balance != "")
            {
              $baln = '-'.number_format_invoice($bank_details->credit_balance);
              $opening_balace_text = 'Opening Balance';
            }
            else{
              $baln = '0.00';
              $opening_balace_text = 'Opening Balance Not Set';
            }


			$output_opending='<tr>
				<td style="display:none">1</td>
				<td>'.date('d-M-Y', strtotime($date->opening_balance_date)).'</td>
				<td>'.$opening_balace_text.'</td>
				<td>Open Balance</td>
				<td align="right">'.$baln.'</td>
				<td></td>
				<td align="right"><a href="javascript:" class="journal_id_viewer" data-element="'.$bank_details->journal_id.'">'.$bank_details->journal_id.'</td>
				<td></td>
			</tr>';
		}
		$receipts_details = DB::table('receipts')->where('debit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('receipt_date', 'asc')->get();
		$receipt_ids='';
		$outstanding_receipt='';
		$receipt_total='';
		$i=2;
		$output_receipt=$output_opending;
		if(count($receipts_details)){
			foreach ($receipts_details as $receipts) {				

				if($receipts->clearance_date == '0000-00-00'){
					$clearance_date = '<a href="javascript:" style="width:100%; float:left; text-align:center" class="single_accept" type="1" data-element="'.$receipts->id.'">-</a>';					

					if($receipts->amount < 0){
						$color = 'red';
						$outstanding = $receipts->amount;
					}
					else{
						$color = 'green';
						$outstanding = $receipts->amount;
					}

					if($outstanding_receipt == ''){
						$outstanding_receipt = $receipts->amount;
					}
					else{
						$outstanding_receipt = $outstanding_receipt+$receipts->amount;
					}
					
				}				
				else{
					$clearance_date = date('d-M-Y', strtotime($receipts->clearance_date));
					$color = 'blue';
					$outstanding = 0;

				}

				$description = $receipts->credit_nominal.'-'.$receipts->credit_description;

				if($receipt_ids == ''){
					$receipt_ids = $receipts->id;
				}
				else{
					$receipt_ids = $receipt_ids.','.$receipts->id;
				}

				if($receipt_total == ''){
					$receipt_total = $receipts->amount;
				}
				else{
					$receipt_total = $receipt_total+$receipts->amount;
				}
				$process_journal ='';
				if(($receipts->journal_id == 0) && ($receipts->clearance_date != '0000-00-00')){
					$process_journal = 'process_journal';
				}

				$output_receipt.='<tr>
				<td style="display:none">'.$i.'</td>
				<td>'.date('d-M-Y', strtotime($receipts->receipt_date)).'</td>
				<td>'.$description.'</td>
				<td><a href="javascript:" class="receipt_viewer_class" data-element="'.$receipts->id.'">Receipts</a></td>
				<td align="right" id="receipt_amount_'.$receipts->id.'">'.number_format_invoice($receipts->amount).'</td>
				<td style="color:'.$color.'; text-align:right; font-weight:bold" id="receipt_out_'.$receipts->id.'">'.number_format_invoice($outstanding).'</td>
				<td class="journal_td">';
					if($receipts->journal_id != 0){
						$output_receipt.='<a href="javascript:" class="journal_id_viewer" data-element="'.$receipts->journal_id.'">'.$receipts->journal_id.'</a>';
					}
				$output_receipt.='</td>
				<td class="receipt_clear '.$process_journal.'" id="receipt_clear_'.$receipts->id.'" data-element="'.$receipts->id.'">'.$clearance_date.'</td>
			</tr>';
			$i++;
			}			
		}
		$payment_details = DB::table('payments')->where('credit_nominal', $bank_details->nominal_code)->where('imported',0)->orderBy('payment_date', 'asc')->get();
		$payment_ids='';
		$payment_total='';
		$j=$i;
		$outstanding_payment=$outstanding_receipt;
		$output_payment=$output_receipt;
		if(count($payment_details)){
			foreach ($payment_details as $payment) {

				if($payment->clearance_date == '0000-00-00'){
					$clearance_date = '<a href="javascript:" class="single_accept" type="2" data-element="'.$payment->payments_id.'" style="width:100%; float:left; text-align:center">-</a>';					

					if($payment->amount < 0){
						$color = 'red';
						$outstanding = $payment->amount;
					}
					else{
						$color = 'green';
						$outstanding = $payment->amount;
					}

					if($outstanding_payment == ''){
						$outstanding_payment = $payment->amount;
					}
					else{
						$outstanding_payment = $outstanding_payment+$payment->amount;
					}
				}				
				else{
					$clearance_date = date('d-M-Y', strtotime($payment->clearance_date));
					$color = 'blue';
					$outstanding = 0;

				}

				if($payment_ids == ''){
					$payment_ids = $payment->payments_id;
				}
				else{
					$payment_ids = $payment_ids.','.$payment->payments_id;
				}

				$description = $payment->debit_nominal.'-'.$payment->debit_description;

				if($payment_total == ''){
					$payment_total = $payment->amount;
				}
				else{
					$payment_total = $payment_total+$payment->amount;
				}
				$process_journal = '';
				if(($payment->journal_id == 0) && ($payment->clearance_date != '0000-00-00')){
					$process_journal = 'process_journal';
				}

				$output_payment.='<tr>
				<td style="display:none">'.$j.'</td>
				<td>'.date('d-M-Y', strtotime($payment->payment_date)).'</td>
				<td>'.$description.'</td>
				<td><a href="javascript:" class="payment_viewer_class" data-element="'.$payment->payments_id.'">Payments</a></td>
				<td align="right" id="payment_amount_'.$payment->payments_id.'">'.number_format_invoice($payment->amount).'</td>
				<td style="color:'.$color.'; text-align:right; font-weight:bold" id="payment_out_'.$payment->payments_id.'">'.number_format_invoice($outstanding).'</td>
				<td class="journal_td">';
					if($payment->journal_id != 0){
						$output_payment.='<a href="javascript:" class="journal_id_viewer" data-element="'.$payment->journal_id.'">'.$payment->journal_id.'</a>';
					}
				$output_payment.='</td>
				<td class="payment_clear '.$process_journal.'" id="payment_clear_'.$payment->payments_id.'" data-element="'.$payment->payments_id.'">'.$clearance_date.'</td>
			</tr>';
			$j++;
			}			
		}
		$outstanding = $outstanding_payment;
		$balance_transaction = ($bank_details->debit_balance+$receipt_total)-$payment_total;
		$output_reconcilation='
			<tr>
		        <td>Opening Balance</td>
		        <td class="op_reconcile" align="right" style="width: 150px;">'.number_format_invoice($bank_details->debit_balance).'</td>
		        <td></td>
		      </tr>
		      <tr>
		        <td>Total Value of Receipts</td>
		        <td class="tvr_reconcile" align="right">'.number_format_invoice($receipt_total).'</td>
		        <td></td>
		      </tr>
		      <tr>
		        <td>Total Value of Payments</td>
		        <td class="tvp_reconcile" align="right" style="border-bottom:1px solid #000;">'.number_format_invoice($payment_total).'</td>
		        <td></td>
		      </tr>
		      <tr>
		      	<td>Balance Per Processed Transactions</td>		      	
		      	<td class="bpp_reconcile" align="right">'.number_format_invoice($balance_transaction).'		      			      	
		      	</td>
		      	<td></td>
		      	
		      </tr>
		      
		      <tr>
		        <td>Balance Per Bank Statement</td>
		        <td align="right"><input type="number" placeholder="Enter Value" class="form-control input_balance_bank" style="width:120px;" /></td>
		        <td align="right"><input type="text" placeholder="Select Date" class="form-control date_balance_bank" style="width:120px;" /></td>
		      </tr>
		      
		      <tr>
		        <td>Total Value of Outstanding Items</td>
		        <td align="right"><span class="class_total_outstanding_refresh">'.number_format_invoice($outstanding_payment).'
		        	
		        </span>
		        <input type="hidden" class="refresh_input_outstanding" readonly value="'.$outstanding_payment.'">
		        
		        </td>
		        <td><a href="javascript:" class="common_black_button refresh_button fa fa-refresh" style="float: left; padding:5px 9px" title="Refresh"></a></td>
		      </tr>
		      
		      <tr>
		        <td>Reconciled Bank Statement Closing Balance</td>
		        <td align="right">
		        <span class="class_close_balance"></span>
		        <input type="hidden" value="" class="input_close_balance" readonly>
		        </td>
		        <td></td>
		      </tr>
		      
		      <tr>
		        <td>Difference</td>
		        <td align="right" style="border-top:1px solid #000;">
		        <span class="class_difference"></span>
		        <input type="hidden" class="input_difference" value="" readonly >
		        </td>
		        <td></td>
		      </tr>
		      <tr>
		        <td>&nbsp;</td>
		        <td></td>
		        <td></td>
		      </tr>
		      <tr>  
		      	<td></td>
		        <td colspan="2"><a href="javascript:" class="common_black_button accept_reconciliation">Accept Reconciliation</a></td>
		      </tr>';


		

		echo json_encode(array('transactions' => $output_payment, 'receipt_ids' => $receipt_ids, 'payment_ids' => $payment_ids, 'outstanding_payment' => $outstanding_payment, 'reconcilation' => $output_reconcilation, 'balance_transaction' => $balance_transaction, 'outstanding_html' => number_format_invoice($outstanding),  'outstanding' => $outstanding));		

	}

	public function finance_bank_single_accept(){
		$type = Input::get('type');
		$id = Input::get('id');
		$receipt_id = Input::get('receipt_id');
		$payment_id = Input::get('payment_id');



		if($type == 1){
			$receipts_details = DB::table('receipts')->where('id', $id)->first();
			$data['clearance_date'] = $receipts_details->receipt_date;
			$data['hold_status'] = '1';
			DB::table('receipts')->where('id', $id)->update($data);

			$clearance_date = $receipts_details->receipt_date;

			

		}
		else{
			$payment_details = DB::table('payments')->where('payments_id', $id)->first();
			$data['clearance_date'] = $payment_details->payment_date;
			$data['hold_status'] = '1';
			DB::table('payments')->where('payments_id', $id)->update($data);

			$clearance_date = $payment_details->payment_date;			
		}

		$outstanding = '0.00';

		$explode_receipt = explode(',', $receipt_id);
		$outstanding_receipt = DB::table('receipts')->whereIn('id', $explode_receipt)->where('clearance_date', '0000-00-00')->sum('amount');

		$explode_payment = explode(',', $payment_id);
		$outstanding_payment = DB::table('payments')->whereIn('payments_id', $explode_payment)->where('clearance_date', '0000-00-00')->sum('amount');

		$total_outstanding = $outstanding_receipt+$outstanding_payment;

		echo json_encode(array('outstanding' => $outstanding, 'clearance_date' => date('d-M-Y', strtotime($clearance_date)), 'total_outstanding_html' => number_format_invoice($total_outstanding), 'total_outstanding' => $total_outstanding));

	}

	public function balance_per_bank(){
		$input_balance_bank = Input::get('input_balance_bank');
		$input_total_outstanding = Input::get('input_total_outstanding');
		$input_bala_transaction = Input::get('input_bala_transaction');



		$close_balance = $input_balance_bank+$input_total_outstanding;

		$diffence = $input_bala_transaction-$close_balance;


		echo json_encode(array('close_balance' => $close_balance, 'close_balance_span' => number_format_invoice($close_balance), 'diffence' => $diffence, 'diffence_span' => number_format_invoice($diffence)));

	}

	public function finance_bank_refresh(){
		$input_balance_bank = Input::get('input_balance_bank');
		$input_total_outstanding = Input::get('input_total_outstanding');
		$input_bala_transaction = Input::get('input_bala_transaction');

		$close_balance = $input_balance_bank+$input_total_outstanding;

		$diffence = $input_bala_transaction-$close_balance;


		echo json_encode(array('close_balance' => $close_balance, 'close_balance_span' => number_format_invoice($close_balance), 'diffence' => $diffence, 'diffence_span' => number_format_invoice($diffence), 'outstanding' => $input_total_outstanding, 'outstanding_span' => number_format_invoice($input_total_outstanding)));
	}

	public function finance_bank_all_accept(){
		$receipt_id = Input::get('receipt_id');		
		$payment_id = Input::get('payment_id');
		$select_bank = base64_decode(Input::get('select_bank'));	

		$bank_details = DB::table('financial_banks')->where('id', $select_bank)->first();

		if(count($bank_details)){
			$date = DB::table('user_login')->where('id',1)->first();
			$output_opending='<tr>
				<td>'.date('d-M-Y', strtotime($date->opening_balance_date)).'</td>
				<td>Opening Balance</td>
				<td>Open Balance</td>
				<td align="right">'.number_format_invoice($bank_details->debit_balance).'</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>';
		}

		$explode_receipt = explode(',', $receipt_id);
		$receipts_details = DB::table('receipts')->whereIn('id', $explode_receipt)->orderBy('receipt_date', 'asc')->get();
		
		$output_receipt=$output_opending;

		if(count($receipts_details)){
			foreach ($receipts_details as $receipts){
				$color = 'blue';
				
				$outstanding = 0;

				$description = $receipts->credit_nominal.'-'.$receipts->credit_description;

				if($receipts->clearance_date == '0000-00-00'){
					$outstanding_color = 'orange';
				}
				else{
					$outstanding_color = '';
				}
				$process_journal = '';
				if(($receipts->journal_id == 0)) {
					$process_journal = 'process_journal';
				}

				$output_receipt.='
				<tr>
					<td>'.date('d-M-Y', strtotime($receipts->receipt_date)).'--'.$receipts->id.'</td>
					<td>'.$description.'</td>
					<td>Receipts</td>
					<td align="right" id="receipt_amount_'.$receipts->id.'">'.number_format_invoice($receipts->amount).'</td>
					<td style="color:'.$color.'; text-align:right; font-weight:bold" id="receipt_out_'.$receipts->id.'">'.number_format_invoice($outstanding).'</td>
					<td class="journal_td">';
						if($receipts->journal_id != 0){
							$output_receipt.='<a href="javascript:" class="journal_id_viewer" data-element="'.$receipts->journal_id.'">'.$receipts->journal_id.'</a>';
						}
					$output_receipt.='</td>
					<td class="receipt_clear '.$process_journal.'" id="receipt_clear_'.$receipts->id.'" data-element="'.$receipts->id.'" style="color:'.$outstanding_color.'">'.date('d-M-Y', strtotime($receipts->receipt_date)).'</td>
				</tr>';
				$data['clearance_date'] = $receipts->receipt_date;
				$data['hold_status'] = '1';
				DB::table('receipts')->where('id', $receipts->id)->update($data);
			}
		}
		else{
			$output_receipt=$output_opending;
		}

		$explode_payment = explode(',', $payment_id);

		$payment_details = DB::table('payments')->whereIn('payments_id', $explode_payment)->orderBy('payment_date', 'asc')->get();
		

		$output_payment=$output_receipt;
		if(count($payment_details)){
			foreach ($payment_details as $payment) {				
				$description = $payment->debit_nominal.'-'.$payment->debit_description;
				$color = 'blue';				
				$outstanding = 0;
				if($payment->clearance_date == '0000-00-00'){
					$outstanding_color = 'orange';
				}
				else{
					$outstanding_color = '';
				}
				$process_journal = '';	
				if(($payment->journal_id == 0)) {
					$process_journal = 'process_journal';
				}

				$output_payment.='<tr>
				<td>'.date('d-M-Y', strtotime($payment->payment_date)).'</td>
				<td>'.$description.'</td>
				<td>Payments</td>
				<td align="right" id="payment_amount_'.$payment->payments_id.'">'.number_format_invoice($payment->amount).'</td>
				<td style="color:'.$color.'; text-align:right; font-weight:bold" id="payment_out_'.$payment->payments_id.'">'.number_format_invoice($outstanding).'</td>
				<td class="journal_td">';
					if($payment->journal_id != 0){
						$output_payment.='<a href="javascript:" class="journal_id_viewer" data-element="'.$payment->journal_id.'">'.$payment->journal_id.'</a>';
					}
				$output_payment.='</td>
				<td class="payment_clear '.$process_journal.'" id="payment_clear_'.$payment->payments_id.'" data-element="'.$payment->payments_id.'" style="color:'.$outstanding_color.'">'.date('d-M-Y', strtotime($payment->payment_date)).'</td>
			</tr>';

			$data['clearance_date'] = $payment->payment_date;
			$data['hold_status'] = '1';
			DB::table('payments')->where('payments_id', $payment->payments_id)->update($data);
			}			
		}
		$total_outstanding = 0;
		echo json_encode(array('transactions' => $output_payment, 'total_outstanding' => number_format_invoice($total_outstanding)));
	}
	public function check_bank_nominal_code()
	{
		$code = Input::get('nominal_code');
		$nominal = DB::table('financial_banks')->where('nominal_code',$code)->first();

		if(count($nominal)) {
			echo $nominal->id;
		}
		else {
			echo 0;
		}
	}
	public function create_journal_reconciliation()
	{
		$id = Input::get('id');
		$type = Input::get('type');
		$bank_id = base64_decode(Input::get('bank_id'));
		$bank_details = DB::table('financial_banks')->where('id',$bank_id)->first();

		if($type == 1){
			$details = DB::table('receipts')->where('id',$id)->first();

			if($details->credit_nominal == "712") {
				$exp_des = explode('-',$details->credit_description);
				$journal_description = 'Client Payment from '.$details->client_code.' '.$exp_des[0];
			} elseif($details->credit_nominal == "813A") {
				$exp_des = explode('-',$details->credit_description);
				$journal_description = 'Client Money Holding Account from '.$details->client_code.' '.$exp_des[0];
			} else{
				$journal_description = 'Received From '.$details->credit_nominal.' '.$details->credit_description.' '.$details->comment;
			}

			$count_total_journals = DB::table('journals')->groupBy('reference')->get();
			$next_connecting_journal = count($count_total_journals) + 1;
			$journal_id_val = $next_connecting_journal;

			$dataval['journal_date'] = $details->receipt_date;
			$dataval['description'] = $journal_description;
			$dataval['reference'] = 'RCPT_'.$id;
			$dataval['journal_source'] = 'RCPT';

			$dataval['connecting_journal_reference'] = $next_connecting_journal;
			$dataval['nominal_code'] = $bank_details->nominal_code;
			$dataval['dr_value'] = '0.00';
			$dataval['cr_value'] = $details->amount;
			DB::table('journals')->insert($dataval);

			$next_connecting_journal = $next_connecting_journal.'.01';

			$dataval['journal_date'] = $details->receipt_date;
			$dataval['description'] = $journal_description;
			$dataval['reference'] = 'RCPT_'.$id;
			$dataval['journal_source'] = 'RCPT';
			$dataval['connecting_journal_reference'] = $next_connecting_journal;

			$dataval['nominal_code'] = $details->credit_nominal;
			$dataval['dr_value'] = $details->amount;
			$dataval['cr_value'] = '0.00';
			DB::table('journals')->insert($dataval);

			$datarep['status'] = 2;
			$datarep['journal_id'] = $journal_id_val;
			DB::table('receipts')->where('id',$id)->update($datarep);
		}
		else{
			$details = DB::table('payments')->where('payments_id',$id)->first();

			if($details->debit_nominal == "813") {
				$exp_des = explode('-',$details->debit_description);
				$supplier_details = DB::table('suppliers')->where('id',$details->supplier_code)->first();
				$journal_description = 'Supplier Payment To '.$supplier_details->supplier_code.' '.$exp_des[0];
			} elseif($details->debit_nominal == "813A") {
				$exp_des = explode('-',$details->debit_description);
				$journal_description = 'Client Money Holding Account to '.$details->client_code.' '.$exp_des[0];
			} else{
				$journal_description = 'Payment To '.$details->debit_nominal.' '.$details->debit_description;
			}

			$count_total_journals = DB::table('journals')->groupBy('reference')->get();
			$next_connecting_journal = count($count_total_journals) + 1;
			$journal_id_val = $next_connecting_journal;

			$dataval['journal_date'] = $details->payment_date;
			$dataval['description'] = $journal_description;
			$dataval['reference'] = 'PAY_'.$id;
			$dataval['journal_source'] = 'PAY';

			$dataval['connecting_journal_reference'] = $next_connecting_journal;
			$dataval['nominal_code'] = $details->debit_nominal;
			$dataval['dr_value'] = $details->amount;
			$dataval['cr_value'] = '0.00';
			DB::table('journals')->insert($dataval);

			$next_connecting_journal = $next_connecting_journal.'.01';

			$dataval['journal_date'] = $details->payment_date;
			$dataval['description'] = $journal_description;
			$dataval['reference'] = 'PAY_'.$id;
			$dataval['journal_source'] = 'PAY';
			$dataval['connecting_journal_reference'] = $next_connecting_journal;

			$dataval['nominal_code'] = $bank_details->nominal_code;
			$dataval['dr_value'] = '0.00';
			$dataval['cr_value'] = $details->amount;
			DB::table('journals')->insert($dataval);

			$datapay['status'] = 2;
			$datapay['journal_id'] = $journal_id_val;
			DB::table('payments')->where('payments_id',$id)->update($datapay);
		}

		echo $journal_id_val;
	}
	public function generate_reconcile_pdf() {
		$bank_id = Input::get('bank_id');
		$bank_details = DB::table('financial_banks')->where('id', $bank_id)->first();
		$receipt_ids = explode(',',Input::get('receipt_id'));
		$payment_ids = explode(',',Input::get('payment_id'));

		$receipts = DB::table('receipts')->whereIn('id',$receipt_ids)->where('clearance_date','0000-00-00')->where('imported',0)->orderBy('receipt_date', 'asc')->get();
		$payments = DB::table('payments')->whereIn('payments_id',$payment_ids)->where('clearance_date','0000-00-00')->where('imported',0)->orderBy('payment_date', 'asc')->get();

		$receipts_payment_html = '';
		$receipt_total = '';
		$payment_total = '';
		if(count($receipts)) {
			foreach($receipts as $receipt){
				$description = $receipt->credit_nominal.'-'.$receipt->credit_description;
				$outstanding = $receipt->amount;

				if($receipt_total == ''){
					$receipt_total = $receipt->amount;
				}
				else{
					$receipt_total = $receipt_total+$receipt->amount;
				}

				$receipts_payment_html.='<tr>
				<td>'.date('d-M-Y', strtotime($receipt->receipt_date)).'</td>
				<td>'.$description.'</td>
				<td>Receipts</td>
				<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
				<td style="text-align:right">'.number_format_invoice($outstanding).'</td>
				</tr>';
			}
		}
		if(count($payments)) {
			foreach($payments as $payment){
				$description = $payment->debit_nominal.'-'.$payment->debit_description;
				$outstanding = $payment->amount;

				if($payment_total == ''){
					$payment_total = $payment->amount;
				}
				else{
					$payment_total = $payment_total+$payment->amount;
				}

				$receipts_payment_html.='<tr>
				<td>'.date('d-M-Y', strtotime($payment->payment_date)).'</td>
				<td>'.$description.'</td>
				<td>Payments</td>
				<td style="text-align:right">'.number_format_invoice($payment->amount).'</td>
				<td style="text-align:right">'.number_format_invoice($outstanding).'</td>
				</tr>';
			}
		}

		if($receipts_payment_html == ''){
			$receipts_payment_html.='<tr>
				<td>No Outstanding Items Found</td>
			<tr>';
		}

		$balance_transaction = ($bank_details->debit_balance+$receipt_total)-$payment_total;
		$output_reconcilation='
		<style>
		.table_style1 {
		    width: 100%;
		    border-collapse:collapse;
		    border:1px solid #c5c5c5;
		    margin-bottom:20px;
		}
		.table_style1 tr th,.table_style1 tr td {
		    border:1px solid #c5c5c5;
		}
		.table_style2 {
		    width: 70%;
		    border-collapse:collapse;
		    margin-bottom:20px;
		}
		.table_style2 tr th,.table_style2 tr td {
		    border-bottom:1px solid #c5c5c5;
		    padding:10px;
		}
		.table_style3 {
		    width: 100%;
		    border-collapse:collapse;
		}
		.table_style3 tr th,.table_style3 tr td {
		    border-bottom:1px solid #c5c5c5;
		    padding:5px;
		}
		body{
			font-size:14px;
		}
		</style>
		<h3 style="text-align:center">'.$bank_details->description.'</h3>
		<table class="table_style1">
	        <thead>
	          <tr>
	            <th>Bank Name</th>
	            <th>Account Name</th>
	            <th>Account Number</th>
	            <th>Description</th>
	            <th>Nominal Code</th>
	          </tr>
	        </thead>
	        <tbody>
	          <tr>
	            <td>'.$bank_details->bank_name.'</td>
	            <td>'.$bank_details->account_name.'</td>
	            <td>'.$bank_details->account_number.'</td>
	            <td>'.$bank_details->description.'</td>
	            <td>'.$bank_details->nominal_code.'</td>
	          </tr>
	        </tbody>
	    </table>
	    <h3>RECONCILATION SECTION:</h3>
	    <table class="table_style2">
			<tr>
		        <td style="width:70%">Opening Balance</td>
		        <td style="width:20%" class="op_reconcile" align="right" style="width: 150px;">'.number_format_invoice($bank_details->debit_balance).'</td>
		        <td style="width:10%"></td>
		    </tr>
		    <tr>
		        <td>Total Value of Receipts</td>
		        <td class="tvr_reconcile" align="right">'.number_format_invoice($receipt_total).'</td>
		        <td></td>
		    </tr>
		    <tr>
		        <td>Total Value of Payments</td>
		        <td class="tvp_reconcile" align="right" style="border-bottom:2px solid #000;">'.number_format_invoice($payment_total).'</td>
		        <td></td>
			</tr>
			<tr>
		      	<td>Balance Per Processed Transactions</td>		      	
		      	<td class="bpp_reconcile" align="right">'.number_format_invoice($balance_transaction).'
		      	</td>
		      	<td></td>
			</tr>
			<tr>
		        <td>Balance Per Bank Statement</td>
		        <td align="right">'.number_format_invoice(Input::get('input')).'</td>
		        <td align="right">'.Input::get('date').'</td>
		    </tr>
		    <tr>
		        <td>Total Value of Outstanding Items</td>
		        <td align="right"><span class="class_total_outstanding_refresh">'.Input::get('tor').'
		        </span>
		        </td>
		        <td></td>
		    </tr>
		    <tr>
		        <td>Reconciled Bank Statement Closing Balance</td>
		        <td align="right">
		        <span class="class_close_balance">'.Input::get('cb').'</span>
		        </td>
		        <td></td>
		    </tr>
		    <tr>
		        <td>Difference</td>
		        <td align="right" style="border-top:2px solid #000;">
		        <span class="class_difference">'.Input::get('cd').'</span>
		        </td>
		        <td></td>
		    </tr>
		</table>
		<h3>Outstanding Transactions:</h3>
		<table class="table_style3">
			<tr>
				<th style="text-align:left">Date</th>
				<th style="text-align:left">Description</th>
				<th style="text-align:left">Source</th>
				<th style="text-align:right">Value</th>
				<th style="text-align:right">Outstanding</th>
			</tr>
			'.$receipts_payment_html.'
		</table>';

		$pdf = PDF::loadHTML($output_reconcilation);
		$pdf->setPaper('A4', 'portrait');

		$file = 'Reconciliaion Process for Bank Name - '.$bank_details->bank_name.'_'.time().'.pdf';
		$pdf->save('papers/'.$file.'');
		echo $file;
	}
	public function generate_reconcile_csv() {
		$bank_id = Input::get('bank_id');
		$bank_details = DB::table('financial_banks')->where('id', $bank_id)->first();
		$receipt_ids = explode(',',Input::get('receipt_id'));
		$payment_ids = explode(',',Input::get('payment_id'));


		$filename = 'Reconciliaion Process for Bank Name - '.$bank_details->bank_name.'_'.time().'.csv';
		$file = fopen('papers/'.$filename.'', 'w');

		$receipts = DB::table('receipts')->whereIn('id',$receipt_ids)->where('clearance_date','0000-00-00')->where('imported',0)->orderBy('receipt_date', 'asc')->get();
		$payments = DB::table('payments')->whereIn('payments_id',$payment_ids)->where('clearance_date','0000-00-00')->where('imported',0)->orderBy('payment_date', 'asc')->get();

		$receipts_payment_html = array();
		$receipt_total = '';
		$payment_total = '';
		if(count($receipts)) {
			foreach($receipts as $receipt){
				$description = $receipt->credit_nominal.'-'.$receipt->credit_description;
				$outstanding = $receipt->amount;

				if($receipt_total == ''){
					$receipt_total = $receipt->amount;
				}
				else{
					$receipt_total = $receipt_total+$receipt->amount;
				}

				$column_arr = array(date('d-M-Y', strtotime($receipt->receipt_date)),$description,'Receipts',number_format_invoice($receipt->amount),number_format_invoice($outstanding));

				array_push($receipts_payment_html,$column_arr);
			}
		}
		if(count($payments)) {
			foreach($payments as $payment){
				$description = $payment->debit_nominal.'-'.$payment->debit_description;
				$outstanding = $payment->amount;

				if($payment_total == ''){
					$payment_total = $payment->amount;
				}
				else{
					$payment_total = $payment_total+$payment->amount;
				}

				$column_arr = array(date('d-M-Y', strtotime($payment->payment_date)),$description,'Payments',number_format_invoice($payment->amount),number_format_invoice($outstanding));

				array_push($receipts_payment_html,$column_arr);
			}
		}

		if(!count($receipts_payment_html)){

			$column_arr = array('No Outstanding Items Found','','','','');
			array_push($receipts_payment_html,$column_arr);
		}

		$balance_transaction = ($bank_details->debit_balance+$receipt_total)-$payment_total;


		$columns = array('','',$bank_details->description,'','');
		fputcsv($file, $columns);

		$columns = array('','','','','');
		fputcsv($file, $columns);

		$columns = array('Bank Name','Account Name','Account Number','Description','Nominal Code');
		fputcsv($file, $columns);

		$columns = array($bank_details->bank_name,$bank_details->account_name,$bank_details->account_number,$bank_details->description,$bank_details->nominal_code);
		fputcsv($file, $columns);

		$columns = array('','','','','');
		fputcsv($file, $columns);

		$columns = array('RECONCILATION SECTION:','','','','');
		fputcsv($file, $columns);

		$columns = array('','','','','');
		fputcsv($file, $columns);

		$columns = array('Opening Balance','',number_format_invoice($bank_details->debit_balance),'','');
		fputcsv($file, $columns);

		$columns = array('Total Value of Receipts','',number_format_invoice($receipt_total),'','');
		fputcsv($file, $columns);

		$columns = array('Total Value of Payments','',number_format_invoice($payment_total),'','');
		fputcsv($file, $columns);

		$columns = array('Balance Per Processed Transactions','',number_format_invoice($balance_transaction),'','');
		fputcsv($file, $columns);

		$columns = array('Balance Per Bank Statement','',number_format_invoice(Input::get('input')),'',Input::get('date'));
		fputcsv($file, $columns);

		$columns = array('Total Value of Outstanding Items','',Input::get('tor'),'','');
		fputcsv($file, $columns);

		$columns = array('Reconciled Bank Statement Closing Balance','',Input::get('cb'),'','');
		fputcsv($file, $columns);

		$columns = array('Difference','',Input::get('cd'),'','');
		fputcsv($file, $columns);

		$columns = array('','','','','');
		fputcsv($file, $columns);

		$columns = array('Outstanding Transactions:','','','','');
		fputcsv($file, $columns);

		$columns = array('','','','','');
		fputcsv($file, $columns);

		$columns = array('Date','Description','Source','Value','Outstanding');
		fputcsv($file, $columns);

		if(count($receipts_payment_html))
		{
			foreach($receipts_payment_html as $receipt_payment)
			{
				fputcsv($file, $receipt_payment);
			}
		}

		fclose($file);
		echo $filename;
	}
	public function save_general_journals(){
		$nominal_date_exp = explode('/',Input::get('nominal_date'));

		$nominal_date = $nominal_date_exp[2].'-'.$nominal_date_exp[1].'-'.$nominal_date_exp[0];
		//$nominal_codes = Input::get('nominal_codes');
		// $journal_desription = unserialize(Input::get('journal_desription'));
		// $debitvalues = unserialize(Input::get('debitvalues'));
		// $creditvalues = unserialize(Input::get('creditvalues'));

		parse_str($_POST['nominal_codes'], $nominal_codes_serialize);
		parse_str($_POST['journal_desription'], $journal_desription_serialize);
		parse_str($_POST['debitvalues'], $debitvalues_serialize);
		parse_str($_POST['creditvalues'], $creditvalues_serialize);

		$nominal_codes = $nominal_codes_serialize['general_nominal'];
		$journal_desription = $journal_desription_serialize['general_journal_desription'];
		$debitvalues = $debitvalues_serialize['general_debit'];
		$creditvalues = $creditvalues_serialize['general_credit'];


		$count_total_journals = DB::table('journals')->groupBy('reference')->get();
		$next_connecting_journal = count($count_total_journals) + 1;

		$reference = 'General_Journal_'.time().'_'.$next_connecting_journal;

		$dataval['journal_date'] = $nominal_date;
		$dataval['reference'] = $reference;
		$dataval['journal_source'] = 'GF';

		if(count($nominal_codes))
		{
			foreach($nominal_codes as $key => $code){
				$dataval['connecting_journal_reference'] = $next_connecting_journal;
				$dataval['nominal_code'] = $code;
				$dataval['description'] = $journal_desription[$key];
				$dataval['dr_value'] = $debitvalues[$key];
				$dataval['cr_value'] = $creditvalues[$key];
				DB::table('journals')->insert($dataval);

				$next_connecting_journal = $next_connecting_journal + '.01';
			}
		}
	}

	public function finance_load_details_analysis(){
		$clientlist = DB::table('cm_clients')->get();	

		$output='';
		$date = DB::table('user_login')->where('id',1)->first();
		if(count($clientlist)){
			foreach ($clientlist as $client) {

				$finance_client = DB::table('finance_clients')->where('client_id','like',$client->client_id.'%')->first();
				$balance='';
				if(count($finance_client)){
					$balance = ($finance_client->balance != "")?number_format_invoice_without_comma($finance_client->balance):"0.00";
					$owed_text='Opening Balance';
					if($balance != "0.00" && $balance != "" && $balance != "0")
					{
						if($finance_client->balance >= 0){
							$finance_client->owed_text = 'Client Owes Back';
						}
						else{
							$owed_text = 'Client Is Owed';
						}
					}

					if($finance_client->balance > 0){
						$openening_debit = '€ '.number_format_invoice($finance_client->balance);
						$openening_credit='';							
					}
					elseif($finance_client->balance == '' || $finance_client->balance == 0){
						$openening_debit = '';
						$openening_credit = '€ '.number_format_invoice($finance_client->balance);
					}
					else{							
						$openening_debit = '';
						$openening_credit = '€ '.number_format_invoice($finance_client->balance * -1);
					}



					

					$client_opening = '<tr>
								<td>'.date('d-M-Y', strtotime($date->opening_balance_date)).'</td>
								<td>Opening Balance</td>
								<td>'.$owed_text.'</td>								
								<td align="right">'.$openening_debit.'</td>
								<td align="right">'.$openening_credit.'</td>
								<td align="right">€ '.number_format_invoice($finance_client->balance).'</td>
							</tr>';
				}
				else{
					$balance='';
					$client_opening = '';
				}

				$get_receipts = DB::select('SELECT *,UNIX_TIMESTAMP(`receipt_date`) as dateval from `receipts` WHERE `imported` = 0 AND `credit_nominal` = "813A" AND client_code = "'.$client->client_id.'"');
				$get_payments = DB::select('SELECT *,UNIX_TIMESTAMP(`payment_date`) as dateval from `payments` WHERE `imported` = 0 AND `debit_nominal` = "813A" AND client_code = "'.$client->client_id.'"');

				$get_receipt_payments=array_merge($get_receipts,$get_payments);

				$dateval = array();
				foreach ($get_receipt_payments as $key => $row)
				{
				    $dateval[$key] = $row->dateval;
				}
				array_multisort($dateval, SORT_ASC, $get_receipt_payments);
				$balance_val = $balance;

				if(count($finance_client) && !count($get_receipt_payments)) {
					if($finance_client->balance != "" && $finance_client->balance != 0 && $finance_client->balance != '0.00')
					{
						$client_details = DB::table('cm_clients')->where('client_id', $finance_client->client_id)->first();

						if($client_details->company == ''){
							$companyname = $client_details->surname.' '.$client_details->firstname;
						}
						else{
							$companyname = $client_details->company;
						} 

						$output.='<table class="table own_table_white">
						<thead><tr>
								<th>'.$client_details->client_id.'</th>
								<th colspan="2">'.$companyname.'</th>							
								<th></th>
								<th></th>
								<th></th>
							</tr>
							<tr>
								<th style="width:120px;">Date</th>
								<th style="width:230px;">Source</th>
								<th>Description</th>
								<th style="width:120px; text-align:right">Debit €</th>
								<th style="width:120px; text-align:right">Creedit €</th>
								<th style="width:120px; text-align:right">Balance</th>
							</tr>
							</thead><tbody>'.$client_opening.'</tbody></table>';
					}
				}


				
				if(count($get_receipt_payments))
				{
					$client_details = DB::table('cm_clients')->where('client_id', $get_receipt_payments[0]->client_code)->first();

					if($client_details->company == ''){
						$companyname = $client_details->surname.' '.$client_details->firstname;
					}
					else{
						$companyname = $client_details->company;
					}

					$output.='<table class="table own_table_white">
						<thead><tr>
								<th>'.$get_receipt_payments[0]->client_code.'</th>
								<th>'.$companyname.'</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
							<tr>
								<th style="width:120px;">Date</th>
								<th style="width:230px;">Source</th>
								<th>Description</th>
								<th style="width:120px; text-align:right">Debit €</th>
								<th style="width:120px; text-align:right">Creedit €</th>
								<th style="width:120px; text-align:right">Balance</th>
							</tr>
							</thead><tbody>'.$client_opening;
					foreach($get_receipt_payments as $list)
					{
												

						if(isset($list->payments_id)) { 
							$source = 'Payments';
							$amount_credit = '';
							$amount_debit = '€ '.number_format_invoice($list->amount);
							
							$textvalue = 'Payment Made Back to Client';
							$amount = number_format_invoice($list->amount);
							$amt = $list->amount;
							$balance_val = ($balance_val + ($list->amount));
							$class = 'payment_viewer_class';
							$id = $list->payments_id;



						}
						else { 
							$source = 'Receipts'; 
							$amount_credit = '€ '.number_format_invoice($list->amount);
							$amount_debit = '';

							if($list->amount != '0' && $list->amount != '0.00' && $list->amount != '')
							{								
								$amount = number_format_invoice($list->amount * -1);
								$amt = ($list->amount * -1);
								$textvalue = 'Client Money Received';
								$balance_val = $balance_val + ($list->amount * -1);
							}
							else{								
								$amount = number_format_invoice($list->amount);
								$amt = $list->amount;
								$textvalue = '';
								$balance_val = $balance_val + $list->amount;
							}
							$id = $list->id;

							$class = 'receipt_viewer_class';
						}						

						$output.='<tr>
							<td>'.date('d-M-Y', $list->dateval).'</td>
							<td><a href="javascript:" class="'.$class.'" data-element="'.$id.'">'.$source.'</a></td>
							<td>'.$textvalue.'</td>
							<td align="right">'.$amount_debit.'</td>
							<td align="right">'.$amount_credit.'</td>
							<td align="right">'.number_format_invoice($balance_val).'</td>
						</tr>';
					}
					$output.='</tbody></table>';
					
				}
				
			}

			
		}


		echo json_encode(array('output' => $output));

	}

	public function finance_analysis_report(){
		$type = Input::get('type');
		$format = Input::get('format');

		$clientlist = DB::table('cm_clients')->get();
		if($format == 3){
			// $font_family = "font-family: font-family: 'Roboto', sans-serif;";
			$font_family = '';
			if($type == 1){
				$output='<table class="table own_table_white" border="0px" cellpadding="0px" cellspacing="0px" style="font-family: Roboto, sans-serif; font-size: 13px; max-width: 600px; width: 100%;">
						<tr>
							<td style="width:100px;padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;background:#000; color:#fff;'.$font_family.'">Client Code</td>
							<td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;background:#000; color:#fff;'.$font_family.'">Surname</td>
							<td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left; background:#000; color:#fff;'.$font_family.'">Firstname</td>
							<td style="padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff; text-align:left;'.$font_family.'">Company Name</td>
							<td style="text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff; text-align:left;'.$font_family.'">Opening Balance</td>
							<td style="text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Client Money Received</td>
							<td style="text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Payments Made</td>
							<td style="text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Balance</td>
						</tr>';
						if(count($clientlist)){
							foreach ($clientlist as $client) {
								$finance_client = DB::table('finance_clients')->where('client_id',$client->client_id)->first();
								$opening_bal = '0.0';
								if(count($finance_client))
								{
									if($finance_client->debit != "" && $finance_client->debit != "0.00" && $finance_client->debit != "0.0" && $finance_client->debit != "0")
									{
										$opening_bal = $finance_client->debit;
									}
									if($finance_client->credit != "" && $finance_client->credit != "0.00" && $finance_client->credit != "0.0" && $finance_client->credit != "0")
									{
										$opening_bal = '-'.$finance_client->credit;
									}
								}
								$client_receipt = DB::table('receipts')->where('client_code',$client->client_id)->where('credit_nominal','813A')->where('imported',0)->sum('amount');
								$client_payment = DB::table('payments')->where('client_code',$client->client_id)->where('imported',0)->sum('amount');

								$sumval = $opening_bal + ($client_receipt * -1);

								$sumval = $sumval + $client_payment;

								$output.='<tr>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'">'.$client->client_id.'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'">'.$client->surname.'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'">'.$client->firstname.'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'">'.$client->company.'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:right;'.$font_family.'">'.number_format_invoice($opening_bal).'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:right;'.$font_family.'">'.number_format_invoice($client_receipt * -1).'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:right;'.$font_family.'">'.number_format_invoice($client_payment).'</td>
								  <td style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:right;'.$font_family.'">'.number_format_invoice($sumval).'</td>
								</tr>';
							}					
						}
					$output.='</tbody>
				</table>';
				$time=time();
				$pdf = PDF::loadHTML($output);
			    $pdf->setPaper('A4', 'landscape');
			    $pdf->save('papers/Client Finance Account_'.$time.'.pdf');

			    $filename_download = 'Client Finance Account_'.$time.'.pdf';
			    echo $filename_download;
			}
			else{
				$output='';
				$date = DB::table('user_login')->where('id',1)->first();
				if(count($clientlist)){
					foreach ($clientlist as $client) {

						$finance_client = DB::table('finance_clients')->where('client_id','like',$client->client_id.'%')->first();
						$balance='';
						if(count($finance_client)){
							$balance = ($finance_client->balance != "")?number_format_invoice_without_comma($finance_client->balance):"0.00";
							$owed_text='Opening Balance';
							if($balance != "0.00" && $balance != "" && $balance != "0")
							{
								if($finance_client->balance >= 0){
									$finance_client->owed_text = 'Client Owes Back';
								}
								else{
									$owed_text = 'Client Is Owed';
								}
							}

							if($finance_client->balance > 0){
								$openening_debit = '€ '.number_format_invoice($finance_client->balance);
								$openening_credit='';							
							}
							elseif($finance_client->balance == '' || $finance_client->balance == 0){
								$openening_debit = '';
								$openening_credit = '€ '.number_format_invoice($finance_client->balance);
							}
							else{							
								$openening_debit = '';
								$openening_credit = '€ '.number_format_invoice($finance_client->balance * -1);
							}



							

							$client_opening = '<tr>
										<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'">'.date('d-M-Y', strtotime($date->opening_balance_date)).'</td>
										<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'">Opening Balance</td>
										<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'">'.$owed_text.'</td>								
										<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'" align="right">'.$openening_debit.'</td>
										<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'" align="right">'.$openening_credit.'</td>
										<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'" align="right">€ '.number_format_invoice($finance_client->balance).'</td>
									</tr>';
						}
						else{
							$balance='';
							$client_opening = '';
						}

						$get_receipts = DB::select('SELECT *,UNIX_TIMESTAMP(`receipt_date`) as dateval from `receipts` WHERE `imported` = 0 AND `credit_nominal` = "813A" AND client_code = "'.$client->client_id.'"');
						$get_payments = DB::select('SELECT *,UNIX_TIMESTAMP(`payment_date`) as dateval from `payments` WHERE `imported` = 0 AND `debit_nominal` = "813A" AND client_code = "'.$client->client_id.'"');

						$get_receipt_payments=array_merge($get_receipts,$get_payments);

						$dateval = array();
						foreach ($get_receipt_payments as $key => $row)
						{
						    $dateval[$key] = $row->dateval;
						}
						array_multisort($dateval, SORT_ASC, $get_receipt_payments);
						$balance_val = $balance;

						if(count($finance_client) && !count($get_receipt_payments)) {
							if($finance_client->balance != "" && $finance_client->balance != 0 && $finance_client->balance != '0.00')
							{

								$client_details = DB::table('cm_clients')->where('client_id', $finance_client->client_id)->first();

								if($client_details->company == ''){
									$companyname = $client_details->surname.' '.$client_details->firstname;
								}
								else{
									$companyname = $client_details->company;
								}

								$output.='<table class="table own_table_white" border="0px" cellpadding="0px" cellspacing="0px" style="font-family: Roboto, sans-serif; font-size: 13px; max-width: 600px; line-height: 20px; width: 100%; margin: 0px auto;page-break-after: always; ">
								<thead><tr>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'">'.$client_details->client_id.'</th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'" colspan="2">'.$companyname.'</th>							
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'"></th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'"></th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'"></th>
									</tr>
									<tr>
										<th style="width:120px; padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left; background:#000; color:#fff;'.$font_family.'">Date</th>
										<th style="width:230px; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff; text-align:left;'.$font_family.'">Source</th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff; text-align:left;'.$font_family.'">Description</th>
										<th style="width:120px; text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Debit €</th>
										<th style="width:120px; text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Creedit €</th>
										<th style="width:120px; text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Balance</th>
									</tr>
									</thead><tbody>'.$client_opening.'</tbody></table>';
							}
						}


						
						if(count($get_receipt_payments))
						{
							$client_details = DB::table('cm_clients')->where('client_id', $get_receipt_payments[0]->client_code)->first();

							if($client_details->company == ''){
								$companyname = $client_details->surname.' '.$client_details->firstname;
							}
							else{
								$companyname = $client_details->company;
							}

							$output.='<table class="table own_table_white" border="0px" cellpadding="0px" cellspacing="0px" style="font-family: Roboto, sans-serif; font-size: 13px; max-width: 600px; line-height: 20px; width: 100%; margin: 0px auto; page-break-after: always;">
								<thead><tr>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left;'.$font_family.'">'.$get_receipt_payments[0]->client_code.'</th>
										<th colspan="2" style="padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:left'.$font_family.'">'.$companyname.'</th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'"></th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'"></th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'"></th>
										
									</tr>
									<tr>
										<th style="width:120px; padding: 8px; border-bottom: 1px solid #c3c3c3; text-align:right; background:#000; color:#fff; text-align:left;'.$font_family.'">Date</th>
										<th style="width:230px; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff; text-align:left;'.$font_family.'">Source</th>
										<th style="padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff; text-align:left;'.$font_family.'">Description</th>
										<th style="width:120px; text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Debit €</th>
										<th style="width:120px; text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Creedit €</th>
										<th style="width:120px; text-align:right; padding: 8px; border-bottom: 1px solid #c3c3c3; background:#000; color:#fff;'.$font_family.'">Balance</th>
									</tr>
									</thead><tbody>'.$client_opening;
							foreach($get_receipt_payments as $list)
							{
														

								if(isset($list->payments_id)) { 
									$source = 'Payments';
									$amount_credit = '';
									$amount_debit = '€ '.number_format_invoice($list->amount);
									
									$textvalue = 'Payment Made Back to Client';
									$amount = number_format_invoice($list->amount);
									$amt = $list->amount;
									$balance_val = ($balance_val + ($list->amount));
									$class = 'payment_viewer_class';
									$id = $list->payments_id;



								}
								else { 
									$source = 'Receipts'; 
									$amount_credit = '€ '.number_format_invoice($list->amount);
									$amount_debit = '';

									if($list->amount != '0' && $list->amount != '0.00' && $list->amount != '')
									{								
										$amount = number_format_invoice($list->amount * -1);
										$amt = ($list->amount * -1);
										$textvalue = 'Client Money Received';
										$balance_val = $balance_val + ($list->amount * -1);
									}
									else{								
										$amount = number_format_invoice($list->amount);
										$amt = $list->amount;
										$textvalue = '';
										$balance_val = $balance_val + $list->amount;
									}
									$id = $list->id;

									$class = 'receipt_viewer_class';
								}						

								$output.='<tr>
									<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'">'.date('d-M-Y', $list->dateval).'</td>
									<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'">'.$source.'</td>
									<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'">'.$textvalue.'</td>
									<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'" align="right">'.$amount_debit.'</td>
									<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'" align="right">'.$amount_credit.'</td>
									<td style="padding: 8px; border-bottom: 1px solid #c3c3c3;'.$font_family.'" align="right">'.number_format_invoice($balance_val).'</td>
								</tr>';
							}
							$output.='</tbody></table>';
							
						}
						
					}

					
				}
				$time = time();
				$pdf = PDF::loadHTML($output);
			    $pdf->setPaper('A4', 'landscape');
			    $pdf->save('papers/Client Finance Account_'.$time.'.pdf');

			    $filename_download = 'Client Finance Account_'.$time.'.pdf';
			    echo $filename_download;


				/*--------------Close type else here------------*/
			}
		}
		if($format == 4){
			$csvfilename = 'Client Finance Account.csv';
			$file = fopen('papers/Client Finance Account.csv', 'w');
			
			if($type == 1){
				$columns = array('Client Code','Surname','Firstname','Company Name','Opening Balance', 'Client Money Received', 'Payments Made','Balance');
				fputcsv($file, $columns);
				if(count($clientlist)){
					foreach ($clientlist as $client) {
						$finance_client = DB::table('finance_clients')->where('client_id',$client->client_id)->first();
						$opening_bal = '0.0';
						if(count($finance_client))
						{
							if($finance_client->debit != "" && $finance_client->debit != "0.00" && $finance_client->debit != "0.0" && $finance_client->debit != "0")
							{
								$opening_bal = $finance_client->debit;
							}
							if($finance_client->credit != "" && $finance_client->credit != "0.00" && $finance_client->credit != "0.0" && $finance_client->credit != "0")
							{
								$opening_bal = '-'.$finance_client->credit;
							}
						}
						$client_receipt = DB::table('receipts')->where('client_code',$client->client_id)->where('credit_nominal','813A')->where('imported',0)->sum('amount');
						$client_payment = DB::table('payments')->where('client_code',$client->client_id)->where('imported',0)->sum('amount');

						$sumval = $opening_bal + ($client_receipt * -1);
						$sumval = $sumval + $client_payment;

						$columns = array($client->client_id,$client->surname,$client->firstname,$client->company,number_format_invoice($opening_bal), number_format_invoice($client_receipt * -1), number_format_invoice($client_payment),number_format_invoice($sumval));
							fputcsv($file, $columns);
					}					
				}
				fclose($file);
			    echo $csvfilename;
			}
			else{
				$date = DB::table('user_login')->where('id',1)->first();
				if(count($clientlist)){
					foreach ($clientlist as $client) {
						$finance_client = DB::table('finance_clients')->where('client_id','like',$client->client_id.'%')->first();
						$balance='';
						if(count($finance_client)){
							$balance = ($finance_client->balance != "")?number_format_invoice_without_comma($finance_client->balance):"0.00";
							$owed_text='Opening Balance';
							if($balance != "0.00" && $balance != "" && $balance != "0")
							{
								if($finance_client->balance >= 0){
									$finance_client->owed_text = 'Client Owes Back';
								}
								else{
									$owed_text = 'Client Is Owed';
								}
							}

							if($finance_client->balance > 0){
								$openening_debit = number_format_invoice($finance_client->balance);
								$openening_credit='';							
							}
							elseif($finance_client->balance == '' || $finance_client->balance == 0){
								$openening_debit = '';
								$openening_credit = number_format_invoice($finance_client->balance);
							}
							else{							
								$openening_debit = '';
								$openening_credit = number_format_invoice($finance_client->balance * -1);
							}


							$client_opening = array(date('d-M-Y', strtotime($date->opening_balance_date)),'Opening Balance',$owed_text,$openening_debit,$openening_credit, number_format_invoice($finance_client->balance));
						}
						else{
							$balance='';
							$client_opening = [];
						}

						$get_receipts = DB::select('SELECT *,UNIX_TIMESTAMP(`receipt_date`) as dateval from `receipts` WHERE `imported` = 0 AND `credit_nominal` = "813A" AND client_code = "'.$client->client_id.'"');
						$get_payments = DB::select('SELECT *,UNIX_TIMESTAMP(`payment_date`) as dateval from `payments` WHERE `imported` = 0 AND `debit_nominal` = "813A" AND client_code = "'.$client->client_id.'"');

						$get_receipt_payments=array_merge($get_receipts,$get_payments);

						$dateval = array();
						foreach ($get_receipt_payments as $key => $row)
						{
						    $dateval[$key] = $row->dateval;
						}
						array_multisort($dateval, SORT_ASC, $get_receipt_payments);
						$balance_val = $balance;

						if(count($finance_client) && !count($get_receipt_payments)) {
							if($finance_client->balance != "" && $finance_client->balance != 0 && $finance_client->balance != '0.00')
							{

								$client_details = DB::table('cm_clients')->where('client_id', $finance_client->client_id)->first();

								if($client_details->company == ''){
									$companyname = $client_details->surname.' '.$client_details->firstname;
								}
								else{
									$companyname = $client_details->company;
								}

								$columns = array('','','','','','');
							fputcsv($file, $columns);
							
								$columns = array($client_details->client_id,$companyname,'','','','');
								fputcsv($file, $columns);

								$columns = array('Date','Source','Description','Debit','Credit','Balance');
								fputcsv($file, $columns);
								if(count($client_opening)){
									fputcsv($file, $client_opening);
								}
								
							}
						}


						
						if(count($get_receipt_payments))
						{
							$client_details = DB::table('cm_clients')->where('client_id', $get_receipt_payments[0]->client_code)->first();

							if($client_details->company == ''){
								$companyname = $client_details->surname.' '.$client_details->firstname;
							}
							else{
								$companyname = $client_details->company;
							}
							$columns = array('','','','','','');
							fputcsv($file, $columns);

							$columns = array($get_receipt_payments[0]->client_code,$companyname,'','','','');
							fputcsv($file, $columns);

							$columns = array('Date','Source','Description','Debit','Credit','Balance');
							fputcsv($file, $columns);

							if(count($client_opening)){
									fputcsv($file, $client_opening);
								}

							foreach($get_receipt_payments as $list)
							{
														

								if(isset($list->payments_id)) { 
									$source = 'Payments';
									$amount_credit = '';
									$amount_debit = number_format_invoice($list->amount);
									
									$textvalue = 'Payment Made Back to Client';
									$amount = number_format_invoice($list->amount);
									$amt = $list->amount;
									$balance_val = ($balance_val + ($list->amount));
									$class = 'payment_viewer_class';
									$id = $list->payments_id;



								}
								else { 
									$source = 'Receipts'; 
									$amount_credit = number_format_invoice($list->amount);
									$amount_debit = '';

									if($list->amount != '0' && $list->amount != '0.00' && $list->amount != '')
									{								
										$amount = number_format_invoice($list->amount * -1);
										$amt = ($list->amount * -1);
										$textvalue = 'Client Money Received';
										$balance_val = $balance_val + ($list->amount * -1);
									}
									else{								
										$amount = number_format_invoice($list->amount);
										$amt = $list->amount;
										$textvalue = '';
										$balance_val = $balance_val + $list->amount;
									}
									$id = $list->id;

									$class = 'receipt_viewer_class';
								}		

								$columns = array(date('d-M-Y', $list->dateval),$source,$textvalue,$amount_debit,$amount_credit,number_format_invoice($balance_val));
								fputcsv($file, $columns);
							}
						}
					}
				}
				fclose($file);
			    echo $csvfilename;
			}
		}
	}
}
