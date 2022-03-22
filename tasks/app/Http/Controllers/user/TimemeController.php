<?php namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;

use DB;

use Input;

use Redirect;

use App\TimeTask;

use App\CmClients;

use Session;

use URL;

use PDF;

use Response;

use PHPExcel; 

use PHPExcel_IOFactory;

use PHPExcel_Cell;

use Hash;



class TimemeController extends Controller {



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

	public function __construct(timetask $timetask)

	{

		$this->middleware('userauth');

		$this->timetask = $timetask;

		date_default_timezone_set("Europe/Dublin");

	}



	/**

	 * Show the application welcome screen to the user.

	 *

	 * @return Response

	 */

	public function time_task()

	{	
		$clients = DB::table('cm_clients')->get();
		$client_id_count = DB::table("cm_clients")->where("client_id","!=","")->count();
		$time_task = DB::table('time_task')->get();
		return view('user/timeme/timetask', array('title' => 'Time Task', 'tasklist' => $time_task, 'clientlist' => $clients,"client_id_count" => $client_id_count));

	}

	public function time_task_client_details(){
		
		$clientlist = DB::table('cm_clients')->get();
		$output = '';
		$i=1;
		if(count($clientlist)){
			foreach ($clientlist as $key => $clients) {
				$output.='
						<tr>
							<td>'.$i.'</td>
							<td><input type="checkbox" name="select_client[]" class="select_client class_'.$clients->client_id.'" data-element="'.$clients->client_id.'" value="'.$clients->client_id.'"><label>&nbsp</label></td>
							<td>'.$clients->client_id.'</td>
							<td>'.$clients->firstname.'</td>
							<td>'.$clients->surname.'</td>
							<td width="40%">'.$clients->company.'</td>
						</tr>
				';
				$i++;
			}
		}

		echo $output;
	}

	public function time_task_add(){


		$type = Input::get("task_type");
		
		if($type == 0){
			$project = Input::get('select_projects');
			$data['clients'] ='';
			$data['task_name'] = Input::get("task_name");
			$data['task_type'] = Input::get("task_type");
			$data['project_id'] = $project;
		}
		else{
			$data['clients'] = implode(",",Input::get('select_client'));
			$data['task_name'] = Input::get("task_name");
			$data['task_type'] = Input::get("task_type");
		}
		DB::table('time_task')->insert($data);
		return Redirect::back()->with('message', 'Time Task added Succefully');
	}

	public function time_task_client_counts(){
		$taskid = base64_decode(Input::get('id'));
		
		$tasklist = DB::table('time_task')->where('id', $taskid)->first();
		$client_id = explode(",",$tasklist->clients);

		

		$i=1;
		$output='';


		if(count($client_id)){
			foreach ($client_id as $key => $client) {

				$clientdetails = DB::table('cm_clients')->where('client_id', $client)->first();

				if(count($clientdetails)){
						$client_id = $clientdetails->client_id;
						$firstname = $clientdetails->firstname;
						$surname = $clientdetails->surname;
						$company = $clientdetails->company;
				}
				else{
						$client_id = '';
						$firstname = '';
						$surname = '';
						$company = '';

				}


				$output.='
						<tr>
							<td>'.$i.'</td>
							<td>'.$client_id.'</td>
							<td>'.$firstname.'</td>
							<td>'.$surname.'</td>
							<td width="40%">'.$company.'</td>
						</tr>';
				$i++;
			}
		}

		echo $output;		
	}

	public function timetasklock_unlock(){


		$id = base64_decode(Input::get('id'));
		$status = Input::get('status');	

		$admin_details = DB::table('admin')->first();

		DB::table('time_task')->where('id', $id)->update(['status' => $status]);
		if($status == '1'){
			return redirect::back()->with('message','Lock Success');
		}
		else{
			return redirect::back()->with('message','Unlock Success');
		}
	}

	public function timetask_edit(){
		$id = base64_decode(Input::get('id'));
		$task_details = DB::table('time_task')->where('id',$id)->first();

		echo json_encode(array('taskname' => $task_details->task_name,'taskid' => $task_details->id,'clients' => $task_details->clients, 'tasktype' => $task_details->task_type, 'project_id' => $task_details->project_id));
	}

	public function time_task_update(){

		$taskid = Input::get("taskid");
		$type = Input::get("task_type");
		
		if($type == 0){
			$data['project_id'] = Input::get('select_projects_edit');
			$data['clients'] = '';
			$data['task_name'] = Input::get("task_name");
			$data['task_type'] = Input::get("task_type");
		}
		else{
			$data['clients'] = implode(",",Input::get('select_client'));
			$data['task_name'] = Input::get("task_name");
			$data['task_type'] = Input::get("task_type");
		}
		DB::table('time_task')->where('id', $taskid)->update($data);
		return Redirect::back()->with('message', 'Updated Succefully');
	}

	public function time_task_review(){
		$taskid = base64_decode(Input::get("id"));
		$client_ids = DB::table("cm_clients")->where("client_id","!=","")->get();

		$update_id ='';	
		$commo = '';

		if(count($client_ids)){
			foreach ($client_ids as $key => $clienid) {
				if($commo == ''){
					$commo = $clienid->client_id;
				}
				else{
					$commo =  $commo.','. $clienid->client_id;
				}
			}
		}
		$data['clients'] = $commo;
		DB::table('time_task')->where('id', $taskid)->update($data);

		$clients_count = DB::table('time_task')->where('id', $taskid)->first();
		$count = count(explode(',',$clients_count->clients));

		echo json_encode(array('reviewcount' => $count, 'taskid' => $taskid));
	}

	public function time_task_review_all(){
		$task_ids = DB::table('time_task')->where('task_type', 2)->get();
		$array = array();
		if(count($task_ids)){
			foreach ($task_ids as $key => $singletask) {
				$taskid = $singletask->id;


				$client_ids = DB::table("cm_clients")->where("client_id","!=","")->get();

				$update_id ='';	
				$commo = '';

				if(count($client_ids)){
					foreach ($client_ids as $key => $clienid) {
						if($commo == ''){
							$commo = $clienid->client_id;
						}
						else{
							$commo =  $commo.','. $clienid->client_id;
						}
					}
				}
				$data['clients'] = $commo;
				DB::table('time_task')->where('id', $taskid)->update($data);

				$clients_count = DB::table('time_task')->where('id', $taskid)->first();
				$count = count(explode(',',$clients_count->clients));
				array_push($array,array('reviewcount' => $count, 'taskid' => $taskid));
			}
		}
		echo json_encode($array);
	}
	public function import_client_list_timeme()
	{
		$import_file = $_FILES['import_file']['name'];
		$tmp_name = $_FILES['import_file']['tmp_name'];

		$upload_dir = 'papers/timeme_import';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		move_uploaded_file($tmp_name, $upload_dir.'/'.$import_file);

		$filepath = $upload_dir.'/'.$import_file;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);
		$i = 0;
		$message = '';
		$client_codes = array();
		$client_firstname = array();
		$client_surname = array();
		$client_company = array();
		$client_errors = array();
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			$height = $highestRow;

			$client_id_header = $worksheet->getCellByColumnAndRow(0, 1); $client_id_header = trim($client_id_header->getValue());
			
			if($client_id_header == "Client Code")
			{
				for ($row = 2; $row <= $height; ++ $row) {
					$client_code = $worksheet->getCellByColumnAndRow(0,$row); $client_code = trim($client_code->getValue());
					$client_details = DB::table('cm_clients')->where('client_id',$client_code)->first();
					if(count($client_details))
					{
						if(!in_array($client_code,$client_codes))
						{
							array_push($client_codes,$client_code);
							array_push($client_firstname,$client_details->firstname);
							array_push($client_surname,$client_details->surname);
							array_push($client_company,$client_details->company);
						}
					}
					else{
						if(!in_array($client_code,$client_errors))
						{
							array_push($client_errors,$client_code);
						}
						$i = $i + 1;
						$message = "This file contains Invalid Client ID's as follows";
					}
				}
			}
			else{
				$i = $i + 1;
				$message = 'Invalid CSV File Format';
			}
		}
		echo json_encode(array("error" => $i, "message" => $message, "client_codes" => $client_codes, "client_errors" => $client_errors, "client_firstname" => $client_firstname, "client_surname" => $client_surname, "client_company" => $client_company));
	}
}


