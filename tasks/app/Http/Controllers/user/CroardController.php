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
		return view('user/croard/croardmanager', array('title' => 'Easypayroll - CRO ARD Manager', 'cro' => $cro,'clientlist' => $client));
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

		$nextard = $results_array->next_ar_date;
		$company = $results_array->company_name;

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
}

