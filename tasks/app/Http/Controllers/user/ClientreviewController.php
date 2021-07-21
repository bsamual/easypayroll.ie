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
class ClientreviewController extends Controller {

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
	public function client_account_review()
	{
		return view('user/client_review/client_account_review', array('title' => 'Client Account Review'));
	}
	public function client_review_commonclient_search()
	{
		$value = Input::get('term');
		$details = DB::table('cm_clients')->Where('client_id','like','%'.$value.'%')->orWhere('company','like','%'.$value.'%')->Where('status', 0)->get();

		$data=array();
		foreach ($details as $single) {
			if($single->company != "")
			{
				$company = $single->company;
			}
			else{
				$company = $single->firstname.' '.$single->surname;
			}
                $data[]=array('value'=>$company.'-'.$single->client_id,'id'=>$single->client_id);

        }
         if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];

	}
	public function client_review_client_select()
	{

		$client_id = Input::get('client_id');
		$cro_informations = DB::table('croard')->where('client_id',$client_id)->first();
		$cm_details = DB::table('cm_clients')->where('client_id',$client_id)->first();

		if(count($cm_details))
		{
			if(count($cro_informations))
			{
				$croard_val = $cro_informations->cro_ard;
			}
			else{
				$croard_val = '';
			}
			$client_details = '<div class="col-md-4">Client Name: </div>
			<div class="col-md-8" style="font-weight:600">'.$cm_details->company.'</div>
			<div class="col-md-4">Address: </div>
			<div class="col-md-8" style="font-weight:600">';
		        if($cm_details->address1 != ''){
		          $client_details.=$cm_details->address1.'<br/>';
		        }
		        if($cm_details->address2 != ''){
		          $client_details.=$cm_details->address2.'<br/>';
		        }
		        if($cm_details->address3 != ''){
		          $client_details.=$cm_details->address3.'<br/>';
		        }
		        if($cm_details->address4 != ''){
		          $client_details.=$cm_details->address4.'<br/>';
		        }
		        if($cm_details->address5 != ''){
		          $client_details.=$cm_details->address5.'<br/>';
		        }
		    $client_details.='</div>
		    <div class="col-md-4">Email: </div>
			<div class="col-md-8" style="font-weight:600">'.$cm_details->email.'</div>';

			$data['client_details'] = $client_details;
			$data['client_email'] = $cm_details->email;

			$data['company'] = $cm_details->company;
			$data['cro'] = $cm_details->cro;
			$data['ard'] = $cm_details->ard;
			$data['type'] = $cm_details->tye;
			$data['cro_ard'] = $croard_val;

			$invoice_year = DB::select('SELECT *,SUBSTR(`invoice_date`, 1, 4) as `invoice_year` from `invoice_system` WHERE client_id = "'.$client_id.'" GROUP BY SUBSTR(`invoice_date`, 1, 4) ORDER BY SUBSTR(`invoice_date`, 1, 4) ASC');
			$output_year = '<option value="">Select Year</option>';
			if(count($invoice_year))
			{
				foreach($invoice_year as $year)
				{
					$output_year.='<option value="'.$year->invoice_year.'">'.$year->invoice_year.'</option>';
				}
			}
			$data['invoice_year'] = $output_year;

			$receipt_year = DB::select('SELECT *,SUBSTR(`receipt_date`, 1, 4) as `receipt_year` from `receipts` WHERE `client_code` LIKE "%'.$client_id.'%" AND `credit_nominal` = "712" AND `imported` = "0" AND `status` = "0" GROUP BY SUBSTR(`receipt_date`, 1, 4) ORDER BY SUBSTR(`receipt_date`, 1, 4) ASC');
			$output_receipt_year = '<option value="">Select Year</option>';
			if(count($receipt_year))
			{
				foreach($receipt_year as $year)
				{
					$output_receipt_year.='<option value="'.$year->receipt_year.'">'.$year->receipt_year.'</option>';
				}
			}
			$data['receipt_year'] = $output_receipt_year;

			echo json_encode($data);
		}
	}
	public function update_cro_ard_date()
	{
		$client_id = Input::get('client_id');
		$cro_ard = Input::get('cro_ard');

		$data['ard'] = $cro_ard;
		DB::table('cm_clients')->where('client_id',$client_id)->update($data);
	}
	public function client_review_load_all_client_invoice()
	{
		$client_id = Input::get('client_id');
		$type = Input::get('type');
		$client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
		if($type == "1")
		{
			$year = Input::get('year');
			$invoicelist = DB::select('SELECT * from `invoice_system` WHERE client_id = "'.$client_id.'" AND `invoice_date` LIKE "'.$year.'%"');

			$margin_top = 'margin-top:-3px !important;';
		}
		elseif($type == "2")
		{
			$invoicelist = DB::table('invoice_system')->where('client_id', $client_details->client_id)->get();
			$margin_top = 'margin-top:40px !important;';
		}
		elseif($type == "3")
		{
			$exp_from = explode("/",Input::get('from'));
			$exp_to = explode("/",Input::get('to'));

			$from = $exp_from[2].'-'.$exp_from[1].'-'.$exp_from[0];
			$to = $exp_to[2].'-'.$exp_to[1].'-'.$exp_to[0];

			$invoicelist = DB::table('invoice_system')->where('client_id', $client_details->client_id)->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->get();

			$margin_top = 'margin-top:-3px !important;';
		}

		$outputinvoice = '<div style="width:100%;position:absolute; '.$margin_top.'">
			<p style="position: relative;bottom: 0px;"><input type="checkbox" name="select_all_invoice" class="select_all_invoice" id="select_all_invoice" value=""><label for="select_all_invoice">Select All</label></p>
			<table class="display nowrap fullviewtablelist own_table_white" id="invoice_expand" width="100%">
                <thead>
                  <tr style="background: #fff;">
                      <th style="text-align: left;">S.No <i class="fa fa-sort sort_sno"></i></th>
                      <th style="text-align: left;">Invoice # <i class="fa fa-sort sort_invoice"></i></th>
                      <th style="text-align: left;">Date <i class="fa fa-sort sort_date"></i></th>
                      <th style="text-align: right;">Net <i class="fa fa-sort sort_net"></i></th>
                      <th style="text-align: right;">VAT <i class="fa fa-sort sort_vat"></i></th>
                      <th style="text-align: right;">Gross <i class="fa fa-sort sort_gross"></i></th>                      
                  </tr>
                </thead>                            
                <tbody id="invoice_tbody">';
		$i=1;
		$total_net = 0;
		$total_vat = 0;
		$total_gross = 0;
		if(count($invoicelist)){ 
			foreach($invoicelist as $invoice){ 
				$client_details = DB::table('cm_clients')->where('client_id', $invoice->client_id)->first();
				if($invoice->statement == "No"){
					$textcolor="color:#f00";
				}
				else{
					$textcolor="color:#00751a";
				}

				$outputinvoice.='
					<tr>
						<td><spam class="sno_sort_val">'.$i.'</spam> <input type="checkbox" name="invoice_check" class="invoice_check" data-element="'.$invoice->invoice_number.'" id="invoice_id_'.$invoice->invoice_number.'"> <label for="invoice_id_'.$invoice->invoice_number.'">&nbsp;</label></td>
						<td align="left" style="'.$textcolor.'"><a href="javascript:" class="invoice_inside_class invoice_sort_val" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>
						<td align="left" style="'.$textcolor.'"><spam class="date_sort_val" style="display:none">'.strtotime($invoice->invoice_date).'</spam>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
						<td align="right" style="'.$textcolor.'"><spam class="net_sort_val" style="display:none">'.$invoice->inv_net.'</spam>'.number_format_invoice($invoice->inv_net).'</td>
						<td align="right" style="'.$textcolor.'"><spam class="vat_sort_val" style="display:none">'.$invoice->vat_value.'</spam>'.number_format_invoice($invoice->vat_value).'</td>
						<td align="right" style="'.$textcolor.'"><spam class="gross_sort_val" style="display:none">'.$invoice->gross.'</spam>'.number_format_invoice($invoice->gross).'</td>						
					</tr>
				';

				$total_net = $total_net + $invoice->inv_net;
				$total_vat = $total_vat + $invoice->vat_value;
				$total_gross = $total_gross + $invoice->gross;
				$i++;
			}		
			$outputinvoice.='
			</tbody>
			<tbody>
					<tr>
						<td colspan="3">Total</td>
						<td align="right" style="'.$textcolor.'">'.number_format_invoice($total_net).'</td>
						<td align="right" style="'.$textcolor.'">'.number_format_invoice($total_vat).'</td>
						<td align="right" style="'.$textcolor.'">'.number_format_invoice($total_gross).'</td>						
					</tr>
				';		
		}

		if($i == 1)
        {
          $outputinvoice.='<tr>
          	<td></td>
          	<td></td>
          	<td>Empty</td>
          	<td align="right"></td>
          	<td></td>
          	<td></td>
          </tr>';
        }

        $outputinvoice.='                
                </tbody>
            </table>
            </div>';

        echo json_encode(array('invoiceoutput' => $outputinvoice));

	}
	public function client_review_load_all_client_receipt()
	{
		$client_id = Input::get('client_id');
		$type = Input::get('type');
		$client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
		if($type == "1")
		{
			$year = Input::get('year');
			$receiptlist = DB::select('SELECT * from `receipts` WHERE client_code LIKE "%'.$client_id.'%" AND credit_nominal = "712" AND imported = "0" AND status = "0" AND `receipt_date` LIKE "'.$year.'%"');
			$margin_top = 'margin-top:48px !important;';
		}
		elseif($type == "2")
		{
			$receiptlist = DB::table('receipts')->where('client_code', 'like', '%'.$client_details->client_id.'%')->where('credit_nominal','712')->where('imported',0)->where('status',0)->get();
			$margin_top = 'margin-top:102px !important;';
		}
		elseif($type == "3")
		{
			$exp_from = explode("/",Input::get('from'));
			$exp_to = explode("/",Input::get('to'));

			$from = $exp_from[2].'-'.$exp_from[1].'-'.$exp_from[0];
			$to = $exp_to[2].'-'.$exp_to[1].'-'.$exp_to[0];

			$receiptlist = DB::table('receipts')->where('client_code','like', '%'.$client_details->client_id.'%')->where('credit_nominal','712')->where('receipt_date','>=',$from)->where('receipt_date','<=',$to)->where('imported',0)->where('status',0)->get();
			$margin_top = 'margin-top:45px !important;';
		}

		$outputreceipt = '<table class="display nowrap fullviewtablelist own_table_white" id="receipt_expand" width="100%" style="position:absolute; '.$margin_top.'">
                <thead>
                  <tr style="background: #fff;">
                      <th style="text-align: left;">Date <i class="fa fa-sort sort_receipt_date"></i></th>
                      <th style="text-align: right;">Amount <i class="fa fa-sort sort_amount"></i></th>                   
                  </tr>
                </thead>                            
                <tbody id="receipt_tbody">';
		$i=1;
		$total_amount = 0;
		if(count($receiptlist)){ 
			foreach($receiptlist as $receipt){ 
				$client_details = DB::table('cm_clients')->where('client_id', $receipt->client_code)->first();
				

				$outputreceipt.='
					<tr>
						<td align="left"><spam class="receipt_date_sort_val" style="display:none">'.strtotime($receipt->receipt_date).'</spam>'.date('d-M-Y', strtotime($receipt->receipt_date)).'</td>
						<td align="right" class="amount_sort_val">'.number_format_invoice_empty($receipt->amount).'</td>
					</tr>
				';

				$total_amount = $total_amount + $receipt->amount;
				$i++;
			}		
			$outputreceipt.='
			</tbody>
			<tbody>
			<tr>
				<td>Total</td>
				<td align="right">'.number_format_invoice_empty($total_amount).'</td>						
			</tr>';		
		}

		if($i == 1)
        {
          $outputreceipt.='<tr>
          	<td>Empty</td>
          	<td align="right"></td>
          </tr>';
        }

        $outputreceipt.='                
                </tbody>
            </table>';

        echo json_encode(array('receiptoutput' => $outputreceipt));

	}
	public function invoice_email_selected_pdfs()
	{
		$ids = explode(',',Input::get('ids'));
		$pdfsname = '';
		if(count($ids) == 1)
		{
			$html_main = '<style>
				@page { margin: 0in; }
			    body {
			        background-image: url('.URL::to('assets/invoice_letterpad_1.jpg').');
			        background-position: top left right bottom;
				    background-repeat: no-repeat;
				    font-family: Verdana,Geneva,sans-serif; 
			    }
			    .tax_table_div{width: 100%; margin-top:-30px}
	            .tax_table{margin-left:73%;width: 20%;}
	            .details_table .class_row .class_row_td { font-size: 14px; float:left; }
	            .details_table .class_row .class_row_td.left{position:absolute; width:70%; line-height:20px;  text-align: left;  font-size:14px; }
	            .details_table .class_row .class_row_td.left_corner{position:absolute; margin-left:71%; width:10%; line-height:20px;  text-align: right;}
	            .details_table .class_row .class_row_td.right_start{position:absolute; margin-left:81%; width:9%; line-height:20px;  text-align: right;}
	            .details_table .class_row .class_row_td.right{position:absolute;line-height:20px; margin-left:90%; text-align: right; font-size:14px; width:10%;}
	            .details_table .class_row{line-height: 30px; clear:both}
	            .details_table { height : 420px !important; }
	            .class_row{width: 100%; clear:both; height:20px:}
	            .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;float:left;}
	            .tax_table .tax_row .tax_row_td.left{position:absolute; left:80px; width:600px; text-align: left; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
	            .tax_table .tax_row .tax_row_td.right{{margin-left:605px;text-align: right; padding-right: 20px;border-top: 2px solid #000;}
	            .tax_table .tax_row{line-height: 30px;}
	            .company_details_class{width:100%; height:auto; }
	            .account_details_main_address_div{width:200px; margin-top:-100px;  float:left; margin-left:550px;}
	            .account_details_invoice_div{width:200px; }
	            .company_details_div{width: 400px; margin-top: 220px; height:75px; float:left; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
	            .firstname_div{position:absolute; width:300px; left:80px; right:80px; margin-top: 90px; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
	            .aib_account{ color: #ccc; font-family: Verdana,Geneva,sans-serif; font-size: 12px; width:200px;  }
	            .account_details_div{width: 400px; font-family: Verdana,Geneva,sans-serif; font-size:14px; line-height:20px; margin-top:40px;}
	            .account_details_address_div{position:absolute; left:80px; width:250px; margin-top:60px; }
	            .account_table .account_row .account_row_td.left{margin-left:0px;}
	            .account_table .account_row .account_row_td.right{margin-left:100px;padding-top:-18px;}
	            .invoice_label{ width: 100%; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px; }
	            .tax_details_class_maindiv{width: 100%; float: left;}
			</style>';
			foreach($ids as $id)
			{
				$invoice_details = DB::table('invoice_system')->where('invoice_number', $id)->first();
				$client_details = DB::table('cm_clients')->where('client_id', $invoice_details->client_id)->first();
				if(count($client_details) == ''){
					$companyname = '<div style="width: 100%; height: auto; float: left; margin: 200px 0px 0px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 0px;">Company Details not found</div>';
					$companyname = $company_firstname;
					$taxdetails = '';
					$row1 = '';
					$row2 = ''; 
					$row3 = ''; 
					$row4 = ''; 
					$row5 = ''; 
					$row6 = ''; 
					$row7 = ''; 
					$row8 = '';
					$row9 = ''; 
					$row10 = ''; 
					$row11 = ''; 
					$row12 = ''; 
					$row13 = ''; 
					$row14 = ''; 
					$row15 = ''; 
					$row16 = ''; 
					$row17 = ''; 
					$row18 = ''; 
					$row19 = ''; 
					$row20 = '';
				}
				else{
					$company_firstname='
	          		<div class="company_details_div">
		              <div class="firstname_div">
		                <b>To:</b><br/>
		                '.$client_details->firstname.' '.$client_details->surname.'<br/>  
		                '.$client_details->company.'<br/>  
		                '.$client_details->address1.'<br/>  
		                '.$client_details->address2.'<br/>
		                '.$client_details->address3.'
		              </div>
		            </div>
		            <div class="account_details_div">
		              <div class="account_details_main_address_div">
		              		<div class="aib_account">
				                AIB Account: 48870061<br/>
				                Sort Code: 93-72-23<br/>
				                VAT Number: 9754009E<br/>
				                Company Number: 485123
				            </div>	                 
		                  <div class="account_details_invoice_div">
		                    <div class="account_table">
		                      <div class="account_row">
		                        <div class="account_row_td left"><b>Account:</b></div>
		                        <div class="account_row_td right">'.$client_details->client_id.'</div>
		                      </div>
		                      <div class="account_row">
		                        <div class="account_row_td left"><b>Invoice:</b></div>
		                        <div class="account_row_td right">'.$invoice_details->invoice_number.'</div>
		                      </div>
		                      <div class="account_row">
		                        <div class="account_row_td left"><b>Date:</b></div>
		                        <div class="account_row_td right">'.date('d-M-Y',strtotime($invoice_details->invoice_date)).'</div>
		                      </div>
		                    </div>
		                  </div>
		              </div>
		            </div>
		            <div class="invoice_label">
		              INVOICE
		            </div>';
		            if($invoice_details->bn_row1 != "")
		            {
		            	$bn_row1_add_zero = number_format_invoice($invoice_details->bn_row1);
		            }
		            else{
		            	$bn_row1_add_zero = '';
		            }
	            	$row1 = '
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->f_row1).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->z_row1.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->at_row1.'</div>
	                  <div class="class_row_td right">'.$bn_row1_add_zero.'</div>';

					if($invoice_details->bo_row2 != "")
					{
						$bo_row2_add_zero = number_format_invoice($invoice_details->bo_row2);
					}
					else{
						$bo_row2_add_zero = '';
					}

	            	$row2 = '<div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->g_row2).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->aa_row2.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->au_row2.'</div>
	                  <div class="class_row_td right">'.$bo_row2_add_zero.'</div>';
		            if($invoice_details->bp_row3 != "")
		            {
		            	$bp_row3_add_zero = number_format_invoice($invoice_details->bp_row3);
		            }
		            else{
		            	$bp_row3_add_zero = '';
		            }
	            	$row3 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->h_row3).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ab_row3.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->av_row3.'</div>
	                  <div class="class_row_td right">'.$bp_row3_add_zero.'</div>';
		            if($invoice_details->bq_row4 != "")
		            {
		            	$bq_row4_add_zero = number_format_invoice($invoice_details->bq_row4);
		            }
		            else{
		            	$bq_row4_add_zero = '';
		            }
	            	$row4 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->i_row4).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ac_row4.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->aw_row4.'</div>
	                  <div class="class_row_td right">'.$bq_row4_add_zero.'</div>';
					if($invoice_details->br_row5 != "")
					{
						$br_row5_add_zero = number_format_invoice($invoice_details->br_row5);
					}
					else{
						$br_row5_add_zero = '';
					}
	            	$row5 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->j_row5).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ad_row5.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->ax_row5.'</div>
	                  <div class="class_row_td right">'.$br_row5_add_zero.'</div>';
		            if($invoice_details->bs_row6 != "")
		            {
		            	$bs_row6_add_zero = number_format_invoice($invoice_details->bs_row6);
		            }
		            else{
		            	$bs_row6_add_zero = '';
		            }
	            	$row6 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->k_row6).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ae_row6.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->ay_row6.'</div>
	                  <div class="class_row_td right">'.$bs_row6_add_zero.'</div>';
		            if($invoice_details->bt_row7 != "")
		            {
		            	$bt_row7_add_zero = number_format_invoice($invoice_details->bt_row7);
		            }
		            else{
		            	$bt_row7_add_zero = '';
		            }
	            	$row7 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->l_row7).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->af_row7.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->az_row7.'</div>
	                  <div class="class_row_td right">'.$bt_row7_add_zero.'</div>';
		            if($invoice_details->bu_row8 != "")
		            {
		            	$bu_row8_add_zero = number_format_invoice($invoice_details->bu_row8);
		            }
		            else{
		            	$bu_row8_add_zero = '';
		            }
	            	$row8 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->m_row8).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ag_row8.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->ba_row8.'</div>
	                  <div class="class_row_td right">'.$bu_row8_add_zero.'</div>';
		            if($invoice_details->bv_row9 != "")
		            {
		            	$bv_row9_add_zero = number_format_invoice($invoice_details->bv_row9);
		            }
		            else{
		            	$bv_row9_add_zero = '';
		            }
	            	$row9 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->n_row9).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ah_row9.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->bb_row9.'</div>
	                  <div class="class_row_td right">'.$bv_row9_add_zero.'</div>';
					if($invoice_details->bw_row10 != "")
					{
						$bw_row10_add_zero = number_format_invoice($invoice_details->bw_row10);
					}
					else{
						$bw_row10_add_zero = '';
					}
	            	$row10 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->o_row10).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ai_row10.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->bc_row10.'</div>
	                  <div class="class_row_td right">'.$bw_row10_add_zero.'</div>';
		            if($invoice_details->bx_row11 != "")
		            {
		            	$bx_row11_add_zero = number_format_invoice($invoice_details->bx_row11);
		            }
		            else{
		            	$bx_row11_add_zero = '';
		            }
		            	$row11 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->p_row11).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->aj_row11.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bd_row11.'</div>
		                  <div class="class_row_td right">'.$bx_row11_add_zero.'</div>';
		           if($invoice_details->by_row12 != "")
		           {
		           	$by_row12_add_zero = number_format_invoice($invoice_details->by_row12);
		           }
		           else{
		           	$by_row12_add_zero = '';
		           }
		            	$row12 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->q_row12).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->ak_row12.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->be_row12.'</div>
		                  <div class="class_row_td right">'.$by_row12_add_zero.'</div>';
		            if($invoice_details->bz_row13 != "")
		            {
		            	$bz_row13_add_zero = number_format_invoice($invoice_details->bz_row13);
		            }
		            else{
		            	$bz_row13_add_zero = '';
		            }
		            	$row13 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->r_row13).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->al_row13.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bf_row13.'</div>
		                  <div class="class_row_td right">'.$bz_row13_add_zero.'</div>';
		            if($invoice_details->ca_row14 != "")
		            {
		            	$ca_row14_add_zero = number_format_invoice($invoice_details->ca_row14);
		            }
		            else{
		            	$ca_row14_add_zero = '';
		            }
		            	$row14 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->s_row14).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->am_row14.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bg_row14.'</div>
		                  <div class="class_row_td right">'.$ca_row14_add_zero.'</div>';
		            if($invoice_details->cb_row15 != "")
		            {
		            	$cb_row15_add_zero = number_format_invoice($invoice_details->cb_row15);
		            }
		            else{
		            	$cb_row15_add_zero = '';
		            }
		            	$row15 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->t_row15).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->an_row15.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bh_row15.'</div>
		                  <div class="class_row_td right">'.$cb_row15_add_zero.'</div>';
		           if($invoice_details->cc_row16 != "")
		           {
		           	$cc_row16_add_zero = number_format_invoice($invoice_details->cc_row16);
		           }
		           else{
		           	$cc_row16_add_zero = '';
		           }
		            	$row16 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->u_row16).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->ao_row16.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bi_row16.'</div>
		                  <div class="class_row_td right">'.$cc_row16_add_zero.'</div>';
		            if($invoice_details->cd_row17 != "")
		            {
		            	$cd_row17_add_zero = number_format_invoice($invoice_details->cd_row17);
		            }
		            else{
		            	$cd_row17_add_zero = '';
		            }
		            	$row17 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->v_row17).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->ap_row17.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bj_row17.'</div>
		                  <div class="class_row_td right">'.$cd_row17_add_zero.'</div>';
		           if($invoice_details->ce_row18 != "")
		           {
		           	$ce_row18_add_zero = number_format_invoice($invoice_details->ce_row18);
		           }
		           else{
		           	$ce_row18_add_zero = '';
		           }
		            	$row18 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->w_row18).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->aq_row18.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bk_row18.'</div>
		                  <div class="class_row_td right">'.$ce_row18_add_zero.'</div>';
		            if($invoice_details->cf_row19 != "")
		            {
		            	$cf_row19_add_zero = number_format_invoice($invoice_details->cf_row19);
		            }
		            else{
		            	$cf_row19_add_zero = '';
		            }
		            	$row19 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->x_row19).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->ar_row19.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bl_row19.'</div>
		                  <div class="class_row_td right">'.$cf_row19_add_zero.'</div>';
		            if($invoice_details->cg_row20 != "")
		            {
		            	$cg_row20_add_zero = number_format_invoice($invoice_details->cg_row20);
		            }
		            else{
		            	$cg_row20_add_zero = '';
		            }
		            	$row20 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->y_row20).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->as_row20.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bm_row20.'</div>
		                  <div class="class_row_td right">'.$cg_row20_add_zero.'</div>';
			         $tax_details ='
			         <div class="tax_table_div">
			            <div class="tax_table">
			              <div class="tax_row">
			                <div class="tax_row_td left">Total Fees (as agreed)</div>
			                <div class="tax_row_td right" width="13%">'.number_format_invoice($invoice_details->inv_net).'</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left">VAT @ 23%</div>
			                <div class="tax_row_td right" style="border-top:0px;">'.number_format_invoice($invoice_details->vat_value).'</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left" style="color:#fff">.</div>
			                <div class="tax_row_td right">'.number_format_invoice($invoice_details->gross).'</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left">Outlay @ 0%</div>
			                <div class="tax_row_td right" style="border-top:0px;" style="color:#fff">..</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left">Total Fees Due</div>
			                <div class="tax_row_td right" style="border-bottom: 2px solid #000">'.number_format_invoice($invoice_details->gross).'</div>
			              </div>
			            </div>
			          </div>
			         ';
			         $companyname = $company_firstname;
			         $taxdetails = $tax_details;
			         $row1 = $row1;
			         $row2 = $row2; 
			         $row3 = $row3; 
			         $row4 = $row4; 
			         $row5 = $row5; 
			         $row6 = $row6; 
			         $row7 = $row7; 
			         $row8 = $row8;
			         $row9 = $row9; 
			         $row10 = $row10; 
			         $row11 = $row11; 
			         $row12 = $row12; 
			         $row13 = $row13; 
			         $row14 = $row14; 
			         $row15 = $row15; 
			         $row16 = $row16; 
			         $row17 = $row17; 
			         $row18 = $row18; 
			         $row19 = $row19; 
			         $row20 = $row20;     
			    }
				$output = '<div class="company_details_class">'.$companyname.'</div>
	            <div class="tax_details_class_maindiv">
	              <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
	                <div class="class_row class_row1">'.$row1.'</div>
	                <div class="class_row class_row2">'.$row2.'</div>
	                <div class="class_row class_row3">'.$row3.'</div>
	                <div class="class_row class_row4">'.$row4.'</div>
	                <div class="class_row class_row5">'.$row5.'</div>
	                <div class="class_row class_row6">'.$row6.'</div>
	                <div class="class_row class_row7">'.$row7.'</div>
	                <div class="class_row class_row8">'.$row8.'</div>
	                <div class="class_row class_row9">'.$row9.'</div>
	                <div class="class_row class_row10">'.$row10.'</div>
	                <div class="class_row class_row11">'.$row11.'</div>
	                <div class="class_row class_row12">'.$row12.'</div>
	                <div class="class_row class_row13">'.$row13.'</div>
	                <div class="class_row class_row14">'.$row14.'</div>
	                <div class="class_row class_row15">'.$row15.'</div>
	                <div class="class_row class_row16">'.$row16.'</div>
	                <div class="class_row class_row17">'.$row17.'</div>
	                <div class="class_row class_row18">'.$row18.'</div>
	                <div class="class_row class_row19">'.$row19.'</div>
	                <div class="class_row class_row20">'.$row20.'</div>
	              </div>
	            </div>
	            <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
	            <div class="tax_details_class">'.$taxdetails.'</div>';


	            $html = $html_main.$output;
				$pdf = PDF::loadHTML($html);
				$pdf->save('papers/'.time().'_Invoice of '.$id.'.pdf');

				$zipFileName = time().'_Invoice of '.$id.'.pdf';
				$pdfsname = '<p>'.$zipFileName.'</p>';
			}
		}
		else{
			$html_main = '<style>
				@page { margin: 0in; }
			    body {
			        background-image: url('.URL::to('assets/invoice_letterpad_1.jpg').');
			        background-position: top left right bottom;
				    background-repeat: no-repeat;
				    font-family: Verdana,Geneva,sans-serif; 
			    }
			    .tax_table_div{width: 100%; margin-top:-30px}
	            .tax_table{margin-left:73%;width: 20%;}
	            .details_table .class_row .class_row_td { font-size: 14px; float:left; }
	            .details_table .class_row .class_row_td.left{position:absolute; width:70%; line-height:20px;  text-align: left;  font-size:14px; }
	            .details_table .class_row .class_row_td.left_corner{position:absolute; margin-left:71%; width:10%; line-height:20px;  text-align: right;}
	            .details_table .class_row .class_row_td.right_start{position:absolute; margin-left:81%; width:9%; line-height:20px;  text-align: right;}
	            .details_table .class_row .class_row_td.right{position:absolute;line-height:20px; margin-left:90%; text-align: right; font-size:14px; width:10%;}
	            .details_table .class_row{line-height: 30px; clear:both}
	            .details_table { height : 420px !important; }
	            .class_row{width: 100%; clear:both; height:20px:}
	            .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;float:left;}
	            .tax_table .tax_row .tax_row_td.left{position:absolute; left:80px; width:600px; text-align: left; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
	            .tax_table .tax_row .tax_row_td.right{{margin-left:605px;text-align: right; padding-right: 20px;border-top: 2px solid #000;}
	            .tax_table .tax_row{line-height: 30px;}
	            .company_details_class{width:100%; height:auto; }
	            .account_details_main_address_div{width:200px; margin-top:-100px;  float:left; margin-left:550px;}
	            .account_details_invoice_div{width:200px; }
	            .company_details_div{width: 400px; margin-top: 220px; height:75px; float:left; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
	            .firstname_div{position:absolute; width:300px; left:80px; right:80px; margin-top: 90px; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
	            .aib_account{ color: #ccc; font-family: Verdana,Geneva,sans-serif; font-size: 12px; width:200px;  }
	            .account_details_div{width: 400px; font-family: Verdana,Geneva,sans-serif; font-size:14px; line-height:20px; margin-top:40px;}
	            .account_details_address_div{position:absolute; left:80px; width:250px; margin-top:60px; }
	            .account_table .account_row .account_row_td.left{margin-left:0px;}
	            .account_table .account_row .account_row_td.right{margin-left:100px;padding-top:-18px;}
	            .invoice_label{ width: 100%; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px; }
	            .tax_details_class_maindiv{width: 100%; float: left;}
			</style>';
			$time = time();
			$zipFileName = $time.'.zip';
	        $zip = new ZipArchive;
	        if ($zip->open('papers/'.$zipFileName, ZipArchive::CREATE) === TRUE) {
				if(count($ids))
				{
					foreach($ids as $id)
					{
						$invoice_details = DB::table('invoice_system')->where('invoice_number', $id)->first();
						$client_details = DB::table('cm_clients')->where('client_id', $invoice_details->client_id)->first();
						if(count($client_details) == ''){
							$companyname = '<div style="width: 100%; height: auto; float: left; margin: 200px 0px 0px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 0px;">Company Details not found</div>';
							
							$companyname = $company_firstname;
							$taxdetails = '';
							$row1 = '';
							$row2 = ''; 
							$row3 = ''; 
							$row4 = ''; 
							$row5 = ''; 
							$row6 = ''; 
							$row7 = ''; 
							$row8 = '';
							$row9 = ''; 
							$row10 = ''; 
							$row11 = ''; 
							$row12 = ''; 
							$row13 = ''; 
							$row14 = ''; 
							$row15 = ''; 
							$row16 = ''; 
							$row17 = ''; 
							$row18 = ''; 
							$row19 = ''; 
							$row20 = '';
						}
						else{
							$company_firstname='
			          		<div class="company_details_div">
				              <div class="firstname_div">
				                <b>To:</b><br/>
				                '.$client_details->firstname.' '.$client_details->surname.'<br/>  
				                '.$client_details->company.'<br/>  
				                '.$client_details->address1.'<br/>  
				                '.$client_details->address2.'<br/>
				                '.$client_details->address3.'
				              </div>
				            </div>
				            <div class="account_details_div">
				              <div class="account_details_main_address_div">
				              		<div class="aib_account">
						                AIB Account: 48870061<br/>
						                Sort Code: 93-72-23<br/>
						                VAT Number: 9754009E<br/>
						                Company Number: 485123
						            </div>	                 
				                  <div class="account_details_invoice_div">
				                    <div class="account_table">
				                      <div class="account_row">
				                        <div class="account_row_td left"><b>Account:</b></div>
				                        <div class="account_row_td right">'.$client_details->client_id.'</div>
				                      </div>
				                      <div class="account_row">
				                        <div class="account_row_td left"><b>Invoice:</b></div>
				                        <div class="account_row_td right">'.$invoice_details->invoice_number.'</div>
				                      </div>
				                      <div class="account_row">
				                        <div class="account_row_td left"><b>Date:</b></div>
				                        <div class="account_row_td right">'.date('d-M-Y',strtotime($invoice_details->invoice_date)).'</div>
				                      </div>
				                    </div>
				                  </div>
				              </div>
				            </div>
				            <div class="invoice_label">
				              INVOICE
				            </div>';
				            if($invoice_details->bn_row1 != "")
				            {
				            	$bn_row1_add_zero = number_format_invoice($invoice_details->bn_row1);
				            }
				            else{
				            	$bn_row1_add_zero = '';
				            }
			            	$row1 = '
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->f_row1).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->z_row1.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->at_row1.'</div>
			                  <div class="class_row_td right">'.$bn_row1_add_zero.'</div>';

							if($invoice_details->bo_row2 != "")
							{
								$bo_row2_add_zero = number_format_invoice($invoice_details->bo_row2);
							}
							else{
								$bo_row2_add_zero = '';
							}

			            	$row2 = '<div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->g_row2).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->aa_row2.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->au_row2.'</div>
			                  <div class="class_row_td right">'.$bo_row2_add_zero.'</div>';
				            if($invoice_details->bp_row3 != "")
				            {
				            	$bp_row3_add_zero = number_format_invoice($invoice_details->bp_row3);
				            }
				            else{
				            	$bp_row3_add_zero = '';
				            }
			            	$row3 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->h_row3).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ab_row3.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->av_row3.'</div>
			                  <div class="class_row_td right">'.$bp_row3_add_zero.'</div>';
				            if($invoice_details->bq_row4 != "")
				            {
				            	$bq_row4_add_zero = number_format_invoice($invoice_details->bq_row4);
				            }
				            else{
				            	$bq_row4_add_zero = '';
				            }
			            	$row4 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->i_row4).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ac_row4.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->aw_row4.'</div>
			                  <div class="class_row_td right">'.$bq_row4_add_zero.'</div>';
							if($invoice_details->br_row5 != "")
							{
								$br_row5_add_zero = number_format_invoice($invoice_details->br_row5);
							}
							else{
								$br_row5_add_zero = '';
							}
			            	$row5 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->j_row5).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ad_row5.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->ax_row5.'</div>
			                  <div class="class_row_td right">'.$br_row5_add_zero.'</div>';
				            if($invoice_details->bs_row6 != "")
				            {
				            	$bs_row6_add_zero = number_format_invoice($invoice_details->bs_row6);
				            }
				            else{
				            	$bs_row6_add_zero = '';
				            }
			            	$row6 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->k_row6).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ae_row6.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->ay_row6.'</div>
			                  <div class="class_row_td right">'.$bs_row6_add_zero.'</div>';
				            if($invoice_details->bt_row7 != "")
				            {
				            	$bt_row7_add_zero = number_format_invoice($invoice_details->bt_row7);
				            }
				            else{
				            	$bt_row7_add_zero = '';
				            }
			            	$row7 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->l_row7).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->af_row7.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->az_row7.'</div>
			                  <div class="class_row_td right">'.$bt_row7_add_zero.'</div>';
				            if($invoice_details->bu_row8 != "")
				            {
				            	$bu_row8_add_zero = number_format_invoice($invoice_details->bu_row8);
				            }
				            else{
				            	$bu_row8_add_zero = '';
				            }
			            	$row8 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->m_row8).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ag_row8.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->ba_row8.'</div>
			                  <div class="class_row_td right">'.$bu_row8_add_zero.'</div>';
				            if($invoice_details->bv_row9 != "")
				            {
				            	$bv_row9_add_zero = number_format_invoice($invoice_details->bv_row9);
				            }
				            else{
				            	$bv_row9_add_zero = '';
				            }
			            	$row9 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->n_row9).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ah_row9.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->bb_row9.'</div>
			                  <div class="class_row_td right">'.$bv_row9_add_zero.'</div>';
							if($invoice_details->bw_row10 != "")
							{
								$bw_row10_add_zero = number_format_invoice($invoice_details->bw_row10);
							}
							else{
								$bw_row10_add_zero = '';
							}
			            	$row10 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->o_row10).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ai_row10.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->bc_row10.'</div>
			                  <div class="class_row_td right">'.$bw_row10_add_zero.'</div>';
				            if($invoice_details->bx_row11 != "")
				            {
				            	$bx_row11_add_zero = number_format_invoice($invoice_details->bx_row11);
				            }
				            else{
				            	$bx_row11_add_zero = '';
				            }
				            	$row11 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->p_row11).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->aj_row11.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bd_row11.'</div>
				                  <div class="class_row_td right">'.$bx_row11_add_zero.'</div>';
				           if($invoice_details->by_row12 != "")
				           {
				           	$by_row12_add_zero = number_format_invoice($invoice_details->by_row12);
				           }
				           else{
				           	$by_row12_add_zero = '';
				           }
				            	$row12 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->q_row12).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->ak_row12.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->be_row12.'</div>
				                  <div class="class_row_td right">'.$by_row12_add_zero.'</div>';
				            if($invoice_details->bz_row13 != "")
				            {
				            	$bz_row13_add_zero = number_format_invoice($invoice_details->bz_row13);
				            }
				            else{
				            	$bz_row13_add_zero = '';
				            }
				            	$row13 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->r_row13).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->al_row13.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bf_row13.'</div>
				                  <div class="class_row_td right">'.$bz_row13_add_zero.'</div>';
				            if($invoice_details->ca_row14 != "")
				            {
				            	$ca_row14_add_zero = number_format_invoice($invoice_details->ca_row14);
				            }
				            else{
				            	$ca_row14_add_zero = '';
				            }
				            	$row14 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->s_row14).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->am_row14.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bg_row14.'</div>
				                  <div class="class_row_td right">'.$ca_row14_add_zero.'</div>';
				            if($invoice_details->cb_row15 != "")
				            {
				            	$cb_row15_add_zero = number_format_invoice($invoice_details->cb_row15);
				            }
				            else{
				            	$cb_row15_add_zero = '';
				            }
				            	$row15 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->t_row15).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->an_row15.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bh_row15.'</div>
				                  <div class="class_row_td right">'.$cb_row15_add_zero.'</div>';
				           if($invoice_details->cc_row16 != "")
				           {
				           	$cc_row16_add_zero = number_format_invoice($invoice_details->cc_row16);
				           }
				           else{
				           	$cc_row16_add_zero = '';
				           }
				            	$row16 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->u_row16).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->ao_row16.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bi_row16.'</div>
				                  <div class="class_row_td right">'.$cc_row16_add_zero.'</div>';
				            if($invoice_details->cd_row17 != "")
				            {
				            	$cd_row17_add_zero = number_format_invoice($invoice_details->cd_row17);
				            }
				            else{
				            	$cd_row17_add_zero = '';
				            }
				            	$row17 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->v_row17).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->ap_row17.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bj_row17.'</div>
				                  <div class="class_row_td right">'.$cd_row17_add_zero.'</div>';
				           if($invoice_details->ce_row18 != "")
				           {
				           	$ce_row18_add_zero = number_format_invoice($invoice_details->ce_row18);
				           }
				           else{
				           	$ce_row18_add_zero = '';
				           }
				            	$row18 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->w_row18).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->aq_row18.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bk_row18.'</div>
				                  <div class="class_row_td right">'.$ce_row18_add_zero.'</div>';
				            if($invoice_details->cf_row19 != "")
				            {
				            	$cf_row19_add_zero = number_format_invoice($invoice_details->cf_row19);
				            }
				            else{
				            	$cf_row19_add_zero = '';
				            }
				            	$row19 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->x_row19).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->ar_row19.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bl_row19.'</div>
				                  <div class="class_row_td right">'.$cf_row19_add_zero.'</div>';
				            if($invoice_details->cg_row20 != "")
				            {
				            	$cg_row20_add_zero = number_format_invoice($invoice_details->cg_row20);
				            }
				            else{
				            	$cg_row20_add_zero = '';
				            }
				            	$row20 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->y_row20).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->as_row20.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bm_row20.'</div>
				                  <div class="class_row_td right">'.$cg_row20_add_zero.'</div>';
					         $tax_details ='
					         <div class="tax_table_div">
					            <div class="tax_table">
					              <div class="tax_row">
					                <div class="tax_row_td left">Total Fees (as agreed)</div>
					                <div class="tax_row_td right" width="13%">'.number_format_invoice($invoice_details->inv_net).'</div>
					              </div>
					              <div class="tax_row">
					                <div class="tax_row_td left">VAT @ 23%</div>
					                <div class="tax_row_td right" style="border-top:0px;">'.number_format_invoice($invoice_details->vat_value).'</div>
					              </div>
					              <div class="tax_row">
					                <div class="tax_row_td left" style="color:#fff">.</div>
					                <div class="tax_row_td right">'.number_format_invoice($invoice_details->gross).'</div>
					              </div>
					              <div class="tax_row">
					                <div class="tax_row_td left">Outlay @ 0%</div>
					                <div class="tax_row_td right" style="border-top:0px;" style="color:#fff">..</div>
					              </div>
					              <div class="tax_row">
					                <div class="tax_row_td left">Total Fees Due</div>
					                <div class="tax_row_td right" style="border-bottom: 2px solid #000">'.number_format_invoice($invoice_details->gross).'</div>
					              </div>
					            </div>
					          </div>
					         ';
					         $companyname = $company_firstname;
					         $taxdetails = $tax_details;
					         $row1 = $row1;
					         $row2 = $row2; 
					         $row3 = $row3; 
					         $row4 = $row4; 
					         $row5 = $row5; 
					         $row6 = $row6; 
					         $row7 = $row7; 
					         $row8 = $row8;
					         $row9 = $row9; 
					         $row10 = $row10; 
					         $row11 = $row11; 
					         $row12 = $row12; 
					         $row13 = $row13; 
					         $row14 = $row14; 
					         $row15 = $row15; 
					         $row16 = $row16; 
					         $row17 = $row17; 
					         $row18 = $row18; 
					         $row19 = $row19; 
					         $row20 = $row20;     
					    }
						$output = '<div class="company_details_class">'.$companyname.'</div>
			            <div class="tax_details_class_maindiv">
			              <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
			                <div class="class_row class_row1">'.$row1.'</div>
			                <div class="class_row class_row2">'.$row2.'</div>
			                <div class="class_row class_row3">'.$row3.'</div>
			                <div class="class_row class_row4">'.$row4.'</div>
			                <div class="class_row class_row5">'.$row5.'</div>
			                <div class="class_row class_row6">'.$row6.'</div>
			                <div class="class_row class_row7">'.$row7.'</div>
			                <div class="class_row class_row8">'.$row8.'</div>
			                <div class="class_row class_row9">'.$row9.'</div>
			                <div class="class_row class_row10">'.$row10.'</div>
			                <div class="class_row class_row11">'.$row11.'</div>
			                <div class="class_row class_row12">'.$row12.'</div>
			                <div class="class_row class_row13">'.$row13.'</div>
			                <div class="class_row class_row14">'.$row14.'</div>
			                <div class="class_row class_row15">'.$row15.'</div>
			                <div class="class_row class_row16">'.$row16.'</div>
			                <div class="class_row class_row17">'.$row17.'</div>
			                <div class="class_row class_row18">'.$row18.'</div>
			                <div class="class_row class_row19">'.$row19.'</div>
			                <div class="class_row class_row20">'.$row20.'</div>
			              </div>
			            </div>
			            <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
			            <div class="tax_details_class">'.$taxdetails.'</div>';


			            $html = $html_main.$output;
						$pdf = PDF::loadHTML($html);
						$pdf->save('papers/Invoice of '.$id.'.pdf');

						$zip->addFile('papers/Invoice of '.$id.'.pdf','Invoice of '.$id.'.pdf');

						if($pdfsname == "")
						{
							$pdfsname = '<p>Invoice of '.$id.'.pdf</p>';
						}
						else{
							$pdfsname = $pdfsname.'<p>Invoice of '.$id.'.pdf</p>';
						}
					}
				}
			}
			$zip->close();
		}

		echo json_encode(array("zipfile" => $zipFileName,"pdfs" => $pdfsname));
	}
	public function invoice_download_selected_pdfs()
	{
		$ids = explode(',',Input::get('ids'));
		$pdfsname = '';
		if(count($ids) == 1)
		{
			$html_main = '<style>
				@page { margin: 0in; }
			    body {
			        background-image: url('.URL::to('assets/invoice_letterpad_1.jpg').');
			        background-position: top left right bottom;
				    background-repeat: no-repeat;
				    font-family: Verdana,Geneva,sans-serif; 
			    }
			    .tax_table_div{width: 100%; margin-top:-30px}
	            .tax_table{margin-left:73%;width: 20%;}
	            .details_table .class_row .class_row_td { font-size: 14px; float:left; }
	            .details_table .class_row .class_row_td.left{position:absolute; width:70%; line-height:20px;  text-align: left;  font-size:14px; }
	            .details_table .class_row .class_row_td.left_corner{position:absolute; margin-left:71%; width:10%; line-height:20px;  text-align: right;}
	            .details_table .class_row .class_row_td.right_start{position:absolute; margin-left:81%; width:9%; line-height:20px;  text-align: right;}
	            .details_table .class_row .class_row_td.right{position:absolute;line-height:20px; margin-left:90%; text-align: right; font-size:14px; width:10%;}
	            .details_table .class_row{line-height: 30px; clear:both}
	            .details_table { height : 420px !important; }
	            .class_row{width: 100%; clear:both; height:20px:}
	            .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;float:left;}
	            .tax_table .tax_row .tax_row_td.left{position:absolute; left:80px; width:600px; text-align: left; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
	            .tax_table .tax_row .tax_row_td.right{{margin-left:605px;text-align: right; padding-right: 20px;border-top: 2px solid #000;}
	            .tax_table .tax_row{line-height: 30px;}
	            .company_details_class{width:100%; height:auto; }
	            .account_details_main_address_div{width:200px; margin-top:-100px;  float:left; margin-left:550px;}
	            .account_details_invoice_div{width:200px; }
	            .company_details_div{width: 400px; margin-top: 220px; height:75px; float:left; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
	            .firstname_div{position:absolute; width:300px; left:80px; right:80px; margin-top: 90px; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
	            .aib_account{ color: #ccc; font-family: Verdana,Geneva,sans-serif; font-size: 12px; width:200px;  }
	            .account_details_div{width: 400px; font-family: Verdana,Geneva,sans-serif; font-size:14px; line-height:20px; margin-top:40px;}
	            .account_details_address_div{position:absolute; left:80px; width:250px; margin-top:60px; }
	            .account_table .account_row .account_row_td.left{margin-left:0px;}
	            .account_table .account_row .account_row_td.right{margin-left:100px;padding-top:-18px;}
	            .invoice_label{ width: 100%; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px; }
	            .tax_details_class_maindiv{width: 100%; float: left;}
			</style>';
			foreach($ids as $id)
			{
				$invoice_details = DB::table('invoice_system')->where('invoice_number', $id)->first();
				$client_details = DB::table('cm_clients')->where('client_id', $invoice_details->client_id)->first();
				if(count($client_details) == ''){
					$companyname = '<div style="width: 100%; height: auto; float: left; margin: 200px 0px 0px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 0px;">Company Details not found</div>';
					$companyname = $company_firstname;
					$taxdetails = '';
					$row1 = '';
					$row2 = ''; 
					$row3 = ''; 
					$row4 = ''; 
					$row5 = ''; 
					$row6 = ''; 
					$row7 = ''; 
					$row8 = '';
					$row9 = ''; 
					$row10 = ''; 
					$row11 = ''; 
					$row12 = ''; 
					$row13 = ''; 
					$row14 = ''; 
					$row15 = ''; 
					$row16 = ''; 
					$row17 = ''; 
					$row18 = ''; 
					$row19 = ''; 
					$row20 = '';
				}
				else{
					$company_firstname='
	          		<div class="company_details_div">
		              <div class="firstname_div">
		                <b>To:</b><br/>
		                '.$client_details->firstname.' '.$client_details->surname.'<br/>  
		                '.$client_details->company.'<br/>  
		                '.$client_details->address1.'<br/>  
		                '.$client_details->address2.'<br/>
		                '.$client_details->address3.'
		              </div>
		            </div>
		            <div class="account_details_div">
		              <div class="account_details_main_address_div">
		              		<div class="aib_account">
				                AIB Account: 48870061<br/>
				                Sort Code: 93-72-23<br/>
				                VAT Number: 9754009E<br/>
				                Company Number: 485123
				            </div>	                 
		                  <div class="account_details_invoice_div">
		                    <div class="account_table">
		                      <div class="account_row">
		                        <div class="account_row_td left"><b>Account:</b></div>
		                        <div class="account_row_td right">'.$client_details->client_id.'</div>
		                      </div>
		                      <div class="account_row">
		                        <div class="account_row_td left"><b>Invoice:</b></div>
		                        <div class="account_row_td right">'.$invoice_details->invoice_number.'</div>
		                      </div>
		                      <div class="account_row">
		                        <div class="account_row_td left"><b>Date:</b></div>
		                        <div class="account_row_td right">'.date('d-M-Y',strtotime($invoice_details->invoice_date)).'</div>
		                      </div>
		                    </div>
		                  </div>
		              </div>
		            </div>
		            <div class="invoice_label">
		              INVOICE
		            </div>';
		            if($invoice_details->bn_row1 != "")
		            {
		            	$bn_row1_add_zero = number_format_invoice($invoice_details->bn_row1);
		            }
		            else{
		            	$bn_row1_add_zero = '';
		            }
	            	$row1 = '
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->f_row1).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->z_row1.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->at_row1.'</div>
	                  <div class="class_row_td right">'.$bn_row1_add_zero.'</div>';

					if($invoice_details->bo_row2 != "")
					{
						$bo_row2_add_zero = number_format_invoice($invoice_details->bo_row2);
					}
					else{
						$bo_row2_add_zero = '';
					}

	            	$row2 = '<div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->g_row2).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->aa_row2.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->au_row2.'</div>
	                  <div class="class_row_td right">'.$bo_row2_add_zero.'</div>';
		            if($invoice_details->bp_row3 != "")
		            {
		            	$bp_row3_add_zero = number_format_invoice($invoice_details->bp_row3);
		            }
		            else{
		            	$bp_row3_add_zero = '';
		            }
	            	$row3 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->h_row3).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ab_row3.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->av_row3.'</div>
	                  <div class="class_row_td right">'.$bp_row3_add_zero.'</div>';
		            if($invoice_details->bq_row4 != "")
		            {
		            	$bq_row4_add_zero = number_format_invoice($invoice_details->bq_row4);
		            }
		            else{
		            	$bq_row4_add_zero = '';
		            }
	            	$row4 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->i_row4).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ac_row4.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->aw_row4.'</div>
	                  <div class="class_row_td right">'.$bq_row4_add_zero.'</div>';
					if($invoice_details->br_row5 != "")
					{
						$br_row5_add_zero = number_format_invoice($invoice_details->br_row5);
					}
					else{
						$br_row5_add_zero = '';
					}
	            	$row5 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->j_row5).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ad_row5.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->ax_row5.'</div>
	                  <div class="class_row_td right">'.$br_row5_add_zero.'</div>';
		            if($invoice_details->bs_row6 != "")
		            {
		            	$bs_row6_add_zero = number_format_invoice($invoice_details->bs_row6);
		            }
		            else{
		            	$bs_row6_add_zero = '';
		            }
	            	$row6 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->k_row6).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ae_row6.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->ay_row6.'</div>
	                  <div class="class_row_td right">'.$bs_row6_add_zero.'</div>';
		            if($invoice_details->bt_row7 != "")
		            {
		            	$bt_row7_add_zero = number_format_invoice($invoice_details->bt_row7);
		            }
		            else{
		            	$bt_row7_add_zero = '';
		            }
	            	$row7 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->l_row7).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->af_row7.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->az_row7.'</div>
	                  <div class="class_row_td right">'.$bt_row7_add_zero.'</div>';
		            if($invoice_details->bu_row8 != "")
		            {
		            	$bu_row8_add_zero = number_format_invoice($invoice_details->bu_row8);
		            }
		            else{
		            	$bu_row8_add_zero = '';
		            }
	            	$row8 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->m_row8).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ag_row8.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->ba_row8.'</div>
	                  <div class="class_row_td right">'.$bu_row8_add_zero.'</div>';
		            if($invoice_details->bv_row9 != "")
		            {
		            	$bv_row9_add_zero = number_format_invoice($invoice_details->bv_row9);
		            }
		            else{
		            	$bv_row9_add_zero = '';
		            }
	            	$row9 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->n_row9).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ah_row9.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->bb_row9.'</div>
	                  <div class="class_row_td right">'.$bv_row9_add_zero.'</div>';
					if($invoice_details->bw_row10 != "")
					{
						$bw_row10_add_zero = number_format_invoice($invoice_details->bw_row10);
					}
					else{
						$bw_row10_add_zero = '';
					}
	            	$row10 = '            	
	                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->o_row10).'</div>
	                  <div class="class_row_td left_corner">'.$invoice_details->ai_row10.'</div>
	                  <div class="class_row_td right_start">'.$invoice_details->bc_row10.'</div>
	                  <div class="class_row_td right">'.$bw_row10_add_zero.'</div>';
		            if($invoice_details->bx_row11 != "")
		            {
		            	$bx_row11_add_zero = number_format_invoice($invoice_details->bx_row11);
		            }
		            else{
		            	$bx_row11_add_zero = '';
		            }
		            	$row11 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->p_row11).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->aj_row11.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bd_row11.'</div>
		                  <div class="class_row_td right">'.$bx_row11_add_zero.'</div>';
		           if($invoice_details->by_row12 != "")
		           {
		           	$by_row12_add_zero = number_format_invoice($invoice_details->by_row12);
		           }
		           else{
		           	$by_row12_add_zero = '';
		           }
		            	$row12 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->q_row12).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->ak_row12.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->be_row12.'</div>
		                  <div class="class_row_td right">'.$by_row12_add_zero.'</div>';
		            if($invoice_details->bz_row13 != "")
		            {
		            	$bz_row13_add_zero = number_format_invoice($invoice_details->bz_row13);
		            }
		            else{
		            	$bz_row13_add_zero = '';
		            }
		            	$row13 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->r_row13).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->al_row13.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bf_row13.'</div>
		                  <div class="class_row_td right">'.$bz_row13_add_zero.'</div>';
		            if($invoice_details->ca_row14 != "")
		            {
		            	$ca_row14_add_zero = number_format_invoice($invoice_details->ca_row14);
		            }
		            else{
		            	$ca_row14_add_zero = '';
		            }
		            	$row14 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->s_row14).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->am_row14.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bg_row14.'</div>
		                  <div class="class_row_td right">'.$ca_row14_add_zero.'</div>';
		            if($invoice_details->cb_row15 != "")
		            {
		            	$cb_row15_add_zero = number_format_invoice($invoice_details->cb_row15);
		            }
		            else{
		            	$cb_row15_add_zero = '';
		            }
		            	$row15 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->t_row15).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->an_row15.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bh_row15.'</div>
		                  <div class="class_row_td right">'.$cb_row15_add_zero.'</div>';
		           if($invoice_details->cc_row16 != "")
		           {
		           	$cc_row16_add_zero = number_format_invoice($invoice_details->cc_row16);
		           }
		           else{
		           	$cc_row16_add_zero = '';
		           }
		            	$row16 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->u_row16).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->ao_row16.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bi_row16.'</div>
		                  <div class="class_row_td right">'.$cc_row16_add_zero.'</div>';
		            if($invoice_details->cd_row17 != "")
		            {
		            	$cd_row17_add_zero = number_format_invoice($invoice_details->cd_row17);
		            }
		            else{
		            	$cd_row17_add_zero = '';
		            }
		            	$row17 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->v_row17).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->ap_row17.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bj_row17.'</div>
		                  <div class="class_row_td right">'.$cd_row17_add_zero.'</div>';
		           if($invoice_details->ce_row18 != "")
		           {
		           	$ce_row18_add_zero = number_format_invoice($invoice_details->ce_row18);
		           }
		           else{
		           	$ce_row18_add_zero = '';
		           }
		            	$row18 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->w_row18).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->aq_row18.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bk_row18.'</div>
		                  <div class="class_row_td right">'.$ce_row18_add_zero.'</div>';
		            if($invoice_details->cf_row19 != "")
		            {
		            	$cf_row19_add_zero = number_format_invoice($invoice_details->cf_row19);
		            }
		            else{
		            	$cf_row19_add_zero = '';
		            }
		            	$row19 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->x_row19).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->ar_row19.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bl_row19.'</div>
		                  <div class="class_row_td right">'.$cf_row19_add_zero.'</div>';
		            if($invoice_details->cg_row20 != "")
		            {
		            	$cg_row20_add_zero = number_format_invoice($invoice_details->cg_row20);
		            }
		            else{
		            	$cg_row20_add_zero = '';
		            }
		            	$row20 = '            	
		                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->y_row20).'</div>
		                  <div class="class_row_td left_corner">'.$invoice_details->as_row20.'</div>
		                  <div class="class_row_td right_start">'.$invoice_details->bm_row20.'</div>
		                  <div class="class_row_td right">'.$cg_row20_add_zero.'</div>';
			         $tax_details ='
			         <div class="tax_table_div">
			            <div class="tax_table">
			              <div class="tax_row">
			                <div class="tax_row_td left">Total Fees (as agreed)</div>
			                <div class="tax_row_td right" width="13%">'.number_format_invoice($invoice_details->inv_net).'</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left">VAT @ 23%</div>
			                <div class="tax_row_td right" style="border-top:0px;">'.number_format_invoice($invoice_details->vat_value).'</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left" style="color:#fff">.</div>
			                <div class="tax_row_td right">'.number_format_invoice($invoice_details->gross).'</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left">Outlay @ 0%</div>
			                <div class="tax_row_td right" style="border-top:0px;" style="color:#fff">..</div>
			              </div>
			              <div class="tax_row">
			                <div class="tax_row_td left">Total Fees Due</div>
			                <div class="tax_row_td right" style="border-bottom: 2px solid #000">'.number_format_invoice($invoice_details->gross).'</div>
			              </div>
			            </div>
			          </div>
			         ';
			         $companyname = $company_firstname;
			         $taxdetails = $tax_details;
			         $row1 = $row1;
			         $row2 = $row2; 
			         $row3 = $row3; 
			         $row4 = $row4; 
			         $row5 = $row5; 
			         $row6 = $row6; 
			         $row7 = $row7; 
			         $row8 = $row8;
			         $row9 = $row9; 
			         $row10 = $row10; 
			         $row11 = $row11; 
			         $row12 = $row12; 
			         $row13 = $row13; 
			         $row14 = $row14; 
			         $row15 = $row15; 
			         $row16 = $row16; 
			         $row17 = $row17; 
			         $row18 = $row18; 
			         $row19 = $row19; 
			         $row20 = $row20;     
			    }
				$output = '<div class="company_details_class">'.$companyname.'</div>
	            <div class="tax_details_class_maindiv">
	              <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
	                <div class="class_row class_row1">'.$row1.'</div>
	                <div class="class_row class_row2">'.$row2.'</div>
	                <div class="class_row class_row3">'.$row3.'</div>
	                <div class="class_row class_row4">'.$row4.'</div>
	                <div class="class_row class_row5">'.$row5.'</div>
	                <div class="class_row class_row6">'.$row6.'</div>
	                <div class="class_row class_row7">'.$row7.'</div>
	                <div class="class_row class_row8">'.$row8.'</div>
	                <div class="class_row class_row9">'.$row9.'</div>
	                <div class="class_row class_row10">'.$row10.'</div>
	                <div class="class_row class_row11">'.$row11.'</div>
	                <div class="class_row class_row12">'.$row12.'</div>
	                <div class="class_row class_row13">'.$row13.'</div>
	                <div class="class_row class_row14">'.$row14.'</div>
	                <div class="class_row class_row15">'.$row15.'</div>
	                <div class="class_row class_row16">'.$row16.'</div>
	                <div class="class_row class_row17">'.$row17.'</div>
	                <div class="class_row class_row18">'.$row18.'</div>
	                <div class="class_row class_row19">'.$row19.'</div>
	                <div class="class_row class_row20">'.$row20.'</div>
	              </div>
	            </div>
	            <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
	            <div class="tax_details_class">'.$taxdetails.'</div>';


	            $html = $html_main.$output;
				$pdf = PDF::loadHTML($html);
				$pdf->save('papers/'.time().'_Invoice of '.$id.'.pdf');

				$zipFileName = time().'_Invoice of '.$id.'.pdf';
				$pdfsname = '<p>'.$zipFileName.'</p>';
			}
		}
		else{
			$html_main = '<style>
				@page { margin: 0in; }
			    body {
			        background-image: url('.URL::to('assets/invoice_letterpad_1.jpg').');
			        background-position: top left right bottom;
				    background-repeat: no-repeat;
				    font-family: Verdana,Geneva,sans-serif; 
			    }
			    .tax_table_div{width: 100%; margin-top:-30px}
	            .tax_table{margin-left:73%;width: 20%;}
	            .details_table .class_row .class_row_td { font-size: 14px; float:left; }
	            .details_table .class_row .class_row_td.left{position:absolute; width:70%; line-height:20px;  text-align: left;  font-size:14px; }
	            .details_table .class_row .class_row_td.left_corner{position:absolute; margin-left:71%; width:10%; line-height:20px;  text-align: right;}
	            .details_table .class_row .class_row_td.right_start{position:absolute; margin-left:81%; width:9%; line-height:20px;  text-align: right;}
	            .details_table .class_row .class_row_td.right{position:absolute;line-height:20px; margin-left:90%; text-align: right; font-size:14px; width:10%;}
	            .details_table .class_row{line-height: 30px; clear:both}
	            .details_table { height : 420px !important; }
	            .class_row{width: 100%; clear:both; height:20px:}
	            .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;float:left;}
	            .tax_table .tax_row .tax_row_td.left{position:absolute; left:80px; width:600px; text-align: left; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
	            .tax_table .tax_row .tax_row_td.right{{margin-left:605px;text-align: right; padding-right: 20px;border-top: 2px solid #000;}
	            .tax_table .tax_row{line-height: 30px;}
	            .company_details_class{width:100%; height:auto; }
	            .account_details_main_address_div{width:200px; margin-top:-100px;  float:left; margin-left:550px;}
	            .account_details_invoice_div{width:200px; }
	            .company_details_div{width: 400px; margin-top: 220px; height:75px; float:left; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
	            .firstname_div{position:absolute; width:300px; left:80px; right:80px; margin-top: 90px; font-family: Verdana,Geneva,sans-serif; font-size:14px;}
	            .aib_account{ color: #ccc; font-family: Verdana,Geneva,sans-serif; font-size: 12px; width:200px;  }
	            .account_details_div{width: 400px; font-family: Verdana,Geneva,sans-serif; font-size:14px; line-height:20px; margin-top:40px;}
	            .account_details_address_div{position:absolute; left:80px; width:250px; margin-top:60px; }
	            .account_table .account_row .account_row_td.left{margin-left:0px;}
	            .account_table .account_row .account_row_td.right{margin-left:100px;padding-top:-18px;}
	            .invoice_label{ width: 100%; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px; }
	            .tax_details_class_maindiv{width: 100%; float: left;}
			</style>';
			$time = time();
			$zipFileName = $time.'.zip';
	        $zip = new ZipArchive;
	        if ($zip->open('papers/'.$zipFileName, ZipArchive::CREATE) === TRUE) {
				if(count($ids))
				{
					foreach($ids as $id)
					{
						$invoice_details = DB::table('invoice_system')->where('invoice_number', $id)->first();
						$client_details = DB::table('cm_clients')->where('client_id', $invoice_details->client_id)->first();
						if(count($client_details) == ''){
							$companyname = '<div style="width: 100%; height: auto; float: left; margin: 200px 0px 0px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 0px;">Company Details not found</div>';
							
							$companyname = $company_firstname;
							$taxdetails = '';
							$row1 = '';
							$row2 = ''; 
							$row3 = ''; 
							$row4 = ''; 
							$row5 = ''; 
							$row6 = ''; 
							$row7 = ''; 
							$row8 = '';
							$row9 = ''; 
							$row10 = ''; 
							$row11 = ''; 
							$row12 = ''; 
							$row13 = ''; 
							$row14 = ''; 
							$row15 = ''; 
							$row16 = ''; 
							$row17 = ''; 
							$row18 = ''; 
							$row19 = ''; 
							$row20 = '';
						}
						else{
							$company_firstname='
			          		<div class="company_details_div">
				              <div class="firstname_div">
				                <b>To:</b><br/>
				                '.$client_details->firstname.' '.$client_details->surname.'<br/>  
				                '.$client_details->company.'<br/>  
				                '.$client_details->address1.'<br/>  
				                '.$client_details->address2.'<br/>
				                '.$client_details->address3.'
				              </div>
				            </div>
				            <div class="account_details_div">
				              <div class="account_details_main_address_div">
				              		<div class="aib_account">
						                AIB Account: 48870061<br/>
						                Sort Code: 93-72-23<br/>
						                VAT Number: 9754009E<br/>
						                Company Number: 485123
						            </div>	                 
				                  <div class="account_details_invoice_div">
				                    <div class="account_table">
				                      <div class="account_row">
				                        <div class="account_row_td left"><b>Account:</b></div>
				                        <div class="account_row_td right">'.$client_details->client_id.'</div>
				                      </div>
				                      <div class="account_row">
				                        <div class="account_row_td left"><b>Invoice:</b></div>
				                        <div class="account_row_td right">'.$invoice_details->invoice_number.'</div>
				                      </div>
				                      <div class="account_row">
				                        <div class="account_row_td left"><b>Date:</b></div>
				                        <div class="account_row_td right">'.date('d-M-Y',strtotime($invoice_details->invoice_date)).'</div>
				                      </div>
				                    </div>
				                  </div>
				              </div>
				            </div>
				            <div class="invoice_label">
				              INVOICE
				            </div>';
				            if($invoice_details->bn_row1 != "")
				            {
				            	$bn_row1_add_zero = number_format_invoice($invoice_details->bn_row1);
				            }
				            else{
				            	$bn_row1_add_zero = '';
				            }
			            	$row1 = '
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->f_row1).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->z_row1.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->at_row1.'</div>
			                  <div class="class_row_td right">'.$bn_row1_add_zero.'</div>';

							if($invoice_details->bo_row2 != "")
							{
								$bo_row2_add_zero = number_format_invoice($invoice_details->bo_row2);
							}
							else{
								$bo_row2_add_zero = '';
							}

			            	$row2 = '<div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->g_row2).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->aa_row2.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->au_row2.'</div>
			                  <div class="class_row_td right">'.$bo_row2_add_zero.'</div>';
				            if($invoice_details->bp_row3 != "")
				            {
				            	$bp_row3_add_zero = number_format_invoice($invoice_details->bp_row3);
				            }
				            else{
				            	$bp_row3_add_zero = '';
				            }
			            	$row3 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->h_row3).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ab_row3.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->av_row3.'</div>
			                  <div class="class_row_td right">'.$bp_row3_add_zero.'</div>';
				            if($invoice_details->bq_row4 != "")
				            {
				            	$bq_row4_add_zero = number_format_invoice($invoice_details->bq_row4);
				            }
				            else{
				            	$bq_row4_add_zero = '';
				            }
			            	$row4 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->i_row4).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ac_row4.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->aw_row4.'</div>
			                  <div class="class_row_td right">'.$bq_row4_add_zero.'</div>';
							if($invoice_details->br_row5 != "")
							{
								$br_row5_add_zero = number_format_invoice($invoice_details->br_row5);
							}
							else{
								$br_row5_add_zero = '';
							}
			            	$row5 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->j_row5).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ad_row5.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->ax_row5.'</div>
			                  <div class="class_row_td right">'.$br_row5_add_zero.'</div>';
				            if($invoice_details->bs_row6 != "")
				            {
				            	$bs_row6_add_zero = number_format_invoice($invoice_details->bs_row6);
				            }
				            else{
				            	$bs_row6_add_zero = '';
				            }
			            	$row6 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->k_row6).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ae_row6.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->ay_row6.'</div>
			                  <div class="class_row_td right">'.$bs_row6_add_zero.'</div>';
				            if($invoice_details->bt_row7 != "")
				            {
				            	$bt_row7_add_zero = number_format_invoice($invoice_details->bt_row7);
				            }
				            else{
				            	$bt_row7_add_zero = '';
				            }
			            	$row7 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->l_row7).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->af_row7.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->az_row7.'</div>
			                  <div class="class_row_td right">'.$bt_row7_add_zero.'</div>';
				            if($invoice_details->bu_row8 != "")
				            {
				            	$bu_row8_add_zero = number_format_invoice($invoice_details->bu_row8);
				            }
				            else{
				            	$bu_row8_add_zero = '';
				            }
			            	$row8 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->m_row8).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ag_row8.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->ba_row8.'</div>
			                  <div class="class_row_td right">'.$bu_row8_add_zero.'</div>';
				            if($invoice_details->bv_row9 != "")
				            {
				            	$bv_row9_add_zero = number_format_invoice($invoice_details->bv_row9);
				            }
				            else{
				            	$bv_row9_add_zero = '';
				            }
			            	$row9 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->n_row9).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ah_row9.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->bb_row9.'</div>
			                  <div class="class_row_td right">'.$bv_row9_add_zero.'</div>';
							if($invoice_details->bw_row10 != "")
							{
								$bw_row10_add_zero = number_format_invoice($invoice_details->bw_row10);
							}
							else{
								$bw_row10_add_zero = '';
							}
			            	$row10 = '            	
			                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->o_row10).'</div>
			                  <div class="class_row_td left_corner">'.$invoice_details->ai_row10.'</div>
			                  <div class="class_row_td right_start">'.$invoice_details->bc_row10.'</div>
			                  <div class="class_row_td right">'.$bw_row10_add_zero.'</div>';
				            if($invoice_details->bx_row11 != "")
				            {
				            	$bx_row11_add_zero = number_format_invoice($invoice_details->bx_row11);
				            }
				            else{
				            	$bx_row11_add_zero = '';
				            }
				            	$row11 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->p_row11).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->aj_row11.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bd_row11.'</div>
				                  <div class="class_row_td right">'.$bx_row11_add_zero.'</div>';
				           if($invoice_details->by_row12 != "")
				           {
				           	$by_row12_add_zero = number_format_invoice($invoice_details->by_row12);
				           }
				           else{
				           	$by_row12_add_zero = '';
				           }
				            	$row12 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->q_row12).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->ak_row12.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->be_row12.'</div>
				                  <div class="class_row_td right">'.$by_row12_add_zero.'</div>';
				            if($invoice_details->bz_row13 != "")
				            {
				            	$bz_row13_add_zero = number_format_invoice($invoice_details->bz_row13);
				            }
				            else{
				            	$bz_row13_add_zero = '';
				            }
				            	$row13 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->r_row13).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->al_row13.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bf_row13.'</div>
				                  <div class="class_row_td right">'.$bz_row13_add_zero.'</div>';
				            if($invoice_details->ca_row14 != "")
				            {
				            	$ca_row14_add_zero = number_format_invoice($invoice_details->ca_row14);
				            }
				            else{
				            	$ca_row14_add_zero = '';
				            }
				            	$row14 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->s_row14).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->am_row14.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bg_row14.'</div>
				                  <div class="class_row_td right">'.$ca_row14_add_zero.'</div>';
				            if($invoice_details->cb_row15 != "")
				            {
				            	$cb_row15_add_zero = number_format_invoice($invoice_details->cb_row15);
				            }
				            else{
				            	$cb_row15_add_zero = '';
				            }
				            	$row15 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->t_row15).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->an_row15.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bh_row15.'</div>
				                  <div class="class_row_td right">'.$cb_row15_add_zero.'</div>';
				           if($invoice_details->cc_row16 != "")
				           {
				           	$cc_row16_add_zero = number_format_invoice($invoice_details->cc_row16);
				           }
				           else{
				           	$cc_row16_add_zero = '';
				           }
				            	$row16 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->u_row16).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->ao_row16.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bi_row16.'</div>
				                  <div class="class_row_td right">'.$cc_row16_add_zero.'</div>';
				            if($invoice_details->cd_row17 != "")
				            {
				            	$cd_row17_add_zero = number_format_invoice($invoice_details->cd_row17);
				            }
				            else{
				            	$cd_row17_add_zero = '';
				            }
				            	$row17 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->v_row17).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->ap_row17.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bj_row17.'</div>
				                  <div class="class_row_td right">'.$cd_row17_add_zero.'</div>';
				           if($invoice_details->ce_row18 != "")
				           {
				           	$ce_row18_add_zero = number_format_invoice($invoice_details->ce_row18);
				           }
				           else{
				           	$ce_row18_add_zero = '';
				           }
				            	$row18 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->w_row18).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->aq_row18.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bk_row18.'</div>
				                  <div class="class_row_td right">'.$ce_row18_add_zero.'</div>';
				            if($invoice_details->cf_row19 != "")
				            {
				            	$cf_row19_add_zero = number_format_invoice($invoice_details->cf_row19);
				            }
				            else{
				            	$cf_row19_add_zero = '';
				            }
				            	$row19 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->x_row19).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->ar_row19.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bl_row19.'</div>
				                  <div class="class_row_td right">'.$cf_row19_add_zero.'</div>';
				            if($invoice_details->cg_row20 != "")
				            {
				            	$cg_row20_add_zero = number_format_invoice($invoice_details->cg_row20);
				            }
				            else{
				            	$cg_row20_add_zero = '';
				            }
				            	$row20 = '            	
				                  <div class="class_row_td left">'.str_replace(" ","&nbsp;",$invoice_details->y_row20).'</div>
				                  <div class="class_row_td left_corner">'.$invoice_details->as_row20.'</div>
				                  <div class="class_row_td right_start">'.$invoice_details->bm_row20.'</div>
				                  <div class="class_row_td right">'.$cg_row20_add_zero.'</div>';
					         $tax_details ='
					         <div class="tax_table_div">
					            <div class="tax_table">
					              <div class="tax_row">
					                <div class="tax_row_td left">Total Fees (as agreed)</div>
					                <div class="tax_row_td right" width="13%">'.number_format_invoice($invoice_details->inv_net).'</div>
					              </div>
					              <div class="tax_row">
					                <div class="tax_row_td left">VAT @ 23%</div>
					                <div class="tax_row_td right" style="border-top:0px;">'.number_format_invoice($invoice_details->vat_value).'</div>
					              </div>
					              <div class="tax_row">
					                <div class="tax_row_td left" style="color:#fff">.</div>
					                <div class="tax_row_td right">'.number_format_invoice($invoice_details->gross).'</div>
					              </div>
					              <div class="tax_row">
					                <div class="tax_row_td left">Outlay @ 0%</div>
					                <div class="tax_row_td right" style="border-top:0px;" style="color:#fff">..</div>
					              </div>
					              <div class="tax_row">
					                <div class="tax_row_td left">Total Fees Due</div>
					                <div class="tax_row_td right" style="border-bottom: 2px solid #000">'.number_format_invoice($invoice_details->gross).'</div>
					              </div>
					            </div>
					          </div>
					         ';
					         $companyname = $company_firstname;
					         $taxdetails = $tax_details;
					         $row1 = $row1;
					         $row2 = $row2; 
					         $row3 = $row3; 
					         $row4 = $row4; 
					         $row5 = $row5; 
					         $row6 = $row6; 
					         $row7 = $row7; 
					         $row8 = $row8;
					         $row9 = $row9; 
					         $row10 = $row10; 
					         $row11 = $row11; 
					         $row12 = $row12; 
					         $row13 = $row13; 
					         $row14 = $row14; 
					         $row15 = $row15; 
					         $row16 = $row16; 
					         $row17 = $row17; 
					         $row18 = $row18; 
					         $row19 = $row19; 
					         $row20 = $row20;     
					    }
						$output = '<div class="company_details_class">'.$companyname.'</div>
			            <div class="tax_details_class_maindiv">
			              <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
			                <div class="class_row class_row1">'.$row1.'</div>
			                <div class="class_row class_row2">'.$row2.'</div>
			                <div class="class_row class_row3">'.$row3.'</div>
			                <div class="class_row class_row4">'.$row4.'</div>
			                <div class="class_row class_row5">'.$row5.'</div>
			                <div class="class_row class_row6">'.$row6.'</div>
			                <div class="class_row class_row7">'.$row7.'</div>
			                <div class="class_row class_row8">'.$row8.'</div>
			                <div class="class_row class_row9">'.$row9.'</div>
			                <div class="class_row class_row10">'.$row10.'</div>
			                <div class="class_row class_row11">'.$row11.'</div>
			                <div class="class_row class_row12">'.$row12.'</div>
			                <div class="class_row class_row13">'.$row13.'</div>
			                <div class="class_row class_row14">'.$row14.'</div>
			                <div class="class_row class_row15">'.$row15.'</div>
			                <div class="class_row class_row16">'.$row16.'</div>
			                <div class="class_row class_row17">'.$row17.'</div>
			                <div class="class_row class_row18">'.$row18.'</div>
			                <div class="class_row class_row19">'.$row19.'</div>
			                <div class="class_row class_row20">'.$row20.'</div>
			              </div>
			            </div>
			            <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
			            <div class="tax_details_class">'.$taxdetails.'</div>';


			            $html = $html_main.$output;
						$pdf = PDF::loadHTML($html);
						$pdf->save('papers/Invoice of '.$id.'.pdf');

						$zip->addFile('papers/Invoice of '.$id.'.pdf','Invoice of '.$id.'.pdf');

						if($pdfsname == "")
						{
							$pdfsname = '<p>Invoice of '.$id.'.pdf</p>';
						}
						else{
							$pdfsname = $pdfsname.'<p>Invoice of '.$id.'.pdf</p>';
						}
					}
				}
			}
			$zip->close();
		}

		echo json_encode(array("zipfile" => $zipFileName,"pdfs" => $pdfsname));
	}
	public function client_review_email_selected_pdf()
	{
		$from_input = Input::get('from_user_to_client');
		$attachment = Input::get('hidden_attachment');
		$client_id = Input::get('hidden_client_id');
		
		$details = DB::table('user')->where('user_id',$from_input)->first();
		$from = $details->email;
		$user_name = $details->lastname.' '.$details->firstname;

		$to_user = Input::get('client_search');

		$toemails = $to_user.','.Input::get('cc_approval_to_client');
		$sentmails = $to_user.', '.Input::get('cc_approval_to_client');
		$subject = Input::get('subject_to_client'); 
		$message = Input::get('message_editor_to_client');
		$explode = explode(',',$toemails);
		$data['sentmails'] = $sentmails;

		if(count($explode))
		{
			foreach($explode as $exp)
			{
				$to = trim($exp);
				$data['logo'] = URL::to('assets/images/logo.png');
				$data['message'] = $message;
				$contentmessage = view('user/email_share_paper_crm', $data);
				$email = new PHPMailer();
				$email->SetFrom($from, $user_name); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress($to);
				$email->AddAttachment('papers/'.$attachment, $attachment);
				$email->Send();
			}

			$client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
			$datamessage['message_id'] = time();
			$datamessage['message_from'] = $from_input;
			$datamessage['subject'] = $subject;
			$datamessage['message'] = $message;
			$datamessage['client_ids'] = $client_id;
			$datamessage['primary_emails'] = $client_details->email;
			$datamessage['secondary_emails'] = $client_details->email2;
			$datamessage['date_sent'] = date('Y-m-d H:i:s');
			$datamessage['date_saved'] = date('Y-m-d H:i:s');
			$datamessage['source'] = "Invoice System";
			$datamessage['attachments'] = 'papers/'.$attachment;
			$datamessage['status'] = 1;

			DB::table('messageus')->insert($datamessage);
			return Redirect::back()->with('message', 'Email Sent Successfully for Client.');
		}
		else{
			return Redirect::back()->with('error', 'Email Field is empty so email is not sent');
		}
	}
}
