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
class SupplierinvoiceController extends Controller {
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
	public function supplier_invoice_management()
	{
		$global_invoices = DB::table('supplier_global_invoice')->orderBy('id','desc')->get();
		return view('user/supplier_invoice_management/home', array('title' => 'Supplier Invoice Management','global_invoices' => $global_invoices));
	}
	public function get_supplier_info_details()
	{
		$id = Input::get('id');
		$get_details = DB::table('suppliers')->where('id',$id)->first();
		if(count($get_details))
		{
			$data['supplier_code'] = $get_details->supplier_code;
			$data['supplier_name'] = $get_details->supplier_name;
			$data['web_url'] = $get_details->web_url;
			$data['phone_no'] = $get_details->phone_no;
			$data['email'] = $get_details->email;
			$data['iban'] = $get_details->iban;
			$data['bic'] = $get_details->bic;
			$data['vat_no'] = $get_details->vat_no;
			$data['currency'] = $get_details->currency;
			$data['opening_balance'] = $get_details->opening_balance;
			$data['default_nominal'] = $get_details->default_nominal;

			echo json_encode($data);
		}
	}
	public function store_purchase_invoice()
	{
		$supplier = Input::get('select_supplier');
		$inv_no_global = Input::get('inv_no_global');
		$inv_date_global = Input::get('inv_date_global');
		$ref_global = Input::get('ref_global');
		$net_global = Input::get('net_global');
		$vat_global = Input::get('vat_global');
		$gross_global = Input::get('gross_global');
		$global_url = Input::get('global_file_url');
		$global_filename = Input::get('global_file_name');
		$globalid = Input::get('hidden_global_id');
		$sno = Input::get('hidden_sno');

		$inv_date = explode('-',$inv_date_global);
		$invoice_date = '';
		if(count($inv_date)) {
			$invoice_date = $inv_date[2].'-'.$inv_date[1].'-'.$inv_date[0];
		}

		$supplier_details = DB::table('suppliers')->where('id',$supplier)->first();
		$data['supplier_id'] = $supplier;
		$data['invoice_no'] = $inv_no_global;
		$data['invoice_date'] = $invoice_date;
		$data['reference'] = $ref_global;
		$data['net'] = $net_global;
		$data['vat'] = $vat_global;
		$data['gross'] = $gross_global;
		
		if($globalid != "") {
			$filename = Input::get('global_file_name');
			if($filename != "")
			{
				$datafile['status'] = 0;
				$datafile['supplier_id'] = $supplier;
				$datafile['inv_date'] = $invoice_date;
				DB::table('supplier_purchase_invoice_files')->where('filename',$filename)->update($datafile);
			}

			$global_details = DB::table('supplier_global_invoice')->where('id',$globalid)->first();
			DB::table('supplier_global_invoice')->where('id',$globalid)->update($data);
			
			$next_connecting_journal = $global_details->journal_id;
			DB::table('supplier_detail_invoice')->where('global_id',$globalid)->delete();

			DB::table('journals')->where('connecting_journal_reference',$next_connecting_journal)->delete();
			DB::table('journals')->where('connecting_journal_reference','like',$next_connecting_journal.'.%')->delete();

			$global_invoices_sno = $sno;
		}
		else{

			$data['filename'] = $global_filename;
			$data['url'] = $global_url;

			DB::table('supplier_purchase_invoice_files')->where('filename',$data['filename'])->update(['status' => 0,'inv_date' => $invoice_date, 'supplier_id' => $supplier]);

			$count_total_journals = DB::table('journals')->groupBy('reference')->get();
			$next_connecting_journal = count($count_total_journals) + 1;
			$data['journal_id'] = $next_connecting_journal;
			$globalid = DB::table('supplier_global_invoice')->insertGetid($data);
			$global_invoices_sno = 1;
		}

		$journal_ref = time().'_SPI_'.$globalid;

		$datajournal['journal_date'] = $invoice_date;
		$datajournal['connecting_journal_reference'] = $next_connecting_journal;
		$datajournal['reference'] = $journal_ref;
		$datajournal['description'] = 'Purchase Invoice '.$supplier_details->supplier_name.' '.$ref_global.''; 	
		$datajournal['nominal_code'] = '813'; 	
		$datajournal['dr_value'] = '0.00';
		$datajournal['cr_value'] = $gross_global; 	
		$datajournal['journal_source'] = 'PI';
		DB::table('journals')->insert($datajournal);

		if($vat_global == "0" || $vat_global == "0.00" || $vat_global == "0.0") {

		}
		else{
			$next_connecting_journal = $next_connecting_journal + 0.01;
			$datajournal['journal_date'] = $invoice_date;
			$datajournal['connecting_journal_reference'] = $next_connecting_journal;
			$datajournal['reference'] = $journal_ref;
			$datajournal['description'] = 'Purchase Invoice '.$supplier_details->supplier_name.' '.$ref_global.''; 	
			$datajournal['nominal_code'] = '845'; 	
			$datajournal['dr_value'] = $vat_global;
			$datajournal['cr_value'] = '0.00'; 	
			$datajournal['journal_source'] = 'PI';
			DB::table('journals')->insert($datajournal);
		}

		$next_connecting_journal = $next_connecting_journal + 0.01;

		$des_detail = Input::get('description_detail');
		$code_detail = Input::get('select_nominal_codes');
		$net_detail = Input::get('net_detail');
		$vat_rate_detail = Input::get('select_vat_rates');
		$vat_detail = Input::get('vat_detail');
		$gross_detail = Input::get('gross_detail');

		if(count($code_detail)) {
			foreach($code_detail as $key => $code) {
				$datadetail['global_id'] = $globalid;
				$datadetail['invoice_no'] = $inv_no_global;
				$datadetail['description'] = $des_detail[$key];
				$datadetail['nominal_code'] = $code;
				$datadetail['net'] = $net_detail[$key];
				$datadetail['vat_rate'] = $vat_rate_detail[$key];
				$datadetail['vat_value'] = $vat_detail[$key];
				$datadetail['gross'] = $gross_detail[$key];
				//$datadetail['journal_id'] = $next_connecting_journal;

				$detailid = DB::table('supplier_detail_invoice')->insertGetid($datadetail);

				$code_details = DB::table('nominal_codes')->where('id',$code)->first();

				$datajournal['journal_date'] = $invoice_date;
				$datajournal['connecting_journal_reference'] = $next_connecting_journal;
				$datajournal['reference'] = $journal_ref;
				$datajournal['description'] = 'Purchase Invoice '.$supplier_details->supplier_name.' '.$ref_global.''; 	
				$datajournal['nominal_code'] = $code_details->code; 	
				$datajournal['dr_value'] = $net_detail[$key];
				$datajournal['cr_value'] = '0.00'; 	
				$datajournal['journal_source'] = 'PI';
				DB::table('journals')->insert($datajournal);

				$next_connecting_journal = $next_connecting_journal + 0.01;
			}
		}

		$exp_journal_id = explode(".",$next_connecting_journal);
		$journal_id = $exp_journal_id[0];

		return redirect::back()->with('message','Invoice has been saved with Serial Number - '.$global_invoices_sno.', Journal ID Number '.$journal_id.' Do you want to review the journal now? <a href="javascript:" class="common_black_button journal_id_viewer" data-element="'.$journal_id.'">View Journal</a>');
	}
	public function supplier_upload_global_files()
	{
		$global_id = Input::get('hidden_global_inv_id');

		$upload_dir = 'uploads/supplier_global_files';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if (!empty($_FILES)) {
			$tmpFile = $_FILES['file']['tmp_name'];
			$fname = str_replace("#","",$_FILES['file']['name']);
			$fname = str_replace("#","",$fname);
			$fname = str_replace("#","",$fname);
			$fname = str_replace("#","",$fname);

			$fname = str_replace("%","",$fname);
			$fname = str_replace("%","",$fname);
			$fname = str_replace("%","",$fname);

			$fname = time().'_'.$fname;

			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);

			$download_url = URL::to($filename);

			if($global_id != ""){
				$data['url'] = $upload_dir;
				$data['filename'] = $fname; 

				DB::table('supplier_purchase_invoice_files')->where('filename',$data['filename'])->update(['status' => 0]);
				
				DB::table('supplier_global_invoice')->where('id',$global_id)->update($data);
			}

		 	echo json_encode(array('filename' => $fname, 'url' => $upload_dir, 'download_url' => $download_url));
		}


	}
	public function purchase_invoice_files()
	{
		$upload_dir = 'uploads/supplier_global_files';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if (!empty($_FILES)) {
			$tmpFile = $_FILES['file']['tmp_name'];
			$fname = str_replace("#","",$_FILES['file']['name']);
			$fname = str_replace("#","",$fname);
			$fname = str_replace("#","",$fname);
			$fname = str_replace("#","",$fname);

			$fname = str_replace("%","",$fname);
			$fname = str_replace("%","",$fname);
			$fname = str_replace("%","",$fname);

			$fname = time().'_'.$fname;

			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);

			$download_url = URL::to($filename);

			$data['url'] = $upload_dir;
			$data['filename'] = $fname; 
			$data['status'] = 1; 
			DB::table('supplier_purchase_invoice_files')->insert($data);


		 	echo json_encode(array('filename' => $fname, 'url' => $upload_dir, 'download_url' => $download_url));
		}


	}
	
	public function edit_purchase_invoice_supplier()
	{
		$id = Input::get('id');
		$global = DB::table('supplier_global_invoice')->where('id',$id)->first();
		$supplier_output = '';
		$global_output = '';
		if(count($global))
		{
			$supplier_id = $global->supplier_id;
			$supplier_details = DB::table('suppliers')->where('id',$supplier_id)->first();

			$supplier_output = '
			<tr>
              <td style="font-weight:600">Supplier Code: </td> 
              <td class="supplier_code_td">'.$supplier_details->supplier_code.'</td>
              <td style="font-weight:600">Supplier Name: </td> 
              <td class="supplier_name_td">'.$supplier_details->supplier_name.'</td>
              <td style="font-weight:600">Web Url: </td> 
              <td class="web_url_td">'.$supplier_details->web_url.'</td>
            </tr>
            <tr>
              <td style="font-weight:600">Phone No: </td> 
              <td class="phone_no_td">'.$supplier_details->phone_no.'</td>
              <td style="font-weight:600">Email: </td>
              <td class="email_td">'.$supplier_details->email.'</td>
              <td style="font-weight:600">IBAN: </td> 
              <td class="iban_td">'.$supplier_details->iban.'</td>
            </tr>
            <tr>
              <td style="font-weight:600">BIC: </td> 
              <td class="bic_td">'.$supplier_details->bic.'</td>
              <td style="font-weight:600">VAT No: </td> 
              <td class="vat_no_td">'.$supplier_details->vat_no.'</td>
              <td></td>
              <td></td>
            </tr>';

            $thisinput = "this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');";
            $global_output = '<tr>
                  <td><input type="text" name="inv_no_global" class="form-control inv_no_global" value="'.$global->invoice_no.'" placeholder="Enter Invoice No" required></td>
                  <td><input type="text" name="inv_date_global" class="form-control inv_date_global" value="'.date('d-m-Y', strtotime($global->invoice_date)).'" placeholder="Enter Invoice Date" required></td>
                  <td><input type="text" name="ref_global" class="form-control ref_global" value="'.$global->reference.'" placeholder="Enter Reference" required></td>
                  <td><input type="text" name="net_global" class="form-control net_global" value="'.$global->net.'" placeholder="Enter Net Value" oninput="'.$thisinput.'" onpaste="'.$thisinput.'" required></td>
                  <td></td>
                  <td><input type="text" name="vat_global" class="form-control vat_global" value="'.$global->vat.'" placeholder="Enter VAT Value" oninput="'.$thisinput.'" onpaste="'.$thisinput.'" required></td>
                  <td><input type="text" name="gross_global" class="form-control gross_global" value="'.$global->gross.'" placeholder="Enter Gross" readonly required></td>
                </tr>';

                $attachment_output = '<spam class="global_file_upload">';
                    if($global->filename != ""){
                    	$attachment_output.='<a href="'.URL::To($global->url.'/'.$global->filename).'" class="file_attachments" download>'.$global->filename.'</a> <a href="javascript:" class="fa fa-trash delete_global_attachments"></a>';
                    }
                    $attachment_output.='</spam>
                    <input type="hidden" name="global_file_url" id="global_file_url" value="'.$global->url.'">
                    <input type="hidden" name="global_file_name" id="global_file_name" value="'.$global->filename.'">
                    <a href="javascript:" class="fa fa-plus faplus_progress" style="padding:5px;background: #dfdfdf;" title="Insert Files" data-element="'.$global->id.'"></a>';

            $detail_output = '';
            $detail_invoice = DB::table("supplier_detail_invoice")->where('global_id',$id)->get();
            $nominal_codes = DB::table('nominal_codes')->orderBy('code','asc')->get();
			$vat_rates = DB::table('supplier_vat_rates')->get();
			$detail_invoice_count = count($detail_invoice);
            $i = 1;
            if(count($detail_invoice)) {
            	foreach($detail_invoice as $key => $detail) {
            		$detail_output.= '<tr>
					  <td>'.$i.'</td>
					  <td><input type="text" name="description_detail[]" class="form-control description_detail" value="'.$detail->description.'" placeholder="Enter Description"></td>
					  <td>
					    <select name="select_nominal_codes[]" class="form-control select_nominal_codes">
					      <option value="">Select Nominal Code</option>';
					      if(count($nominal_codes)) {
					        foreach($nominal_codes as $code){
					        	if($code->id == $detail->nominal_code) { $selected = 'selected'; }
					        	else { $selected = ''; }
					          	$detail_output.='<option value="'.$code->id.'" '.$selected.'>'.$code->code.' - '.$code->description.'</option>';
					        }
					      }
					    $detail_output.='</select>
					  </td>
					  <td><input type="text" name="net_detail[]" class="form-control net_detail" value="'.$detail->net.'" placeholder="Enter Net Value" oninput="'.$thisinput.'" onpaste="'.$thisinput.'"></td>
					  <td>
					    <select name="select_vat_rates[]" class="form-control select_vat_rates">
					      <option value="">Select VAT Rate</option>';
					      if(count($vat_rates)) {
					        foreach($vat_rates as $rate){
					        	if($rate->vat_rate == $detail->vat_rate) { $selected = 'selected'; }
					        	else { $selected = ''; }
					          	$detail_output.='<option value="'.$rate->vat_rate.'" '.$selected.'>'.$rate->vat_rate.' %</option>';
					        }
					      }
					    $detail_output.='</select>
					  </td>
					  <td><input type="text" name="vat_detail[]" class="form-control vat_detail" value="'.$detail->vat_value.'" placeholder="Enter VAT Value" readonly></td>
					  <td><input type="text" name="gross_detail[]" class="form-control gross_detail" value="'.$detail->gross.'" placeholder="Enter Gross" readonly></td>
					  <td class="detail_last_td" style="vertical-align: middle;text-align: center">
					    <a href="javascript:" class="fa fa-trash remove_detail_section"></a>';
					    $keyval = $key + 1;
					    if($detail_invoice_count == $keyval) {
					    	$detail_output.='&nbsp;&nbsp;<a href="javascript:" class="fa fa-plus add_detail_section"></a>';
					    }
					    else{
					    	$detail_output.='';
					    }
					  $detail_output.='</td>
					</tr>';
					$i++;
            	}
            }
            $datajson['supplier_output'] = $supplier_output;
            $datajson['global_output'] = $global_output;
            $datajson['detail_output'] = $detail_output;
            $datajson['attachment_output'] = $attachment_output;
            $datajson['supplier_id'] = $supplier_id;
            echo json_encode($datajson);	
        }
	}
	public function view_purchase_invoice_supplier()
	{
		$id = Input::get('id');
		$global = DB::table('supplier_global_invoice')->where('id',$id)->first();

		if(count($global))
		{
            $detail_output = '';
            $detail_invoice = DB::table("supplier_detail_invoice")->where('global_id',$id)->get();
            $nominal_codes = DB::table('nominal_codes')->get();
			$vat_rates = DB::table('supplier_vat_rates')->get();
			$detail_invoice_count = count($detail_invoice);
            $i = 1;

            $detail_output = '';
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
            		$detail_output.= '<tr>
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
            	$detail_output.='<tr>
            		<td colspan="3" style="text-align:right">Total:</td>
            		<td>'.number_format_invoice($total_net).'</td>
            		<td></td>
            		<td>'.number_format_invoice($total_vat).'</td>
            		<td>'.number_format_invoice($total_gross).'</td>
            	</tr>';
            }
            $datajson['detail_output'] = $detail_output;
            echo json_encode($datajson);	
        }
	}
	
	public function load_all_global_invoice()
	{
		$type = Input::get('type');
		if($type == "1")
		{
			$year = Input::get('year');
			$invoicelist = DB::select('SELECT * from `supplier_global_invoice` WHERE `invoice_date` LIKE "'.$year.'%" ORDER BY `id` DESC');
		}
		elseif($type == "2")
		{
			$invoicelist = DB::table('supplier_global_invoice')->orderBy('id','desc')->get();
		}
		elseif($type == "3")
		{
			$from = date('Y-m-d', strtotime(Input::get('from')));
			$to = date('Y-m-d', strtotime(Input::get('to')));

			$invoicelist = DB::table('supplier_global_invoice')->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->orderBy('id','desc')->get();
		}
		$i = 1;
		$output = '';
		if(count($invoicelist)){ 
			foreach($invoicelist as $key => $global){ 
				$inv_date_str = strtotime($global->invoice_date);
				$supplier_details = DB::table('suppliers')->where('id',$global->supplier_id)->first();
	            $supplier_code = '';
	            $supplier_name = '';
	            $balance = 0.00;
	            if(count($supplier_details)) {
	              $supplier_code = $supplier_details->supplier_code;
	              $supplier_name = $supplier_details->supplier_name;
	              $balance = $supplier_details->opening_balance;
	            }
				$output.='<tr>
	              <td>'.$i.'</td>
	              <td>'.$global->invoice_no.'</td>
	              <td>'.date('d-M-Y', strtotime($global->invoice_date)).'</td>
	              <td>'.$supplier_code.'</td>
	              <td>'.$supplier_name.'</td>
	              <td style="text-align: right">'.number_format_invoice($balance).'</td>
	              <td style="text-align: right">'.number_format_invoice($global->net).'</td>
	              <td style="text-align: right">'.number_format_invoice($global->vat).'</td>
	              <td style="text-align: right">'.number_format_invoice($global->gross).'</td>
	              <td><a href="javascript:" class="journal_id_viewer" data-element="'.$global->journal_id.'">'.$global->journal_id.'</a></td>
	              <td><a href="javascript:" class="fa fa-edit edit_purchase_invoice" data-element="'.$global->id.'" title="Edit Purchase Invoice"></a></td>
	            </tr>';
				$i++;
			}
		}
		if($i == 1)
        {
          $output.='<tr>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center">Empty</td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          	<td align="center"></td>
	          </tr>';
        }
        echo $output;
	}
	public function export_all_global_invoice()
	{
		$type = Input::get('type');
		if($type == "1")
		{
			$year = Input::get('year');
			$invoicelist = DB::select('SELECT * from `supplier_global_invoice` WHERE `invoice_date` LIKE "'.$year.'%"');
		}
		elseif($type == "2")
		{
			$invoicelist = DB::table('supplier_global_invoice')->get();
		}
		elseif($type == "3")
		{
			$from = date('Y-m-d', strtotime(Input::get('from')));
			$to = date('Y-m-d', strtotime(Input::get('to')));

			$invoicelist = DB::table('supplier_global_invoice')->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->get();
		}
		$columns_1 = array('S.No', 'Invoice No', 'Date', 'Supplier Code','Supplier Name', 'Net', 'VAT', 'Gross', 'Journal ID');
		$filename = time().'_Supplier Invoice Management Report.csv';
		$fileopen = fopen('public/'.$filename.'', 'w');
	    fputcsv($fileopen, $columns_1);

		$i = 1;
		$output = '';
		if(count($invoicelist)){ 
			foreach($invoicelist as $key => $global){ 
				$inv_date_str = strtotime($global->invoice_date);
				$supplier_details = DB::table('suppliers')->where('id',$global->supplier_id)->first();
	            $supplier_code = '';
	            $supplier_name = '';
	            $balance = 0.00;
	            if(count($supplier_details)) {
	              $supplier_code = $supplier_details->supplier_code;
	              $supplier_name = $supplier_details->supplier_name;
	              $balance = $supplier_details->opening_balance;
	            }
	            $columns_2 = array($i, $global->invoice_no, date('d-M-Y', strtotime($global->invoice_date)),$supplier_code, $supplier_name, number_format_invoice($global->net), number_format_invoice($global->vat), number_format_invoice($global->gross), $global->journal_id);
				fputcsv($fileopen, $columns_2);
				$i++;
			}
		}
		fclose($fileopen);
        echo $filename;
	}
	public function export_supplier_transaction_list()
	{
		$id = Input::get('supplier_id');
		$details = DB::table('suppliers')->where('id',$id)->first();
		if(count($details))
		{
			$formatted_bal = '0.00';
			$opening_bal = '0.00';
			if($details->opening_balance != "")
			{
				$opening_bal = $details->opening_balance;
				$formatted_bal = number_format_invoice($details->opening_balance);
			}
			if($opening_bal < 0)
			{
				$textval = 'Client is Owed';
				$debit_open = $formatted_bal;
				$credit_open = '';
			}
			elseif($opening_bal > 0)
			{
				$textval = 'Client Owes Back';
				$debit_open = '';
				$credit_open = $formatted_bal;
			}
			else{
				$textval = 'Opening Balance';
				$debit_open = '';
				$credit_open = $formatted_bal;
			}

			$columns_1 = array('Date', 'Source', 'Description', 'Debit', 'Credit','Balance');
			$filename = time().'_Supplier Transaction List Report.csv';
			$fileopen = fopen('public/'.$filename.'', 'w');
		    fputcsv($fileopen, $columns_1);

		    $columns_2 = array('', 'Opening Balance', 'Opening Balance', $debit_open, $credit_open,$formatted_bal);
		    fputcsv($fileopen, $columns_2);

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
						$source = 'Purchase Invoice';
						$textvalue = 'Purchase Invoice - '.$list->invoice_no;
						$amount_invoice = number_format_invoice($list->gross);
						$amount_payment='';
						$balance_val = $balance_val + $list->gross;
					}
					else{
						$source = 'Payments'; 
						$textvalue = 'Payment Made';
						$amount_payment = number_format_invoice($list->amount);
						$amount_invoice='';
						$balance_val = $balance_val - $list->amount;
					}

					$columns_3 = array(date('d-M-Y', strtotime($list->transaction_date)), $source, $textvalue, $amount_payment,$amount_invoice,number_format_invoice($balance_val));
		    		fputcsv($fileopen, $columns_3);
				}
			}

			fclose($fileopen);
        	echo $filename;
		}
	}
	
	public function store_supplier_vat_rate()
	{
		$value = Input::get('value');
		$tr_length = Input::get('tr_length');
		$ival = $tr_length + 1;
		$data['vat_rate'] = $value;
		$data['status'] = 0;
		$id = DB::table('supplier_vat_rates')->insertGetid($data);

		echo '<tr class="vat_tr">
			<td>'.$ival.'</td>
			<td>'.$value.' %</td>
			<td>
				<a href="javascript:" class="fa fa-check" data-element="'.$id.'" data-status="1" title="Disable Rate" style="color:green"></a>
			</td>
		</tr>';

	}
	public function change_supplier_vat_status()
	{
		$id = Input::get('id');
		$status = Input::get('status');

		$data['status'] = $status;
		DB::table('supplier_vat_rates')->where('id',$id)->update($data);
	}
	public function delete_supplier_global_attachment(){
		$global_id = Input::get('global_id');

		$data['url'] = '';
		$data['filename'] = '';
		DB::table('supplier_global_invoice')->where('id',$global_id)->update($data);
	}
	public function check_supplier_journal_repost(){
		$globalid = Input::get('invid');

		$global_details = DB::table('supplier_global_invoice')->where('id',$globalid)->first();
		$next_connecting_journal = $global_details->journal_id;
		$supplier_details = DB::table('suppliers')->where('id',$global_details->supplier_id)->first();

		DB::table('journals')->where('connecting_journal_reference',$next_connecting_journal)->delete();
		DB::table('journals')->where('connecting_journal_reference','like',$next_connecting_journal.'.%')->delete();


		$journal_ref = time().'_'.$global_details->reference;
		$datajournal['journal_date'] = $global_details->invoice_date;
		$datajournal['connecting_journal_reference'] = $next_connecting_journal;
		$datajournal['reference'] = $journal_ref;
		$datajournal['description'] = 'Purchase Invoice '.$supplier_details->supplier_name.' '.$global_details->reference.''; 	
		$datajournal['nominal_code'] = '813'; 	
		$datajournal['dr_value'] = '0.00';
		$datajournal['cr_value'] = $global_details->gross; 	
		$datajournal['journal_source'] = 'PI';

		DB::table('journals')->insert($datajournal);

		if($global_details->vat == "0" || $global_details->vat == "0.00" || $global_details->vat == "0.0") {

		}
		else{
			$next_connecting_journal = $next_connecting_journal + 0.01;
			$datajournal['journal_date'] = $global_details->invoice_date;
			$datajournal['connecting_journal_reference'] = $next_connecting_journal;
			$datajournal['reference'] = $journal_ref;
			$datajournal['description'] = 'Purchase Invoice '.$supplier_details->supplier_name.' '.$global_details->reference.''; 	
			$datajournal['nominal_code'] = '845'; 	
			$datajournal['dr_value'] = $global_details->vat;
			$datajournal['cr_value'] = '0.00'; 	
			$datajournal['journal_source'] = 'PI';
			DB::table('journals')->insert($datajournal);
		}

		$next_connecting_journal = $next_connecting_journal + 0.01;

		$detail_invs = DB::table('supplier_detail_invoice')->where('global_id',$globalid)->get();
		if(count($detail_invs))
		{
			foreach($detail_invs as $inv){
				$code_details = DB::table('nominal_codes')->where('id',$inv->nominal_code)->first();
				$codes = '';
				if(count($code_details)){
					$codes = $code_details->code; 
				}
				$datajournal['journal_date'] = $global_details->invoice_date;
				$datajournal['connecting_journal_reference'] = $next_connecting_journal;
				$datajournal['reference'] = $journal_ref;
				$datajournal['description'] = 'Purchase Invoice '.$supplier_details->supplier_name.' '.$global_details->reference.''; 	
				$datajournal['nominal_code'] = $codes;
				$datajournal['dr_value'] = $inv->net;
				$datajournal['cr_value'] = '0.00'; 	
				$datajournal['journal_source'] = 'PI';
				DB::table('journals')->insert($datajournal);

				$next_connecting_journal = $next_connecting_journal + 0.01;
			}
		}
	}
	public function store_supplier_invoice()
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
			$id = $code;
			$count = sprintf("%04d",$code);
			$dataid['supplier_code'] = 'GBS'.$count;
			DB::table('suppliers')->where('id',$code)->update($dataid);
		}
		else{
			DB::table('suppliers')->where('id',$id)->update($data);
		}

		$supplier_count_invoice = DB::table('suppliers')->orderBy('id','desc')->first(); 
        if(count($supplier_count_invoice))
        {
          $count_invoice = substr($supplier_count_invoice->supplier_code,3,7);
          $count_invoice = sprintf("%04d",$count_invoice + 1);
        }
        else{
          $count_invoice = sprintf("%04d",1);
        }

        $message = '<p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Supplier Added Successfully</p>';

        $suppliers = DB::table('suppliers')->orderBy('supplier_name','asc')->get();

        $drop_supplier='<option value="">Select Supplier</option>';
        if(count($suppliers)){
		    foreach($suppliers as $supplier){

		    	if($id == $supplier->id){
		    		$selected = 'selected';
		    	}
		    	else{
		    		$selected = '';
		    	}


		    	$drop_supplier.='<option value="'.$supplier->id.'" '.$selected.'>'.$supplier->supplier_name.'</option>';
		    }
		}

		$get_details = DB::table('suppliers')->where('id',$id)->first();
		if(count($get_details))
		{
			$data['supplier_code'] = $get_details->supplier_code;
			$data['supplier_name'] = $get_details->supplier_name;
			$data['web_url'] = $get_details->web_url;
			$data['phone_no'] = $get_details->phone_no;
			$data['email'] = $get_details->email;
			$data['iban'] = $get_details->iban;
			$data['bic'] = $get_details->bic;
			$data['vat_no'] = $get_details->vat_no;
			$data['currency'] = $get_details->currency;
			$data['opening_balance'] = $get_details->opening_balance;

			$data['code'] = 'GBS'.$count_invoice;
			$data['message'] = $message;
			$data['drop_supplier'] = $drop_supplier;

			echo json_encode($data);
		}

		//echo json_encode(array('code' =>  'message' => $message, 'drop_supplier' => $drop_supplier));
		//return redirect::back()->with('message','Supplier Added Successfully');
	}
	public function purchase_invoice_to_process(){
		$global_invoices = DB::table('supplier_global_invoice')->orderBy('id','desc')->get();
		$unprocessed_purchase_files = DB::table('supplier_purchase_invoice_files')->orderBy('id','desc')->get();
		// $processed_purchase_files = DB::table('supplier_purchase_invoice_files')->where('status',0)->orderBy('id','desc')->get();
		$suppliers = DB::table('suppliers')->orderBy('supplier_name','ASC')->get();
		return view('user/supplier_invoice_management/purchase_invoice', array('title' => 'Supplier Invoice Management','global_invoices' => $global_invoices,'unprocessed_purchase_files' => $unprocessed_purchase_files, 'suppliers' => $suppliers));
	}
	public function change_supplier_files_supplier_id(){
		$id = Input::get('id');
		$value = Input::get('value');
		$data['supplier_id'] = $value;
		DB::table('supplier_purchase_invoice_files')->where('id',$id)->update($data);
	}
	public function change_supplier_files_inv_date(){
		$id = Input::get('id');
		$value = explode('-',Input::get('value'));
		$data['inv_date'] = $value[2].'-'.$value[1].'-'.$value[0];
		DB::table('supplier_purchase_invoice_files')->where('id',$id)->update($data);
	}
	public function change_supplier_files_ignore_file(){
		$id = Input::get('id');
		$value = Input::get('value');
		$data['ignore_file'] = $value;
		DB::table('supplier_purchase_invoice_files')->where('id',$id)->update($data);
	}
	public function delete_purchase_files(){
		$id = Input::get('id');
		DB::table('supplier_purchase_invoice_files')->where('id',$id)->delete();
	}
	public function get_purchase_invoice_files_details(){
		$id = Input::get('id');
		$details = DB::table('supplier_purchase_invoice_files')->where('id',$id)->first();
		if(count($details))
		{
			$data['filename'] = $details->filename;
			$data['url'] = $details->url;
			$data['supplier_id'] = $details->supplier_id;
			if($details->inv_date != ""){
				$data['inv_date'] = date('d-m-Y', strtotime($details->inv_date));
			} else {
				$data['inv_date'] = '';
			}
			echo json_encode($data);
		}
	}
	public function supplier_invoice_report_download(){
		$type = Input::get('type');
		$from = date('Y-m-d', strtotime(Input::get('from')));
		$to = date('Y-m-d', strtotime(Input::get('to')));




		if($type == 1){
			$invoicelist = DB::table('supplier_global_invoice')->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->get();			

			$columns_1 = array('S.No', 'Invoice No', 'Date', 'Supplier Code','Supplier Name', 'Net', 'VAT', 'Gross', 'Journal ID');
			$filename = time().'_Supplier Invoice Management Report.csv';
			$fileopen = fopen('public/'.$filename.'', 'w');
		    fputcsv($fileopen, $columns_1);

			$i = 1;
			$output = '';
			if(count($invoicelist)){ 
				foreach($invoicelist as $key => $global){ 
					$inv_date_str = strtotime($global->invoice_date);
					$supplier_details = DB::table('suppliers')->where('id',$global->supplier_id)->first();
		            $supplier_code = '';
		            $supplier_name = '';
		            $balance = 0.00;
		            if(count($supplier_details)) {
		              $supplier_code = $supplier_details->supplier_code;
		              $supplier_name = $supplier_details->supplier_name;
		              $balance = $supplier_details->opening_balance;
		            }
		            $columns_2 = array($i, $global->invoice_no, date('d-M-Y', strtotime($global->invoice_date)),$supplier_code, $supplier_name, number_format_invoice($global->net), number_format_invoice($global->vat), number_format_invoice($global->gross), $global->journal_id);
					fputcsv($fileopen, $columns_2);
					$i++;
				}
			}
			fclose($fileopen);
	        echo $filename;

		}
		elseif($type == 2){

			$invoicelist = DB::table('supplier_global_invoice')->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->orderBy('invoice_date', 'desc')->get();			

			$columns_1 = array('Date', 'Invoice No', 'Supplier Code','Supplier Name', 'Debit Nominal', 'Description', 'Net', 'VAT', 'Gross');
			$filename = time().'_Supplier Invoice Management Report.csv';
			$fileopen = fopen('public/'.$filename.'', 'w');
		    fputcsv($fileopen, $columns_1);

			$i = 1;
			$output = '';
			if(count($invoicelist)){ 
				foreach($invoicelist as $key => $global){ 

					$detail_invoice = DB::table("supplier_detail_invoice")->where('global_id',$global->id)->get();	

					$inv_date_str = strtotime($global->invoice_date);
					$supplier_details = DB::table('suppliers')->where('id',$global->supplier_id)->first();
		            $supplier_code = '';
		            $supplier_name = '';
		            $balance = 0.00;
		            if(count($supplier_details)) {
		              $supplier_code = $supplier_details->supplier_code;
		              $supplier_name = $supplier_details->supplier_name;
		              $balance = $supplier_details->opening_balance;
		            }
		            /*$columns_2 = array( $global->invoice_no, date('d-M-Y', strtotime($global->invoice_date)),$supplier_code, $supplier_name, number_format_invoice($balance), number_format_invoice($global->net), number_format_invoice($global->vat), number_format_invoice($global->gross), $global->journal_id);
					fputcsv($fileopen, $columns_2);*/
					
					$j='';
					if(count($detail_invoice)){
						foreach ($detail_invoice as $details) {

							$nominal = DB::table('nominal_codes')->where('id', $details->nominal_code)->first();

							$nominal_codes = $nominal->code.'-'.$nominal->description;

							$columns_2 = array(date('d-M-Y', strtotime($global->invoice_date)), $global->invoice_no, $supplier_code, $supplier_name, $nominal_codes, $details->description, number_format_invoice($details->net), number_format_invoice($details->vat_value), number_format_invoice($details->gross));
						fputcsv($fileopen, $columns_2);

							$j++;	
						}
					}

					$i++;

				}
			}
			fclose($fileopen);
	        echo $filename;

		}
		else{
			$invoicelist = DB::table('supplier_global_invoice')->where('invoice_date','>=',$from)->where('invoice_date','<=',$to)->get();

			/*$columns_1 = array('Debit Nominal', 'Net', 'VAT', 'Gross');*/
			$filename = time().'_Supplier Invoice Management Report.csv';
			$fileopen = fopen('public/'.$filename.'', 'w');
		    /*fputcsv($fileopen, $columns_1);*/

			$i = 1;
			$output = '';

			$total_net='';
			$total_vat_value='';
			$total_gross='';

			$invoice_id='';
			
			if(count($invoicelist)){ 
				foreach($invoicelist as $key => $global){

					if($invoice_id == ''){
						$invoice_id = $global->id;
					}
					else{
						$invoice_id = $invoice_id.','.$global->id;
					}

					$detail_invoice = DB::table("supplier_detail_invoice")->where('global_id',$global->id)->get();	

					$inv_date_str = strtotime($global->invoice_date);
					$supplier_details = DB::table('suppliers')->where('id',$global->supplier_id)->first();
		            $supplier_code = '';
		            $supplier_name = '';
		            $balance = 0.00;
		            if(count($supplier_details)) {
		              $supplier_code = $supplier_details->supplier_code;
		              $supplier_name = $supplier_details->supplier_name;
		              $balance = $supplier_details->opening_balance;
		            }	            
					
					
					if(count($detail_invoice)){
						foreach ($detail_invoice as $details) {

							if($total_net==''){
								$total_net= $details->net;
							}
							else{
								$total_net = $total_net+$details->net;
							}

							if($total_vat_value == ''){
								$total_vat_value = $details->vat_value;
							}
							else{
								$total_vat_value = $total_vat_value+$details->vat_value;
							}

							if($total_gross == ''){
								$total_gross = $details->gross;
							}
							else{
								$total_gross = $total_gross+$details->gross;
							}

							$nominal = DB::table('nominal_codes')->where('id', $details->nominal_code)->first();
							$nominal_codes = $nominal->code.'-'.$nominal->description;

							/*$columns_2 = array($nominal_codes, number_format_invoice($details->net), number_format_invoice($details->vat_value), number_format_invoice($details->gross));
						fputcsv($fileopen, $columns_2);*/

						}
					}			


					$i++;
				}
			}	


			/*$columns_3 = array('', '', '', '');
			fputcsv($fileopen, $columns_3);

			$columns_4 = array('', number_format_invoice($total_net), number_format_invoice($total_vat_value), number_format_invoice($total_gross));
			fputcsv($fileopen, $columns_4);*/

			$columns_5 = array('', '', '', '');
			fputcsv($fileopen, $columns_5);

			$columns_6 = array('Total Net', number_format_invoice($total_net));
			fputcsv($fileopen, $columns_6);

			$columns_7 = array('Total VAT', number_format_invoice($total_vat_value));
			fputcsv($fileopen, $columns_7);

			$columns_7 = array('Total Gross Purchases', number_format_invoice($total_gross));
			fputcsv($fileopen, $columns_7);

			$columns_8 = array('', '', '', '');
			fputcsv($fileopen, $columns_8);

			$columns_9 = array('NET SUMMARY', 'SUM OF NET');
			fputcsv($fileopen, $columns_9);

			$explode_invoice_id = explode(',', $invoice_id);
			
			$nominal_code_list = DB::table("supplier_detail_invoice")->whereIn('global_id',$explode_invoice_id)->where('net', '!=', 0)->groupBy('nominal_code')->get();

			$nominal_grand='';
			if(count($nominal_code_list)){
				foreach ($nominal_code_list as $single_nominal) {

					$single_normail_total = DB::table("supplier_detail_invoice")->whereIn('global_id',$explode_invoice_id)->where('nominal_code', $single_nominal->nominal_code)->sum('net');

					$nominal = DB::table('nominal_codes')->where('id', $single_nominal->nominal_code)->first();
					$nominal_codes = $nominal->code.'-'.$nominal->description;

					if($nominal_grand == ''){
						$nominal_grand = $single_normail_total;
					}
					else{
						$nominal_grand = $nominal_grand+$single_normail_total;
					}

					$columns_10 = array($nominal_codes, number_format_invoice($single_normail_total));
					fputcsv($fileopen, $columns_10);
				}
			}

			$columns_11 = array('GRAND TOTAL', number_format_invoice($nominal_grand));
			fputcsv($fileopen, $columns_11);

			$columns_12 = array('', '', '', '');
			fputcsv($fileopen, $columns_12);

			$columns_13 = array('VAT SUMMARY', 'SUM OF NET');
			fputcsv($fileopen, $columns_13);

			$vat_percentage_list = DB::table("supplier_detail_invoice")->whereIn('global_id',$explode_invoice_id)->groupBy('vat_rate')->get();


			$vat_grand = '';
			if(count($vat_percentage_list)){
				foreach ($vat_percentage_list as $key => $vat_percentage) {

					$single_vat_percentage =  $single_normail_total = DB::table("supplier_detail_invoice")->whereIn('global_id',$explode_invoice_id)->where('vat_rate', $vat_percentage->vat_rate)->sum('net');

					$percentage = $vat_percentage->vat_rate.' %';

					if($vat_grand == ''){
						$vat_grand = $single_vat_percentage;
					}
					else{
						$vat_grand = $vat_grand+$single_vat_percentage;
					}

					$columns_14 = array($percentage, number_format_invoice($single_vat_percentage));
					fputcsv($fileopen, $columns_14);
				}
			}

			$columns_15 = array('GRAND TOTAL', number_format_invoice($vat_grand));
			fputcsv($fileopen, $columns_15);
			

			fclose($fileopen);
	        echo $filename;
		}

		
	}
}