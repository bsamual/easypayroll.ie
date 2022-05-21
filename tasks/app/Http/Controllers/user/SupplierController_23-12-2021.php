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
use DateTime;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class SupplierController extends Controller {
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
		date_default_timezone_set("Europe/Dublin");
		require_once(app_path('Http/helpers.php'));
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function supplier_management()
	{
		$suppliers = DB::table('suppliers')->get();
		$nominal_codes = DB::table('nominal_codes')->get();
		$vat_rates = DB::table('supplier_vat_rates')->get();
		return view('user/supplier_management/home', array('title' => 'Supplier Management','suppliers' => $suppliers,'nominal_codes' => $nominal_codes,'vat_rates' => $vat_rates));
	}
	public function store_supplier()
	{
		$data['supplier_name'] = Input::get('supplier_name');
		$data['web_url'] = Input::get('supplier_address');
		$data['supplier_address'] = Input::get('supp_address');
		$data['phone_no'] = Input::get('phone_no');
		$data['email'] = Input::get('supplier_email');
		$data['iban'] = Input::get('supplier_iban');
		$data['bic'] = Input::get('supplier_bic');
		$data['vat_no'] = Input::get('vat_number');
		$data['currency'] = Input::get('currency');
		$data['opening_balance'] = Input::get('opening_balance');

		$id = Input::get('supplier_id');
		if($id == "")
		{
			$code = DB::table('suppliers')->insertGetid($data);
			$count = sprintf("%04d",$code);
			$dataid['supplier_code'] = 'GBS'.$count;
			DB::table('suppliers')->where('id',$code)->update($dataid);
		}
		else{
			DB::table('suppliers')->where('id',$id)->update($data);
		}
		return redirect::back()->with('message','Supplier Added Successfully');
	}
	public function edit_supplier()
	{
		$id = Input::get('id');
		$get_details = DB::table('suppliers')->where('id',$id)->first();
		if(count($get_details))
		{
			$data['supplier_code'] = $get_details->supplier_code;
			$data['supplier_name'] = $get_details->supplier_name;
			$data['supplier_address'] = $get_details->supplier_address;
			$data['web_url'] = $get_details->web_url;
			$data['phone_no'] = $get_details->phone_no;
			$data['email'] = $get_details->email;
			$data['iban'] = $get_details->iban;
			$data['bic'] = $get_details->bic;
			$data['vat_no'] = $get_details->vat_no;
			$data['currency'] = $get_details->currency;
			$data['opening_balance'] = $get_details->opening_balance;


			echo json_encode($data);
		}
	}
	public function get_supplier_transaction_list()
	{
		$id = Input::get('id');
		$type = Input::get('type');



		if($type == 0){
			$class = "col-md-9";
			$display = "block";

			
		}
		else{
			$class = "col-md-12";
			$display = "none";

			
		}

		$details = DB::table('suppliers')->where('id',$id)->first();
		$output = '';
		if(count($details))
		{
			$modal_title = $details->supplier_code.' '.$details->supplier_name;
			$output.='
			<div class="col-md-3 supplier_info_div">
				<table class="own_table_white table" style="width:100%">
					
					<tr>
						<td style="width:11%">Web Url: </td> 
						<td style="width:22%">'.$details->web_url.' </td>						
					</tr>
					<tr>
						<td>Email: </td>
						<td>'.$details->email.' </td>						
					</tr>
					<tr>
						<td>BIC: </td> 
						<td>'.$details->bic.' </td>						
					</tr>
					<tr>
						<td>Phone No: </td> 
						<td>'.$details->phone_no.' </td>
					</tr>
					<tr>
						<td>IBAN: </td> 
						<td>'.$details->iban.' </td>
					</tr>
					<tr>
						<td>VAT No: </td> 
						<td>'.$details->vat_no.' </td>
					</tr>
					<tr>
						<td colspan="2" style="padding:0px;">
							<a href="javascript:" class="common_black_button add_purchase_invoice" data-element="'.$id.'" data-supplier="'.$details->supplier_name.'" style="float:left; width:100%; margin-top:10px; margin-left:0px;">Add Purchase Invoice</a>

							<a href="javascript:" name="export_transaction_list" class="common_black_button export_transaction_list" id="export_transaction_list" style="float: left;width:100%; margin-top:10px; margin-left:0px;">Export as CSV</a>
						</td>
					</tr>
					<tr>
						<td colspan="2">

						</td>
					</tr>

				</table>
			</div>';


			$formatted_bal = '0.00';
			$opening_bal = '0.00';
			if($details->opening_balance != "")
			{
				$opening_bal = $details->opening_balance;
				$formatted_bal = number_format_invoice($details->opening_balance);
			}
			if($opening_bal < 0)
			{
				$color = 'green';
				$textval = 'Client is Owed';
				$debit_open = $formatted_bal;
				$credit_open = '';
				
			}
			elseif($opening_bal > 0)
			{
				$color = 'red';
				$textval = 'Client Owes Back';
				$debit_open = '';
				$credit_open = $formatted_bal;
				
			}
			else{
				$color = 'red';
				$textval = 'Opening Balance';
				$debit_open = '';
				$credit_open = $formatted_bal;
				
			}
			$i=1;
			$output.='
			<div class="'.$class.'" style="max-height:700px; overflow:hidden; overflow-y:scroll;">
				<h4 style="display:'.$display.'">Transaction List</h4>
				<input type="hidden" name="hidden_trans_supplier_id" id="hidden_trans_supplier_id" value="'.$id.'">
				<table class="table own_table_white client_account_table" id="transaction_table" style="width: 100%;">
				<thead>
				<tr>
					<th style="display:none">#</th>
					<th>Date</th>
				    <th>Source</th>				    
				    <th>Description</th>				    				    
				    <th style="text-align:center">Debit</th>
				    <th style="text-align:center">Credit</th>
				    <th style="text-align:center">Balance</th>
				    <th style="text-align:center">Action</th>
					</tr>
				</thead>
				<tbody id="client_account_tbody">
					<tr>
						<td style="display:none">'.$i.'</td>
						<td></td>						
						<td style="color:'.$color.'">Opening Balance</td>
						<td style="color:'.$color.'">'.$textval.'</td>						
						<td style="color:'.$color.'; text-align:right">'.$debit_open.'</td>
						<td style="color:'.$color.'; text-align:right">'.$credit_open.'</td>
						<td style="color:'.$color.'; text-align:right">'.$formatted_bal.'</td>
						<td></td>
					</tr>';
					$get_payments = DB::select('SELECT *,UNIX_TIMESTAMP(`payment_date`) as dateval,`payment_date` as transaction_date from `payments` WHERE `imported` = 0 AND `debit_nominal` = "813" AND supplier_code = "'.$details->id.'"');

					$get_invoice = DB::select('SELECT *,UNIX_TIMESTAMP(`invoice_date`) as dateval,`invoice_date` as transaction_date from `supplier_global_invoice` WHERE supplier_id = "'.$details->id.'"');

					$get_invoice_payments=array_merge($get_payments,$get_invoice);					

					$dateval = array();
					foreach ($get_invoice_payments as $key => $row)
					{
					    $dateval[$key] = $row->dateval;
					}
					array_multisort($dateval, SORT_ASC, $get_invoice_payments);
					$balance_val = $details->opening_balance;

					if(count($get_invoice_payments))
					{
						foreach($get_invoice_payments as $list)
						{
							if(isset($list->invoice_no)) { 
								$colorval = 'color:red';
								$source = '<a href="javascript:" class="purchase_class" style="'.$colorval.'" data-element="'.$list->id.'">Purchase Invoice</a>';
								
								$textvalue = 'Purchase Invoice - '.$list->invoice_no;
								$amount_invoice = number_format_invoice($list->gross);
								$amount_payment='';
								$balance_val = $balance_val + $list->gross;

								$invocie_text = '';
								$payment_text = '';

								$download = '<a href="'.URL::to($list->url.'/'.$list->filename).'" class="fa fa-download"  title="Download Invoice" download></a>';

							}
							else{
								$colorval = 'color:green';
								$source = '<a href="javascript:" class="payment_class" style="'.$colorval.'" data-element="'.$list->payments_id.'">Payments</a>'; 
								
								$textvalue = 'Payment Made';
								$amount_payment = number_format_invoice($list->amount);
								$amount_invoice='';
								$balance_val = $balance_val - $list->amount;

								
								$download='';
							}
							

							if($balance_val <= 0) { $bal_color = 'color:green'; }
							else{ $bal_color = 'color:red'; }

							$output.='<tr>
								<td style="display:none">'.$i.'</td>
								<td style="'.$colorval.'">'.date('d-M-Y', strtotime($list->transaction_date)).'</td>
								<td style="'.$colorval.'">'.$source.'</td>								
								<td style="'.$colorval.'">'.$textvalue.'</td>								
								<td style="'.$colorval.'" align="right">'.$amount_payment.'</td>
								<td style="'.$colorval.'" align="right">'.$amount_invoice.'</td>
								<td align="right" style="'.$bal_color.';">'.number_format_invoice($balance_val).'</td>
								<td style="'.$colorval.'" align="center">'.$download.'</td>
							</tr>';
							$i++;
						}
					}
				$output.='</tbody>
				</table>
			</div>';
		}



		echo json_encode(array('output' => $output, 'modal_title' => $modal_title ));	
	}
	public function refresh_supplier_counts()
	{
		$supplier_id = Input::get('id');
		$supplier_details = DB::table('suppliers')->where('id',$supplier_id)->first();
		$invoice_count = DB::table('supplier_global_invoice')->select(DB::raw('count(*) as invoice_count, sum(gross) as gross_sum'))->where('supplier_id',$supplier_details->id)->first();
        $payment_sum = DB::table('payments')->select(DB::raw('sum(amount) as payment_sum'))->where('imported',0)->where('debit_nominal','813')->where('supplier_code',$supplier_details->id)->first();
        $balance = ($supplier_details->opening_balance + $invoice_count->gross_sum) - $payment_sum->payment_sum;

        $data['invoice_count'] = $invoice_count->invoice_count;
        $data['balance'] = number_format_invoice($balance);
        echo json_encode($data);
	}
	public function export_suppliers_list(){
		$suppliers = DB::table('suppliers')->get();
		$columns_1 = array('Supplier Code', 'Supplier Name', 'Email Address', 'Web URL','Invoice Count', 'Balance Count');
		$filename = time().'_Supplier Management Report.csv';
		$fileopen = fopen('public/'.$filename.'', 'w');
	    fputcsv($fileopen, $columns_1);

		if(count($suppliers)){
			foreach($suppliers as $supplier){
				$invoice_count = DB::table('supplier_global_invoice')->select(DB::raw('count(*) as invoice_count, sum(gross) as gross_sum'))->where('supplier_id',$supplier->id)->first();
            	$payment_sum = DB::table('payments')->select(DB::raw('sum(amount) as payment_sum'))->where('supplier_code',$supplier->id)->where('debit_nominal','813')->where('imported',0)->first();
            	$balance = ($supplier->opening_balance + $invoice_count->gross_sum) - $payment_sum->payment_sum;

            	$columns_2 = array($supplier->supplier_code, $supplier->supplier_name, $supplier->email, $supplier->web_url,$invoice_count->invoice_count, number_format_invoice($balance));
            	fputcsv($fileopen, $columns_2);
			}
		}

		fclose($fileopen);
        echo $filename;
	}

	public function load_single_invoice_payment(){
		$id = Input::get('id');
		$type = Input::get('type');

		if($type == 0){
			$global = DB::table('supplier_global_invoice')->where('id',$id)->first();
			$output='<thead>
				<tr>
					<th>S.No</th>
					<th>Description</th>
					<th>Nominal Code</th>
					<th>Net Value</th>
					<th>VAT Rate</th>
					<th>VAT</th>					
					<th>Gross</th>
				</tr>
			</thead>';

			if(count($global)){

				
	            $detail_invoice = DB::table("supplier_detail_invoice")->where('global_id',$id)->get();
	            $nominal_codes = DB::table('nominal_codes')->get();
				$vat_rates = DB::table('supplier_vat_rates')->get();
				$detail_invoice_count = count($detail_invoice);
	            $i = 1;

	            
	            $total_net = 0;
	            $total_vat = 0;
	            $total_gross  = 0;

	            if(count($detail_invoice)) {
	            	foreach($detail_invoice as $key => $detail) {
	            		$nominal_details = DB::table('nominal_codes')->where('id',$detail->nominal_code)->first();
	            		$code_name = '';
	            		if(count($nominal_details)){
	            			$code_name = $nominal_details->code;
	            		}
	            		$output.= '<tr>
						  <td>'.$i.'</td>
						  <td>'.$detail->description.'</td>
						  <td>'.$code_name.'</td>
						  <td>'.number_format_invoice($detail->net).'</td>
						  <td>'.number_format_invoice($detail->vat_rate).'</td>
						  <td>'.number_format_invoice($detail->vat_value).'</td>
						  <td>'.number_format_invoice($detail->gross).'</td>
						</tr>';
						$i++;

						$total_net = number_format_invoice_without_comma(number_format_invoice_without_comma($total_net) + number_format_invoice_without_comma($detail->net));
						$total_vat = number_format_invoice_without_comma(number_format_invoice_without_comma($total_vat) + number_format_invoice_without_comma($detail->vat_value));
						$total_gross = number_format_invoice_without_comma(number_format_invoice_without_comma($total_gross) + number_format_invoice_without_comma($detail->gross));
	            	}
	            	$output.='<tr>
	            		<td colspan="3" style="text-align:right">Total:</td>
	            		<td>'.number_format_invoice($total_net).'</td>
	            		<td></td>
	            		<td>'.number_format_invoice($total_vat).'</td>
	            		<td>'.number_format_invoice($total_gross).'</td>
	            	</tr>';
	            }

			$page_title = 'Invoice Line Details';
		}

			

		}
		else{
			$payment = DB::table('payments')->where('payments_id',$id)->first();
			$output='<thead>
				<tr>
					<th>Date</th>
					<th>Debit Nominal </th>
					<th>Credit Nominal & Description</th>
					<th>Client/Supplier Code</th>
					<th>Debit Nominal Description </th>
					<th>Comment</th>
					<th style="text-align:right;">Amount</th>
					<th>Journal ID</th>
					<th>Status</th>
				</tr>
			</thead>';
			if(count($payment)){
				$get_details = DB::table('payment_nominal_codes')->where('code',$payment->credit_nominal)->first();
				$credit_nominal = '';
				if(count($get_details))
				{
					$credit_nominal = $get_details->code.' - '.$get_details->description;
				}

				if($payment->hold_status == 0)
				{
					$hold_status = '<a href="javascript:" class="change_to_unhold" data-element="'.$payment->payments_id.'">On Hold</a>';
				}
				else{
					$hold_status = '<a href="javascript:" class="unhold_payment" data-element="'.$payment->payments_id.'">Unhold</a>';
				}

				$output.='<tr>
					<td><spam class="date_sort_val" style="display:none">'.strtotime($payment->payment_date).'</spam>'.date('d/m/Y', strtotime($payment->payment_date)).'</td>
					<td class="debit_sort_val">'.$payment->debit_nominal.'</td>
					<td class="credit_sort_val">'.$credit_nominal.'</td>';
					if($payment->debit_nominal == "813") {
						$supplier_details = DB::table('suppliers')->where('id',$payment->supplier_code)->first();
						$output.='<td class="client_sort_val">'.$supplier_details->supplier_code.'</td>';
					}
					else {
						$output.='<td class="client_sort_val">'.$payment->client_code.'</td>';
					}
					$output.='<td class="des_sort_val">'.$payment->debit_description.'</td>
					<td class="comment_sort_val">'.$payment->comment.'</td>	
					<td style="text-align:right"><spam class="amount_sort_val" style="display:none">'.$payment->amount.'</spam>'.number_format_invoice_empty($payment->amount).'</td>	
					<td style=";text-align:right"></td>	
					<td>'.$hold_status.'</td>	
				</tr>';
			}

			$page_title = 'Payments';

		}

		echo json_encode(array('page_title' => $page_title, 'output' => $output ));


	}
}