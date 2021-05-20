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

		$bank_count = DB::table('financial_banks')->get();

		echo json_encode(array("id" => $id,"bank_name" => $bank_name, "account_name" => $account_name,"account_no" => $account_no,"description" => $description, "code" => $code,"bank_counts" => count($bank_count)));
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
					$dataval['nominal_code'] = $code;
					$dataval['dr_value'] = $debit_balance;
					$dataval['cr_value'] = '0.00';
					DB::table('invoice_journals')->where('connecting_journal_reference',$journal_id)->update($dataval);

					$dataval['nominal_code'] = '991';
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $debit_balance;
					DB::table('invoice_journals')->where('connecting_journal_reference',$journal_id.'.01')->update($dataval);
				}
				else{
					$dataval['nominal_code'] = '991';
					$dataval['dr_value'] = $credit_balance;
					$dataval['cr_value'] = '0.00';
					DB::table('invoice_journals')->where('connecting_journal_reference',$journal_id)->update($dataval);

					$dataval['nominal_code'] = $code;
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $credit_balance;
					DB::table('invoice_journals')->where('connecting_journal_reference',$journal_id.'.01')->update($dataval);
				}

				$data['debit_balance'] = $debit_balance;
				$data['credit_balance'] = $credit_balance;
				DB::table('financial_banks')->where('id',$id)->update($data);
			}
			else{
				$count_total_journals = DB::table('invoice_journals')->groupBy('reference')->get();
				$next_connecting_journal = count($count_total_journals) + 1;
				$date = DB::table('user_login')->where('id',1)->first();

				$dataval['journal_date'] = $date->opening_balance_date;
				$dataval['description'] = 'Bank Account Opening Balance';
				$dataval['reference'] = 'OB'.$id;

				if($debit_balance != "" && $debit_balance != "0.00" && $debit_balance != "0")
				{
					$dataval['connecting_journal_reference'] = $next_connecting_journal;
					$dataval['nominal_code'] = $bank_details->nominal_code;
					$dataval['dr_value'] = $debit_balance;
					$dataval['cr_value'] = '0.00';
					DB::table('invoice_journals')->insert($dataval);

					$dataval['connecting_journal_reference'] = $next_connecting_journal.'.01';
					$dataval['nominal_code'] = '991';
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $debit_balance;
					DB::table('invoice_journals')->insert($dataval);
				}
				else{
					$dataval['connecting_journal_reference'] = $next_connecting_journal;
					$dataval['nominal_code'] = '991';
					$dataval['dr_value'] = $credit_balance;
					$dataval['cr_value'] = '0.00';
					DB::table('invoice_journals')->insert($dataval);

					$dataval['connecting_journal_reference'] = $next_connecting_journal.'.01';
					$dataval['nominal_code'] = $bank_details->nominal_code;
					$dataval['dr_value'] = '0.00';
					$dataval['cr_value'] = $credit_balance;
					DB::table('invoice_journals')->insert($dataval);
				}

				$data['debit_balance'] = $debit_balance;
				$data['credit_balance'] = $credit_balance;
				$data['journal_id'] = $next_connecting_journal;
				DB::table('financial_banks')->where('id',$id)->update($data);
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
		$from = Input::get('from');
		$to = Input::get('to');

		if($selection == "4")
		{
			$from_date = strtotime(date('Y-m-d',strtotime($from)));
			$to_date = strtotime(date('Y-m-d',strtotime($to)));

			$journals = DB::select('SELECT * from `invoice_journals` WHERE UNIX_TIMESTAMP(`journal_date`) >= "'.$from_date.'" AND UNIX_TIMESTAMP(`journal_date`) <= "'.$to_date.'" ORDER BY `id` ASC');
		}
		elseif($selection == "1")
		{
			$current_year = date('Y');
			$journals = DB::table('invoice_journals')->where('journal_date','like',$current_year.'%')->orderBy('id','asc')->get();
		}
		elseif($selection == "2")
		{
			$current_year = date('Y') - 1;
			$journals = DB::table('invoice_journals')->where('journal_date','like',$current_year.'%')->orderBy('id','asc')->get();
		}
		elseif($selection == "3")
		{
			$current_month = date('m') - 1;
			$journals = DB::table('invoice_journals')->where('journal_date','like','%-'.$current_month.'-%')->orderBy('id','asc')->get();
		}
		$output = '<table class="table">
		<thead>
			<th style="text-align:left">Journal <br/>ID</th>
			<th style="text-align:left">Journal <br/>Date</th>
			<th style="text-align:left">Journal <br/>Description</th>
			<th style="text-align:left">Nominal <br/>Code</th>
			<th style="text-align:left">Nominal Code <br/>Description</th>
			<th style="text-align:right">Debit <br/>Value</th>
			<th style="text-align:right">Credit <br/>Value</th>
		</thead>
		<tbody>';
		if(count($journals))
		{
			foreach($journals as $journal)
			{
				$get_nominal = DB::table('nominal_codes')->where('code',$journal->nominal_code)->first();
				$output.='<tr>
					<td>'.$journal->connecting_journal_reference.'</td>
					<td>'.date('d-M-Y',strtotime($journal->journal_date)).'</td>
					<td>'.$journal->description.'</td>
					<td>'.$journal->nominal_code.'</td>
					<td>'.$get_nominal->description.'</td>
					<td style="text-align:right">'.number_format_invoice($journal->dr_value).'</td>
					<td style="text-align:right">'.number_format_invoice($journal->cr_value).'</td>
				</tr>';
			}
		}
		else{
			$output.='<tr>
				<td colspan="7" style="text-align:center">No Journals Found</td>
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
		echo json_encode(array("bal_status" => $bal_status, 'commit_status' => $commit_status, 'owed_text' => $owed_text, "balance" => $data['balance']));
	}
}
