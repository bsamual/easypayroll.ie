<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Year;
use App\Week;
use App\Task;
use App\Classified;
use App\User;
use App\Vatclients;
use App\Task_Job;
use App\CmClients;
use App\Job_Break_Time;
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
class CroardController extends Controller {
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
	public function __construct(year $year, week $week, task $task, classified $classified, user $user, vatclients $vatclients, task_job $task_job, cmclients $cmclients, job_break_time  $job_break_time)
	{
		$this->middleware('userauth');
		$this->year = $year;
		$this->week = $week;
		$this->task = $task;
		$this->classified = $classified;
		$this->user = $user;
		$this->vatclients = $vatclients;
		$this->task_job = $task_job;
		$this->cmclients = $cmclients;
		$this->job_break_time = $job_break_time;
		date_default_timezone_set("Europe/Dublin");
		//date_default_timezone_set("Asia/Calcutta");
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function manage_croard()
	{
		$client = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id','tye','ard','cro')->orderBy('id','asc')->get();
		$cro = DB::table('cro_credentials')->first();
		return view('user/croard/croardmanager', array('title' => 'Bubble - CRO ARD Manager', 'cro' => $cro,'clientlist' => $client));
	}
	public function get_company_details_cro()
	{
		$company_number = Input::get('company_number');
		$indicator = Input::get('indicator');

		if($indicator == "C") { $indi = 'Limited Company'; }
		else { $indi = 'Registered Business'; }

		$ch = curl_init();
		$url = "https://services.cro.ie/cws/company/".$company_number."/".$indicator."";

		$cro = DB::table('cro_credentials')->first();
		$authenticate = $cro->username.':'.$cro->api_key;
		 
		$headers = array( "Authorization: Basic ".base64_encode($authenticate),  
		    "Content-Type: application/json", 
		    "charset: utf-8");
		 
		 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($ch, CURLOPT_PROXY, 'http://ip of your proxy:8080');  // Proxy if applicable
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 0); 

		$response = curl_exec($ch);
 
		// Some values from the header if want to take a look... 
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headerOut = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		//echo $code.'<p>'.$headerOut.'</p>';
		 
		 
		// Don't forget to close handle.
		curl_close($ch);
		$results_array = json_decode($response);


		$address = '';
		if(isset($results_array->company_addr_1))
		{
			$address.= $results_array->company_addr_1;
		}
		if(isset($results_array->company_addr_2))
		{
			$address.= PHP_EOL.$results_array->company_addr_2;
		}
		if(isset($results_array->company_addr_3))
		{
			$address.= PHP_EOL.$results_array->company_addr_3;
		}
		if(isset($results_array->company_addr_3))
		{
			$address.= PHP_EOL.$results_array->company_addr_3;
		}


		$output = '
		<table class="table">
	        <tbody>
	          <tr>
	            <td>Company Number:</td>
	            <td><input type="text" name="company_number" class="form-control company_number" value="'.$results_array->company_num.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company / Business indicator:</td>
	            <td><input type="text" name="indicator_text" class="form-control indicator_text" value="'.$indi.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Name:</td>
	            <td><input type="text" name="company_name" class="form-control company_name" value="'.$results_array->company_name.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Address:</td>
	            <td><textarea name="company_address" class="form-control company_address" disabled style="height:110px">'.$address.'</textarea></td>
	          </tr>
	          <tr>
	            <td>Company Registration Date:</td>
	            <td><input type="text" name="company_reg_date" class="form-control company_reg_date" value="'.date('Y-m-d', strtotime($results_array->company_reg_date)).'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Status:</td>
	            <td><input type="text" name="company_status_desc" class="form-control company_status_desc" value="'.$results_array->company_status_desc.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Status Date:</td>
	            <td><input type="text" name="company_status_date" class="form-control company_status_date" value="'.date('Y-m-d', strtotime($results_array->company_status_date)).'" disabled></td>
	          </tr>
	          <tr>
	            <td>Next ARD:</td>
	            <td><input type="text" name="next_ar_date" class="form-control next_ar_date" value="'.date('Y-m-d', strtotime($results_array->next_ar_date)).'" disabled></td>
	          </tr>
	          <tr>
	            <td>Last ARD:</td>
	            <td><input type="text" name="last_ar_date" class="form-control last_ar_date" value="'.date('Y-m-d', strtotime($results_array->last_ar_date)).'" disabled></td>
	          </tr>
	          <tr>
	            <td>Accounts Upto:</td>
	            <td><input type="text" name="last_acc_date" class="form-control last_acc_date" value="'.date('Y-m-d', strtotime($results_array->last_acc_date)).'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Type:</td>
	            <td><input type="text" name="comp_type_desc" class="form-control comp_type_desc" value="'.$results_array->comp_type_desc.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Type Code:</td>
	            <td><input type="text" name="company_type_code" class="form-control company_type_code" value="'.$results_array->company_type_code.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Company Status Code:</td>
	            <td><input type="text" name="company_status_code" class="form-control company_status_code" value="'.$results_array->company_status_code.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Place of Business:</td>
	            <td><input type="text" name="place_of_business" class="form-control place_of_business" value="'.$results_array->place_of_business.'" disabled></td>
	          </tr>
	          <tr>
	            <td>Eircode:</td>
	            <td><input type="text" name="eircode" class="form-control eircode" value="'.$results_array->eircode.'" disabled></td>
	          </tr>
	        </tbody>
	      </table>';
		
		echo $output;
	}
	public function refresh_cro_ard()
	{
		$client_id = Input::get('clientid');
		$cro = Input::get('cro');

		$company_number = Input::get('cro');
		$indicator = 'C';

		//$ch = curl_init();
		$url = "https://services.cro.ie/cws/company/".$company_number."/".$indicator."";

		$cro = DB::table('cro_credentials')->first();
		$authenticate = $cro->username.':'.$cro->api_key;
		 
		$headers = array( "Authorization: Basic ".base64_encode($authenticate),  
		    "Content-Type: application/json", 
		    "charset: utf-8");
		 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		$userpwd = $cro->username.":".$cro->api_key; // new
		curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ) ); // change
		curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		
// 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// 		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
// 		// curl_setopt($ch, CURLOPT_PROXY, 'http://ip of your proxy:8080');  // Proxy if applicable
// 		curl_setopt($ch, CURLOPT_FAILONERROR,1);
// 		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
// 		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
// 		curl_setopt($ch, CURLOPT_URL, $url );
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// 		curl_setopt($ch, CURLOPT_POST, 0); 

		$response = curl_exec($ch);
 
		// Some values from the header if want to take a look... 
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headerOut = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		//echo $code.'<p>'.$headerOut.'</p>';
		 
		 
		// Don't forget to close handle.
		curl_close($ch);
		$results_array = json_decode($response);

		if(count($results_array))
		{
			$nextard = $results_array->next_ar_date;
			$company = $results_array->company_name;
		}
		else{
			$nextard = '';
			$company = '';
		}
		

		$client = DB::table('cm_clients')->where('client_id',$client_id)->first();
		$ard = explode("/",$client->ard);

		if(count($ard) > 1)
		{
			$ard_date_month = $ard[0].'/'.$ard[1];
		}
		else{
			$ard_date_month = '';
		}

		if($nextard != "")
		{
			$api_date_month = date('d/m',strtotime($nextard));
			if($ard_date_month == $api_date_month)
			{
				$coreard = date('d/m/Y',strtotime($nextard));
				$corard_timestamp = strtotime($nextard);
				$ardstatus = "0";
			}
			else{
				$coreard = date('d/m/Y',strtotime($nextard));
				$corard_timestamp = strtotime($nextard);
				$ardstatus = "1";
			}
		}
		else{
			$coreard = '';
			$corard_timestamp = '';
			$ardstatus = "0";
		}

		if(strtolower($company) == strtolower($client->company))
		{
			$companyname = $company;
			$companystatus = "0";
		}
		else{
			$companyname = $company;
			$companystatus = "1";
		}

		$data['company_name'] = $companyname;
		$data['cro_ard'] = $coreard;
		
		$detail = DB::table('croard')->where('client_id',$client_id)->first();
		if(count($detail))
		{
			DB::table('croard')->where('client_id',$client_id)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			DB::table('croard')->insert($data);
		}
		
		echo json_encode(array('company_name' => $companyname, 'next_ard' => $coreard, 'corard_timestamp' => $corard_timestamp, 'companystatus' => $companystatus, 'ardstatus' => $ardstatus));
	}
	public function refresh_blue_cro_ard()
	{
		$client_id = Input::get('clientid');
		$cro = Input::get('cro');

		$company_number = Input::get('cro');
		$indicator = 'C';

		$ch = curl_init();
		$url = "https://services.cro.ie/cws/company/".$company_number."/".$indicator."";

		$cro = DB::table('cro_credentials')->first();
		$authenticate = $cro->username.':'.$cro->api_key;
		 
		$headers = array( "Authorization: Basic ".base64_encode($authenticate),  
		    "Content-Type: application/json", 
		    "charset: utf-8");
		 
		 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($ch, CURLOPT_PROXY, 'http://ip of your proxy:8080');  // Proxy if applicable
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 0); 

		$response = curl_exec($ch);
 
		// Some values from the header if want to take a look... 
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headerOut = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		//echo $code.'<p>'.$headerOut.'</p>';
		 
		 
		// Don't forget to close handle.
		curl_close($ch);
		$results_array = json_decode($response);

		if(count($results_array))
		{
			$nextard = $results_array->next_ar_date;
			$company = $results_array->company_name;
		}
		else{
			$nextard = '';
			$company = '';
		}
		

		$client = DB::table('cm_clients')->where('client_id',$client_id)->first();
		$ard = explode("/",$client->ard);

		if(count($ard) > 1)
		{
			$ard_date_month = $ard[0].'/'.$ard[1];
		}
		else{
			$ard_date_month = '';
		}

		if($nextard != "")
		{
			$api_date_month = date('d/m',strtotime($nextard));
			if($ard_date_month == $api_date_month)
			{
				$coreard = date('d/m/Y',strtotime($nextard));
				$corard_timestamp = strtotime($nextard);
				$ardstatus = "0";
			}
			else{
				$coreard = date('d/m/Y',strtotime($nextard));
				$corard_timestamp = strtotime($nextard);
				$ardstatus = "1";
			}
		}
		else{
			$coreard = '';
			$corard_timestamp = '';
			$ardstatus = "0";
		}

		if(strtolower($company) == strtolower($client->company))
		{
			$companyname = $company;
			$companystatus = "0";
		}
		else{
			$companyname = $company;
			$companystatus = "1";
		}

		$data['company_name'] = $companyname;
		$data['cro_ard'] = $coreard;
		
		$detail = DB::table('croard')->where('client_id',$client_id)->first();
		if(count($detail))
		{
			DB::table('croard')->where('client_id',$client_id)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			DB::table('croard')->insert($data);
		}
		
		echo json_encode(array('company_name' => $companyname, 'next_ard' => $coreard, 'corard_timestamp' => $corard_timestamp, 'companystatus' => $companystatus, 'ardstatus' => $ardstatus));
	}
	public function update_cro_notes()
	{
		$value = Input::get('input_val');
		$clientid = Input::get('clientid');
		$details = DB::table('croard')->where('client_id',$clientid)->first();
		if(count($details))
		{
			$data['notes'] = $value;
			DB::table('croard')->where('id',$details->id)->update($data);
		}
		else{
			$data['notes'] = $value;
			DB::table('croard')->insert($data);
		}
	}
	public function update_rbo_submission()
	{
		$value = Input::get('input_val');
		$clientid = Input::get('clientid');
		$details = DB::table('croard')->where('client_id',$clientid)->first();
		if(count($details))
		{
			$data['rbo_submission'] = $value;
			DB::table('croard')->where('id',$details->id)->update($data);
		}
		else{
			$data['rbo_submission'] = $value;
			DB::table('croard')->insert($data);
		}
	}
	public function croard_upload_images()
	{
		$client_id = Input::get('hidden_client_id_croard');

		$upload_dir = 'uploads/croard_uploads';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.$client_id;
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

			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);

			$data['client_id'] = $client_id;
			$data['filename'] = $fname;
			$data['url'] = $upload_dir;
			$client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
			if(count($client_details))
			{
				$data['company_name'] = $client_details->company;
			}

			$details = DB::table('croard')->where('client_id',$client_id)->first();
			if(!count($details))
			{
				$insertedid = DB::table('croard')->insertGetid($data);
			}
			else{
				DB::table('croard')->where('client_id',$client_id)->update($data);
				$insertedid = $details->id;
			}

			$download_url = URL::to($filename);
			$delete_url = URL::to('user/delete_croard_files?file_id='.$insertedid.'');
			
		 	echo json_encode(array('id' => $insertedid,'filename' => $fname,'client_id' => $client_id, 'download_url' => $download_url, 'delete_url' => $delete_url));
		}
	}
	public function get_company_details_next_crd()
	{
		$company_number = Input::get('company_number');
		$indicator = Input::get('indicator');
		$client_id = Input::get('client_id');

		if($indicator == "C") { $indi = 'Limited Company'; }
		else { $indi = 'Registered Business'; }

		$ch = curl_init();
		$url = "https://services.cro.ie/cws/company/".$company_number."/C";

		$cro = DB::table('cro_credentials')->first();
		$authenticate = $cro->username.':'.$cro->api_key;
		 
		$headers = array( "Authorization: Basic ".base64_encode($authenticate),  
		    "Content-Type: application/json", 
		    "charset: utf-8");
		 
		 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($ch, CURLOPT_PROXY, 'http://ip of your proxy:8080');  // Proxy if applicable
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 0); 

		$response = curl_exec($ch);
 
		// Some values from the header if want to take a look... 
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headerOut = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		//echo $code.'<p>'.$headerOut.'</p>';
		// Don't forget to close handle.
		curl_close($ch);
		$results_array = json_decode($response);

		$color_status = '';
		$status_label = '';
		$cro_ard_val = date('d/m/Y', strtotime($results_array->next_ar_date));
		$dataval['cro_ard'] = $cro_ard_val;
		$dataval['signature_date'] = $results_array->next_ar_date;
		DB::table('croard')->where('client_id',$client_id)->update($dataval);
		$updated = 0;
		$cro_ard_details = DB::table('croard')->where('client_id',$client_id)->first();
		if(count($cro_ard_details))
		{
			$expandcroard = explode('/',$cro_ard_details->cro_ard);
	        if(count($expandcroard) > 1)
	        {
	          $correctcroard = $expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0];
	          if($correctcroard != $results_array->next_ar_date)
	          {
	          	$updated = 1;
	          	$datavall['signature'] = 0;
				DB::table('croard')->where('client_id',$client_id)->update($datavall);
	          }

	          $timestampcroard = strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]);

	          $current_date = date('Y-m-d');
	          $current_year = date('Y');
	          $croard_year = $expandcroard[2];

	          if($croard_year > $current_year)
	          {
	            $color_status = 'blue_status';
	            $status_label = 'Current Year OK';
	          }
	          else{
	            $firstdate = strtotime($correctcroard);
	            $seconddate = strtotime($current_date);

	            $diff = ceil(($firstdate - $seconddate)/60/60/24);
	            if($diff < 0 || $diff == 0)
	            {
	              $color_status = 'red_status';
	              $status_label = 'Submission Late';
	            }
	            elseif($diff <= 30)
	            {
	              $color_status = 'orange_status';
	              $status_label = 'Submission Pending';
	            }
	            elseif($diff > 30)
	            {
	              $color_status = 'green_status';
	              $status_label = 'Future Submission';
	            }
	          }
	        }
		}

		echo json_encode(array("croard" => date('d/m/Y', strtotime($results_array->next_ar_date)), "color_status" => $color_status, "status_label" => $status_label,'updated' => $updated));
	}
	public function edit_email_unsent_files_croard()
	{
		$admin_details = Db::table('admin')->first();
		$client_id = Input::get('client_id');
		$client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
		$result = DB::table('croard')->where('client_id',$client_id)->first();
		$files = '';
		$html = '<p>Hi '.$client_details->firstname.'</p>
		<p>Please find attached the B1 for '.$result->company_name.' which needs to be signed and sent back to my office at your very earliest convenience.</p>
		<p>I note this B1 can be scanned-in and emailed back to me, it must be signed and dated before you send it back, and the quality of the scan must be very good Quality.</p>

		'.$admin_details->croard_signature.'';
		$files ='<p>'.$result->filename.'</p>';
	    $subject = 'CROARD: '.$result->company_name.'';

	    
	    if(count($client_details))
	    {
	    	if($client_details->email2 != '')
			{
				$to_email = $client_details->email.','.$client_details->email2;
			}
			else{
				$to_email = $client_details->email;
			}
	    }
	    echo json_encode(["files" => $files,"html" => $html,"to" => $to_email,'subject' => $subject]);
	}
	public function email_unsent_files_croard()
	{
		$from_input = Input::get('select_user');
		$client_id = Input::get('hidden_client_id_email_croard');
		$details = DB::table('user')->where('user_id',$from_input)->first();
		$from = $details->email;
		$user_name = $details->lastname.' '.$details->firstname;
		$toemails = Input::get('to_user').','.Input::get('cc_unsent');
		$sentmails = Input::get('to_user').', '.Input::get('cc_unsent');
		$subject = Input::get('subject_unsent'); 
		$message = Input::get('message_editor');
		$explode = explode(',',$toemails);
		$data['sentmails'] = $sentmails;
		$croard_details = DB::table('croard')->where('client_id',$client_id)->first();
		$attach = $croard_details->url.'/'.$croard_details->filename;
		$filename = $croard_details->filename;

		if(count($explode))
		{
			foreach($explode as $exp)
			{
				$to = trim($exp);
				$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
				$data['message'] = $message;
				$contentmessage = view('user/email_share_paper_croard', $data);
				$email = new PHPMailer();
				$email->SetFrom($from, $user_name); //Name is optional
				$email->Subject   = $subject;
				$email->Body      = $contentmessage;
				$email->IsHTML(true);
				$email->AddAddress( $to );
				$email->AddAttachment( $attach , $filename );
				$email->Send();
			}
			$date = date('Y-m-d H:i:s');
			
			$client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
			$datamessage['message_id'] = time();
			$datamessage['message_from'] = $from_input;
			$datamessage['subject'] = $subject;
			$datamessage['message'] = $message;
			$datamessage['client_ids'] = $client_id;
			$datamessage['primary_emails'] = $client_details->email;
			$datamessage['secondary_emails'] = $client_details->email2;
			$datamessage['date_sent'] = $date;
			$datamessage['date_saved'] = $date;
			$datamessage['source'] = "CROARD";
			$datamessage['attachments'] = $attach;
			$datamessage['status'] = 1;
			DB::table('messageus')->insert($datamessage);


			DB::table('croard')->where('client_id',$client_id)->update(['last_email_sent' => $date]);
			
			$last_date = date('d F Y @ H : i', strtotime($date));
			echo $last_date.'||'.$client_id;
		}
		else{
			echo "1";
		}
	}
	public function change_yellow_status_croard()
	{
		$client_id = Input::get('client_id');
		$status = Input::get('status');
		$data['signature'] = $status;
		$date = date('Y-m-d');
		if($status == 1)
		{
			$data['signature_date'] = $date;
		}
		else{
			$data['signature_date'] = "";
		}
		DB::table('croard')->where('client_id',$client_id)->update($data);
		echo date('d/m/Y', strtotime($date));
	}
	public function save_croard_signature_date()
	{
		$client_id = Input::get('client');
		$date = explode('/', Input::get('date'));
		$data['signature_date'] = $date[2].'-'.$date[1].'-'.$date[0];
		DB::table('croard')->where('client_id',$client_id)->update($data);
	}
	public function croard_get_yellow_status_clients()
	{
		$clients = DB::table('croard')->where('signature',1)->orderBy('client_id','asc')->get();
		$output = '<table class="table own_table_white">
		<thead>
			<th>Client Code</th>
			<th>Company Name</th>
			<th>CRO Number</th>
			<th>Current CRO ARD</th>
			<th>Updated CRO ARD</th>
		</thead>
		<tbody>';
		if(count($clients))
		{
			foreach($clients as $client)
			{
				$client_details = DB::table('cm_clients')->where('client_id',$client->client_id)->first();
				$output.='<tr class="overlay_tr_'.$client->client_id.'">
					<td>'.$client->client_id.'</td>
					<td>'.$client->company_name.'</td>
					<td class="overlay_cro" data-element="'.$client->client_id.'">'.$client_details->cro.'</td>
					<td class="overlay_current_croard">'.$client->cro_ard.'</td>
					<td class="overlay_updated_croard"></td>
				</tr>';
			}
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function check_cro_in_api()
	{
		$company_number = Input::get('cro');
		$indicator = 'C';
		$client_id = Input::get('client');

		if($indicator == "C") { $indi = 'Limited Company'; }
		else { $indi = 'Registered Business'; }

		$ch = curl_init();
		$url = "https://services.cro.ie/cws/company/".$company_number."/C";

		$cro = DB::table('cro_credentials')->first();
		$authenticate = $cro->username.':'.$cro->api_key;
		 
		$headers = array( "Authorization: Basic ".base64_encode($authenticate),  
		    "Content-Type: application/json", 
		    "charset: utf-8");
		 
		 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($ch, CURLOPT_PROXY, 'http://ip of your proxy:8080');  // Proxy if applicable
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 0); 

		$response = curl_exec($ch);
 
		// Some values from the header if want to take a look... 
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$headerOut = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		//echo $code.'<p>'.$headerOut.'</p>';
		// Don't forget to close handle.
		curl_close($ch);
		$results_array = json_decode($response);

		$color_status = '';
		$status_label = '';
		$cro_ard_val = date('d/m/Y', strtotime($results_array->next_ar_date));
		$updated = 0;
		$ard_color = 'color:#000';

		$cro_ard_details = DB::table('croard')->where('client_id',$client_id)->first();
		if(count($cro_ard_details))
		{
			if($cro_ard_val != $cro_ard_details->cro_ard)
			{
				$dataval['cro_ard'] = $cro_ard_val;
				$dataval['signature'] = 0;
				$dataval['signature_date'] = '';

				$dataval['filename'] = '';
				$dataval['url'] = '';
				DB::table('croard')->where('client_id',$client_id)->update($dataval);
				$updated = 1;
			}
			else{
				$dataval['cro_ard'] = $cro_ard_details->cro_ard;
				$dataval['signature'] = 0;
				$dataval['signature_date'] = '';

				$dataval['filename'] = '';
				$dataval['url'] = '';
				DB::table('croard')->where('client_id',$client_id)->update($dataval);
				$updated = 0;
			}

			$expandcroard = explode('/',$cro_ard_val);
	        if(count($expandcroard) > 1)
	        {
	          $correctcroard = $expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0];
	          $timestampcroard = strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]);

	          $current_date = date('Y-m-d');
	          $current_year = date('Y');
	          $croard_year = $expandcroard[2];

	          if($croard_year > $current_year)
	          {
	            $color_status = 'blue_status';
	            $status_label = 'Current Year OK';
	            $ard_color = 'blue';
	          }
	          else{
	            $firstdate = strtotime($correctcroard);
	            $seconddate = strtotime($current_date);

	            $diff = ceil(($firstdate - $seconddate)/60/60/24);
	            if($diff < 0 || $diff == 0)
	            {
	              $color_status = 'red_status';
	              $status_label = 'Submission Late';
	              $ard_color = 'red';
	            }
	            elseif($diff <= 30)
	            {
	              $color_status = 'orange_status';
	              $status_label = 'Submission Pending';
	              $ard_color = 'orange';
	            }
	            elseif($diff > 30)
	            {
	              $color_status = 'green_status';
	              $status_label = 'Future Submission';
	              $ard_color = 'green';
	            }
	          }
	        }
		}

		echo json_encode(array("croard" => date('d/m/Y', strtotime($results_array->next_ar_date)), "color_status" => $color_status, "status_label" => $status_label,'updated' => $updated,'ard_color' => $ard_color));
	}
	public function save_croard_settings()
	{
		$signature = Input::get('message_editor');
		$cc = Input::get('croard_cc_input');
		$croard_days_input = Input::get('croard_days_input');

		$data['croard_signature'] = $signature;
		$data['croard_cc_email'] = $cc;
		$data['croard_submission_days'] = $croard_days_input;
		DB::table('admin')->where('id',1)->update($data);

		$username = Input::get('username');
		$api_key = Input::get('api_key');
		DB::table('cro_credentials')->where('id',1)->update(['username' =>$username, 'api_key' =>$api_key]);
		return Redirect::back()->with('message_settings',"Croard Settings Saved successfully.");
	}
	public function rbo_review_list()
	{
		$clientlist = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id','tye','ard','cro')->orderBy('id','asc')->get();
		$output = '<table class="table table-fixed-header1 own_table_white" style="width:100%;margin-top:0px; background: #fff">
          <thead class="header">
              <th style="width:3.5%;text-align: left;">S.No <i class="fa fa-sort sno_rbo_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:6%;text-align: left;">Client Code <i class="fa fa-sort clientid_rbo_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:25%;text-align: left;">Company Name <i class="fa fa-sort company_rbo_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:10%;text-align: left;">Type <i class="fa fa-sort type_rbo_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:7%;text-align: left;">CRO Number <i class="fa fa-sort cro_rbo_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:10%;text-align: left;">RBO Reference <i class="fa fa-sort rbo_ref_sort" aria-hidden="true" style="float: right;"></i></th>
          </thead>                            
          <tbody id="clients_rbo_tbody">';
          $i=1;
          if(count($clientlist)){              
            foreach($clientlist as $key => $client){
                $disabled='';
                $style="color:#000";
                if($client->active != "")
                {
                  if($client->active == 2)
                  {
                    $disabled='disabled_rbo_tr';
                    $style="color:#f00";
                  }
                }

                $cmp = '<spam class="company_rbo_td" style="font-style:italic;"></spam>';
                $timestampcroard = '';
                $cro_ard_details = DB::table('croard')->where('client_id',$client->client_id)->first();
                $notes = '';
                $rbo_submission = '';
                if(count($cro_ard_details))
                {
                  $notes = $cro_ard_details->notes;
                  if(strtolower($client->company) == strtolower($cro_ard_details->company_name))
                  {
                    $cmp = '<spam class="company_td" style="color:green;font-style:italic">'.$cro_ard_details->company_name.'</spam>';
                  }
                  else{
                    $cmp = '<spam class="company_td" style="color:blue;font-style:italic;font-weight:800">'.$cro_ard_details->company_name.'</spam>';
                  }
                  $rbo_submission = $cro_ard_details->rbo_submission;
                }
                if($client->tye == "") { $tye = '-'; } else { $tye = $client->tye; }
                if($client->cro == "") { $croval = '-'; } else { $croval = $client->cro; }
                if($client->company == "") { $cmpval = $client->firstname.' & '.$client->surname; }
                else{ $cmpval = $client->company; }
                $output.='<tr class="edit_rbo_task '.$disabled.'" style="'.$style.'"  id="clientidtr_rbo_'.$client->client_id.'">
                    <td style="'.$style.'" class="sno_rbo_sort_val">'.$i.'</td>
                    <td style="'.$style.'" class="clientid_rbo_sort_val" align="left">'.$client->client_id.'</td>
                    <td style="'.$style.'" align="left"><spam class="company_rbo_sort_val">'.$cmpval.'</spam> <br/> '.$cmp.'</td>
                    <td style="'.$style.'" class="type_rbo_sort_val" align="left">'.$tye.'</td>
                    <td style="'.$style.'" class="cro_rbo_sort_val" align="left">'.$croval.'</td>
                    <td style="'.$style.'" class="rbo_ref_sort_val" align="left">'.$rbo_submission.'</td>
                </tr>';
                $i++;
              }              
            }
            if($i == 1)
            {
              $output.='<tr><td colspan="6" align="center">Empty</td></tr>';
            } 
          $output.='</tbody>
        </table>';
        echo $output;
	}
	public function report_csv_rbo()
	{
		$filename = 'rbo_submission_review.csv';

		$columns = array('S.No', 'Client Code','Company Name','Type','CRO Number','RBO Reference','Activate');
		$file = fopen('papers/rbo_submission_review.csv', 'w');
		fputcsv($file, $columns);

		$clientlist = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id','tye','ard','cro')->orderBy('id','asc')->get();
		
          $i=1;
          if(count($clientlist)){              
            foreach($clientlist as $key => $client){
                $act_text = 'Active';
                if($client->active != "")
                {
                  if($client->active == 2)
                  {
                    $act_text = 'Deactive';
                  }
                }

                $cmp = '';
                $timestampcroard = '';
                $cro_ard_details = DB::table('croard')->where('client_id',$client->client_id)->first();
                $notes = '';
                $rbo_submission = '';
                if(count($cro_ard_details))
                {
                  $notes = $cro_ard_details->notes;
                  if(strtolower($client->company) == strtolower($cro_ard_details->company_name))
                  {
                    $cmp = $cro_ard_details->company_name;
                  }
                  else{
                    $cmp = $cro_ard_details->company_name;
                  }
                  $rbo_submission = $cro_ard_details->rbo_submission;
                }

                if($client->tye == "") { $tye = '-'; } else { $tye = $client->tye; }
                if($client->cro == "") { $croval = '-'; } else { $croval = $client->cro; }
                if($client->company == "") { $cmpval = $client->firstname.' & '.$client->surname; }
                else{ $cmpval = $client->company; }

                $columns1 = array($i,$client->client_id,"$cmpval\n$cmp",$tye,$croval,$rbo_submission,$act_text);
				fputcsv($file, $columns1);
                $i++;
            }              
          }
        fclose($file);
		echo $filename;
	}
	public function remove_croard_refresh()
	{
		$clientid = Input::get('clientid');
		$data['cro_ard'] = '';
		$data['company_name'] = '';
		
		$detail = DB::table('croard')->where('client_id',$clientid)->first();
		if(count($detail))
		{
			DB::table('croard')->where('client_id',$clientid)->update($data);
		}
	}
	public function remove_blue_croard_refresh()
	{
		$clientid = Input::get('clientid');
		$data['cro_ard'] = '';
		$data['company_name'] = '';
		
		$detail = DB::table('croard')->where('client_id',$clientid)->first();
		if(count($detail))
		{
			DB::table('croard')->where('client_id',$clientid)->update($data);
		}
	}
	
}

