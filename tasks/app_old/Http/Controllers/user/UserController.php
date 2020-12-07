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
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class UserController extends Controller {
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

	public function dashboard()

	{

		$time_job = DB::table('task_job')->get();

		$tasks = DB::table('time_task')->where('task_type', 0)->get();

		$user = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();

		return view('user/dashboad', array('title' => 'Easypayroll - Dashboard', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

	}

	public function time_track()

	{

		if(Session::has('task_job_user'))

		{

			$userid = Session::get('task_job_user');

			$time_job = DB::table('task_job')->where('user_id',$userid)->where('active_id',0)->orderBy('start_time', 'asc')->get();

		}

		else{

			$time_job = array();

		}
		$tasks = DB::table('time_task')->where('task_type', 0)->orderBy('task_name', 'asc')->get();
		$user = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		
		return view('user/dashboad_time_track', array('title' => 'Welcome to Easypayroll', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));

	}	

	public function unavailable()

	{

		return view('user/unavailable');

	}

	public function manageweek()

	{

		$year = DB::table('year')->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','dec')->get();

		return view('user/week', array('title' => 'Select Year', 'yearlist' => $year));

	}

	public function managemonth()

	{

		$year = DB::table('year')->where('delete_status',0)->where('year_status', 0)->orderBy('year_name','dec')->get();

		return view('user/month', array('title' => 'Select Year', 'yearlist' => $year));

	}

	public function weekmanage($id=""){

		$id = base64_decode($id);

		$year = DB::table('year')->where('year_id',$id)->first();

		$week = DB::table('week')->where('year', $id)->orderBy('week_id','dec')->get();

		return view('user/week_manage', array('title' => 'Select Week', 'weeklist' => $week,'year' => $year));

	}

	public function monthmanage($id=""){

		$id = base64_decode($id);

		$year = DB::table('year')->where('year_id',$id)->first();

		$month = DB::table('month')->where('year', $id)->orderBy('month_id','dec')->get();

		return view('user/month_manage', array('title' => 'Select Week', 'monthlist' => $month,'year' => $year));

	}

	public function logout(){

		if(Session::has('task_job_user'))

		{

			Session::forget('task_job_user');

		}

		Session::forget("userid");

		return redirect('/');

	}

	public function selectweek($id=""){

		$id =base64_decode($id);

		$year = DB::table('week')->where('week_id', $id)->first();

		$year_id = $this->year->getdetail($year->year);

		$user_year = $year_id->year_id;

		$year_user = DB::table('taskyear')->where('taskyear', $user_year)->first();

		$week_id = DB::table('week')->where('week_id', $id)->first();

		$week2 = $week_id->week_id;

		$year2 = $week_id->year;

		$result_task_standard = DB::table('task')->where('task_year', $year2)->where('task_week', $week2)->where('task_classified',1)->get();

		$result_task_enhanced = DB::table('task')->where('task_year', $year2)->where('task_week', $week2)->where('task_classified',2)->get();

		$result_task_complex = DB::table('task')->where('task_year', $year2)->where('task_week', $week2)->where('task_classified',3)->get();



		if($year_user->taskyear_user != '')

		{

			$userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();

			$uname = '<option value="">Select Username</option>';

			$email = '<option value="">Select Email</option>';

			$initial = '<option value="">Select Initial</option>';

			if(count($userlist)){

				foreach ($userlist as $singleuser) {

					

						if($uname == '')

						{

							$uname = '<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';

							$email = '<option value="'.$singleuser->user_id.'">'.$singleuser->email.'</option>';

							$initial = '<option value="'.$singleuser->user_id.'">'.$singleuser->initial.'</option>';

						}

						else{

							$uname = $uname.'<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';

							$email = $email.'<option value="'.$singleuser->user_id.'">'.$singleuser->email.'</option>';

							$initial = $initial.'<option value="'.$singleuser->user_id.'">'.$singleuser->initial.'</option>';

						}

					}

			}

		}

		else{

			$uname = '<option value="">Select Username</option>';

			$email = '<option value="">Select Email</option>';

			$initial = '<option value="">Select Initial</option>';

		}

		$classified = DB::table('classified')->get();

		$userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();



		return view('user/select_week', array('title' => 'Week Task', 'yearname' => $year_id, 'weekid' => $year, 'classifiedlist' => $classified, 'unamelist' => $uname,'emaillist' => $email,'initiallist' => $initial,'resultlist_standard' => $result_task_standard,'resultlist_enhanced' => $result_task_enhanced,'resultlist_complex' => $result_task_complex,'year_user' => $year_user, 'userlist' => $userlist));

	}

	public function selectmonth($id=""){

		$id =base64_decode($id);

		$year = DB::table('month')->where('month_id', $id)->first();

		$year_id = $this->year->getdetail($year->year);

		$user_year = $year_id->year_id;

		$year_user = DB::table('taskyear')->where('taskyear', $user_year)->first();

		$month_id = DB::table('month')->where('month_id', $id)->first();

		$month2 = $month_id->month_id;

		$year2 = $month_id->year;

		$result_task_standard = DB::table('task')->where('task_year', $year2)->where('task_month', $month2)->where('task_classified',1)->get();

		$result_task_enhanced = DB::table('task')->where('task_year', $year2)->where('task_month', $month2)->where('task_classified',2)->get();

		$result_task_complex = DB::table('task')->where('task_year', $year2)->where('task_month', $month2)->where('task_classified',3)->get();

		if($year_user->taskyear_user != '')

		{

			$userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();

			$uname = '<option value="">Select Username</option>';

			$email = '<option value="">Select Email</option>';

			$initial = '<option value="">Select Initial</option>';

			if(count($userlist)){

				foreach ($userlist as $singleuser) {

						if($uname == '')

						{

							$uname = '<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';

							$email = '<option value="'.$singleuser->user_id.'">'.$singleuser->email.'</option>';

							$initial = '<option value="'.$singleuser->user_id.'">'.$singleuser->initial.'</option>';

						}

						else{

							$uname = $uname.'<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';

							$email = $email.'<option value="'.$singleuser->user_id.'">'.$singleuser->email.'</option>';

							$initial = $initial.'<option value="'.$singleuser->user_id.'">'.$singleuser->initial.'</option>';

						}

					}

			}

		}

		else{

			$uname = '<option value="">Select Username</option>';

			$email = '<option value="">Select Email</option>';

			$initial = '<option value="">Select Initial</option>';

		}

		$classified = DB::table('classified')->get();

		$userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();



		return view('user/select_month', array('title' => 'Month Task', 'yearname' => $year_id, 'monthid' => $year, 'classifiedlist' => $classified, 'unamelist' => $uname, 'emaillist' => $email,'initiallist' => $initial,'resultlist_standard' => $result_task_standard,'resultlist_enhanced' => $result_task_enhanced,'resultlist_complex' => $result_task_complex,'year_user' => $year_user,'userlist' => $userlist));

	}



	public function addnewtask(){



		$year = Input::get('year');



		$week = Input::get('weekid');



		$weekid = Input::get('weekid');



		$tastname =  Input::get('tastname');



		$classified =  Input::get('classified');



		$enterhours =  Input::get('enterhours');



		$holiday =  Input::get('holiday');



		$process =  Input::get('process');



		$payslips =  Input::get('payslips');



		$email =  Input::get('email');



		$secondary_email =  Input::get('secondary_email');



		$salutation =  Input::get('salutation');



		$upload =  Input::get('uploadd');



		$task_email =  Input::get('task_email');



		



		$location =  Input::get('location');	







		$task_enumber =  trim(Input::get('task_enumber'));







		$tasklevel =  trim(Input::get('tasklevel'));



		$p30_pay =  trim(Input::get('pay_p30'));



		$p30_email =  trim(Input::get('email_p30'));







		$clientid =  trim(Input::get('clientid'));







		



		$taskid = DB::table('task')->insertGetId(['task_year' => $year, 'task_week' => $week, 'task_name' => $tastname, 'task_classified' => $classified, 'enterhours' => $enterhours, 'holiday' => $holiday, 'process' => $process, 'payslips' => $payslips, 'email' => $email, 'secondary_email' => $secondary_email, 'salutation' => $salutation, 'upload' => $upload, 'task_email' => $task_email,'network' => $location, 'task_enumber' => $task_enumber, 'tasklevel' => $tasklevel, 'p30_pay' => $p30_pay, 'p30_email' => $p30_email, 'client_id' => $clientid]);



		$datavalue['task_created_id'] = $taskid;
		DB::table('task')->where('task_id',$taskid)->update($datavalue);



		$emp_client = Input::get('hidden_client_emp');



		$saluation_client = Input::get('hidden_client_salutation');



		$clientid = Input::get('hidden_client_id');







		if($emp_client == 1)



		{



			$data['employer_no'] = trim(Input::get('task_enumber'));



		}



		if($saluation_client == 1)



		{



			$data['salutation'] = trim(Input::get('salutation'));



		}



		if($emp_client == 1 || $saluation_client == 1)



		{



			DB::table('cm_clients')->where('client_id',$clientid)->update($data);



		}



		return redirect('user/select_week/'.base64_encode($weekid))->with('message', 'Task Created Success');



	}



	public function addnewtask_month(){



		$year = Input::get('year');



		$month = Input::get('monthid');



		$monthid = Input::get('monthid');



		$tastname =  Input::get('tastname');



		$classified =  Input::get('classified');



		$enterhours =  Input::get('enterhours');



		$holiday =  Input::get('holiday');



		$process =  Input::get('process');



		$payslips =  Input::get('payslips');



		$email =  Input::get('email');



		$secondary_email =  Input::get('secondary_email');



		$salutation =  Input::get('salutation');



		$upload =  Input::get('uploadd');



		$task_email =  Input::get('task_email');



		



		



		$location =  Input::get('location');







		$task_enumber =  trim(Input::get('task_enumber'));







		$tasklevel =  trim(Input::get('tasklevel'));



		$p30_pay =  trim(Input::get('pay_p30'));



		$p30_email =  trim(Input::get('email_p30'));







		$clientid =  trim(Input::get('clientid'));



		



		$taskid = DB::table('task')->insertGetId(['task_year' => $year, 'task_month' => $month, 'task_name' => $tastname, 'task_classified' => $classified, 'enterhours' => $enterhours, 'holiday' => $holiday, 'process' => $process, 'payslips' => $payslips, 'email' => $email, 'secondary_email' => $secondary_email, 'salutation' => $salutation,  'upload' => $upload, 'task_email' => $task_email,'network' => $location, 'task_enumber' => $task_enumber, 'tasklevel' => $tasklevel, 'p30_pay' => $p30_pay, 'p30_email' => $p30_email, 'client_id' => $clientid]);

		$datavalue['task_created_id'] = $taskid;
		DB::table('task')->where('task_id',$taskid)->update($datavalue);


		$emp_client = Input::get('hidden_client_emp');



		$saluation_client = Input::get('hidden_client_salutation');



		$clientid = Input::get('hidden_client_id');







		if($emp_client == 1)



		{



			$data['employer_no'] = trim(Input::get('task_enumber'));



		}



		if($saluation_client == 1)



		{



			$data['salutation'] = trim(Input::get('salutation'));



		}



		if($emp_client == 1 || $saluation_client == 1)



		{



			DB::table('cm_clients')->where('client_id',$clientid)->update($data);



		}



		return redirect('user/select_month/'.base64_encode($monthid))->with('message', 'Task Created Success');



	}



	public function deletetask($id=""){



		$id = base64_decode($id);


		$get_week_month = DB::table('task')->where('task_id',$id)->first();
		DB::table('task')->where('task_id',$id)->delete();
		if(count($get_week_month))
		{
			if($get_week_month->task_week != 0)
			{
				$get_week_name = DB::table('week')->where('week_id',$get_week_month->task_week)->first();
				$dataupdate['period'] = 'week'.$get_week_name->week;
				$dataupdate['year_id'] = $get_week_month->task_year;
				$dataupdate['task_enumber'] = $get_week_month->task_enumber;
				$sum = DB::table('task')->where('task_year',$get_week_month->task_year)->where('task_week',$get_week_month->task_week)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
				$dataupdate['value'] = $sum;
				$check_update = DB::table('paye_task_update')->where('year_id',$get_week_month->task_year)->where('period','week'.$get_week_name->week)->where('task_enumber',$get_week_month->task_enumber)->first();
				if(count($check_update))
				{
					DB::table('paye_task_update')->where('id',$check_update->id)->update($dataupdate);
				}
				else{
					DB::table('paye_task_update')->insert($dataupdate);
				}
			}
			else{
				$get_month_name = DB::table('month')->where('month_id',$get_week_month->task_month)->first();
				$dataupdate['period'] = 'month'.$get_month_name->month;
				$dataupdate['year_id'] = $get_week_month->task_year;
				$dataupdate['task_enumber'] = $get_week_month->task_enumber;
				$sum = DB::table('task')->where('task_year',$get_week_month->task_year)->where('task_month',$get_week_month->task_month)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
				$dataupdate['value'] = $sum;
				$check_update = DB::table('paye_task_update')->where('year_id',$get_week_month->task_year)->where('period','month'.$get_month_name->month)->where('task_enumber',$get_week_month->task_enumber)->first();
				if(count($check_update))
				{
					DB::table('paye_task_update')->where('id',$check_update->id)->update($dataupdate);
				}
				else{
					DB::table('paye_task_update')->insert($dataupdate);
				}
			}
		}
		return Redirect::back();
	}



	public function task_enterhours()



	{



		$id = Input::get('id');



		$enterhouse = Input::get('enterhouse');



		DB::table('task')->where('task_id',$id)->update(['enterhours' => $enterhouse]);



	}



	public function task_started_checkbox()



	{



		$id = Input::get('id');



		$task_started = Input::get('task_started');



		DB::table('task')->where('task_id',$id)->update(['task_started' => $task_started]);



	}



	public function task_holiday()



	{



		$id = Input::get('id');



		$holiday = Input::get('holiday');



		DB::table('task')->where('task_id',$id)->update(['holiday' => $holiday]);



	}



	public function task_process()



	{



		$id = Input::get('id');



		$process = Input::get('process');



		DB::table('task')->where('task_id',$id)->update(['process' => $process]);



	}



	public function task_payslips()



	{



		$id = Input::get('id');



		$payslips = Input::get('payslips');



		DB::table('task')->where('task_id',$id)->update(['payslips' => $payslips]);



	}



	public function task_email()



	{



		$id = Input::get('id');



		$email = Input::get('email');



		DB::table('task')->where('task_id',$id)->update(['email' => $email]);



	}



	public function task_upload()



	{



		$id = Input::get('id');



		$upload = Input::get('upload');



		DB::table('task')->where('task_id',$id)->update(['upload' => $upload]);



	}



	public function task_date_update()



	{



		$id = Input::get('id');



		$date = Input::get('date');



		$exp = explode('-',$date);



		$date = $exp[2].'-'.$exp[0].'-'.$exp[1];



		DB::table('task')->where('task_id',$id)->update(['date' => $date]);



	}



	public function task_email_update()



	{



		$id = Input::get('id');



		$email = Input::get('email');



		DB::table('task')->where('task_id',$id)->update(['task_email' => $email]);



	}



	public function task_users_update()



	{



		$id = Input::get('id');



		$users = Input::get('users');



		DB::table('task')->where('task_id',$id)->update(['users' => $users]);



	}



	public function task_classified_update()



	{



		$id = Input::get('id');



		$classified = Input::get('classified');



		DB::table('task')->where('task_id',$id)->update(['task_classified' => $classified]);



	}



	



	public function task_comments_update()



	{



		$id = Input::get('id');



		$comments = Input::get('comments');



		DB::table('task')->where('task_id',$id)->update(['comments' => $comments]);



	}

	public function task_liability_update()
	{
		$id = Input::get('id');
		$liability = Input::get('liability');
		$liability = str_replace(',','',$liability);
		$liability = str_replace(',','',$liability);
		$liability = str_replace(',','',$liability);
		$liability = str_replace(',','',$liability);
		$liability = str_replace(',','',$liability);
		$liability = str_replace(',','',$liability);
		DB::table('task')->where('task_id',$id)->update(['liability' => $liability]);

		$get_week_month = DB::table('task')->where('task_id',$id)->first();
		if(count($get_week_month))
		{
			if($get_week_month->task_week != 0)
			{
				$get_week_name = DB::table('week')->where('week_id',$get_week_month->task_week)->first();
				$dataupdate['period'] = 'week'.$get_week_name->week;
				$dataupdate['year_id'] = $get_week_month->task_year;
				$dataupdate['task_enumber'] = $get_week_month->task_enumber;
				$sum = DB::table('task')->where('task_year',$get_week_month->task_year)->where('task_week',$get_week_month->task_week)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
				$dataupdate['value'] = $sum;
				$check_update = DB::table('paye_task_update')->where('year_id',$get_week_month->task_year)->where('period','week'.$get_week_name->week)->where('task_enumber',$get_week_month->task_enumber)->first();
				if(count($check_update))
				{
					DB::table('paye_task_update')->where('id',$check_update->id)->update($dataupdate);
				}
				else{
					DB::table('paye_task_update')->insert($dataupdate);
				}
			}
			else{
				$get_month_name = DB::table('month')->where('month_id',$get_week_month->task_month)->first();
				$dataupdate['period'] = 'month'.$get_month_name->month;
				$dataupdate['year_id'] = $get_week_month->task_year;
				$dataupdate['task_enumber'] = $get_week_month->task_enumber;
				$sum = DB::table('task')->where('task_year',$get_week_month->task_year)->where('task_month',$get_week_month->task_month)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
				$dataupdate['value'] = $sum;
				$check_update = DB::table('paye_task_update')->where('year_id',$get_week_month->task_year)->where('period','month'.$get_month_name->month)->where('task_enumber',$get_week_month->task_enumber)->first();
				if(count($check_update))
				{
					DB::table('paye_task_update')->where('id',$check_update->id)->update($dataupdate);
				}
				else{
					DB::table('paye_task_update')->insert($dataupdate);
				}
			}
		}
	}

	public function task_image_upload()

	{



		$total = count($_FILES['image_file']['name']);



		$id = Input::get('hidden_id');



		$type = Input::get('type');



		for($i=0; $i<$total; $i++) {



		 	$filename = $_FILES['image_file']['name'][$i];



			$data_img = DB::table('task')->where('task_id',$id)->first();



			



			$tmp_name = $_FILES['image_file']['tmp_name'][$i];



			$upload_dir = 'uploads/task_image';



			if (!file_exists($upload_dir)) {



				mkdir($upload_dir);



			}



			$upload_dir = $upload_dir.'/'.base64_encode($data_img->users);



			if (!file_exists($upload_dir)) {



				mkdir($upload_dir);



			}



			$upload_dir = $upload_dir.'/'.base64_encode($data_img->task_id);



			if (!file_exists($upload_dir)) {



				mkdir($upload_dir);



			}



			move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);	



			$data['task_id'] = $data_img->task_id;



			$data['attachment'] = $filename;



			$data['url'] = $upload_dir;



			if($type == 2)



			{



				$data['network_attach'] = 1;



			}



			else{



				$data['network_attach'] = 0;



			}



			DB::table('task_attached')->insert($data);



		}


		if($type == 2)
		{
			$dataval['task_started'] = 1;

			$dataval['task_notify'] = 1;

			DB::table('task')->where('task_id',$id)->update($dataval);
		}
		



		if($data_img->task_week != 0)



		{



			return redirect('user/select_week/'.base64_encode($data_img->task_week).'?divid=taskidtr_'.$id);



		}



		else{



			return redirect('user/select_month/'.base64_encode($data_img->task_month).'?divid=taskidtr_'.$id);



		}



	}



	public function task_notepad_upload()

	{

		$id = Input::get('hidden_id');

		$data_img = DB::table('task')->where('task_id',$id)->first();

		$count = DB::table('task_attached')->where('task_id',$id)->where('network_attach',1)->count();

		$counts = $count + 1;

		$task_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $data_img->task_name); 

		if($data_img->task_week != 0)

		{

			$week_details = DB::table('week')->where('week_id',$data_img->task_week)->first();

			$filename = $task_name.' - Week '.$week_details->week.' - '.$counts;

		}

		else{

			$month_details = DB::table('month')->where('month_id',$data_img->task_month)->first();

			$filename = $task_name.' - Month '.$month_details->month.' - '.$counts;

		}

		

		$contents = Input::get('notepad_contents');



		



		$upload_dir = 'uploads/task_image';

		if (!file_exists($upload_dir)) {

			mkdir($upload_dir);

		}

		$upload_dir = $upload_dir.'/'.base64_encode($data_img->users);

		if (!file_exists($upload_dir)) {

			mkdir($upload_dir);

		}

		$upload_dir = $upload_dir.'/'.base64_encode($data_img->task_id);

		if (!file_exists($upload_dir)) {

			mkdir($upload_dir);

		}



		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");

		fwrite($myfile, $contents);

		fclose($myfile);



		$data['task_id'] = $data_img->task_id;

		$data['attachment'] = $filename.".txt";

		$data['url'] = $upload_dir;

		$data['network_attach'] = 1;



		DB::table('task_attached')->insert($data);

		

		$dataval['task_started'] = 1;

		$dataval['task_notify'] = 1;

		DB::table('task')->where('task_id',$id)->update($dataval);



		if($data_img->task_week != 0)

		{

			return redirect('user/select_week/'.base64_encode($data_img->task_week).'?divid=taskidtr_'.$id);

		}

		else{

			return redirect('user/select_month/'.base64_encode($data_img->task_month).'?divid=taskidtr_'.$id);

		}

	}

	public function task_delete_image()



	{



		$imgid = Input::get('imgid');



		$check_network = DB::table('task_attached')->where('id',$imgid)->first();

		DB::table('task_attached')->where('id',$imgid)->delete();





		if($check_network->network_attach == 1)

		{

			$count = DB::table('task_attached')->where('task_id',$check_network->task_id)->where('network_attach',1)->count();

			if($count > 0)

			{

				

			}

			else{

				$dataval['task_started'] = 0;

				$dataval['task_notify'] = 0;

				DB::table('task')->where('task_id',$check_network->task_id)->update($dataval);

			}

		}

		elseif($check_network->network_attach == 0)

		{

			$count = DB::table('task_attached')->where('task_id',$check_network->task_id)->where('network_attach',0)->count();

			if($count > 0)

			{

				

			}

			else{

				$dataval['liability'] = '';
				DB::table('task')->where('task_id',$check_network->task_id)->update($dataval);


				$get_week_month = DB::table('task')->where('task_id',$check_network->task_id)->first();
				if(count($get_week_month))
				{
					if($get_week_month->task_week != 0)
					{
						$get_week_name = DB::table('week')->where('week_id',$get_week_month->task_week)->first();
						$dataupdate['period'] = 'week'.$get_week_name->week;
						$dataupdate['year_id'] = $get_week_month->task_year;
						$dataupdate['task_enumber'] = $get_week_month->task_enumber;
						$sum = DB::table('task')->where('task_year',$get_week_month->task_year)->where('task_week',$get_week_month->task_week)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
						$dataupdate['value'] = $sum;
						$check_update = DB::table('paye_task_update')->where('year_id',$get_week_month->task_year)->where('period','week'.$get_week_name->week)->where('task_enumber',$get_week_month->task_enumber)->first();
						if(count($check_update))
						{
							DB::table('paye_task_update')->where('id',$check_update->id)->update($dataupdate);
						}
						else{
							DB::table('paye_task_update')->insert($dataupdate);
						}
					}
					else{
						$get_month_name = DB::table('month')->where('month_id',$get_week_month->task_month)->first();
						$dataupdate['period'] = 'month'.$get_month_name->month;
						$dataupdate['year_id'] = $get_week_month->task_year;
						$dataupdate['task_enumber'] = $get_week_month->task_enumber;
						$sum = DB::table('task')->where('task_year',$get_week_month->task_year)->where('task_month',$get_week_month->task_month)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
						$dataupdate['value'] = $sum;
						$check_update = DB::table('paye_task_update')->where('year_id',$get_week_month->task_year)->where('period','month'.$get_month_name->month)->where('task_enumber',$get_week_month->task_enumber)->first();
						if(count($check_update))
						{
							DB::table('paye_task_update')->where('id',$check_update->id)->update($dataupdate);
						}
						else{
							DB::table('paye_task_update')->insert($dataupdate);
						}
					}
				}

			}

		}



	}



	public function task_delete_all_image()



	{



		$taskid = Input::get('taskid');



		DB::table('task_attached')->where('task_id',$taskid)->where('network_attach',0)->delete();
		DB::table('task')->where('task_id',$taskid)->update(['liability' => ""]);

		$get_week_month = DB::table('task')->where('task_id',$taskid)->first();
		if(count($get_week_month))
		{
			if($get_week_month->task_week != 0)
			{
				$get_week_name = DB::table('week')->where('week_id',$get_week_month->task_week)->first();
				$dataupdate['period'] = 'week'.$get_week_name->week;
				$dataupdate['year_id'] = $get_week_month->task_year;
				$dataupdate['task_enumber'] = $get_week_month->task_enumber;
				$sum = DB::table('task')->where('task_year',$get_week_month->task_year)->where('task_week',$get_week_month->task_week)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
				$dataupdate['value'] = $sum;
				$check_update = DB::table('paye_task_update')->where('year_id',$get_week_month->task_year)->where('period','week'.$get_week_name->week)->where('task_enumber',$get_week_month->task_enumber)->first();
				if(count($check_update))
				{
					DB::table('paye_task_update')->where('id',$check_update->id)->update($dataupdate);
				}
				else{
					DB::table('paye_task_update')->insert($dataupdate);
				}
			}
			else{
				$get_month_name = DB::table('month')->where('month_id',$get_week_month->task_month)->first();
				$dataupdate['period'] = 'month'.$get_month_name->month;
				$dataupdate['year_id'] = $get_week_month->task_year;
				$dataupdate['task_enumber'] = $get_week_month->task_enumber;
				$sum = DB::table('task')->where('task_year',$get_week_month->task_year)->where('task_month',$get_week_month->task_month)->where('task_enumber',$get_week_month->task_enumber)->sum('liability');
				$dataupdate['value'] = $sum;
				$check_update = DB::table('paye_task_update')->where('year_id',$get_week_month->task_year)->where('period','month'.$get_month_name->month)->where('task_enumber',$get_week_month->task_enumber)->first();
				if(count($check_update))
				{
					DB::table('paye_task_update')->where('id',$check_update->id)->update($dataupdate);
				}
				else{
					DB::table('paye_task_update')->insert($dataupdate);
				}
			}
		}

	}



	public function task_delete_all_image_attachments()



	{



		$taskid = Input::get('taskid');



		DB::table('task_attached')->where('task_id',$taskid)->where('network_attach',1)->delete();



		$dataval['task_started'] = 0;

		$dataval['task_notify'] = 0;

		DB::table('task')->where('task_id',$taskid)->update($dataval);



	}



	public function task_status_update()
	{
		$id = Input::get('id');	
		$status = Input::get('status');
		DB::table('task')->where('task_id',$id)->update(['task_status' => $status,'updatetime' => date('Y-m-d H:i:s')]);
		$details = DB::table('task')->where('task_id',$id)->first();
		if($status == 1){
			$payroll = DB::table('task')->where('task_id', $id)->first();
			if($payroll->client_id != ''){
				$data['client_id'] = $payroll->client_id;
				$data['task_id'] = $payroll->task_id;
				$data['year'] = $payroll->task_year;
				$data['week'] = $payroll->task_week;
				$data['month'] = $payroll->task_month;
				$data['email_sent'] = $payroll->last_email_sent;
				DB::table('payroll_tasks')->insert([$data]);
			}
		}
      $seperatedate = explode(' ',$details->updatetime);
      $explodedate = explode('-',$seperatedate[0]);
      $explodetime = explode(':',$seperatedate[1]);
      $date = $explodedate[1].'-'.$explodedate[2].'-'.$explodedate[0];
      $time = $explodetime[0].':'.$explodetime[1];
      echo json_encode(["date" => $date,"time" => $time]);
	}



	public function get_week_by_year()



	{



		$id = Input::get('id');



		$year = Input::get('year');



		$weeks = DB::table('week')->where('year', $year)->where('week_closed','=', '0000-00-00 00:00:00')->get();



		$output = '<h5 style="font-weight:800">CHOOSE WEEK : </h5>';



		$output.='<ul>';



            if(count($weeks)){



              foreach($weeks as $week){



              	$output.='<li><a href="javascript:" class="week_button" data-element="'.$week->week_id.'">Week '.$week->week.'</a></li>';



              }



            }



            else{



              $output.='Week Not Found';



            }  



        $output.='</ul>';



        echo $output;



	}



	public function get_month_by_year()



	{



		$id = Input::get('id');



		$year = Input::get('year');



		$months = DB::table('month')->where('year', $year)->where('month_closed','=', '0000-00-00 00:00:00')->get();



		$output = '<h5 style="font-weight:800">CHOOSE MONTH : </h5>';



		$output.='<ul>';



            if(count($months)){



              foreach($months as $month){



              	$output.='<li><a href="javascript:" class="month_button" data-element="'.$month->month_id.'">Month '.$month->month.'</a></li>';



              }



            }



            else{



              $output.='Month Not Found';



            }  



        $output.='</ul>';



        echo $output;



	}



	public function copy_task()



	{



		$id = Input::get('hidden_task_id');



		$year = Input::get('hidden_copy_year');



		$week = Input::get('hidden_copy_week');



		$month = Input::get('hidden_copy_month');



		$category = Input::get('category_type_copy');







		$taskdetails = DB::table('task')->where('task_id',$id)->first();

		if($taskdetails->task_created_id == 0) { $data['task_created_id'] = $taskdetails->task_id; }
		else{ $data['task_created_id'] = $taskdetails->task_created_id; }



		$data['task_year'] = $year;



		$data['task_week'] = ($week != '')?$week:0;



		$data['task_month'] = ($month != '')?$month:0;



		$data['task_name'] = $taskdetails->task_name;



		$data['task_classified'] = $category;







		$data['enterhours'] = ($taskdetails->enterhours == 2)?2:0;



		$data['holiday'] = ($taskdetails->holiday == 2)?2:0;



		$data['process'] = ($taskdetails->process == 2)?2:0;



		$data['payslips'] = ($taskdetails->payslips == 2)?2:0;



		$data['email'] = ($taskdetails->email == 2)?2:0;



		$data['upload'] = ($taskdetails->upload == 2)?2:0;







		$data['task_email'] = $taskdetails->task_email;



		$data['secondary_email'] = $taskdetails->secondary_email;



		$data['salutation'] = $taskdetails->salutation;



		$data['comments'] = $taskdetails->comments;



		$data['attached'] = $taskdetails->attached;



		$data['network'] = $taskdetails->network;



		$data['task_enumber'] = $taskdetails->task_enumber;



		$data['task_status'] = 0;



		$data['client_id'] = $taskdetails->client_id;



		$data['task_enumber'] = $taskdetails->task_enumber;



		$data['tasklevel'] = $taskdetails->tasklevel;



		$data['p30_pay'] = $taskdetails->p30_pay;



		$data['p30_email'] = $taskdetails->p30_email;

		$data['default_staff'] = $taskdetails->default_staff;
		$data['disclose_liability'] = $taskdetails->disclose_liability;


		DB::table('task')->where('task_id',$id)->insert([$data]);



		if($week != '')



		{



			return redirect('user/select_week/'.base64_encode($week))->with('message','Task Copied Successfully');



		}



		elseif($month != '')



		{



			return redirect('user/select_month/'.base64_encode($month))->with('message','Task Copied Successfully');



		}



	}



	public function notify_tasks()



	{



		$id = $_GET['id'];



		$value = $_GET['value'];



		$admin_details = DB::table('admin')->where('id',1)->first();



		$year = DB::table('week')->where('week_id', $id)->first();



		$year_id = $this->year->getdetail($year->year);



		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();



		$result_task = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',$value)->get();



		$output = '<table class="table_bg" style="width:100%">';



		if(count($result_task))



		{



			$output.= '<tr class="background_bg"><td>Task Name</td><td>Notify</td><td>Primary Email</td><td>Secondary Email</td></tr>';



			foreach($result_task as $task)



			{



				if($task->task_status == 1) { $task_label='style="color:#f00 !important;font-weight:800;text-align:left;"'; } elseif($task->task_complete_period == 1) { $task_label='style="color:#1b0fd4 !important;font-weight:800;text-align:left"'; } elseif($task->task_started == 1) { $task_label='style="color:#89ff00 !important;font-weight:800;text-align:left;"'; } else{ $task_label='style="color:#000 !important;font-weight:600;text-align:left;"'; } 



				$output.='<tr>



					<td '.$task_label.'>'.$task->task_name.'</td><td style="text-align:center">';



					if($task->task_status == 1 || $task->task_started == 1 || $task->task_complete_period == 1)



					{



						$output.='<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$task->task_id.'" value="1"><label >&nbsp;</label>';



					}



					else{



						$output.='<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$task->task_id.'" value="1" checked><label >&nbsp;</label>';



					}



				$output.='</td>



				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_primary_email" class="notify_primary_email form-control" value="'.$task->task_email.'" data-element="'.$task->task_id.'" readonly></td>



				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_secondary_email" class="notify_secondary_email form-control" value="'.$task->secondary_email.'" data-element="'.$task->task_id.'" readonly></td>



				</tr>';



			}



			$output.='';



		}



		$output.='</table>



		<h5 style="font-weight:800; margin-top:15px">MESSAGE :</h5>



		<textarea class="form-control input-sm" id="editor_1" name="notify_message" style="height:120px">'.$admin_details->notify_message.'</textarea>';



		echo $output;



	}



	public function notify_tasks_month()



	{



		$id = $_GET['id'];



		$value = $_GET['value'];



		$admin_details = DB::table('admin')->where('id',1)->first();



		$year = DB::table('month')->where('month_id', $id)->first();



		$year_id = $this->year->getdetail($year->year);



		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();



		$result_task = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',$value)->get();



		$output = '<table class="table_bg" style="width:100%">';



		if(count($result_task))



		{



			$output.= '<tr class="background_bg"><td>Task Name</td><td>Notify</td><td>Primary Email</td><td>Secondary Email</td></tr>';



			foreach($result_task as $task)



			{



				if($task->task_status == 1) { $task_label='style="color:#f00 !important;font-weight:800;text-align:left;"'; } elseif($task->task_complete_period == 1) { $task_label='style="color:#1b0fd4 !important;font-weight:800;text-align:left"'; } elseif($task->task_started == 1) { $task_label='style="color:#89ff00 !important;font-weight:800;text-align:left;"'; } else{ $task_label='style="color:#000 !important;font-weight:600;text-align:left;"'; } 



				$output.='<tr>



					<td '.$task_label.'>'.$task->task_name.'</td><td style="text-align:center">';



					if($task->task_status == 1 || $task->task_started == 1 || $task->task_complete_period == 1)



					{



						$output.='<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$task->task_id.'" value="1"><label >&nbsp;</label>';



					}



					else{



						$output.='<input type="checkbox" name="notify_option" class="notify_option" data-element="'.$task->task_id.'" value="1" checked><label >&nbsp;</label>';



					}



				$output.='</td>



				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_primary_email" class="notify_primary_email form-control" value="'.$task->task_email.'" data-element="'.$task->task_id.'" readonly></td>



				<td style="text-align:center;color:#000 !important;"><input type="text" name="notify_secondary_email" class="notify_secondary_email form-control" value="'.$task->secondary_email.'" data-element="'.$task->task_id.'" readonly></td>



				</tr>';



			}



			$output.='';



		}



		$output.='</table>







		<h5 style="font-weight:800; margin-top:15px">MESSAGE :</h5>



		<textarea class="form-control input-sm" id="editor_1" name="notify_message" style="height:120px">'.$admin_details->notify_message.'</textarea>



		';



		echo $output;



	}



	public function email_unsent_files()



	{



		$from_input = Input::get('select_user');



		$details = DB::table('user')->where('user_id',$from_input)->first();



		$from = $details->email;



		$user_name = $details->lastname.' '.$details->firstname;



		$toemails = Input::get('to_user').','.Input::get('cc_unsent');



		$sentmails = Input::get('to_user').', '.Input::get('cc_unsent');



		$subject = Input::get('subject_unsent'); 



		$message = Input::get('message_editor');



		$attachments = Input::get('check_attachment');



		$explode = explode(',',$toemails);



		$data['sentmails'] = $sentmails;



		if(count($attachments))



		{



			if(count($explode))



			{



				foreach($explode as $exp)



				{



					$to = trim($exp);



					$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');



					$data['message'] = $message;



					$contentmessage = view('user/email_share_paper', $data);





					$email = new PHPMailer();

					$email->SetFrom($from, $user_name); //Name is optional

					$email->Subject   = $subject;

					$email->Body      = $contentmessage;

					$email->IsHTML(true);

					$email->AddAddress( $to );



					$attach = '';

					foreach($attachments as $attachment)



					{



						$attachment_details = DB::table('task_attached')->where('id',$attachment)->first();



						$path = $attachment_details->url.'/'.$attachment_details->attachment;



						$email->AddAttachment( $path , $attachment_details->attachment );



						DB::table('task_attached')->where('id',$attachment)->update(['status' => 1]);



						$task_id = $attachment_details->task_id;

						if($attach == "")
						{
							$attach = $path;
						}
						else{
							$attach = $attach.'||'.$path;
						}



					}



					$email->Send();			



				}



				$date = date('Y-m-d H:i:s');

				$task_details = DB::table('task')->where('task_id',$task_id)->first();
				if(count($task_details))
				{
					if($task_details->client_id != "")
					{
						$client_details = DB::table('cm_clients')->where('client_id',$task_details->client_id)->first();
						$datamessage['message_id'] = time();
						$datamessage['message_from'] = $from_input;
						$datamessage['subject'] = $subject;
						$datamessage['message'] = $message;
						$datamessage['client_ids'] = $task_details->client_id;
						$datamessage['primary_emails'] = $client_details->email;
						$datamessage['secondary_emails'] = $client_details->email2;
						$datamessage['date_sent'] = date('Y-m-d H:i:s');
						$datamessage['date_saved'] = date('Y-m-d H:i:s');
						$datamessage['source'] = "PMS SYSTEM";
						$datamessage['attachments'] = $attach;
						$datamessage['status'] = 1;

						DB::table('messageus')->insert($datamessage);
					}
				}

				DB::table('task')->where('task_id',$task_id)->update(['last_email_sent' => $date]);

				$task_details = DB::table('task')->where('task_id',$task_id)->first();

				$last_email_date['task_id'] = $task_id;
				$last_email_date['task_created_id'] = $task_details->task_created_id;
				$last_email_date['email_sent'] = $date;
				$last_email_date['options'] = (Input::get('email_sent_option')!= "")?Input::get('email_sent_option'):'0';
				$last_email_date['task_week'] = $task_details->task_week;
				$last_email_date['task_month'] = $task_details->task_month;

				DB::table('task_email_sent')->insert($last_email_date);

				return Redirect::back()->with('message', 'Email Sent Successfully');



			}



			else{



				return Redirect::back()->with('error', 'Email Field is empty so email is not sent');



			}



		}



		else{



			return Redirect::back()->with('error', 'Attachments are empty so Email is not sent');



		}



	}



	public function email_report_send($id='')



	{



		$type = Input::get('type');



		$week = Input::get('week');



		$month = Input::get('month');



		$year = Input::get('year');



		$hidden_report_type = Input::get('hidden_report_type');



		$from_input = Input::get('select_user_report');



		$details = DB::table('user')->where('user_id',$from_input)->first();



		$from = $details->email;



		$user_name = $details->lastname.' '.$details->firstname;



		$toemails = Input::get('to_user_report').','.Input::get('cc_report');



		$sentemails = Input::get('to_user_report').', '.Input::get('cc_report');



		$subject = Input::get('subject_report'); 



		$message = Input::get('message_report');



		$explode = explode(',',$toemails);



		$data['sentmails'] = $sentemails;



		if(count($explode))



		{



			foreach($explode as $exp)



			{



				$to = trim($exp);



				$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');



				$data['message'] = $message;



				$contentmessage = view('user/email_share_paper', $data);



				$email = new PHPMailer();

				$email->SetFrom($from, $user_name); //Name is optional

				$email->Subject   = $subject;

				$email->Body      = $contentmessage;

				$email->IsHTML(true);

				$email->AddAddress( $to );



				if($hidden_report_type == "task_report")



				{



					if($type == 'week')



					{



						$path = 'papers/Task_Report_For_Year-'.$year.'_Week-'.$week.'.pdf';

						$pdfname = 'Task_Report_For_Year-'.$year.'_Week-'.$week.'.pdf';



					}



					elseif($type == 'month'){



						$path = 'papers/Task_Report_For_Year-'.$year.'_Month-'.$month.'.pdf';

						$pdfname = 'Task_Report_For_Year-'.$year.'_Month-'.$month.'.pdf';



					}



				}



				elseif($hidden_report_type == "notify_report"){



					if($type == 'week')



					{



						$path = 'papers/Notify_Report_For_Year-'.$year.'_Week-'.$week.'.pdf';

						$pdfname = 'Notify_Report_For_Year-'.$year.'_Week-'.$week.'.pdf';



					}



					elseif($type == 'month'){



						$path = 'papers/Notify_Report_For_Year-'.$year.'_Month-'.$month.'.pdf';

						$pdfname = 'Notify_Report_For_Year-'.$year.'_Month-'.$month.'.pdf';



					}



				}

				$email->AddAttachment( $path , $pdfname );



				$email->Send();	



			}



		}



		else{



			return Redirect::back()->with('error', 'Email Field is empty so email is not sent');



		}



		return Redirect::back()->with('message', 'Email Sent Successfully');



	}



	public function email_report_pdf(){



		$id = Input::get('id');



		$year = DB::table('week')->where('week_id', $id)->first();



		$year_id = $this->year->getdetail($year->year);



		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();



		$result_task_completed = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',1)->get();



		$result_task_completed_enh = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',1)->get();



		$result_task_completed_cmp = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',1)->get();



		



		$result_task_incomplete = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',0)->get();



		$result_task_incomplete_enh = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',0)->get();



		$result_task_incomplete_cmp = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',0)->get();



		$output = '<style>



						.fa {



						    display: inline-block;



						    font: normal normal normal 14px/1 FontAwesome;



						    font-size: inherit;



						    text-rendering: auto;



						    -webkit-font-smoothing: antialiased;



						    -moz-osx-font-smoothing: grayscale;



						}



						.fa-check:before {



						    content: "\f00c";



						}



						body{



							font-size:8px !important;



						}



						input[type=checkbox]{



							margin-top:10px !important;



							border:0px !important



						}



					</style>



					<p style="font-size:18px !important;">Task Completed</p>



					<p style="font-size:18px !important;">Standard</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed)){



		                $i=1;



		                foreach ($result_task_completed as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important;">Enhanced</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed_enh)){



		                $i=1;



		                foreach ($result_task_completed_enh as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important;">Complex</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed_cmp)){



		                $i=1;



		                foreach ($result_task_completed_cmp as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>



			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete)){



		                $i=1;



		                foreach ($result_task_incomplete as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete_enh)){



		                $i=1;



		                foreach ($result_task_incomplete_enh as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete_cmp)){



		                $i=1;



		                foreach ($result_task_incomplete_cmp as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>';







			    $pdf = PDF::loadHTML($output);



			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf');







	   		 	$subject = 'EasyPayroll.ie: Task Report For Year '.$year_id->year_name.' Week'.$year->week.'';	



	   		 	echo $subject;



	}



	public function email_notify_pdf(){


		$week = Input::get('week');
		$month = Input::get('month');
		$time = Input::get('timeval');

		$email = Input::get('email');
		$message = Input::get('message');
		$admin_details = Db::table('admin')->where('id',1)->first();
		$admin_cc = $admin_details->task_cc_email;
		$from = $admin_details->email;
		$to = trim($email);
		$cc = $admin_cc;
		$data['sentmails'] = $to.' , '.$cc;
		$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
		$data['message'] = $message;
		$contentmessage = view('user/email_notify', $data);
		$subject = 'EasyPayroll.ie: Notification';
		$email = new PHPMailer();
		if($to != '')
		{
			$email->SetFrom($from); //Name is optional
			$email->Subject   = $subject;
			$email->Body      = $contentmessage;
			$email->AddCC($admin_cc);
			$email->IsHTML(true);
			$email->AddAddress( $to );
			$email->Send();	
		}
		if($week == "0")
		{
			$get_task_details = DB::table('task')->where('task_email',$to)->where('task_month',$month)->where('client_id','!=','')->first();
		}
		else{
			$get_task_details = DB::table('task')->where('task_email',$to)->where('task_week',$week)->where('client_id','!=','')->first();
		}

		if(count($get_task_details))
		{
			if($get_task_details->client_id != "")
			{
				$client_details = DB::table('cm_clients')->where('client_id',$get_task_details->client_id)->first();
				$datamessage['message_id'] = $time;
				$datamessage['message_from'] = 0;
				$datamessage['subject'] = $subject;
				$datamessage['message'] = $contentmessage;
				$datamessage['client_ids'] = $get_task_details->client_id;
				$datamessage['primary_emails'] = $client_details->email;
				$datamessage['secondary_emails'] = $client_details->email2;
				$datamessage['date_sent'] = date('Y-m-d H:i:s');
				$datamessage['date_saved'] = date('Y-m-d H:i:s');
				$datamessage['source'] = "PMS SYSTEM";
				$datamessage['status'] = 1;

				DB::table('messageus')->insert($datamessage);
			}
		}



	}



	public function email_notify_tasks_pdf()

	{

		$email = Input::get('email');



		$task_id = Input::get('task_id');

		$client_id = Input::get('clientid');



		$client_details = DB::table('user')->where('user_id',$client_id)->first();



		$task_details = DB::table('task')->where('task_id',$task_id)->first();

		$year = DB::table('year')->where('year_id',$task_details->task_year)->first();

		if($task_details->task_week != 0)

		{

			$week = DB::table('week')->where('week_id',$task_details->task_week)->first();

			$week_month = '<b>Week # : </b>'.$week->week;

		}

		else{

			$month = DB::table('month')->where('month_id',$task_details->task_month)->first();

			$week_month = '<b>Month # : </b>'.$month->month;

		}



		$year = DB::table('year')->where('year_id',$task_details->task_year)->first();

		$admin_details = Db::table('admin')->where('id',1)->first();



		$admin_cc = $admin_details->task_cc_email;

		$from = $admin_details->email;

		$to = trim($email);

		$cc = $admin_cc;



		$data['sentmails'] = $to.' , '.$cc;

		$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');





		$data['task_name'] = $task_details->task_name;

		$data['year'] = $year->year_name;

		$data['week_month'] = $week_month;

		$data['client_name'] = $client_details->firstname;



		$contentmessage = view('user/email_notify_tasks', $data);



		$subject = 'EasyPayroll.ie: Payroll Task Notification';



		$email = new PHPMailer();



		if($to != '')



		{

			$email->SetFrom($from); //Name is optional

			$email->Subject   = $subject;

			$email->Body      = $contentmessage;

			$email->AddCC($cc);

			$email->IsHTML(true);

			$email->AddAddress( $to );

			$email->Send();	

		}

	}



	public function email_notify_pdf_month(){



		$id = Input::get('id');



		$commaval = Input::get('commaval');



		$explode = explode(',',$commaval);



		$type = Input::get('type');







		$year = DB::table('month')->where('month_id', $id)->first();



		$year_id = $this->year->getdetail($year->year);



		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();



		$result_task = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',$type)->get();



		



		$output = '<style>



						.fa {



						    display: inline-block;



						    font: normal normal normal 14px/1 FontAwesome;



						    font-size: inherit;



						    text-rendering: auto;



						    -webkit-font-smoothing: antialiased;



						    -moz-osx-font-smoothing: grayscale;



						}



						.fa-check:before {



						    content: "\f00c";



						}



						body{



							font-size:8px !important;



						}



						input[type=checkbox]{



							margin-top:10px !important;



							border:0px !important



						}



					</style>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Checkbox</td>



				          </tr>



				        



				        ';



		             if(count($result_task)){



		                foreach ($result_task as $result) {



				            $output.= '<tr>';



				                $output.= '<td style="text-align: center;border:1px solid #000;"><label class="task_label">'.$result->task_name.'</label></td><td style="text-align: center;border:1px solid #000;">';



				                if(in_array($result->task_id,$explode))



				                {



				                	$output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                }



				                else{



				                	$output.='<input type="checkbox"><label >&nbsp;</label>';



				                }   



				            $output.='</td></tr>';



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td>



		                </tr>";



		             }



			        $output.='



		    		</table>';







			    $pdf = PDF::loadHTML($output);



			    $pdf->save('papers/Notify_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');







	   		 	$subject = 'EasyPayroll.ie: Notify Report For Year '.$year_id->year_name.' Month'.$year->month.'';	



	   		 	echo $subject;



	}



	public function alltask_report_pdf(){



		$id = Input::get('id');



		$year = DB::table('week')->where('week_id', $id)->first();



		$year_id = $this->year->getdetail($year->year);



		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();



		$result_task_completed = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',1)->get();



		$result_task_completed_enh = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',1)->get();



		$result_task_completed_cmp = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',1)->get();



		



		$result_task_incomplete = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',0)->get();



		$result_task_incomplete_enh = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',0)->get();



		$result_task_incomplete_cmp = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',0)->get();



		$output = '<style>



						.fa {



						    display: inline-block;



						    font: normal normal normal 14px/1 FontAwesome;



						    font-size: inherit;



						    text-rendering: auto;



						    -webkit-font-smoothing: antialiased;



						    -moz-osx-font-smoothing: grayscale;



						}



						.fa-check:before {



						    content: "\f00c";



						}



						body{



							font-size:8px !important;



						}



						input[type=checkbox]{



							margin-top:10px !important;



							border:0px !important



						}



					</style>



					<p style="font-size:18px !important;">Task Completed</p>



					<p style="font-size:18px !important;">Standard</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed)){



		                $i=1;



		                foreach ($result_task_completed as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important;">Enhanced</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed_enh)){



		                $i=1;



		                foreach ($result_task_completed_enh as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important;">Complex</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed_cmp)){



		                $i=1;



		                foreach ($result_task_completed_cmp as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>



			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete)){



		                $i=1;



		                foreach ($result_task_incomplete as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete_enh)){



		                $i=1;



		                foreach ($result_task_incomplete_enh as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete_cmp)){



		                $i=1;



		                foreach ($result_task_incomplete_cmp as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>';







			    $pdf = PDF::loadHTML($output);



			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf');







	   		 	$name = 'Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf';	



	   		 	echo $name;



	}



	public function task_complete_report_pdf(){



		$id = Input::get('id');



		$year = DB::table('week')->where('week_id', $id)->first();



		$year_id = $this->year->getdetail($year->year);



		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();



		



		$result_task_completed = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',1)->get();



		$result_task_completed_enh = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',1)->get();



		$result_task_completed_cmp = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',1)->get();



		



		$output = '<style>



						.fa {



						    display: inline-block;



						    font: normal normal normal 14px/1 FontAwesome;



						    font-size: inherit;



						    text-rendering: auto;



						    -webkit-font-smoothing: antialiased;



						    -moz-osx-font-smoothing: grayscale;



						}



						.fa-check:before {



						    content: "\f00c";



						}



						body{



							font-size:8px !important;



						}



						input[type=checkbox]{



							margin-top:10px !important;



							border:0px !important



						}



					</style>



					<p style="font-size:18px !important;">Task Completed</p>



					<p style="font-size:18px !important;">Standard</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed)){



		                $i=1;



		                foreach ($result_task_completed as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important;">Enhanced</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed_enh)){



		                $i=1;



		                foreach ($result_task_completed_enh as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important;">Complex</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed_cmp)){



		                $i=1;



		                foreach ($result_task_completed_cmp as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>';







			    $pdf = PDF::loadHTML($output);



			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf');







	   		 	$name = 'Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf';	



	   		 	echo $name;



	}



	public function task_incomplete_report_pdf(){



		$id = Input::get('id');



		$year = DB::table('week')->where('week_id', $id)->first();



		$year_id = $this->year->getdetail($year->year);



		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();



		



		$result_task_incomplete = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',1)->where('task_status',0)->get();



		$result_task_incomplete_enh = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',2)->where('task_status',0)->get();



		$result_task_incomplete_cmp = DB::table('task')->where('task_year', $year->year)->where('task_week', $year->week_id)->where('task_classified',3)->where('task_status',0)->get();



		$output = '<style>



						.fa {



						    display: inline-block;



						    font: normal normal normal 14px/1 FontAwesome;



						    font-size: inherit;



						    text-rendering: auto;



						    -webkit-font-smoothing: antialiased;



						    -moz-osx-font-smoothing: grayscale;



						}



						.fa-check:before {



						    content: "\f00c";



						}



						body{



							font-size:8px !important;



						}



						input[type=checkbox]{



							margin-top:10px !important;



							border:0px !important



						}



					</style>



			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>



			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete)){



		                $i=1;



		                foreach ($result_task_incomplete as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete_enh)){



		                $i=1;



		                foreach ($result_task_incomplete_enh as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete_cmp)){



		                $i=1;



		                foreach ($result_task_incomplete_cmp as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>';







			    $pdf = PDF::loadHTML($output);



			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf');







	   		 	$name = 'Task_Report_For_Year-'.$year_id->year_name.'_Week-'.$year->week.'.pdf';	



	   		 	echo $name;



	}



	public function email_report_pdf_month(){



		$id = Input::get('id');



		$year = DB::table('month')->where('month_id', $id)->first();



		$year_id = $this->year->getdetail($year->year);



		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();



		$result_task_completed = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',1)->get();



		$result_task_completed_enh = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',1)->get();



		$result_task_completed_cmp = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',1)->get();



		



		$result_task_incomplete = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',0)->get();



		$result_task_incomplete_enh = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',0)->get();



		$result_task_incomplete_cmp = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',0)->get();



		$output = '<style>



						.fa {



						    display: inline-block;



						    font: normal normal normal 14px/1 FontAwesome;



						    font-size: inherit;



						    text-rendering: auto;



						    -webkit-font-smoothing: antialiased;



						    -moz-osx-font-smoothing: grayscale;



						}



						.fa-check:before {



						    content: "\f00c";



						}



						body{



							font-size:8px !important;



						}



						input[type=checkbox]{



							margin-top:10px !important;



							border:0px !important



						}



					</style>



					<p style="font-size:18px !important;">Task Completed</p>



					<p style="font-size:18px !important;">Standard</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed)){



		                $i=1;



		                foreach ($result_task_completed as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important;">Enhanced</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed_enh)){



		                $i=1;



		                foreach ($result_task_completed_enh as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important;">Complex</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed_cmp)){



		                $i=1;



		                foreach ($result_task_completed_cmp as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>



			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete)){



		                $i=1;



		                foreach ($result_task_incomplete as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete_enh)){



		                $i=1;



		                foreach ($result_task_incomplete_enh as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete_cmp)){



		                $i=1;



		                foreach ($result_task_incomplete_cmp as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>';







			    $pdf = PDF::loadHTML($output);



			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');



				$subject = 'EasyPayroll.ie: Task Report For Year '.$year_id->year_name.' Month'.$year->month.'';	



	   		 	echo $subject;



	}



	public function alltask_report_pdf_month(){



		$id = Input::get('id');



		$year = DB::table('month')->where('month_id', $id)->first();



		$year_id = $this->year->getdetail($year->year);



		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();



		$result_task_completed = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',1)->get();



		$result_task_completed_enh = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',1)->get();



		$result_task_completed_cmp = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',1)->get();



		



		$result_task_incomplete = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',0)->get();



		$result_task_incomplete_enh = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',0)->get();



		$result_task_incomplete_cmp = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',0)->get();



		$output = '<style>



						.fa {



						    display: inline-block;



						    font: normal normal normal 14px/1 FontAwesome;



						    font-size: inherit;



						    text-rendering: auto;



						    -webkit-font-smoothing: antialiased;



						    -moz-osx-font-smoothing: grayscale;



						}



						.fa-check:before {



						    content: "\f00c";



						}



						body{



							font-size:8px !important;



						}



						input[type=checkbox]{



							margin-top:10px !important;



							border:0px !important



						}



					</style>



					<p style="font-size:18px !important;">Task Completed</p>



					<p style="font-size:18px !important;">Standard</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed)){



		                $i=1;



		                foreach ($result_task_completed as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important;">Enhanced</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed_enh)){



		                $i=1;



		                foreach ($result_task_completed_enh as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important;">Complex</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed_cmp)){



		                $i=1;



		                foreach ($result_task_completed_cmp as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>



			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete)){



		                $i=1;



		                foreach ($result_task_incomplete as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete_enh)){



		                $i=1;



		                foreach ($result_task_incomplete_enh as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete_cmp)){



		                $i=1;



		                foreach ($result_task_incomplete_cmp as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>';







			    $pdf = PDF::loadHTML($output);



			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');



	   		 	echo 'Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf';



	}



	public function task_complete_report_pdf_month(){



		$id = Input::get('id');



		$year = DB::table('month')->where('month_id', $id)->first();



		$year_id = $this->year->getdetail($year->year);



		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();



		$result_task_completed = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',1)->get();



		$result_task_completed_enh = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',1)->get();



		$result_task_completed_cmp = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',1)->get();



		



		



		



		$output = '<style>



						.fa {



						    display: inline-block;



						    font: normal normal normal 14px/1 FontAwesome;



						    font-size: inherit;



						    text-rendering: auto;



						    -webkit-font-smoothing: antialiased;



						    -moz-osx-font-smoothing: grayscale;



						}



						.fa-check:before {



						    content: "\f00c";



						}



						body{



							font-size:8px !important;



						}



						input[type=checkbox]{



							margin-top:10px !important;



							border:0px !important



						}



					</style>



					<p style="font-size:18px !important;">Task Completed</p>



					<p style="font-size:18px !important;">Standard</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed)){



		                $i=1;



		                foreach ($result_task_completed as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important;">Enhanced</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed_enh)){



		                $i=1;



		                foreach ($result_task_completed_enh as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important;">Complex</p>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_completed_cmp)){



		                $i=1;



		                foreach ($result_task_completed_cmp as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;">'.$result->updatetime.'</td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>';







			    $pdf = PDF::loadHTML($output);



			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');	



	   		 	echo 'Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf';



	}



	public function task_incomplete_report_pdf_month(){



		$id = Input::get('id');



		$year = DB::table('month')->where('month_id', $id)->first();



		$year_id = $this->year->getdetail($year->year);



		$year_user = DB::table('taskyear')->where('taskyear', $year_id->year_id)->first();



		



		$result_task_incomplete = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',1)->where('task_status',0)->get();



		$result_task_incomplete_enh = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',2)->where('task_status',0)->get();



		$result_task_incomplete_cmp = DB::table('task')->where('task_year', $year->year)->where('task_month', $year->month_id)->where('task_classified',3)->where('task_status',0)->get();



		$output = '<style>



						.fa {



						    display: inline-block;



						    font: normal normal normal 14px/1 FontAwesome;



						    font-size: inherit;



						    text-rendering: auto;



						    -webkit-font-smoothing: antialiased;



						    -moz-osx-font-smoothing: grayscale;



						}



						.fa-check:before {



						    content: "\f00c";



						}



						body{



							font-size:8px !important;



						}



						input[type=checkbox]{



							margin-top:10px !important;



							border:0px !important



						}



					</style>







			    	 <p style="font-size:18px !important; margin-top:10px !important">Task Incomplete</p>



			    	 <p style="font-size:18px !important; margin-top:10px !important">Standard</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete)){



		                $i=1;



		                foreach ($result_task_incomplete as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important; margin-top:10px !important">Enhanced</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete_enh)){



		                $i=1;



		                foreach ($result_task_incomplete_enh as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>







		    		<p style="font-size:18px !important; margin-top:10px !important">Complex</p>



			    	 <table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: center;border:1px solid #000;">S.No</td>



				              <td style="text-align: center;border:1px solid #000;">Task Name</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Enter<br/>Hours</td>



				              <td style="text-align: center;border:1px solid #000;">Holiday<br/>Pay</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Process<br/>Payroll</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">Email<br/>Payslips</td>



				              <td style="text-align: center;border:1px solid #000;">Upload<br/>Report</td>



				              <td style="text-align: center;border:1px solid #000;">Completed On</td>



				          </tr>



				        



				        ';



		             if(count($result_task_incomplete_cmp)){



		                $i=1;



		                foreach ($result_task_incomplete_cmp as $result) {



				            $output.= '<tr>';



				            if($result->task_status == 1) { $disabled='disabled'; } else{ $disabled=''; } 



				                $output.= '<td style="text-align: center;border:1px solid #000;">'.$i.'</td>



				                <td style="text-align: center;border:1px solid #000;"><label class="task_label '.$disabled.'">'.$result->task_name.'</label></td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->enterhours == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->enterhours == 1){



				                       $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->holiday == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->holiday == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->process == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->process == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->payslips == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->payslips == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;" class="table_cray_bg">';



				                    if($result->email == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->email == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td>



				                <td style="text-align: center;border:1px solid #000;">';



				                    if($result->upload == 0){



				                      $output.='<label> - </label>';



				                    }



				                    else if($result->upload == 1){



				                      $output.='<input type="checkbox" checked ><label >&nbsp;</label>';



				                    }



				                    else{



				                      $output.= 'N/A';



				                    }



				                $output.= '</td> 



				                <td style="text-align: center;border:1px solid #000;"><label> - </label></td>       



				            </tr>';



		                $i++;



		                }



		             }



		             else{



		                $output.="<tr>



		                      <td colspan='15' style='text-align: center;border:1px solid #000;'>Task not found</td></tr>";



		             }



			        $output.='



		    		</table>';







			    $pdf = PDF::loadHTML($output);



			    $pdf->save('papers/Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf');



	   		 	echo 'Task_Report_For_Year-'.$year_id->year_name.'_Month-'.$year->month.'.pdf';



	}



	public function close_create_new_week($id = '')
	{
		$week_details = DB::table('week')->where('week_id',$id)->first();
		$check_week = DB::table('week')->where('year',$week_details->year)->get();
		$yearname = DB::table('year')->where('year_id',$week_details->year)->first();
		if(count($check_week) <= 52)

		{
			$count_weeks = $week_details->week + 1;
			$current = date('Y-m-d H:i:s');
			DB::table('week')->where('week_id',$id)->update(['week_closed' => $current]);
			$check_weeks = DB::table('week')->where('year',$week_details->year)->where('week',$count_weeks)->first();
			if(!count($check_weeks))
			{

				$weekid = DB::table('week')->insertGetId(['year' => $week_details->year,'week' => $count_weeks]);
				$gettasks = DB::table('task')->where('task_year',$week_details->year)->where('task_week',$id)->get();
				if(count($gettasks))
				{
					foreach($gettasks as $tasks)
					{
						if($tasks->task_created_id == 0) { $data['task_created_id'] = $tasks->task_id; }
						else{ $data['task_created_id'] = $tasks->task_created_id; }

						$data['task_year'] = $tasks->task_year;
						$data['task_week'] = $weekid;
						$data['task_name'] = $tasks->task_name;
						$data['task_classified'] = $tasks->task_classified;
						$data['enterhours'] = ($tasks->enterhours == 2)?2:0;
						$data['holiday'] = ($tasks->holiday == 2)?2:0;
						$data['process'] = ($tasks->process == 2)?2:0;
						$data['payslips'] = ($tasks->payslips == 2)?2:0;
						$data['email'] = ($tasks->email == 2)?2:0;
						$data['upload'] = ($tasks->upload == 2)?2:0;
						$data['date'] = $tasks->date;
						$data['task_email'] = $tasks->task_email;
						$data['comments'] = $tasks->comments;
						$data['attached'] = $tasks->attached;
						$data['network'] = $tasks->network;
						$data['task_enumber'] = $tasks->task_enumber;
						$data['secondary_email'] = $tasks->secondary_email;
						$data['salutation'] = $tasks->salutation;
						$data['task_status'] = 0;
						$data['client_id'] = $tasks->client_id;
						$data['last_email_sent_carry'] = $tasks->last_email_sent;
						$data['tasklevel'] = $tasks->tasklevel;
						$data['p30_pay'] = $tasks->p30_pay;
						$data['p30_email'] = $tasks->p30_email;
						$data['default_staff'] = $tasks->default_staff;
						$data['scheme_id'] = $tasks->scheme_id;
						$data['disclose_liability'] = $tasks->disclose_liability;


						if($tasks->task_complete_period_type == 2){							
							$data['task_complete_period'] = 1;
							$data['task_complete_period_type'] = 2;
						}
						else{
							$data['task_complete_period'] = 0;
							$data['task_complete_period_type'] = 0;							
						}

						$taskidnew = DB::table('task')->insertGetid($data);

						if($tasks->scheme_id > 0)
						{
							$scheme_det = DB::table('schemes')->where('id',$tasks->scheme_id)->first();
							if(count($scheme_det))
							{
								$upload_dir = 'uploads/task_image';
								if (!file_exists($upload_dir)) {
									mkdir($upload_dir);
								}
								$upload_dir = $upload_dir.'/'.base64_encode($taskidnew);
								if (!file_exists($upload_dir)) {
									mkdir($upload_dir);
								}
								$myfile = fopen($upload_dir.'/'.$scheme_det->scheme_name.".txt", "w") or die("Unable to open file!");
								$txt = "This Payroll is to be run under the Scheme: ".$scheme_det->scheme_name."";
								fwrite($myfile, $txt);
								fclose($myfile);

								$datareceived['task_id'] = $taskidnew;
								$datareceived['attachment'] = $scheme_det->scheme_name.".txt";
								$datareceived['url'] = $upload_dir;
								$datareceived['network_attach'] = 1;
								$datareceived['copied'] = 0;
								DB::table('task_attached')->insert($datareceived);
							}
						}
					}
				}
				return redirect('user/select_week/'.base64_encode($weekid))->with('message','New Week Created Successfully');
			}
			else{
				return Redirect::back()->with('error', 'Sorry! the new week you are trying to create is already exists. Please click Ok to go to that week.<a href="'.URL::to('user/select_week/'.base64_encode($check_weeks->week_id)).'" class="btn btn-sm common_black_button">OK</a><a href="javascript:" class="btn btn-sm common_black_button cancel_week">Cancel</a>');
			}
		}



		else{
			return Redirect::back()->with('error', 'This is the Last week of this year '.$yearname->year_name.' so please contact your Admin to create a new year');



		}



	}



	public function close_create_new_month($id = '')
	{



		$month_details = DB::table('month')->where('month_id',$id)->first();



		$check_month = DB::table('month')->where('year',$month_details->year)->get();



		$yearname = DB::table('year')->where('year_id',$month_details->year)->first();



		if(count($check_month) <= 11)



		{



			$count_months = $month_details->month + 1;



			$current = date('Y-m-d H:i:s');



			DB::table('month')->where('month_id',$id)->update(['month_closed' => $current]);



			$check_months = DB::table('month')->where('year',$month_details->year)->where('month',$count_months)->first();



			if(!count($check_months))



			{



				$monthid = DB::table('month')->insertGetId(['year' => $month_details->year,'month' => $count_months]);



				$gettasks = DB::table('task')->where('task_year',$month_details->year)->where('task_month',$id)->get();



				if(count($gettasks))



				{



					foreach($gettasks as $tasks)



					{

						if($tasks->task_created_id == 0) { $data['task_created_id'] = $tasks->task_id; }
						else{ $data['task_created_id'] = $tasks->task_created_id; }

						$data['task_year'] = $tasks->task_year;
						$data['task_month'] = $monthid;
						$data['task_name'] = $tasks->task_name;
						$data['task_classified'] = $tasks->task_classified;
						$data['enterhours'] = ($tasks->enterhours == 2)?2:0;
						$data['holiday'] = ($tasks->holiday == 2)?2:0;
						$data['process'] = ($tasks->process == 2)?2:0;
						$data['payslips'] = ($tasks->payslips == 2)?2:0;
						$data['email'] = ($tasks->email == 2)?2:0;
						$data['upload'] = ($tasks->upload == 2)?2:0;
						$data['date'] = $tasks->date;
						$data['task_email'] = $tasks->task_email;
						$data['comments'] = $tasks->comments;
						$data['attached'] = $tasks->attached;
						$data['network'] = $tasks->network;
						$data['task_enumber'] = $tasks->task_enumber;
						$data['secondary_email'] = $tasks->secondary_email;
						$data['salutation'] = $tasks->salutation;
						$data['task_status'] = 0;
						$data['client_id'] = $tasks->client_id;
						$data['last_email_sent_carry'] = $tasks->last_email_sent;
						$data['tasklevel'] = $tasks->tasklevel;
						$data['p30_pay'] = $tasks->p30_pay;
						$data['p30_email'] = $tasks->p30_email;
						$data['disclose_liability'] = $tasks->disclose_liability;
						$data['default_staff'] = $tasks->default_staff;
						$data['scheme_id'] = $tasks->scheme_id;

						if($tasks->task_complete_period_type == 2){							
							$data['task_complete_period'] = 1;
							$data['task_complete_period_type'] = 2;
						}
						else{
							$data['task_complete_period'] = 0;
							$data['task_complete_period_type'] = 0;							
						}



						$taskidnew = DB::table('task')->insertGetid($data);

						if($tasks->scheme_id > 0)
						{
							$scheme_det = DB::table('schemes')->where('id',$tasks->scheme_id)->first();
							if(count($scheme_det))
							{
								$upload_dir = 'uploads/task_image';
								if (!file_exists($upload_dir)) {
									mkdir($upload_dir);
								}
								$upload_dir = $upload_dir.'/'.base64_encode($taskidnew);
								if (!file_exists($upload_dir)) {
									mkdir($upload_dir);
								}
								$myfile = fopen($upload_dir.'/'.$scheme_det->scheme_name.".txt", "w") or die("Unable to open file!");
								$txt = "This Payroll is to be run under the Scheme: ".$scheme_det->scheme_name."";
								fwrite($myfile, $txt);
								fclose($myfile);

								$datareceived['task_id'] = $taskidnew;
								$datareceived['attachment'] = $scheme_det->scheme_name.".txt";
								$datareceived['url'] = $upload_dir;
								$datareceived['network_attach'] = 1;
								$datareceived['copied'] = 0;
								DB::table('task_attached')->insert($datareceived);
							}
						}



					}



				}



				return redirect('user/select_month/'.base64_encode($monthid))->with('message','New Month Created Successfully');



			}



			else{



				return Redirect::back()->with('error', 'Sorry! the new month you are trying to create is already exists. Please click Ok to go to that month.<a href="'.URL::to('user/select_month/'.base64_encode($check_months->month_id)).'" class="btn btn-sm common_black_button">OK</a><a href="javascript:" class="btn btn-sm common_black_button cancel_month">Cancel</a>');



			}



		}



		else{



			return Redirect::back()->with('error', 'This is the Last month of this year '.$yearname->year_name.' so please contact your Admin to create a new year');



		}



	}



	public function edit_task_name()

	{

		$task_id = Input::get('task_id');

		$details = DB::table('task')->where('task_id',$task_id)->first();



		if($details->client_id != ''){

			$client_details = DB::table('cm_clients')->where('client_id', $details->client_id)->first();

			if($client_details->company != "")

			{

				$company = $client_details->company;

			}

			else{

				$company = $client_details->firstname.' '.$client_details->surname;

			}

			$companyname = $company.'-'.$client_details->client_id;

			echo json_encode(["task_name" => $details->task_name,"task_email" => $details->task_email,"secondary_email" => $details->secondary_email,"salutation" => $details->salutation,"network" => $details->network,"category" => $details->task_classified,"task_id" => $details->task_id,'holiday' => $details->holiday,'process' => $details->process,'payslips' => $details->payslips,'email' => $details->email,'upload' => $details->upload,'enterhours' => $details->enterhours, 'enumber' => $details->task_enumber,'tasklevel' => $details->tasklevel,'p30_pay' => $details->p30_pay,'p30_email' => $details->p30_email, 'taxreg' => $client_details->tax_reg1, "primaryemail" => $client_details->email, "firstname" => $client_details->firstname, "companyname" => $companyname,"client_id" => $details->client_id]);

		}

		else{

			echo json_encode(["task_name" => $details->task_name,"task_email" => $details->task_email,"secondary_email" => $details->secondary_email,"salutation" => $details->salutation,"network" => $details->network,"category" => $details->task_classified,"task_id" => $details->task_id,'holiday' => $details->holiday,'process' => $details->process,'payslips' => $details->payslips,'email' => $details->email,'upload' => $details->upload,'enterhours' => $details->enterhours, 'enumber' => $details->task_enumber,'tasklevel' => $details->tasklevel,'p30_pay' => $details->p30_pay,'p30_email' => $details->p30_email,"client_id" => $details->client_id]);

		}

	}

	public function edit_email_unsent_files()

	{



		$task_id = Input::get('task_id');



		$result = DB::table('task')->where('task_id',$task_id)->first();



		$files = '';



		$html = '';



		$date = date('d F Y', strtotime($result->last_email_sent));



		$time = date('H:i', strtotime($result->last_email_sent));



		$last_date = $date.' @ '.$time;



		if($result->users != '')



		{



			$user_details = DB::table('user')->where('user_id',$result->users)->first();



			$html.='<strong>On '.date('m-d-Y H:i').', '.$user_details->lastname.' '.$user_details->firstname.' wrote</strong><br/><br/>';  



		}



		else{



			$html.='<strong>On '.date('m-d-Y H:i').', wrote</strong><br/><br/>';  



		}



		$html.='<strong>'.$result->salutation.'</strong><br/>';  



	      $check_attached = DB::table('task_attached')->where('task_id',$task_id)->where('network_attach',0)->where('status',0)->get();



	      if(count($check_attached))



	      {



	        $html.='<strong>Task Name :</strong> <spam>'.$result->task_name.'</spam>';  

	        if($result->disclose_liability == 1)
	        {
	        	if($result->liability == "")
	        	{
	        		$lia = '0.00';
	        	}
	        	else{
	        		$lia = $result->liability;
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        	}
	        	$html.='<p>The PAYE/PRSI/USC Liability for this periods payroll is: '.number_format_invoice_without_decimal($lia).'</p>';
	        }

	        $html.='<ul>';



	        foreach($check_attached as $attch)



	        {



	            $html.='<li>'.$attch->attachment.'</li>';



	            $files.='<p><input type="checkbox" name="check_attachment[]" value="'.$attch->id.'" id="label_'.$attch->id.'" checked><label for="label_'.$attch->id.'">'.$attch->attachment.'</label></p>';



	        }



	        $html.='</ul>';



	      }



	      if($result->task_week != 0)



	      {



	      	$week_details = DB::table('week')->where('week_id',$result->task_week)->first();



	      	$subject = 'EasyPayroll.ie: '.$result->task_name.' Payroll for WEEK '.$week_details->week.'';



	      }



	      else{



	      	$month_details = DB::table('month')->where('month_id',$result->task_month)->first();



	      	$subject = 'EasyPayroll.ie: '.$result->task_name.' Payroll for MONTH '.$month_details->month.'';



	      }



	      if($result->secondary_email != '')



	      {



	      	$to_email = $result->task_email.','.$result->secondary_email;



	      }



	      else{



	      	$to_email = $result->task_email;



	      }







	     echo json_encode(["files" => $files,"html" => $html,"from" => $result->users,"to" => $to_email,'subject' => $subject,'last_email_sent' => $last_date]);



	}





	public function resendedit_email_unsent_files()

	{



		$task_id = Input::get('task_id');



		$result = DB::table('task')->where('task_id',$task_id)->first();



		$files = '';



		$html = '';



		$date = date('d F Y', strtotime($result->last_email_sent));



		$time = date('H:i', strtotime($result->last_email_sent));



		$last_date = $date.' @ '.$time;



		if($result->users != '')



		{



			$user_details = DB::table('user')->where('user_id',$result->users)->first();



			$html.='<strong>On '.date('m-d-Y H:i').', '.$user_details->lastname.' '.$user_details->firstname.' wrote</strong><br/><br/>';  



		}



		else{



			$html.='<strong>On '.date('m-d-Y H:i').', wrote</strong><br/><br/>';  



		}



		$html.='<strong>'.$result->salutation.'</strong><br/>';  



	      $check_attached = DB::table('task_attached')->where('task_id',$task_id)->where('network_attach',0)->where('status',1)->get();



	      if(count($check_attached))



	      {



	        $html.='<strong>Task Name :</strong> <spam>'.$result->task_name.'</spam>';  

	        if($result->disclose_liability == 1)
	        {
	        	if($result->liability == "")
	        	{
	        		$lia = '0.00';
	        	}
	        	else{
	        		$lia = $result->liability;
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        		$lia = str_replace(",","",$lia);
	        	}
	        	$html.='<p>The PAYE/PRSI/USC Liability for this periods payroll is: '.number_format_invoice_without_decimal($lia).'</p>';
	        }

	        $html.='<ul>';



	        foreach($check_attached as $attch)



	        {



	            $html.='<li>'.$attch->attachment.'</li>';



	            $files.='<p><input type="checkbox" name="check_attachment[]" value="'.$attch->id.'" id="label_'.$attch->id.'" checked><label for="label_'.$attch->id.'">'.$attch->attachment.'</label></p>';



	        }



	        $html.='</ul>';



	      }



	      if($result->task_week != 0)



	      {



	      	$week_details = DB::table('week')->where('week_id',$result->task_week)->first();



	      	$subject = 'EasyPayroll.ie: '.$result->task_name.' Payroll for WEEK '.$week_details->week.'';



	      }



	      else{



	      	$month_details = DB::table('month')->where('month_id',$result->task_month)->first();



	      	$subject = 'EasyPayroll.ie: '.$result->task_name.' Payroll for MONTH '.$month_details->month.'';



	      }



	      if($result->secondary_email != '')



	      {



	      	$to_email = $result->task_email.','.$result->secondary_email;



	      }



	      else{



	      	$to_email = $result->task_email;



	      }







	     echo json_encode(["files" => $files,"html" => $html,"from" => $result->users,"to" => $to_email,'subject' => $subject,'last_email_sent' => $last_date]);



	}



	



	public function edit_task_details()



	{



		$id = Input::get('hidden_taskname_id');



		$task_name = Input::get('task_name');



		$task_network = Input::get('task_network');



		$task_category = Input::get('task_category');



		$task_email = Input::get('task_email_edit');



		$secondary_email = Input::get('secondary_email_edit');



		$salutation = Input::get('salutation_edit');



		$enterhours = Input::get('enterhours_edit');



		$holiday = Input::get('holiday_edit');



		$process = Input::get('process_edit');



		$payslips = Input::get('payslips_edit');



		$email = Input::get('email_edit');



		$upload = Input::get('uploadd_edit');







		$enumber = trim(Input::get('enumber'));







		$tasklevel =  trim(Input::get('tasklevel_edit'));



		$p30_pay =  trim(Input::get('pay_p30_edit'));



		$p30_email =  trim(Input::get('email_p30_edit'));



		$clientid = Input::get('hidden_client_id_edit');



		$details = DB::table('task')->where('task_id',$id)->first();



		if($details->enterhours == 2)



		{



			if($enterhours == 0)



			{



				$enterhours = 0;



			}



			else{



				$enterhours = $details->enterhours;



			}



		}



		else{



			if($enterhours == 2)



			{



				$enterhours = 2;



			}



			else{



				$enterhours = $details->enterhours;



			}



		}







		if($details->holiday == 2)



		{



			if($holiday == 0)



			{



				$holiday = 0;



			}



			else{



				$holiday = $details->holiday;



			}



		}



		else{



			if($holiday == 2)



			{



				$holiday = 2;



			}



			else{



				$holiday = $details->holiday;



			}



		}







		if($details->process == 2)



		{



			if($process == 0)



			{



				$process = 0;



			}



			else{



				$process = $details->process;



			}



		}



		else{



			if($process == 2)



			{



				$process = 2;



			}



			else{



				$process = $details->process;



			}



		}







		if($details->payslips == 2)



		{



			if($payslips == 0)



			{



				$payslips = 0;



			}



			else{



				$payslips = $details->payslips;



			}



		}



		else{



			if($payslips == 2)



			{



				$payslips = 2;



			}



			else{



				$payslips = $details->payslips;



			}



		}







		if($details->email == 2)



		{



			if($email == 0)



			{



				$email = 0;



			}



			else{



				$email = $details->email;



			}



		}



		else{



			if($email == 2)



			{



				$email = 2;



			}



			else{



				$email = $details->email;



			}



		}







		if($details->upload == 2)



		{



			if($upload == 0)



			{



				$upload = 0;



			}



			else{



				$upload = $details->upload;



			}



		}



		else{



			if($upload == 2)



			{



				$upload = 2;



			}



			else{



				$upload = $details->upload;



			}



		}



		DB::table('task')->where('task_id',$id)->update(['task_name'=>$task_name,'task_email' => $task_email,'secondary_email' => $secondary_email,'salutation' => $salutation,'network' => $task_network,'task_classified' => $task_category,'enterhours' => $enterhours,'holiday' => $holiday,'process' => $process,'payslips' => $payslips,'email' => $email,'upload' => $upload, 'task_enumber' => $enumber,'tasklevel' =>$tasklevel,'p30_email' => $p30_email,'p30_pay' => $p30_pay,'client_id' => $clientid]);







		$emp_client = Input::get('hidden_client_emp_edit');



		$saluation_client = Input::get('hidden_client_salutation_edit');



		$clientid = Input::get('hidden_client_id_edit');



		



		if($emp_client == 1)



		{



			$data['employer_no'] = trim(Input::get('enumber'));



		}



		if($saluation_client == 1)



		{



			$data['salutation'] = trim(Input::get('salutation_edit'));



		}



		if($emp_client == 1 || $saluation_client == 1)



		{



			DB::table('cm_clients')->where('client_id',$clientid)->update($data);



		}



		return Redirect::back()->with('message', 'Task Name and Network Updated successfully');



	}



	public function downloadpdf()



	{



		$filepath = $_GET['filename'];



		//header("Content-Type: application/pdf");



		header('Content-Description: File Transfer');



		header('Content-Type: application/octet-stream');



		header('Content-Disposition: attachment; filename="'.basename($filepath).'"');



		header('Content-Transfer-Encoding: binary');



		header('Cache-Control: must-revalidate');



		header('Content-Length: '.filesize($filepath));



		//ob_clean();



		//flush();



		try{



			$page = file_get_contents($filepath);



			echo $page;



			header('Set-Cookie: fileDownload=true; path=/');



		}catch(Exception $e){



			header('Set-Cookie: fileDownload=false; path=/');	



		}



		exit;



	}



	public function update_incomplete_status()



	{



		$data['week_incomplete'] = Input::get('value');



		DB::table('user_login')->where('userid',1)->update($data);



	}

	public function update_incomplete_status_month()

	{

		$data['month_incomplete'] = Input::get('value');

		DB::table('user_login')->where('userid',1)->update($data);

	}

	public function vatclients()

	{

		$clients = DB::table('vat_clients')->get();

		return view('user/vatclients', array('clientlist' => $clients));

	}

	public function deactivevatclients($id=""){

		$id = base64_decode($id);

		$deactive =  1;

		DB::table('vat_clients')->where('client_id', $id)->update(['status' => $deactive ]);

		return redirect::back()->with('message','Deactive Success');

	}



	public function activevatclients($id=""){



		$id = base64_decode($id);



		$active =  0;



		DB::table('vat_clients')->where('client_id', $id)->update(['status' => $active ]);



		return redirect::back()->with('message','Active Success');



	}



	public function editvatclients($id=""){

		$id = base64_decode($id);

		$result = DB::table('vat_clients')->where('client_id', $id)->first();

		if($result->cm_client_id != ''){

			$client_details = DB::table('cm_clients')->where('client_id', $result->cm_client_id)->first();

			if($client_details->company != "")

			{

				$companyname = $client_details->company.'-'.$client_details->client_id;

			}

			else{

				$companyname = $client_details->firstname.' '.$client_details->surname.'-'.$client_details->client_id;

			}

			echo json_encode(array('name' => $result->name, 'taxnumber' => $result->taxnumber, 'pemail' =>  $result->pemail, 'semail' =>  $result->semail, 'salutation' =>  $result->salutation, 'self_manage' =>  $result->self_manage,'always_nil' =>  $result->always_nil, 'id' => $result->client_id,'taxreg' => $client_details->tax_reg1, "firstname" => $result->name, "companyname" => $companyname,"cm_client_id" => $result->cm_client_id));

		}

		else{

			echo json_encode(array('name' => $result->name, 'taxnumber' => $result->taxnumber, 'pemail' =>  $result->pemail, 'semail' =>  $result->semail, 'salutation' =>  $result->salutation, 'self_manage' =>  $result->self_manage,'always_nil' =>  $result->always_nil, 'id' => $result->client_id,'cm_client_id' => $result->cm_client_id, "firstname" => $result->name));

		}

	}



	public function updatevatclients(){



		$name = Input::get('name');		



		$pemail = Input::get('pemail');



		$semail = Input::get('semail');



		$salutation = Input::get('salutation');



		$self_manage = Input::get('self');



		$always_nil = Input::get('always_nil');



		$id = Input::get('id');







		$client_id = Input::get('client_id');







		DB::table('vat_clients')->where('client_id', $id)->update(['name' => $name, 'pemail' => $pemail, 'semail' => $semail, 'salutation' => $salutation, 'self_manage' => $self_manage, 'always_nil' => $always_nil,'cm_client_id' => $client_id]);







		$saluation_client = Input::get('hidden_salutation');



		$clientid = Input::get('client_id');



		if($saluation_client == 1)



		{



			$data['salutation'] = trim(Input::get('salutation'));



			DB::table('cm_clients')->where('client_id',$clientid)->update($data);



		}







		$check_status = DB::table('vat_clients')->where('client_id',$id)->first();







		$red = DB::table('vat_clients')->where('status',1)->count();



		$green = DB::table('vat_clients')->where('status',0)->where('pemail','!=', '')->where('self_manage','no')->count();



		$yellow = DB::table('vat_clients')->where('status',0)->where('pemail', '')->where('self_manage','no')->count();



		$purple = DB::table('vat_clients')->where('status',0)->where('self_manage','yes')->count();



		echo json_encode(array('status' => $check_status->status,'cm_client_id' => $check_status->cm_client_id, 'red' => $red, 'green' =>  $green, 'yellow' =>  $yellow, 'purple' =>  $purple));



	}



	public function addvatclients(){



		$data['name'] = Input::get('name');		



		$data['taxnumber'] = Input::get('taxnumber');	



		$data['pemail'] = Input::get('pemail');



		$data['semail'] = Input::get('semail');



		$data['salutation'] = Input::get('salutation');



		$data['tax_type'] = Input::get('tax_type');



		$data['document_type'] = Input::get('document_type');



		$data['ros_filer'] = Input::get('ros_filer');



		$data['period'] = Input::get('period_add');







		$data['cm_client_id'] = Input::get('cmclientid');











		$explodedate = explode('-',Input::get('due_add'));



		if(count($explodedate) == 3)



		{



			$data['due_date'] = $explodedate[2].'-'.$explodedate[0].'-'.$explodedate[1];



		}



		$data['self_manage'] = Input::get('self');



		$data['always_nil'] = Input::get('always_nil');



		DB::table('vat_clients')->insert($data);







		$saluation_client = Input::get('hidden_client_salutation');



		$clientid = Input::get('hidden_client_id');



		if($saluation_client == 1)



		{



			$cmclient['salutation'] = trim(Input::get('salutation'));



			DB::table('cm_clients')->where('client_id',$clientid)->update($cmclient);



		}







		return redirect::back()->with('message','Added Successfully');



	}



	public function checkclientemail()



	{



		$email = Input::get('email');



		$client_id = Input::get('client_id');



		$type = Input::get('type');



		if($client_id == "")



		{



			$check = DB::table('vat_clients')->where('pemail', $email)->first();



			$check_sec = DB::table('vat_clients')->where('semail', $email)->first();



		}



		else{



			$check = DB::table('vat_clients')->where('client_id','!=', $client_id)->where('pemail', $email)->first();



			$check_sec = DB::table('vat_clients')->where('client_id','!=', $client_id)->where('semail', $email)->first();



		}



		if(count($check) || count($check_sec))



		{



			echo 1;



		}



		else{



			echo 0;



		}



	}



	public function checkclienttaxnumber()



	{



		$tax = Input::get('taxnumber');



		$check = DB::table('vat_clients')->where('taxnumber', $tax)->first();



		if(count($check))



		{



			echo 1;



		}



		else{



			echo 0;



		}



	}



	



	public function in_array_r($item , $array){



	    return preg_match('/"'.$item.'"/i' , json_encode($array));



	}



	public function import_form()



	{



		if($_FILES['import_file']['name']!='')



		{



			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';



			$tmp_name = $_FILES['import_file']['tmp_name'];



			$name=$_FILES['import_file']['name'];



			$errorlist = array();



			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){







				$filepath = $uploads_dir.'/'.$name;



				$objPHPExcel = PHPExcel_IOFactory::load($filepath);



				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {



					$worksheetTitle     = $worksheet->getTitle();



					$highestRow         = $worksheet->getHighestRow(); // e.g. 10



					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'



					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);



					$nrColumns = ord($highestColumn) - 64;



					if($highestRow > 500)



					{



						$height = 500;



					}



					else{



						$height = $highestRow;



					}



					$insertrows = array();



					for ($row = 2; $row <= $height; ++ $row) {



						$client_name = $worksheet->getCellByColumnAndRow(0, $row); $client_name = trim($client_name->getValue());



						$ros_filter = $worksheet->getCellByColumnAndRow(1, $row); $ros_filter = trim($ros_filter->getValue());



						$tax_type = $worksheet->getCellByColumnAndRow(2, $row); $tax_type = trim($tax_type->getValue());



						$doc_type = $worksheet->getCellByColumnAndRow(3, $row); $doc_type = trim($doc_type->getValue());



						$tax_no = $worksheet->getCellByColumnAndRow(4, $row);  $tax_no = trim($tax_no->getValue());



						$period = $worksheet->getCellByColumnAndRow(5, $row);  $period = trim($period->getValue());



						$due_date = $worksheet->getCellByColumnAndRow(6, $row);  $due_date = trim($due_date->getValue());







						if($tax_type != 'VAT')



						{



							Session::forget('insertrows');



							return redirect('user/vat_clients')->with('message_import_not_valid', 'This is not a valid VAT import file');



						}



						$check_tax = DB::table('vat_clients')->where('taxnumber',$tax_no)->first();



						$check_array = $this->in_array_r($tax_no,$insertrows);



						if($client_name != "Name not found" && $tax_no != "")



						{



							if(!count($check_tax) && $check_array == 0)



							{



								$data['name'] = $client_name;



								$data['ros_filer'] = $ros_filter;



								$data['tax_type'] = $tax_type;



								$data['document_type'] = $doc_type;



								$data['taxnumber'] = $tax_no;



								$data['period'] = $period;



								$due = explode('-',$due_date);



								if(count($due) == 3)



								{



									$data['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];



								}



								else{



									$due = explode('/',$due_date);



									if(count($due) == 3)



									{



										$data['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];



									}



								}



								$data['self_manage'] = 'no';



								$data['always_nil'] = 'no';



								array_push($insertrows,$data);								



							}



						}



					}



				}



				$sessn=array('insertrows' => $insertrows);



				Session::put($sessn);







				if($height >= $highestRow)



				{



					return redirect('user/vat_clients')->with('message_import', 'Items Listed successfully.');



				}



				else{



					return redirect('user/vat_clients?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type=1');



				}



			}



		}



	}



	public function import_form_one()



	{



		$name = Input::get('filename');



		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';



		$filepath = $uploads_dir.'/'.$name;



		$objPHPExcel = PHPExcel_IOFactory::load($filepath);







		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {



			$worksheetTitle     = $worksheet->getTitle();



			$highestRow         = $worksheet->getHighestRow(); // e.g. 10



			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'



			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);



			$nrColumns = ord($highestColumn) - 64;



			$round = Input::get('round');



			$last_height = Input::get('height');







			$offset = $round - 1;



			$offsetcount = $last_height + 1;



			$roundcount = $round * 500;



			$nextround = $round + 1;







			if($highestRow > $roundcount)



			{



				$height = $roundcount;



			}



			else{



				$height = $highestRow;



			}



			$insertrows = Session::get('insertrows');



			for ($row = $offsetcount; $row <= $height; ++ $row) {



				$client_name = $worksheet->getCellByColumnAndRow(0, $row); $client_name = trim($client_name->getValue());



				$ros_filter = $worksheet->getCellByColumnAndRow(1, $row); $ros_filter = trim($ros_filter->getValue());



				$tax_type = $worksheet->getCellByColumnAndRow(2, $row); $tax_type = trim($tax_type->getValue());



				$doc_type = $worksheet->getCellByColumnAndRow(3, $row); $doc_type = trim($doc_type->getValue());



				$tax_no = $worksheet->getCellByColumnAndRow(4, $row);  $tax_no = trim($tax_no->getValue());



				$period = $worksheet->getCellByColumnAndRow(5, $row);  $period = trim($period->getValue());



				$due_date = $worksheet->getCellByColumnAndRow(6, $row);  $due_date = trim($due_date->getValue());







				if($tax_type != 'VAT')



				{



					Session::forget('insertrows');



					return redirect('user/vat_clients')->with('message_import_not_valid', 'This is not a valid VAT import file');



				}



				$check_tax = DB::table('vat_clients')->where('taxnumber',$tax_no)->first();



				$check_array = $this->in_array_r($tax_no,$insertrows);



				if($client_name != "Name not found" && $tax_no != "")



				{



					if(!count($check_tax) && $check_array == 0)



					{



						$data['name'] = $client_name;



						$data['ros_filer'] = strtolower($ros_filter);



						$data['tax_type'] = $tax_type;



						$data['document_type'] = $doc_type;



						$data['taxnumber'] = $tax_no;



						$data['period'] = $period;



						$due = explode('-',$due_date);



						if(count($due) == 3)



						{



							$data['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];



						}



						else{



							$due = explode('/',$due_date);



							if(count($due) == 3)



							{



								$data['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];



							}



						}



						$data['self_manage'] = 'no';



						$data['always_nil'] = 'no';



						array_push($insertrows,$data);								



					}



				}



			}



		}



		Session::forget("insertrows");



		$sessn=array('insertrows' => $insertrows);



		Session::put($sessn);



		if($height >= $highestRow)



		{



			return redirect('user/vat_clients')->with('message_import', 'Items Listed successfully.');



		}



		else{



			return redirect('user/vat_clients?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&import_type=1');



		}



	}



	public function compare_form()



	{



		DB::table('vat_clients_compare')->truncate();



		if($_FILES['compare_file']['name']!='')



		{



			$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';



			$tmp_name = $_FILES['compare_file']['tmp_name'];



			$name=$_FILES['compare_file']['name'];



			$errorlist = array();



			if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){







				$filepath = $uploads_dir.'/'.$name;



				$objPHPExcel = PHPExcel_IOFactory::load($filepath);



				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {



					$worksheetTitle     = $worksheet->getTitle();



					$highestRow         = $worksheet->getHighestRow(); // e.g. 10



					$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'



					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);



					$nrColumns = ord($highestColumn) - 64;



					if($highestRow > 500)



					{



						$height = 500;



					}



					else{



						$height = $highestRow;



					}



					$customer_name_label = $worksheet->getCellByColumnAndRow(0, 1); $customer_name_label = trim($customer_name_label->getValue());



					$ros_filter_label = $worksheet->getCellByColumnAndRow(1, 1); $ros_filter_label = trim($ros_filter_label->getValue());







					if($customer_name_label == "Customer Name" && $ros_filter_label == "Mandatory ROS filer")



					{



						$insertrows = array();



						$alreadyinsertedrows = array();



						for ($row = 2; $row <= $height; ++ $row) {



							$client_name = $worksheet->getCellByColumnAndRow(0, $row); $client_name = trim($client_name->getValue());



							$ros_filter = $worksheet->getCellByColumnAndRow(1, $row); $ros_filter = trim($ros_filter->getValue());



							$tax_type = $worksheet->getCellByColumnAndRow(2, $row); $tax_type = trim($tax_type->getValue());



							$doc_type = $worksheet->getCellByColumnAndRow(3, $row); $doc_type = trim($doc_type->getValue());



							$tax_no = $worksheet->getCellByColumnAndRow(4, $row);  $tax_no = trim($tax_no->getValue());



							$period = $worksheet->getCellByColumnAndRow(5, $row);  $period = trim($period->getValue());



							$due_date = $worksheet->getCellByColumnAndRow(6, $row);  $due_date = trim($due_date->getValue());



							if($tax_type != 'VAT')



							{



								Session::forget('comparerows');



								Session::forget('alreadyinsertedrows');



								return redirect('user/vat_clients')->with('message_import_not_valid', 'This is not a valid VAT import file');



							}



							$check_tax = DB::table('vat_clients')->where('taxnumber',$tax_no)->first();



							if($client_name != "Name not found" && $tax_no != "")



							{



								if(!count($check_tax))



								{



									$data['name'] = $client_name;



									$data['ros_filer'] = $ros_filter;



									$data['tax_type'] = $tax_type;



									$data['document_type'] = $doc_type;



									$data['taxnumber'] = $tax_no;



									$data['period'] = $period;



									$data['due_date'] = $due_date;



									array_push($insertrows,$data);								



								}



								else{



									array_push($alreadyinsertedrows,$tax_no);



								}







								$dataall['name'] = $client_name;



								$dataall['ros_filer'] = $ros_filter;



								$dataall['tax_type'] = $tax_type;



								$dataall['document_type'] = $doc_type;



								$dataall['taxnumber'] = $tax_no;



								$dataall['period'] = $period;



								$due = explode('-',$due_date);



								if(count($due) == 3)



								{



									$dataall['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];



								}



								else{



									$due = explode('/',$due_date);



									if(count($due) == 3)



									{



										$dataall['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];



									}



								}



								DB::table('vat_clients_compare')->insert($dataall);



							}



						}



					}



					else{



						return redirect('user/vat_clients')->with('message', 'The File Format is not supported.');



					}



				}



				$sessn=array('comparerows' => $insertrows,'alreadyinsertedrows' => $alreadyinsertedrows);



				Session::put($sessn);







				if($height >= $highestRow)



				{



					return redirect('user/vat_clients')->with('message_compare', 'Items Listed successfully.');



				}



				else{



					return redirect('user/vat_clients?filename='.$name.'&height='.$height.'&round=2&highestrow='.$highestRow.'&compare_type=1');



				}



			}



		}



	}



	public function compare_form_one()



	{



		$name = Input::get('filename');



		$uploads_dir = dirname($_SERVER["SCRIPT_FILENAME"]).'/uploads/importfiles';



		$filepath = $uploads_dir.'/'.$name;



		$objPHPExcel = PHPExcel_IOFactory::load($filepath);







		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {



			$worksheetTitle     = $worksheet->getTitle();



			$highestRow         = $worksheet->getHighestRow(); // e.g. 10



			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'



			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);



			$nrColumns = ord($highestColumn) - 64;



			$round = Input::get('round');



			$last_height = Input::get('height');







			$offset = $round - 1;



			$offsetcount = $last_height + 1;



			$roundcount = $round * 500;



			$nextround = $round + 1;







			if($highestRow > $roundcount)



			{



				$height = $roundcount;



			}



			else{



				$height = $highestRow;



			}



			$insertrows = Session::get('comparerows');



			$alreadyinsertedrows = Session::get('alreadyinsertedrows');







			for ($row = $offsetcount; $row <= $height; ++ $row) {



				$client_name = $worksheet->getCellByColumnAndRow(0, $row); $client_name = trim($client_name->getValue());



				$ros_filter = $worksheet->getCellByColumnAndRow(1, $row); $ros_filter = trim($ros_filter->getValue());



				$tax_type = $worksheet->getCellByColumnAndRow(2, $row); $tax_type = trim($tax_type->getValue());



				$doc_type = $worksheet->getCellByColumnAndRow(3, $row); $doc_type = trim($doc_type->getValue());



				$tax_no = $worksheet->getCellByColumnAndRow(4, $row);  $tax_no = trim($tax_no->getValue());



				$period = $worksheet->getCellByColumnAndRow(5, $row);  $period = trim($period->getValue());



				$due_date = $worksheet->getCellByColumnAndRow(6, $row);  $due_date = trim($due_date->getValue());



				if($tax_type != 'VAT')



				{



					Session::forget('comparerows');



					Session::forget('alreadyinsertedrows');



					return redirect('user/vat_clients')->with('message_import_not_valid', 'This is not a valid VAT import file');



				}



				$check_tax = DB::table('vat_clients')->where('taxnumber',$tax_no)->first();



				if($client_name != "Name not found" && $tax_no != "")



				{



					if(!count($check_tax))



					{



						$data['name'] = $client_name;



						$data['ros_filer'] = strtolower($ros_filter);



						$data['tax_type'] = $tax_type;



						$data['document_type'] = $doc_type;



						$data['taxnumber'] = $tax_no;



						$data['period'] = $period;



						$explode = explode("-",$due_date);



						$data['due_date'] = $explode[2].'-'.$explode[1].'-'.$explode[0];



						array_push($insertrows,$data);								



					}



					else{



						array_push($alreadyinsertedrows,$tax_no);



					}



					$dataall['name'] = $client_name;



					$dataall['ros_filer'] = $ros_filter;



					$dataall['tax_type'] = $tax_type;



					$dataall['document_type'] = $doc_type;



					$dataall['taxnumber'] = $tax_no;



					$dataall['period'] = $period;



					$due = explode('-',$due_date);



								if(count($due) == 3)



								{



									$dataall['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];



								}



								else{



									$due = explode('/',$due_date);



									if(count($due) == 3)



									{



										$dataall['due_date'] = $due[2].'-'.$due[1].'-'.$due[0];



									}



								}



					DB::table('vat_clients_compare')->insert($dataall);



				}



			}



		}



		Session::forget("comparerows");



		$sessn=array('comparerows' => $insertrows,'alreadyinsertedrows' => $alreadyinsertedrows);



		Session::put($sessn);



		if($height >= $highestRow)



		{



			return redirect('user/vat_clients')->with('message_compare', 'Items Listed successfully.');



		}



		else{



			return redirect('user/vat_clients?filename='.$name.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&compare_type=1');



		}



	}



	public function vat_notifications()



	{



		$without_emailed = DB::table('vat_clients')->where('pemail','')->where('semail','')->where('status',0)->get();



		$disabled = DB::table('vat_clients')->where('status',1)->get();



		$with_emailed = DB::table('vat_clients')->where('status',0)->where('self_manage','!=','')->where('status',0)->whereRaw('(pemail != "" OR semail != "")')->get();



		return view('user/vatnotifications', array('without_emailed' => $without_emailed, 'disabled' => $disabled, 'with_emailed' => $with_emailed));



	}



	public function import_sessions()



	{



		$sessions = Session::get('insertrows');



		if(count($sessions))



		{



			foreach($sessions as $key => $sess)



			{



				DB::table('vat_clients')->insert($sess);



			}



			return redirect('user/vat_clients')->with('message', 'Clients Imported successfully.');



			Session::forget('insertrows');



		}



		else{



			Session::forget('insertrows');



			return redirect('user/vat_clients')->with('message', 'Sorry there is no datas to upload.');



		}



	}



	public function email_vatnotifications(){
		$admin_details = Db::table('admin')->first();
		$admin_cc = $admin_details->vat_cc_email;
		$client_id = Input::get('client_id');
		$pemail = Input::get('pemail');
		$semail = Input::get('semail');
		$emails = Input::get('emails');
		$explode = explode(",",$emails);
		$exp_emails = array_values(array_filter($explode, 'strlen'));
		$email_sent_to =  implode(" , ",$exp_emails).' , '.$admin_cc;
		$salutation = Input::get('salutation');
		$self_manage = Input::get('self_manage');
		$period = Input::get('period');
		$due_date = Input::get('due_date');
		$time = Input::get('timeval');
		$admin_details = DB::table('admin')->where('id',1)->first();
		$from = $admin_details->email;
		$client_details = DB::table('vat_clients')->where('client_id',$client_id)->first();
		$cc = $admin_cc;
		if($pemail != "" && $semail != '')
		{
			$sentemails = $pemail.' , '.$semail.' , '.$cc;
		}
		elseif($pemail != "" && $semail == '')
		{
			$sentemails = $pemail.' , '.$cc;
		}
		elseif($pemail == "" && $semail != '')
		{
			$sentemails = $semail.' , '.$cc;
		}
		if($pemail != "")
		{
			$to = trim($pemail);
			$data['sentmails'] = $sentemails;
			$data['logo'] = URL::to('assets/images/logo.png');
			$data['salutation'] = $salutation;
			$data['self_manage'] = $self_manage;
			$data['period'] = $period;
			$data['due_date'] = $due_date;
			$data['signature'] = $admin_details->signature;
			$contentmessage = view('user/email_vat_notify', $data);
			$subject = 'GBS & Co: VAT Reminder for '.$client_details->name.'';
			$email = new PHPMailer();
			$email->SetFrom($from); //Name is optional
			$email->Subject   = $subject;
			$email->Body      = $contentmessage;
			$email->AddCC($admin_cc);
			$email->IsHTML(true);
			$email->AddAddress( $to );
			$email->Send();

			if($client_details->cm_client_id != "")
			{
				$cm_client_details = DB::table('cm_clients')->where('client_id',$client_details->cm_client_id)->first();
				$datamessage['message_id'] = $time;
				$datamessage['message_from'] = 0;
				$datamessage['subject'] = $subject;
				$datamessage['message'] = $contentmessage;
				$datamessage['client_ids'] = $client_details->cm_client_id;
				$datamessage['primary_emails'] = $cm_client_details->email;
				$datamessage['secondary_emails'] = $cm_client_details->email2;
				$datamessage['date_sent'] = date('Y-m-d H:i:s');
				$datamessage['date_saved'] = date('Y-m-d H:i:s');
				$datamessage['source'] = 'VAT SYSTEM';
				$datamessage['status'] = 1;

				DB::table('messageus')->insert($datamessage);
			}

			$date = date('Y-m-d H:i:s');
			Db::table('vat_clients')->where('client_id',$client_id)->update(['last_email_sent' => $date]);
		}
		if($semail != "")
		{
			$to = trim($semail);
			$data['sentmails'] = $sentemails;
			$data['logo'] = URL::to('assets/images/logo.png');
			$data['salutation'] = $salutation;
			$data['self_manage'] = $self_manage;
			$data['period'] = $period;
			$data['due_date'] = $due_date;
			$data['signature'] = $admin_details->signature;
			$contentmessage = view('user/email_vat_notify', $data);
			$subject = 'GBS & Co: VAT Reminder for '.$client_details->name.'';
			$email = new PHPMailer();
			$email->SetFrom($from); //Name is optional
			$email->Subject   = $subject;
			$email->Body      = $contentmessage;
			$email->AddCC($admin_cc);
			$email->IsHTML(true);
			$email->AddAddress( $to );
			$email->Send();	
			$date = date('Y-m-d H:i:s');
			Db::table('vat_clients')->where('client_id',$client_id)->update(['last_email_sent' => $date]);
		}
		if($pemail != "" || $semail !="")
		{
			Db::table('vat_clients_email')->insert(['email_sent' => $date,'client_id' => $client_id]);
		}
	}



	public function email_sents()



	{



		$id = base64_decode(Input::get('id'));



		$emails_dates = DB::table('vat_clients_email')->where('client_id',$id)->get();



		$output = '';



		if(count($emails_dates))



		{



			$i = 1;



			foreach($emails_dates as $emails)



			{



				$output.='<tr>



				<td>'.$i.'</td>



				<td>'.date('d F Y', strtotime($emails->email_sent)).'</td>



				<td>'.date('H:i', strtotime($emails->email_sent)).'</td>



				</tr>';



				$i++;



			}



		}



		else{



			$output.='<tr><td colspan="3">No Data Found</td></tr>';



		}



		echo $output;



	}



	public function email_sents_save_pdf()



	{



		$id = base64_decode(Input::get('id'));



		$emails_dates = DB::table('vat_clients_email')->where('client_id',$id)->get();



		$client_details = DB::table('vat_clients')->where('client_id',$id)->first();



		$output = '<h4>Email Sent Date & Time For Client : '.$client_details->name.' and Taxnumber : '.$client_details->taxnumber.'</h4>



					<table style="width: 100%;border-collapse:collapse">



				        



				          <tr>



				              <td style="text-align: left;border:1px solid #000;">S.No</td>



				              <td style="text-align: left;border:1px solid #000;">Date</td>



				              <td style="text-align: left;border:1px solid #000;">Time</td>



				          </tr>



				        



				        ';



						if(count($emails_dates))



						{



							$i = 1;



							foreach($emails_dates as $emails)



							{



								$output.='<tr>



								<td style="text-align: left;border:1px solid #000;">'.$i.'</td>



								<td style="text-align: left;border:1px solid #000;">'.date('d F Y', strtotime($emails->email_sent)).'</td>



								<td style="text-align: left;border:1px solid #000;">'.date('H:i', strtotime($emails->email_sent)).'</td>



								</tr>';



								$i++;



							}



						}



						else{



							$output.='<tr><td colspan="3" style="text-align: center;border:1px solid #000;">No Data Found</td></tr>';



						}



		$output.='



		</table>';



		$pdf = PDF::loadHTML($output);



	    $pdf->save('papers/'.$client_details->name.'_'.$client_details->taxnumber.'.pdf');







	 	$filename = $client_details->name.'_'.$client_details->taxnumber.'.pdf';	



	 	echo $filename;



	}



	public function pdf_without_email(){



		$taxnumber_compared = Session::get('alreadyinsertedrows');



		$without_emailed = DB::table('vat_clients')->where('pemail','')->where('semail','')->where('status',0)->get();



		$logo = URL::to('assets/images/logo.png');



		$output = '<div style="width:100%;"><img src="'.$logo.'" style="width:178px; margin:0px auto;margin-left:38%"/></div><br/><br/><br/><br/>



						<h4>Clients Without Email Address: </h4><br/>



					<table style="width:100%;border-collapse:collapse">



				          <tr>



				              <td style="text-align: left;border:1px solid #000;">#</td>



				              <td style="text-align: left;border:1px solid #000;">Client Name</td>



				              <td style="text-align: left;border:1px solid #000;">Tax Regn./Trader No</td>



				          </tr>';



						$i=1;



                		if(count($without_emailed)){              



                  			foreach($without_emailed as $without){       



                  				if(in_array($without->taxnumber,$taxnumber_compared)) {  



            							$output.='<tr>



						                <td style="text-align: left;border:1px solid #000"><label>'.$i.'</label></td>



						                <td style="text-align: left; border:1px solid #000"><label>'.$without->name.'</label></td>



						                <td style="text-align: left; border:1px solid #000"><label>'.$without->taxnumber.'</label></td>



            							</tr>';



               							$i++;      



                				}                        



                			}              



              			}



			            if($i == 1)



			            {



			                $output.='<tr><td colspan="3" align="left">Empty</td></tr>';



			            }



						$output.='</table>';



			    $pdf = PDF::loadHTML($output);



			    $pdf->save('papers/Vat_Clients_Without_Email.pdf');



	   		 	echo 'Vat_Clients_Without_Email.pdf';



	}



	public function pdf_disabled(){



		$taxnumber_compared = Session::get('alreadyinsertedrows');



		$disabled = DB::table('vat_clients')->where('status',1)->get();



		$logo = URL::to('assets/images/logo.png');



		$output = '<div style="width:100%;"><img src="'.$logo.'" style="width:178px; margin:0px auto;margin-left:38%"/></div><br/><br/><br/><br/>



						<h4>Clients Without Email Address: </h4><br/>



					<table style="width: 100%;border-collapse:collapse">



				          <tr>



				              <td style="text-align: left;border:1px solid #000;">#</td>



				              <td style="text-align: left;border:1px solid #000;">Client Name</td>



				              <td style="text-align: left;border:1px solid #000;">Tax Regn./Trader No</td>



				          </tr>';



						$i=1;



                		if(count($disabled)){              



                  			foreach($disabled as $disable){       



                  				if(in_array($disable->taxnumber,$taxnumber_compared)) {  



            							$output.='<tr style="text-align:left">



						                <td style="text-align: left; border:1px solid #000"><label>'.$i.'</label></td>



						                <td style="text-align: left; border:1px solid #000"><label>'.$disable->name.'</label></td>



						                <td style="text-align: left; border:1px solid #000"><label>'.$disable->taxnumber.'</label></td>



            							</tr>';



               							$i++;      



                				}                        



                			}              



              			}



			            if($i == 1)



			            {



			                $output.='<tr><td colspan="3" align="left">Empty</td></tr>';



			            }



						$output.='</table>';



			    $pdf = PDF::loadHTML($output);



			    $pdf->save('papers/Vat_Clients_Disabled.pdf');



	   		 	echo 'Vat_Clients_Disabled.pdf';



	}



	public function pdf_with_email(){



		$taxnumber_compared = Session::get('alreadyinsertedrows');



		$with_emailed = DB::table('vat_clients')->where('status',0)->where('self_manage','!=','')->where('status',0)->whereRaw('(pemail != "" OR semail != "")')->get();



		$logo = URL::to('assets/images/logo.png');



		$output = '<div style="width:100%;"><img src="'.$logo.'" style="width:178px; margin:0px auto;margin-left:38%"/></div><br/><br/><br/><br/>



						<h4>Clients that we Can Notify : </h4><br/>



					<table style="width: 100%;border-collapse:collapse;">



				          <tr>



				                <td style="text-align: left; border:1px solid #000; font-size:12px;">#</td>



					            <td style="text-align: left; border:1px solid #000; word-wrap: break-word; font-size:12px;">Client Name</td>



					            <td style="text-align: left; border:1px solid #000; font-size:12px;">Tax Regn./Trader No</td>



					            <td style="text-align: left; border:1px solid #000; font-size:12px;">Email</td>



					            <td style="text-align: left; border:1px solid #000; font-size:12px;">Secondary Email</td> 



					            <td style="text-align: left; border:1px solid #000; font-size:12px;">Last Email Sent</td> 



				          </tr>';



						$i=1;



                		if(count($with_emailed)){              



                  			foreach($with_emailed as $with){       



                  				if(in_array($with->taxnumber,$taxnumber_compared)) {  



                  					if($with->last_email_sent == "0000-00-00 00:00:00")



                  					{



                  						$dd = '-';



                  					}



                  					else{



                  						$dd = date('d F Y', strtotime($with->last_email_sent));



                  					}



        							$output.='<tr style="text-align:left">



					                <td style="text-align: left; border:1px solid #000; font-size:12px;">'.$i.'</td>



					                <td style="width: 180px;  word-wrap: break-word;text-align: left; border:1px solid #000;font-size:12px;">'.$with->name.'</td>



					                <td style="text-align: left; border:1px solid #000; font-size:12px;">'.$with->taxnumber.'</td>



					                <td style="text-align: left; border:1px solid #000; font-size:12px;">'.$with->pemail.'</td>



					                <td style="text-align: left; border:1px solid #000; font-size:12px;">'.$with->semail.'</td>



					                <td style="width: 75px; text-align: left; border:1px solid #000; font-size:12px;">'.$dd.'</td>



        							</tr>';



           							$i++;      



                				}                        



                			}              



              			}



			            if($i == 1)



			            {



			                $output.='<tr><td colspan="6" align="left">Empty</td></tr>';



			            }



						$output.='</table>';



			    $pdf = PDF::loadHTML($output);



			    $pdf->save('papers/Vat_Clients_With_Email.pdf');



	   		 	echo 'Vat_Clients_With_Email.pdf';



	}







	public function task_client_search()



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



	public function task_clientsearchselect(){



		$id = Input::get('value');



		$client_details = DB::table('cm_clients')->where('client_id', $id)->first();





		echo json_encode(["taxreg" => $client_details->tax_reg1, "primaryemail" => $client_details->email, "firstname" => $client_details->firstname]);



	}











	public function vat_client_search()



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



	public function vat_clientsearchselect(){



		$id = Input::get('value');



		$client_details = DB::table('cm_clients')->where('client_id', $id)->first();



		if($client_details->company != "")

		{

			$company = $client_details->company;

		}

		else{

			$company = $client_details->firstname.' - '.$client_details->surname;

		}

		echo json_encode(["taxreg" => $client_details->tax_reg1, "primaryemail" => $client_details->email, "secondaryemail" => $client_details->email2, "firstname" => $company]);

	}



	public function getclientcompanyname()



	{



		$clientid= Input::get('clientid');



		$client_details = DB::table('cm_clients')->where('client_id',$clientid)->first();

		if($client_details->company != "")

		{

			$company = $client_details->company;

		}

		else{

			$company = $client_details->firstname.' '.$client_details->surname;

		}



		echo $company;



	}



	public function getclientemail()

	{



		$clientid= Input::get('clientid');



		$client_details = DB::table('cm_clients')->where('client_id',$clientid)->first();



		echo $client_details->email;



	}



	public function getclientemail_secondary()



	{



		$clientid= Input::get('clientid');



		$client_details = DB::table('cm_clients')->where('client_id',$clientid)->first();



		echo $client_details->email2;



	}

	public function taskmanager_upload_images()

	{

		$task_id = $_GET['task_id'];

		$type = $_GET['type'];

		$data_img = DB::table('task')->where('task_id',$task_id)->first();

		$upload_dir = 'uploads/task_image';

		if (!file_exists($upload_dir)) {

			mkdir($upload_dir);

		}

		$upload_dir = $upload_dir.'/'.base64_encode($data_img->users);

		if (!file_exists($upload_dir)) {

			mkdir($upload_dir);

		}

		$upload_dir = $upload_dir.'/'.base64_encode($data_img->task_id);

		if (!file_exists($upload_dir)) {

			mkdir($upload_dir);

		}



		if (!empty($_FILES)) {

			 $tmpFile = $_FILES['file']['tmp_name'];

			 $filename = $upload_dir.'/'.$_FILES['file']['name'];

			 $fname = $_FILES['file']['name'];



		 	move_uploaded_file($tmpFile,$filename);

		 	$data['task_id'] = $data_img->task_id;

			$data['attachment'] = $fname;

			$data['url'] = $upload_dir;

			if($type == 2)

			{

				$data['network_attach'] = 1;

				$dataval['task_started'] = 1;
				$dataval['task_notify'] = 1;
				DB::table('task')->where('task_id',$task_id)->update($dataval);

			}

			else{

				$data['network_attach'] = 0;

			}

			$id = DB::table('task_attached')->insertGetId($data);

		}
		
		echo json_encode(array('id' => $id,'filename' => $fname,'task_id' => $data_img->task_id));

	}

	public function remove_dropzone_attachment()

	{

		$attachment_id = $_POST['attachment_id'];

		$task_id = $_POST['task_id'];

		$check_network = DB::table('task_attached')->where('id',$attachment_id)->first();

		DB::table('task_attached')->where('id',$attachment_id)->delete();





		if($check_network->network_attach == 1)

		{

			$count = DB::table('task_attached')->where('task_id',$check_network->task_id)->where('network_attach',1)->count();

			if($count > 0)

			{

				

			}

			else{

				$dataval['task_started'] = 0;

				DB::table('task')->where('task_id',$check_network->task_id)->update($dataval);

			}

		}

	}



	public function task_complete_update(){

		

		$id = Input::get('id');

		$status = Input::get('status');
		$dontvale = 0;

		$dataval['task_complete_period'] = $status;
		$dataval['task_complete_period_type'] = $dontvale;
		if($status == 1)
		{
			$dataval['task_started'] = 0;
		}

		DB::table('task')->where('task_id', $id)->update($dataval);

	}

	public function task_complete_update_new(){
		$id = Input::get('id');
		$status = 1;
		$dontvale = Input::get('dontvale');		

		$dataval['task_complete_period'] = $status;
		$dataval['task_complete_period_type'] = $dontvale;
		

		if($status == 1)
		{
			$dataval['task_started'] = 0;
		}
		DB::table('task')->where('task_id', $id)->update($dataval);
	}

	public function donot_complete_task_details(){
		$id = Input::get('taskid');
		
		$task_details = DB::table('task')->where('task_id', $id)->first();

		echo $task_details->task_name;
		
	}

	public function email_report_generator()
	{
		$task_id = $_GET['task_id'];
		$task_details = DB::table('task')->where('task_id',$task_id)->first();
		if($task_details->task_week == 0) { $label = 'Month'; } else { $label = 'Week'; }
		$task_created_id = $task_details->task_created_id;
		$get_email_dates = DB::table('task_email_sent')->where('task_created_id',$task_created_id)->get();

		$output = '<h4>Report for Email sent Date & Time</h4>
					<table style="width: 100%;border-collapse:collapse">
				          <tr>
				              <td style="text-align: left;border:1px solid #000;">S.No</td>
				              <td style="text-align: left;border:1px solid #000;">'.$label.'</td>
				              <td style="text-align: left;border:1px solid #000;">Task Name</td>
				              <td style="text-align: left;border:1px solid #000;">Date</td>
				              <td style="text-align: left;border:1px solid #000;">Time</td>
				          </tr>';
						if(count($get_email_dates))
						{
							$i = 1;
							foreach($get_email_dates as $emails)
							{
								$fetch_task = DB::table('task')->where('task_id',$emails->task_id)->first();
								if($fetch_task->task_week == 0) 
								{
									$month_details = DB::table('month')->where('month_id',$fetch_task->task_month)->first();
									$labelval = $month_details->month;
								}
								else{
									$week_details = DB::table('week')->where('week_id',$fetch_task->task_week)->first();
									$labelval = $week_details->week;
								}
								if($emails->options != '0'){
									$optionsval = '('.strtoupper($emails->options).')';
								}
								else{
									$optionsval = '';
								}
								$output.='<tr>
								<td style="text-align: left;border:1px solid #000;">'.$i.'</td>
								<td style="text-align: left;border:1px solid #000;">'.$labelval.'</td>
								<td style="text-align: left;border:1px solid #000;">'.$fetch_task->task_name.'</td>
								<td style="text-align: left;border:1px solid #000;">'.date('d F Y', strtotime($emails->email_sent)).' '.$optionsval.'</td>
								<td style="text-align: left;border:1px solid #000;">'.date('H:i', strtotime($emails->email_sent)).'</td>
								</tr>';
								$i++;
							}
						}
						else{
							$output.='<tr><td colspan="3" style="text-align: center;border:1px solid #000;">No Data Found</td></tr>';
						}
		$output.='</table>';
		$pdf = PDF::loadHTML($output);
	    $pdf->save('papers/Report For Task '.$task_details->task_name.'.pdf');
	    echo 'Report For Task '.$task_details->task_name.'.pdf';
	}

	public function task_default_users_update()
	{
		$id = Input::get('id');
		$users = Input::get('users');
		DB::table('task')->where('task_id',$id)->update(['default_staff' => $users]);
	}
	public function save_disclose_liability()
	{
		$task_id = Input::get('task_id');
		$status = Input::get('status');

		$data['disclose_liability'] = $status;
		DB::table('task')->where('task_id',$task_id)->update($data);
	}
	public function get_clientname_from_pms()
	{
		$taskid = Input::get('taskid');
		$get_task = DB::table('task')->where('task_id',$taskid)->first();
		if(count($get_task))
		{
			if($get_task->client_id == "")
			{
				$clientname = '';
				$client_id = '';
			}
			else{
				$clientdetails = DB::table('cm_clients')->where('client_id',$get_task->client_id)->first();
				if(count($clientdetails))
				{
					if($clientdetails->company != "")
					{
						$company = $clientdetails->company;
					}
					else{
						$company = $clientdetails->firstname.' '.$clientdetails->surname;
					}
					$clientname = $company.'-'.$clientdetails->client_id;
					$client_id = $get_task->client_id;
				}
				else{
					$clientname = '';
					$client_id = '';
				}
			}
		}
		else{
			$clientname = '';
			$client_id = '';
		}
		echo json_encode(array("company" => $clientname,"client_id" => $client_id,"staff" => $get_task->default_staff));
	}
	public function add_scheme()
	{
		$data['scheme_name'] = Input::get('scheme_name');
		$data['status'] = Input::get('status');
		$newschemeid = DB::table('schemes')->insertGetid($data);
	      $schemes = DB::table('schemes')->get();
	      $output = '';
	      if(count($schemes))
	      {
	      	$i = 1;
	        foreach($schemes as $scheme)
	        {
	          
	          $output.='<tr>
	            <td>'.$i.'</td>
	            <td>'.$scheme->scheme_name.'</td>
	            <td>';
	                if($scheme->status == "1")
                    {
                      $output.='<a href="javascript:" data-src="'.URL::to('user/change_scheme_status?status=0&id='.$scheme->id.'').'" class="fa fa-times-circle change_scheme_status" data-element="1" title="Closed" style="color:red"></a>';
                    }
                    else{
                      $output.='<a href="javascript:" data-src="'.URL::to('user/change_scheme_status?status=1&id='.$scheme->id.'').'" class="fa fa-check-circle-o change_scheme_status" data-element="0" title="Open" style="color:green"></a>';
                    }
	            $output.='</td>
	          </tr>';
	          $i++;
	        }
	      }
	      else{
	        $output.='<tr>
	          <td colspan="3">No Schemes Found</td>
	        </tr>';
	      }
	      echo json_encode(array("output" => $output,"option" => '<option value="'.$newschemeid.'">'.Input::get('scheme_name').'</option>'));
	}
	public function set_scheme_for_task()
	{
		$data['scheme_id'] = Input::get('scheme');
		$task_id = Input::get('task_id');
		DB::table('task')->where('task_id',$task_id)->update($data);
	}
	public function check_previous_week()
	{
		$task_id = Input::get('task_id');
		$status = Input::get('status');
		$datastatus['same_as_last'] = $status;
		DB::table('task')->where('task_id',$task_id)->update($datastatus);
		$week = Input::get('week');
		$get_prev_week= DB::table('week')->where('week_id','<',$week)->orderBy('week_id','desc')->first();
		$prev_week_id = $get_prev_week->week_id;
		$get_curr_tasks = DB::table('task')->where('task_id',$task_id)->first();
		$task_enumber = $get_curr_tasks->task_enumber;
		$get_prev_tasks = DB::table('task')->where('task_enumber',$task_enumber)->where('task_week',$prev_week_id)->get();
		if($status == "1")
		{
			if(count($get_prev_tasks))
			{
				foreach($get_prev_tasks as $tasks)
				{
					$attachments = DB::table('task_attached')->where('task_id',$tasks->task_id)->where('network_attach',1)->get();
					if(count($attachments))
					{
						foreach($attachments as $attachment)
						{
							$data['task_id'] = $task_id;
							$data['attachment'] = $attachment->attachment;
							$data['url'] = $attachment->url;
							$data['network_attach'] = $attachment->network_attach;
							$data['copied'] = 1;
							$id = DB::table('task_attached')->insertGetId($data);
						}
					}
				}
			}
		}
		else{
			DB::table('task_attached')->where('task_id',$task_id)->where('copied',1)->delete();
		}
		if($get_curr_tasks->task_status == 1) { $disabled='disabled'; } elseif($get_curr_tasks->task_complete_period == 1){ $disabled='disabled'; } else{ $disabled=''; }
		$output = '';
		$attachments = DB::table('task_attached')->where('task_id',$task_id)->where('network_attach',1)->get();
		if(count($attachments))
		{
		  $output.='<i class="fa fa-minus-square fadeleteall_attachments '.$disabled.'" data-element="'.$task_id.'" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>';
		  $output.='<h5 style="color:#000; font-weight:600">Files Received :</h5>';
		  $output.='<div class="scroll_attachment_div">';
		      foreach($attachments as $attachment)
		      {
		          $output.='<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
		      }
		  $output.='</div>';
		}
		echo $output;
	}
	public function check_previous_month()
	{
		$task_id = Input::get('task_id');
		$status = Input::get('status');
		$datastatus['same_as_last'] = $status;
		DB::table('task')->where('task_id',$task_id)->update($datastatus);
		$month = Input::get('month');
		$get_prev_month= DB::table('month')->where('month_id','<',$month)->orderBy('month_id','desc')->first();
		$prev_month_id = $get_prev_month->month_id;
		$get_curr_tasks = DB::table('task')->where('task_id',$task_id)->first();
		$task_enumber = $get_curr_tasks->task_enumber;
		$get_prev_tasks = DB::table('task')->where('task_enumber',$task_enumber)->where('task_month',$prev_month_id)->get();
		if($status == "1")
		{
			if(count($get_prev_tasks))
			{
				foreach($get_prev_tasks as $tasks)
				{
					$attachments = DB::table('task_attached')->where('task_id',$tasks->task_id)->where('network_attach',1)->get();
					if(count($attachments))
					{
						foreach($attachments as $attachment)
						{
							$data['task_id'] = $task_id;
							$data['attachment'] = $attachment->attachment;
							$data['url'] = $attachment->url;
							$data['network_attach'] = $attachment->network_attach;
							$data['copied'] = 1;
							$id = DB::table('task_attached')->insertGetId($data);
						}
					}
				}
			}
		}
		else{
			DB::table('task_attached')->where('task_id',$task_id)->where('copied',1)->delete();
		}
		if($get_curr_tasks->task_status == 1) { $disabled='disabled'; } elseif($get_curr_tasks->task_complete_period == 1){ $disabled='disabled'; } else{ $disabled=''; }
		$output = '';
		$attachments = DB::table('task_attached')->where('task_id',$task_id)->where('network_attach',1)->get();
		if(count($attachments))
		{
		  $output.='<i class="fa fa-minus-square fadeleteall_attachments '.$disabled.'" data-element="'.$task_id.'" style="margin-top:10px" aria-hidden="true" title="Delete All Attachments"></i>';
		  $output.='<h5 style="color:#000; font-weight:600">Files Received :</h5>';
		  $output.='<div class="scroll_attachment_div">';
		      foreach($attachments as $attachment)
		      {
		          $output.='<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disabled.'"><i class="fa fa-trash trash_image '.$disabled.'" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
		      }
		  $output.='</div>';
		}
		echo $output;
	}
	public function change_scheme_status()
	{
		$data['status'] = Input::get('status');
		$scheme_id = Input::get('id');
		DB::table('schemes')->where('id',$scheme_id)->update($data);
		if($data['status'] == "1")
		{
			echo URL::to('user/change_scheme_status?status=0&id='.$scheme_id.'');
		}
		else{
			echo URL::to('user/change_scheme_status?status=1&id='.$scheme_id.'');
		}
	}
}



