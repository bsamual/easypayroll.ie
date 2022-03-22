<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use DateTime;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class StatementController extends Controller {
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
		//date_default_timezone_set("Asia/Calcutta");
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function statement_list()
	{
		return view('user/statement/statements', array('title' => 'Bubble - Client Statements'));
	}
	public function load_statement_clients()
	{
		$clients = DB::table('cm_clients')->orderBy('id','asc')->get();
		$current_month = Input::get('current_month');

		$current_month = date('M-Y', strtotime($current_month));
        $current_monthh = date('m-Y', strtotime($current_month));
        $curr_str_month = $current_month;

        $opening_month = DB::table('user_login')->first();
        $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));

        $edate = strtotime($curr_str_month);
        $bdate = strtotime($opening_bal_month);
        $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
        $thval = '<th colspan="5" class="text-center" style="border-right:1px solid #d9d9d9">'.date('M-Y', strtotime($curr_str_month)).'</th>';
        $thval_divide = '<th style="border-right:1px solid #d9d9d9"><p style="width:90px">Invoives</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:90px">Received</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Closing Balance</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
        $tdval_divide = '<td class="invoice invoice_td invoice_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">&nbsp;</td>
        <td class="received received_td received_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">&nbsp;</td>
        <td class="closing_bal closing_bal_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">&nbsp;</td>
        <td class="statement_sent statement_sent_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'">&nbsp;</td>
        <td class="build_statement_td build_statement_td_'.date('m-Y', strtotime($curr_str_month)).'" data-month="'.date('m-Y', strtotime($curr_str_month)).'" style="border-right:1px solid #d9d9d9">&nbsp;</td>';
        for($i= 1; $i<=$age; $i++)
        {
          $dateval = date('m-Y', strtotime('first day of previous month', strtotime($curr_str_month)));
          $datevall = date('M-Y', strtotime('first day of previous month', strtotime($curr_str_month)));
          $datevalll = date('Y-m-01', strtotime('first day of previous month', strtotime($curr_str_month)));
          $thval.= '<th colspan="5" class="text-center" style="border-right:1px solid #d9d9d9">'.$datevall.'</th>';
          $thval_divide.= '<th style="border-right:1px solid #d9d9d9"><p style="width:90px">Invoives</p></th>
          <th style="border-right:1px solid #d9d9d9"><p style="width:90px">Received</p></th>
          <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Closing Balance</p></th>
          <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
          <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
          $tdval_divide.= '<td class="invoice invoice_td invoice_'.$dateval.'" data-month="'.$dateval.'">&nbsp;</td>
	        <td class="received received_td received_'.$dateval.'" data-month="'.$dateval.'">&nbsp;</td>
	        <td class="closing_bal closing_bal_'.$dateval.'" data-month="'.$dateval.'">&nbsp;</td>
	        <td class="statement_sent statement_sent_'.$dateval.'" data-month="'.$dateval.'">&nbsp;</td>
	        <td class="build_statement_td build_statement_td_'.$dateval.'" data-month="'.$dateval.'" style="border-right:1px solid #d9d9d9">&nbsp;</td>';
          $curr_str_month = date('Y-m-d', strtotime('first day of previous month', strtotime($curr_str_month)));
        }

		$output = '
		<div id="demoItem3" style="float:left;width:1000px;position:fixed">
		<table class="table table-fixed-header_1 own_table_white" style="background: #f5f5f5;">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th style="border-right:1px solid #d9d9d9"></th>
				</tr>
				<tr>
					<th><p style="width: 45px;">S.No</p></th>
					<th><p style="width: 80px;">ClientID</p></th>
					<th><p style="width: 300px;">Company Name</p></th>
					<th><p style="width: 250px;">Primary Email</p></th>
					<th><p style="width: 175px;">Secondary Email</p></th>
					<th><p style="width: 105px;">Send Statement</p></th>
					<th style="border-right:1px solid #d9d9d9"><p style="width: 115px;">Opening Balance</p></th>
				</tr>
			</thead>
			<tbody id="statement_client_tbody">';
			if(count($clients))
			{
				$i = 1;
				foreach($clients as $client)
				{
					$statement_details = DB::table('client_statement')->where('client_id',$client->client_id)->first();
					if(count($statement_details))
					{
						$primary = $statement_details->email;
						$secondary = $statement_details->email2;
						$salutation = $statement_details->salutation;
					}
					else{
						$primary = $client->email;
						$secondary = $client->email2;
						$salutation = $client->salutation;
					}
					if($client->send_statement == 1) { $cecked = 'checked'; } else { $cecked = '';  }

					$output.='<tr class="client_tr client_tr_'.$client->client_id.'" data-element="'.$client->client_id.'">
						<td>'.$i.'</td>
						<td>'.$client->client_id.'</td>
						<td>'.$client->company.'</td>
						<td>'.$primary.'</td>
						<td>'.$secondary.'</td>
						<td><input type="checkbox" name="ok_to_send_statement" class="ok_to_send_statement" id="ok_to_send_statement'.$client->client_id.'" data-client="'.$client->client_id.'" value="" '.$cecked.'><label style="font-size: 16px;" for="ok_to_send_statement'.$client->client_id.'">&nbsp;</label></td>
						<td class="opening_bal_td" style="border-right:1px solid #d9d9d9"></td>
					</tr>';
					$i++;
				}
			}
			$output.='</tbody>
		</table>
		</div>
		<div style="float:left;max-width:2500px;margin-left:1224px;">
			<table class="table table-fixed-header own_table_white" id="own_table_white2">
				<thead>
					<tr class="tr_header_1">
						'.$thval.'
					</tr>
					<tr class="tr_header_2">
						'.$thval_divide.'
					</tr>
				</thead>
				<tbody>';
				if(count($clients))
				{
					$i = 1;
					foreach($clients as $client)
					{
						$output.='<tr class="client_value_tr client_value_tr_'.$client->client_id.'" data-element="'.$client->client_id.'">'.$tdval_divide.'
						</tr>';
						$i++;
					}
				}
				$output.='</tbody>
			</table>
		</div>';

		echo $output;
	}
	public function get_client_opening_balance()
	{
		$clients = DB::table('cm_clients')->orderBy('id','asc')->get();
		$opening_array = array();
		if(count($clients))
		{
			$i = 1;
			foreach($clients as $client)
			{
				$client_id = $client->client_id;
				$opening_bal_details = DB::table('opening_balance')->where('client_id',$client_id)->first();
				$opening_bal = '0.00';
				if(count($opening_bal_details))
				{
					if($opening_bal_details->opening_balance != "")
					{
						$opening_bal = number_format_invoice_empty($opening_bal_details->opening_balance);
					}
				}
				array_push($opening_array,$opening_bal);
			}
		}
		echo json_encode($opening_array);
	}
	public function get_client_statement_values()
	{
		$count = Input::get('count');
		$limit = 100;
		$offset = $count * 100;

		$clients = DB::table('cm_clients')->orderBy('id','asc')->offset($offset)->limit($limit)->get();
		if(count($clients))
		{
			foreach($clients as $key => $client)
			{
				$client_id = $client->client_id;
				$current_month = Input::get('current_month');

				$current_month = date('M-Y', strtotime($current_month));
		        $current_monthh = date('m-Y', strtotime($current_month));
		        $curr_str_month = $current_month;

		        $opening_month = DB::table('user_login')->first();
		        $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));

		        $edate = strtotime($curr_str_month);
		        $bdate = strtotime($opening_bal_month);
		        $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));

		        $opening_bal_details = DB::table('opening_balance')->where('client_id',$client_id)->first();
				$opening_bal = '0.00';
				if(count($opening_bal_details))
				{
					if($opening_bal_details->opening_balance != "")
					{
						$opening_bal = number_format_invoice($opening_bal_details->opening_balance);
						$opening_bal = str_replace(",","",$opening_bal);
						$opening_bal = str_replace(",","",$opening_bal);
						$opening_bal = str_replace(",","",$opening_bal);
						$opening_bal = str_replace(",","",$opening_bal);
						$opening_bal = str_replace(",","",$opening_bal);
						$opening_bal = str_replace(",","",$opening_bal);
					}
				}

				$thval_array = array();
				$thval_divide_array = array();
				$tdval_divide_array = array();

		        $invoice_details = DB::table('invoice_system')->where('client_id',$client_id)->where('invoice_date','like',date('Y-m', strtotime($opening_bal_month)).'%')->sum('gross');
		        $receipt_details = DB::table('receipts')->where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','like',date('Y-m', strtotime($opening_bal_month)).'%')->sum('amount');

		        $closing_bal = ($opening_bal + $invoice_details) - $receipt_details;
		        if($count == 0)
		        {
		        	$thval = '<th colspan="5" class="text-center" style="border-right:1px solid #d9d9d9">'.date('M-Y', strtotime($opening_bal_month)).'</th>';
			        array_push($thval_array, $thval);
			        $thval_divide = '<th style="border-right:1px solid #d9d9d9"><p style="width:90px">Invoives</p></th>
			        <th style="border-right:1px solid #d9d9d9"><p style="width:90px">Received</p></th>
			        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Closing Balance</p></th>
			        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
			        <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
			        array_push($thval_divide_array, $thval_divide);
		        }
		        

		        $check_attachment = DB::table('build_statement_attachments')->where('client_id',$client_id)->where('month_year',date('m-Y', strtotime($opening_bal_month)))->first();
		    	if(count($check_attachment))
		    	{
		    		$build_button = '<a href="'.URL::to($check_attachment->url).'" title="'.$check_attachment->filename.'" download>'.substr($check_attachment->filename,0,15).'...</a>';
		    	}
		    	else{
		    		$build_button = '<a href="javascript:" name="build_statement" class="common_black_button build_statement" data-client="'.$client_id.'" data-element="'.date('m-Y', strtotime($opening_bal_month)).'" data-balance="'.$opening_bal.'">Build</a>';
		    	}
		    	if($invoice_details > 0) { $inv_color = 'color:blue;font-weight:700'; }
		    	elseif($invoice_details < 0) { $inv_color = 'color:blue;'; }
		    	else { $inv_color= ''; }

		    	if($receipt_details > 0) { $rec_color = 'color:green;font-weight:700'; }
		    	elseif($receipt_details < 0) { $inv_color = 'color:green;'; }
		    	else { $rec_color= ''; }
		        $tdval_divide = '<td class="invoice invoice_td invoice_'.date('m-Y', strtotime($opening_bal_month)).'" data-month="'.date('m-Y', strtotime($opening_bal_month)).'" style="'.$inv_color.'">
		        	'.number_format_invoice($invoice_details).'
		        </td>
		        <td class="received received_td received_'.date('m-Y', strtotime($opening_bal_month)).'" data-month="'.date('m-Y', strtotime($opening_bal_month)).'" style="'.$rec_color.'">
		        	'.number_format_invoice($receipt_details).'
		        </td>
		        <td class="closing_bal closing_bal_'.date('m-Y', strtotime($opening_bal_month)).'" data-month="'.date('m-Y', strtotime($opening_bal_month)).'">
		        	'.number_format_invoice($closing_bal).'
		        </td>
		        <td class="statement_sent statement_sent_'.date('m-Y', strtotime($opening_bal_month)).'" data-month="'.date('m-Y', strtotime($opening_bal_month)).'">
		        	
		        </td>
		        <td class="build_statement_td build_statement_td_'.date('m-Y', strtotime($opening_bal_month)).'" data-month="'.date('m-Y', strtotime($opening_bal_month)).'" style="border-right:1px solid #d9d9d9">
		        	'.$build_button.'
		        </td>';

		        array_push($tdval_divide_array, $tdval_divide);


		        $opening_bal = $closing_bal;
		        for($i= $age; $i>=1; $i--)
		        {
		          $dateval = date('m-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
		          $datevall = date('Y-m', strtotime('first day of next month', strtotime($opening_bal_month)));
		          $datevalll = date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
		            $invoice_details = DB::table('invoice_system')->where('client_id',$client_id)->where('invoice_date','like',$datevall.'%')->sum('gross');
			        $receipt_details = DB::table('receipts')->where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','like',$datevall.'%')->sum('amount');

			        $closing_bal = ($opening_bal + $invoice_details) - $receipt_details;
			        if($count == 0)
		        	{
				        $thval = '<th colspan="5" class="text-center" style="border-right:1px solid #d9d9d9">'.$datevalll.'</th>';
				        array_push($thval_array, $thval);
				        $thval_divide = '<th style="border-right:1px solid #d9d9d9"><p style="width:90px">Invoives</p></th>
				          <th style="border-right:1px solid #d9d9d9"><p style="width:90px">Received</p></th>
				          <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Closing Balance</p></th>
				          <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Statement Sent</p></th>
				          <th style="border-right:1px solid #d9d9d9"><p style="width:105px">Build</p></th>';
				        array_push($thval_divide_array, $thval_divide);
				    }

			        $check_attachment = DB::table('build_statement_attachments')->where('client_id',$client_id)->where('month_year',$dateval)->first();
					if(count($check_attachment))
					{
						$build_button = '<a href="'.URL::to($check_attachment->url).'" title="'.$check_attachment->filename.'" download>'.substr($check_attachment->filename,0,15).'...</a>';
					}
					else{
						$build_button = '<a href="javascript:" name="build_statement" class="common_black_button build_statement" data-client="'.$client_id.'" data-element="'.$dateval.'" data-balance="'.$opening_bal.'">Build</a>';
					}

					if($invoice_details > 0) { $inv_color = 'color:blue;font-weight:700'; }
			    	elseif($invoice_details < 0) { $inv_color = 'color:blue;'; }
			    	else { $inv_color= ''; }

			    	if($receipt_details > 0) { $rec_color = 'color:green;font-weight:700'; }
			    	else { $rec_color= ''; }

					$tdval_divide = '<td class="invoice invoice_td invoice_'.$dateval.'" data-month="'.$dateval.'" style="'.$inv_color.'">
						'.number_format_invoice($invoice_details).'
					</td>
			        <td class="received received_td received_'.$dateval.'" data-month="'.$dateval.'" style="'.$rec_color.'">
			        	'.number_format_invoice($receipt_details).'
			        </td>
			        <td class="closing_bal closing_bal_'.$dateval.'" data-month="'.$dateval.'">
			        	'.number_format_invoice($closing_bal).'
			        </td>
			        <td class="statement_sent statement_sent_'.$dateval.'" data-month="'.$dateval.'">

			        </td>
			        <td class="build_statement_td build_statement_td_'.$dateval.'" data-month="'.$dateval.'" style="border-right:1px solid #d9d9d9">
			        	'.$build_button.'
			        </td>';
			        array_push($tdval_divide_array, $tdval_divide);
		          $opening_bal_month = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
		          $opening_bal = $closing_bal;
		        }
		        if($count == 0)
		        {
			        $thval_reverse = implode("",array_reverse($thval_array));
			        $thval_divide_reverse = implode("",array_reverse($thval_divide_array));

			        $data[$key]['thval'] = $thval_reverse;
		        	$data[$key]['thval_divide'] = $thval_divide_reverse;
			    }

		        $tdval_divide_reverse = implode("",array_reverse($tdval_divide_array));
		        $data[$key]['tdval_divide'] = $tdval_divide_reverse;
		        $data[$key]['client_id'] = $client_id;
			}
		}
		
        echo json_encode($data);
	}
	public function get_invoice_list_statement()
	{
		$client = Input::get('client');
		$month = explode('-',Input::get('month'));
		$monthval = $month[1].'-'.$month[0];
		$get_title_month = date('M-Y', strtotime($month[1].'-'.$month[0].'-01'));
		$client_details = DB::table('cm_clients')->where('client_id',$client)->first();

		$invoice_details = DB::table('invoice_system')->where('client_id',$client)->where('invoice_date','like',$monthval.'%')->get();
		$output = '';
		$total_net = 0;
		$total_vat = 0;
		$total_gross = 0;
		if(count($invoice_details))
		{
			foreach($invoice_details as $invoice)
			{
				$output.='
				<tr>
				<td><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>
				<td>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
				<td style="text-align:right">'.number_format_invoice($invoice->inv_net).'</td>
				<td style="text-align:right">'.number_format_invoice($invoice->vat_value).'</td>
				<td style="text-align:right">'.number_format_invoice($invoice->gross).'</td>
				</tr>';

				$total_net = $total_net + $invoice->inv_net;
				$total_vat = $total_vat + $invoice->vat_value;
				$total_gross = $total_gross + $invoice->gross;
			}
		}
		else{
			$output.= '<tr>
				<td colspan="5">No Invoices Found</td>
			</tr>';
		}
		$data['output'] = $output;
		$data['total_net'] = number_format_invoice($total_net);
		$data['total_vat'] = number_format_invoice($total_vat);
		$data['total_gross'] = number_format_invoice($total_gross);
		$data['title'] = 'Invoice List - '.$client.' '.$client_details->company.' - '.$get_title_month;
		echo json_encode($data);
	}
	public function get_receipt_list_statement()
	{
		$client = Input::get('client');
		$month = explode('-',Input::get('month'));
		$monthval = $month[1].'-'.$month[0];
		$get_title_month = date('M-Y', strtotime($month[1].'-'.$month[0].'-01'));
		$client_details = DB::table('cm_clients')->where('client_id',$client)->first();

		$receipt_details = DB::table('receipts')->where('client_code',$client)->where('credit_nominal','712')->where('receipt_date','like',$monthval.'%')->get();

		$output = '';
		$total_amount = 0;
		if(count($receipt_details))
		{
			foreach($receipt_details as $receipt)
			{
				$output.='
				<tr>
				<td>'.date('d-M-Y', strtotime($receipt->receipt_date)).'</td>
				<td>'.$receipt->debit_nominal.'</td>
				<td>'.$receipt->credit_nominal.'</td>
				<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
				</tr>';
				$total_amount = $total_amount + $receipt->amount;
			}
		}
		else{
			$output.= '<tr>
				<td colspan="5">No Receipts Found</td>
			</tr>';
		}
		$data['output'] = $output;
		$data['total_amount'] = number_format_invoice($total_amount);
		$data['title'] = 'Receipt List - '.$client.' '.$client_details->company.' - '.$get_title_month;
		echo json_encode($data);
	}
	public function save_statement_settings()
	{
		$data['secondary'] = (Input::get('statement_secondary') != "")?Input::get('statement_secondary'):'';
		$data['email_body'] = (Input::get('email_body') != "")?Input::get('email_body'):'';
		$data['minimum_balance'] = (Input::get('minimum_bal') != "")?Input::get('minimum_bal'):'';
		$data['payments_to_iban'] = (Input::get('payments_to_iban') != "")?Input::get('payments_to_iban'):'';
		$data['payments_to_bic'] = (Input::get('payments_to_bic') != "")?Input::get('payments_to_bic'):'';
		$data['filename'] = '';
		$data['url'] = '';
		if(isset($_FILES['bg_image']['name']))
		{
			if($_FILES['bg_image']['name'] != "")
			{
				$filename = $_FILES['bg_image']['name'];

				$tmp_name = $_FILES['bg_image']['tmp_name'];
				$upload_dir = 'uploads/statements';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/bg_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);	

				$data['filename'] = $filename;
				$data['url'] = $upload_dir;
			}
		}
		$check_statement = DB::table('settings')->where('source','statement')->first();
		if(count($check_statement))
		{
			if($_FILES['bg_image']['name'] == "")
			{
				$settingsval = unserialize($check_statement->settings);
				$data['filename'] = $settingsval['filename'];
				$data['url'] = $settingsval['url'];
			}
		}
		$dataval['source'] = 'statement';
		$dataval['settings'] = serialize($data);
		$dataval['fields'] = 'secondary,email_body,minimum_balance,payments_to_iban,payments_to_bic,filename,url';
		
		if(count($check_statement))
		{
			DB::table('settings')->where('id',$check_statement->id)->update($dataval);
		}
		else{
			DB::table('settings')->insert($dataval);
		}
		return redirect('user/statement_list')->with('message','Settings Saved Successfully');
	}
	public function build_statement()
	{
		$client_id = Input::get('client_id');
		$month = Input::get('month');
		$opening_bal_value = Input::get('opening_bal');
		$explode_month = explode('-',$month);
		$curr_month = $explode_month[1].'-'.$explode_month[0].'-'.'01';
		$currrr_month = $explode_month[1].'-'.$explode_month[0];

		$client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
		$settings = DB::table('settings')->where('source','statement')->first();
		$iban = '';
		$bic = '';
		if(count($settings))
		{
			$unserialise = unserialize($settings->settings);

			$iban = $unserialise['payments_to_iban'];
			$bic = $unserialise['payments_to_bic'];
		}
		$opening_bal_date = date('d F Y', strtotime('last day of previous month', strtotime($curr_month)));

		$html = '
		<style> 
			@font-face { font-family: "Roboto Regular"; font-weight: normal; src: url(\"fonts/Roboto-Regular.ttf\") format(\"truetype\"); } 
			@font-face { font-family: "Roboto Bold"; font-weight: bold; src: url(\"fonts/Roboto-Bold.ttf\") format(\"truetype\"); } 
			body{ font-family: "Roboto Regular", sans-serif; font-weight: normal; font-size:14px;line-height:20px;padding:30px }
		</style>
		<h4 style="text-align:center;font-size:20px">Accountancy Fee Statement</h4>
		<table style="width:100%">
			<tr>
				<td style="width:50%">
					<spam>To:</spam><br/>
					'.$client_details->firstname.' '.$client_details->surname.'<br/>
					'.$client_details->company.'<br/>
					'.$client_details->address1.'<br/>
					'.$client_details->address2.'<br/>
					'.$client_details->address3.'<br/>
					'.$client_details->address4.'<br/>
					'.$client_details->address5.'<br/>
				</td>
				<td style="width:50%;vertical-align:top">
					<table style="width:100%;margin-top:18px">
						<tr>
							<td>Date: </td>
							<td style="text-align:right">'.date('d-M-Y').'</td>
						</tr>
						<tr>
							<td>Client Code: </td>
							<td style="text-align:right">'.$client_id.'</td>
						</tr>
						<tr>
							<td colspan="2">Payments can be made to:</td>
						</tr>
						<tr>
							<td>IBAN: </td>
							<td style="text-align:right">'.$iban.'</td>
						</tr>
						<tr>
							<td>BIC: </td>
							<td style="text-align:right">'.$bic.'</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<table style="width:100%;margin-top:40px">
			<tr>
				<td style="width:80%">
					Opening Balance @ '.$opening_bal_date.'
				</td>
				<td style="width:20%;text-align:right">
					'.number_format_invoice($opening_bal_value).'
				</td>
			</tr>
		</table>

		<table style="width:100%;margin-top:20px;border-collapse:collapse;margin-bottom:30px">
			<tr>
				<td style="width:60%;border-bottom:2px solid #000;border-right:2px solid #000;text-align:center">
					Invoices Issued
				</td>
				<td style="width:40%;border-bottom:2px solid #000;text-align:center">
					Remittance
				</td>
			</tr>
			<tr>
				<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
					<table style="width:100%">
						<tr>
							<td style="text-align:left">Date</td>
							<td style="text-align:left">Inv No</td>
							<td style="text-align:center">€</td>
						</tr>';
						$invoices = DB::table('invoice_system')->where('client_id',$client_id)->where('invoice_date','LIKE', $currrr_month.'%')->get();
						$total_inv_issued = 0;
						if(count($invoices)){
							foreach($invoices as $inv){
								$html.= '<tr>
									<td>'.date('d/m/Y', strtotime($inv->invoice_date)).'</td>
									<td>'.$inv->invoice_number.'</td>
									<td style="text-align:right">'.number_format_invoice($inv->gross).'</td>
								</tr>';
								$total_inv_issued = $total_inv_issued + $inv->gross;
							}
						}
						else{
							$html.= '<tr>
									<td>No Invoice Issued</td>
									<td></td>
									<td style="text-align:right"></td>
								</tr>';
						}
					$html.= '</table>
				</td>
				<td style="border-bottom:2px solid #000;vertical-align:top">
					<table style="width:100%">
						<tr>
							<td style="text-align:left">Date</td>
							<td style="text-align:center">€</td>
						</tr>';
						$receipt_details = DB::table('receipts')->where('client_code',$client_id)->where('credit_nominal','712')->where('receipt_date','like',$currrr_month.'%')->get();
						$total_remittance = 0;
						if(count($receipt_details))
						{
							foreach($receipt_details as $receipt)
							{
								$html.= '<tr>
									<td>'.date('d/m/Y', strtotime($receipt->receipt_date)).'</td>
									<td style="text-align:right">'.number_format_invoice($receipt->amount).'</td>
								</tr>';
								$total_remittance = $total_remittance + $receipt->amount;
							}
						}
						else{
							$html.= '<tr>
									<td>No Remittance Found</td>
									<td style="text-align:right"></td>
								</tr>';
						}
					$html.= '</table>
				</td>
			</tr>
			<tr>
				<td style="border-bottom:2px solid #000;border-right:2px solid #000;vertical-align:top">
					<table style="width:100%">
						<tr>
							<td style="text-align:left">Total</td>
							<td style="text-align:right">'.number_format_invoice($total_inv_issued).'</td>
						</tr>
					</table>
				</td>
				<td style="border-bottom:2px solid #000;vertical-align:top">
					<table style="width:100%">
						<tr>
							<td style="text-align:right">'.number_format_invoice($total_remittance).'</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<h5 style="text-align:center;font-size:14px;">Summary</h5>
		<table style="width:100%">
			<tr>
				<td style="width:80%">
					Opening Balance
				</td>
				<td style="width:20%;text-align:right">
					'.number_format_invoice($opening_bal_value).'
				</td>
			</tr>
			<tr>
				<td style="width:80%">
					Invoices
				</td>
				<td style="width:20%;text-align:right">
					'.number_format_invoice($total_inv_issued).'
				</td>
			</tr>
			<tr>
				<td style="width:80%">
					Remittances
				</td>
				<td style="width:20%;text-align:right">
					'.number_format_invoice($total_remittance).'
				</td>
			</tr>
			<tr>';
			$closing_bal = number_format_invoice_without_comma($opening_bal_value) + number_format_invoice_without_comma($total_inv_issued) - number_format_invoice_without_comma($total_remittance);
				$html.='<td style="width:80%;padding-top:15px">
					Closing Balance
				</td>
				<td style="width:20%;padding-top:15px;text-align:right;border-top:2px solid #000;border-bottom:2px solid #000;">
					'.number_format_invoice($closing_bal).'
				</td>
			</tr>
		</table>
		';

		$upload_dir = 'uploads/statements';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/build_statement/';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$filename = $client_id.' Statement '.date('M-Y', strtotime($curr_month)).'.pdf';
		$pdf = PDF::loadHTML($html);
		$pdf->setPaper('A4', 'portrait');
		$pdf->save($upload_dir.$filename);

		$dataval['client_id'] = $client_id;
		$dataval['month_year'] = $month;
		$dataval['url'] = $upload_dir.$filename;
		$dataval['filename'] = $filename;

		DB::table('build_statement_attachments')->insert($dataval);
		echo '<a href="'.URL::to($upload_dir.$filename).'" download>'.$filename.'</a>';
	}
}

