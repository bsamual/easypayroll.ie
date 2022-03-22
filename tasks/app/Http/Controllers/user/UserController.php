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
use ZipArchive;
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
		$admin_details = DB::table('admin')->where('id',1)->first();
		$user_details = DB::table('user_login')->where('userid',1)->first();
		return view('user/dashboad', array('title' => 'Bubble - Dashboard', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks,'admin_details' => $admin_details,'user_details' => $user_details));
	}
	public function updatestatus_timetrack(){
		$jobs = DB::table('task_job')->where('quick_job',0)->where('updatestatus',0)->get();
		if(count($jobs))
		{
			foreach($jobs as $job)
			{
				$dataval['comments'] = $job->comments;
				$datastatus['updatestatus'] = 1;
				DB::table('task_job')->where('active_id',$job->id)->where('comments','')->update($dataval);
				DB::table('task_job')->where('id',$job->id)->update($datastatus);
			}
		}
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
		
		return view('user/dashboad_time_track', array('title' => 'Welcome to Bubble', 'joblist' => $time_job, 'userlist' => $user, 'taskslist' => $tasks));
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
		return redirect('https://bubbleaccounting.ie');
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
			//return redirect('user/select_week/'.base64_encode($data_img->task_week).'?divid=taskidtr_'.$id);
		}
		else{
			//return redirect('user/select_month/'.base64_encode($data_img->task_month).'?divid=taskidtr_'.$id);
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
		$output = '<table class="table_bg own_table_white" style="width:100%">';
		if(count($result_task))
		{
			$output.= '<tr><td>Task Name</td><td>Request</td><td>Primary Email</td><td>Secondary Email</td><td>Emails Sent</td></tr>';
			foreach($result_task as $task)
			{
				$last_email_sent = DB::table('task_email_sent')->where('task_id',$task->task_id)->where('options','n')->orderBy('id','desc')->first();
				if(count($last_email_sent))
				{
					$email_sent = date('d F Y H:i:s', strtotime($last_email_sent->email_sent));
				}
				else{
					$email_sent = '';
				}
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
				<td style="text-align:center;color:#000 !important;">'.$email_sent.'</td>
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
		$output = '<table class="table_bg own_table_white" style="width:100%">';
		if(count($result_task))
		{
			$output.= '<tr><td>Task Name</td><td>Notify</td><td>Primary Email</td><td>Secondary Email</td><td>Emails Sent</td></tr>';
			foreach($result_task as $task)
			{
				$last_email_sent = DB::table('task_email_sent')->where('task_id',$task->task_id)->where('options','n')->orderBy('id','desc')->first();
				if(count($last_email_sent))
				{
					$email_sent = date('d F Y H:i:s', strtotime($last_email_sent->email_sent));
				}
				else{
					$email_sent = '';
				}

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
				<td style="text-align:center;color:#000 !important;">'.$email_sent.'</td>
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
					$admin_details = DB::table('admin')->first();
	    			$data['signature'] = $admin_details->payroll_signature;

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
				$result = DB::table('task')->where('task_id',$task_id)->first();
				if(count($result))
				{
					if($result->last_email_sent != '0000-00-00 00:00:00')
	                {
	                  $get_dates = DB::table('task_email_sent')->where('task_id',$result->task_id)->where('options','!=','n')->get();
	                  $last_date = '';
	                  if(count($get_dates))
	                  {
	                    foreach($get_dates as $dateval)
	                    {
	                      $date = date('d F Y', strtotime($dateval->email_sent));
	                      $time = date('H : i', strtotime($dateval->email_sent));
	                      if($dateval->options != '0')
	                      {
	                        if($dateval->options == 'a') { $text = 'Fix an Error Created In House'; }
	                        elseif($dateval->options == 'b') { $text = 'Fix an Error by Client or Implement a client Requested Change'; }
	                        elseif($dateval->options == 'c') { $text = 'Combined In House and Client Prompted adjustments'; }
	                        else{ $text= ''; }
	                        $itag = '<span class="" title="'.$text.'" style="font-weight:800;"> ('.strtoupper($dateval->options).') </span>';
	                      }
	                      else{
	                        $itag = '';
	                      }
	                      if($last_date == "")
	                      {
	                        $last_date = '<p>'.$date.' @ '.$time.' '.$itag.'</p>';
	                      }
	                      else{
	                        $last_date = $last_date.'<p>'.$date.' @ '.$time.' '.$itag.'</p>';
	                      }
	                    }
	                  }
	                  else{
	                    $date = date('d F Y', strtotime($result->last_email_sent));
	                    $time = date('H : i', strtotime($result->last_email_sent));
	                    $last_date = '<p>'.$date.' @ '.$time.'</p>';
	                  }
	                }
	                else{
	                  $last_date = '';
	                }
				}
				else{
					$last_date = '';
				}
				echo $last_date.'||'.$task_id;
			}
			else{
				echo "1";
			}
		}
		else{
			echo "2";
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
				$admin_details = DB::table('admin')->first();
	    		$data['signature'] = $admin_details->payroll_signature;

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
	   		 	$subject = 'Easypayroll.ie: Task Report For Year '.$year_id->year_name.' Week'.$year->week.'';	
	   		 	echo $subject;
	}
	public function email_notify_pdf(){
		$week = Input::get('week');
		$month = Input::get('month');
		$time = Input::get('timeval');
		$email_id = explode("||",Input::get('email'));
		$email = $email_id[0];
		$id = $email_id[1];
		$message = Input::get('message');
		$admin_details = Db::table('admin')->where('id',1)->first();
		$admin_cc = $admin_details->payroll_cc_email;
		$from = $admin_details->email;
		$to = trim($email);
		$cc = $admin_cc;
		$data['sentmails'] = $to.' , '.$cc;
		$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
		$data['message'] = $message;
		$admin_details = DB::table('admin')->first();
	    $data['signature'] = $admin_details->payroll_signature;
		$contentmessage = view('user/email_notify', $data);
		
		$subject = 'Easypayroll.ie: Notification';
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
			$get_task_details = DB::table('task')->where('task_email',$to)->where('task_month',$month)->where('task_id',$id)->where('client_id','!=','')->first();
		}
		else{
			$get_task_details = DB::table('task')->where('task_email',$to)->where('task_week',$week)->where('task_id',$id)->where('client_id','!=','')->first();
		}
		if(count($get_task_details))
		{
			$curr = date('Y-m-d H:i:s');
			$dataval['task_created_id'] = $get_task_details->task_id;
			$dataval['task_week'] = $get_task_details->task_week;
			$dataval['task_month'] = $get_task_details->task_month;
			$dataval['task_id'] = $get_task_details->task_id;
			$dataval['email_sent'] = $curr;
			$dataval['options'] = 'n';
			DB::table("task_email_sent")->insert($dataval);

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
				$datamessage['date_sent'] = $curr;
				$datamessage['date_saved'] = $curr;
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
		$admin_cc = $admin_details->payroll_cc_email;
		$from = $admin_details->email;
		$to = trim($email);
		$cc = $admin_cc;
		$data['sentmails'] = $to.' , '.$cc;
		$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');
		$data['task_name'] = $task_details->task_name;
		$data['year'] = $year->year_name;
		$data['week_month'] = $week_month;
		$data['client_name'] = $client_details->firstname;
		$admin_details = DB::table('admin')->first();
	    $data['signature'] = $admin_details->payroll_signature;

		$contentmessage = view('user/email_notify_tasks', $data);
		
		$subject = 'Easypayroll.ie: Payroll Task Notification';
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
	   		 	$subject = 'Easypayroll.ie: Notify Report For Year '.$year_id->year_name.' Month'.$year->month.'';	
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
				$subject = 'Easypayroll.ie: Task Report For Year '.$year_id->year_name.' Month'.$year->month.'';	
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
	      	$subject = 'Easypayroll.ie: '.$result->task_name.' Payroll for WEEK '.$week_details->week.'';
	      }
	      else{
	      	$month_details = DB::table('month')->where('month_id',$result->task_month)->first();
	      	$subject = 'Easypayroll.ie: '.$result->task_name.' Payroll for MONTH '.$month_details->month.'';
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
	      	$subject = 'Easypayroll.ie: '.$result->task_name.' Payroll for WEEK '.$week_details->week.'';
	      }
	      else{
	      	$month_details = DB::table('month')->where('month_id',$result->task_month)->first();
	      	$subject = 'Easypayroll.ie: '.$result->task_name.' Payroll for MONTH '.$month_details->month.'';
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
	public function vat_review()
	{
		$clients = DB::table('vat_clients')->get();
		return view('user/vat_review', array('clientlist' => $clients));
	}
	public function load_all_vat_clients()
	{
		$clients = DB::table('vat_clients')->get();
		$output = '';
		$prev_no_sub_due = 0;
		$curr_no_sub_due = 0;
		$next_no_sub_due = 0;
		if(count($clients))
		{
			foreach($clients as $client)
			{
				$deactivated_client = '';
				if($client->cm_client_id != "")
				{
					$cm_clients = DB::table('cm_clients')->where('client_id',$client->cm_client_id)->first();
					if(count($cm_clients))
					{
						if($cm_clients->active == "2")
						{
							$deactivated_client= 'deactivated_tr';
						}
					}
				}
				if($client->status == 1) { $fontcolor = 'red'; }
                elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
                elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
                elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
                else{$fontcolor = '#fff';}

                $prev_month = date('m-Y', strtotime('first day of previous month'));
                $curr_month = date('m-Y');
                $next_month = date('m-Y', strtotime('first day of next month'));

                $prev_attachment_div = '';
				$prev_text_one = 'No Period';
				$prev_text_two = '';
				$prev_t1 = '';
				$prev_t2 = '';
				$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prev_month.'" style="float:right;display:none"></a>';
				$prev_text_three = '';
				$prevv_text_three = '';
				$prev_color_status = '';
				$prev_color_text = '';
				$prev_check_box_color = 'blacK_td';
				$prev_checked = '';
				

				$curr_attachment_div = '';
				$curr_text_one = 'No Period';
				$curr_text_two = '';
				$curr_t1 = '';
				$curr_t2 = '';
				$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$curr_month.'" style="float:right;display:none"></a>';
				$curr_text_three = '';
				$currr_text_three = '';
				$curr_color_status = '';
				$curr_color_text = '';
				$curr_check_box_color = 'blacK_td';
				$curr_checked = '';

				$next_attachment_div = '';
				$next_text_one = 'No Period';
				$next_text_two = '';
				$next_t1 = '';
				$next_t2 = '';
				$next_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$next_month.'" style="float:right;display:none"></a>';
				$next_text_three = '';
				$nextt_text_three = '';
				$next_color_status = '';
				$next_color_text = '';
				$next_check_box_color = 'blacK_td';
				$next_checked = '';

				$latest_import_id = '';

				$get_latest_import_file_id = DB::table('vat_reviews_import_attachment')->where('status',1)->orderBy('id','desc')->first();
            	if(count($get_latest_import_file_id))
            	{
            		$latest_import_id = $get_latest_import_file_id->import_id;
            	}

                $check_reviews_prev = DB::table('vat_reviews')->where('client_id',$client->client_id)->where('month_year',$prev_month)->get();
                if(count($check_reviews_prev))
                {
                	$i= 0; $j=0;
                	foreach($check_reviews_prev as $prev)
                	{
                		if($prev->type == 1){ 
                			$ext = explode('.',$prev->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('assets/images/image.png').'" style="width:100px">';
                			}
                			$prev_attachment_div.= '<p><a href="'.URL::to($prev->url.'/'.$prev->filename).'" class="file_attachments" title="'.$prev->filename.'" download>'.$img.'</a> 

                			<a href="'.URL::to('user/delete_vat_files?file_id='.$prev->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$prev_month.'"></a></p>'; 

                			$prev_t1 = $prev->t1;
                			$prev_t2 = $prev->t2;
                		}

                		if($prev->type == 2){ $prev_text_one = $prev->from_period.' to '.$prev->to_period; }

                		if($prev->type == 3){ 
                			$prev_text_two = $prev->textval; 
                			$prev_color_status = 'green_import'; 
                			$prev_color_text = 'Submitted'; 
                			$prev_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prev_month.'" style="float:right"></a>';
                		}

                		if($prev->type == 4){ 
                			$get_attachment_download = DB::table('vat_reviews_import_attachment')->where('import_id',$prev->textval)->first();
                			if(count($get_attachment_download))
                			{
                				$prevv_text_three = $prev->textval;
                				$prev_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="float:right" download>'.$prev->textval.'</a>'; 
                			}
                			
                			$j = $j + 1;
                		}
                		if($prev->type == 6){
                			$prev_checked = 'checked';
                		} 
                	}
                	
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $prevv_text_three)
                		{
                			$prev_color_status = 'red_import'; 
                			$prev_color_text = 'Submission O/S';
                			$prev_check_box_color = 'os_td';
                			$prev_no_sub_due = $prev_no_sub_due + 1;
                		}
                		else{
                			$prev_color_status = 'blue_import'; 
                			$prev_color_text = 'Potentially Submitted';
                			$prev_check_box_color = 'ps_td';
                		}
                	}
                }

                $check_reviews_curr = DB::table('vat_reviews')->where('client_id',$client->client_id)->where('month_year',$curr_month)->get();
                if(count($check_reviews_curr))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_curr as $curr)
                	{
                		if($curr->type == 1){ 
                			$ext = explode('.',$curr->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('assets/images/image.png').'" style="width:100px">';
                			}
                			$curr_attachment_div.= '<p><a href="'.URL::to($curr->url.'/'.$curr->filename).'" class="file_attachments" title="'.$curr->filename.'" download>'.$img.'</a> 

                			<a href="'.URL::to('user/delete_vat_files?file_id='.$curr->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$curr_month.'"></a></p>'; 
                			$curr_t1 = $curr->t1;
                			$curr_t2 = $curr->t2;
                		}

                		if($curr->type == 2){ $curr_text_one = $curr->from_period.' to '.$curr->to_period; }

                		if($curr->type == 3){ 
                			$curr_text_two = $curr->textval; 
                			$curr_color_status = 'green_import'; 
                			$curr_color_text = 'Submitted'; 
                			$curr_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$curr_month.'" style="float:right"></a>';
                		}

                		if($curr->type == 4){ 
                			$get_attachment_download = DB::table('vat_reviews_import_attachment')->where('import_id',$curr->textval)->first();
                			if(count($get_attachment_download))
                			{
                				$currr_text_three = $curr->textval;
                				$curr_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="float:right" download>'.$curr->textval.'</a>'; 
                			}
                			
                			$j = $j + 1;
                		}
                		if($curr->type == 6)
                		{
                			$curr_checked = 'checked';
                			$k = $k + 1;
                		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $currr_text_three)
                		{
                			$curr_color_status = 'orange_import'; 
                			$curr_color_text = 'Submission Due';
                			$curr_check_box_color = 'due_td';
                			$curr_no_sub_due = $curr_no_sub_due + 1;
                		}
                		else{
                			$curr_color_status = 'blue_import'; 
                			$curr_color_text = 'Potentially Submitted';
                			$curr_check_box_color = 'ps_td';
                		}
                	}
                }
                $check_reviews_next = DB::table('vat_reviews')->where('client_id',$client->client_id)->where('month_year',$next_month)->get();
                if(count($check_reviews_next))
                {
                	$i= 0; $j=0;
                	foreach($check_reviews_next as $next)
                	{
                		if($next->type == 1){ 
                			$ext = explode('.',$next->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('assets/images/image.png').'" style="width:100px">';
                			}
                			$next_attachment_div.= '<p><a href="'.URL::to($next->url.'/'.$next->filename).'" class="file_attachments" title="'.$next->filename.'" download>'.$img.'</a> 

                			<a href="'.URL::to('user/delete_vat_files?file_id='.$next->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$next_month.'"></a></p>'; 

                			$next_t1 = $next->t1;
                			$next_t2 = $next->t2;
                		}

                		if($next->type == 2){ $next_text_one = $next->from_period.' to '.$next->to_period; }

                		if($next->type == 3){ 
                			$next_text_two = $next->textval; 
                			$next_color_status = 'green_import'; 
                			$next_color_text = 'Submitted'; 
                			$next_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$next_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$next_month.'" style="float:right"></a>';
                		}
                		if($next->type == 4){ 
                			$get_attachment_download = DB::table('vat_reviews_import_attachment')->where('import_id',$next->textval)->first();
                			if(count($get_attachment_download))
                			{
                				$nextt_text_three = $next->textval;
                				$next_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="float:right" download>'.$next->textval.'</a>'; 
                			}
                			
                			$j = $j + 1;
                		}
                		if($next->type == 6)
                		{
                			$next_checked = 'checked';
                		}
                	}
                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $nextt_text_three)
                		{
                			$next_color_status = 'white_import'; 
                			$next_color_text = 'Not Due';
                			$next_check_box_color = 'not_due_td';
                		}
                		else{
                			$next_color_status = 'blue_import'; 
                			$next_color_text = 'Potentially Submitted';
                			$next_check_box_color = 'ps_td';
                		}
                	}
                }

				$output.='<tr class="tasks_tr tasks_tr_'.$client->client_id.' '.$deactivated_client.'">
					<td style="color:'.$fontcolor.'" class="sno_sort_val"><a href="javascript:" class="vat_client_class" data-element="'.$client->client_id.'">'.$client->cm_client_id.'</a></td>
					<td style="color:'.$fontcolor.'" class="client_sort_val"><a href="javascript:" class="vat_client_class" data-element="'.$client->client_id.'">'.$client->name.'</a></td>
					<td style="color:'.$fontcolor.'" class="tax_sort_val">'.$client->taxnumber.'</td>
					<td id="add_files_vat_client_'.$prev_month.'">
						<p style="text-align:center"><label class="import_icon '.$prev_color_status.'">'.$prev_color_text.'</label></p>
						<p>Import FIle ID: '.$prev_text_three.'</p>
						<p><input type="checkbox" class="check_records_received" id="check_records_received" data-month="'.$prev_month.'" data-client="'.$client->client_id.'" '.$prev_checked.'><label for="" class="records_receive_label '.$prev_check_box_color.' '.$prev_checked.'">Records Received</label></p>
						<p>Period: &nbsp;&nbsp;<a href="javascript:" class="period_change" style="float:right;font-weight:800;margin-left: 10px;" data-month="'.$prev_month.'" data-client="'.$client->client_id.'">...</a> <spam class="period_import" style="float:right">'.$prev_text_one.'</spam></p>
						<p>Submitted: '.$prev_remove_two.'<input type="text" class="submitted_import" data-client="'.$client->client_id.'" data-element="'.$prev_month.'" style="float:right" value="'.$prev_text_two.'"></p>
						<p>T1: <spam class="t1_spam">'.$prev_t1.'</spam></p>
						<p>T2: <spam class="t2_spam">'.$prev_t2.'</spam></p>
						<div>Submission: <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-element="'.$prev_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a> <div class="attachment_div">'.$prev_attachment_div.'</div></div>
					</td>
					<td id="add_files_vat_client_'.$curr_month.'">
						<p style="text-align:center"><label class="import_icon '.$curr_color_status.'">'.$curr_color_text.'</label></p>
						<p>Import FIle ID: '.$curr_text_three.'</p>
						<p><input type="checkbox" class="check_records_received" id="check_records_received" data-month="'.$curr_month.'" data-client="'.$client->client_id.'" '.$curr_checked.'><label for="" class="records_receive_label '.$curr_check_box_color.' '.$curr_checked.'">Records Received</label></p>
						<p>Period: &nbsp;&nbsp;<a href="javascript:" class="period_change" style="float:right;font-weight:800;margin-left: 10px;" data-month="'.$curr_month.'" data-client="'.$client->client_id.'">...</a> <spam class="period_import" style="float:right">'.$curr_text_one.'</spam></p>
						<p>Submitted: '.$curr_remove_two.'<input type="text" class="submitted_import" data-client="'.$client->client_id.'" data-element="'.$curr_month.'" style="float:right" value="'.$curr_text_two.'"></p>
						<p>T1: <spam class="t1_spam">'.$curr_t1.'</spam></p>
						<p>T2: <spam class="t2_spam">'.$curr_t2.'</spam></p>
						<div>Submission: <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-element="'.$curr_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a><div class="attachment_div">'.$curr_attachment_div.'</div></div>
					</td>
					<td id="add_files_vat_client_'.$next_month.'">
						<p style="text-align:center"><label class="import_icon '.$next_color_status.'">'.$next_color_text.'</label></p>
						<p>Import FIle ID: '.$next_text_three.'</p>
						<p><input type="checkbox" class="check_records_received" id="check_records_received" data-month="'.$next_month.'" data-client="'.$client->client_id.'" '.$next_checked.'><label for="" class="records_receive_label '.$next_check_box_color.' '.$next_checked.'">Records Received</label></p>
						<p>Period: &nbsp;&nbsp;<a href="javascript:" class="period_change" style="float:right;font-weight:800;margin-left: 10px;" data-month="'.$next_month.'" data-client="'.$client->client_id.'">...</a> <spam class="period_import" style="float:right">'.$next_text_one.'</spam></p>
						<p>Submitted: '.$next_remove_two.'<input type="text" class="submitted_import" data-client="'.$client->client_id.'" data-element="'.$next_month.'" style="float:right" value="'.$next_text_two.'"></p>
						<p>T1: <spam class="t1_spam">'.$next_t1.'</spam></p>
						<p>T2: <spam class="t2_spam">'.$next_t2.'</spam></p>
						<div>Submission: <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-element="'.$next_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a> <div class="attachment_div">'.$next_attachment_div.'</div></div>
					</td>
				</tr>';
			}
		}
		$prev_month = date('M-Y', strtotime('first day of previous month'));
		$curr_month = date('M-Y');
		$next_month = date('M-Y', strtotime('first day of next month'));

		echo json_encode(array("output" => $output,"prev_no_sub_due" => $prev_no_sub_due,"curr_no_sub_due" => $curr_no_sub_due,"next_no_sub_due" => $next_no_sub_due, "prev_month" => $prev_month, "curr_month" => $curr_month, "next_month" => $next_month));
	}
	public function show_prev_month()
	{
		$get_date = '01';
		$month_year = explode("-",Input::get('month_year'));
		$get_full_date = $month_year[1].'-'.$month_year[0].'-'.$get_date;

		$prevv_month = date('m-Y',strtotime($get_full_date.' -1 month'));
		$currr_month = date('m-Y',strtotime($get_full_date));
		$nextt_month = date('m-Y',strtotime($get_full_date.' +1 month'));

		$current_str = strtotime(date('Y-m-01'));

		$prev_str = strtotime($get_full_date.' -1 month');
		$curr_str = strtotime($get_full_date);
		$next_str = strtotime($get_full_date.' +1 month');

		$prev_month = '<a href="javascript:" class="fa fa-arrow-circle-left show_prev_month" title="Extend to Prev Month" data-element="'.$prevv_month.'"></a>&nbsp;&nbsp;<a href="javascript:" class="show_month_in_overlay" data-element="'.date('m-Y',strtotime($get_full_date.' -1 month')).'">'.date('M-Y',strtotime($get_full_date.' -1 month')).'</a> <label class="submission_due_no">No of Submission Due: <spam class="no_sub_due no_sub_due_'.$prevv_month.'">0</spam></label>';
		$curr_month = '<a href="javascript:" class="show_month_in_overlay" data-element="'.date('m-Y',strtotime($get_full_date)).'">'.date('M-Y',strtotime($get_full_date)).'</a> <label class="submission_due_no">No of Submission Due: <spam class="no_sub_due no_sub_due_'.$currr_month.'">0</spam></label>';
		$next_month = '<a href="javascript:" class="show_month_in_overlay" data-element="'.date('m-Y',strtotime($get_full_date.' +1 month')).'">'.date('M-Y',strtotime($get_full_date.' +1 month')).'</a>&nbsp;&nbsp;<a href="javascript:" class="fa fa-arrow-circle-right show_next_month" title="Extend to Next Month" data-element="'.$nextt_month.'"></a> <label class="submission_due_no">No of Submission Due: <spam class="no_sub_due no_sub_due_'.$nextt_month.'">0</spam></label>';

		$prev_cell = array();
		array_push($prev_cell, $prev_month);

		$curr_cell = array();
		array_push($curr_cell, $curr_month);

		$next_cell = array();
		array_push($next_cell, $next_month);

		$clients = DB::table('vat_clients')->get();
		$output = '';
		if(count($clients))
		{
			foreach($clients as $client)
			{
				$prev_attachment_div = '';
				$prev_text_one = 'No Period';
				$prev_text_two = '';
				$prev_t1 = '';
				$prev_t2 = '';
				$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'" style="float:right;display:none"></a>';
				$prev_text_three = '';
				$prevv_text_three = '';
				$prev_color_status = '';
				$prev_color_text = '';
				$prev_check_box_color = 'blacK_td';
				$prev_checked = '';

				$curr_attachment_div = '';
				$curr_text_one = 'No Period';
				$curr_text_two = '';
				$curr_t1 = '';
				$curr_t2 = '';
				$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right;display:none"></a>';
				$curr_text_three = '';
				$currr_text_three = '';
				$curr_color_status = '';
				$curr_color_text = '';
				$curr_check_box_color = 'blacK_td';
				$curr_checked = '';

				$next_attachment_div = '';
				$next_text_one = 'No Period';
				$next_text_two = '';
				$next_t1 = '';
				$next_t2 = '';
				$next_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'" style="float:right;display:none"></a>';
				$next_text_three = '';
				$nextt_text_three = '';
				$next_color_status = '';
				$next_color_text = '';
				$next_check_box_color = 'blacK_td';
				$next_checked = '';

				$get_latest_import_file_id = DB::table('vat_reviews_import_attachment')->where('status',1)->orderBy('id','desc')->first();
            	if(count($get_latest_import_file_id))
            	{
            		$latest_import_id = $get_latest_import_file_id->import_id;
            	}

            	$check_reviews_prev = DB::table('vat_reviews')->where('client_id',$client->client_id)->where('month_year',$prevv_month)->get();
                if(count($check_reviews_prev))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_prev as $prev)
                	{
                		if($prev->type == 1){ 
                			$ext = explode('.',$prev->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('assets/images/image.png').'" style="width:100px">';
                			}
                			$prev_attachment_div.= '<p><a href="'.URL::to($prev->url.'/'.$prev->filename).'" class="file_attachments" title="'.$prev->filename.'" download>'.$img.'</a> 

                			<a href="'.URL::to('user/delete_vat_files?file_id='.$prev->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'"></a></p>'; 

                			$prev_t1 = $prev->t1;
                			$prev_t2 = $prev->t2;
                		}
                		if($prev->type == 2){ $prev_text_one = $prev->from_period.' to '.$prev->to_period; }

                		if($prev->type == 3){ 
                			$prev_text_two = $prev->textval; 
                			$prev_color_status = 'green_import'; 
                			$prev_color_text = 'Submitted'; 
                			$prev_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'" style="float:right"></a>';
                		}

                		if($prev->type == 4){ 
                			$get_attachment_download = DB::table('vat_reviews_import_attachment')->where('import_id',$prev->textval)->first();
                			if(count($get_attachment_download))
                			{
                				$prevv_text_three = $prev->textval;
                				$prev_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="float:right" download>'.$prev->textval.'</a>'; 
                			}
                			
                			$j = $j + 1;
                		}
                		if($prev->type == 6)
                		{
                			$prev_checked = 'checked';
                			$k = $k + 1;
                		}
                	}

                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $prevv_text_three)
                		{
                			if($current_str > $prev_str)
                			{
                				$prev_color_status = 'red_import'; 
                				$prev_color_text = 'Submission O/S';
                				$prev_check_box_color = 'os_td';
                			}
                			elseif($current_str == $prev_str)
                			{
                				$prev_color_status = 'orange_import'; 
                				$prev_color_text = 'Submission Due';
                				$prev_check_box_color = 'due_td';
                			}
                			else if($current_str < $prev_str)
                			{
                				$prev_color_status = 'white_import'; 
                				$prev_color_text = '`';
                				$prev_check_box_color = 'not_due_td';
                			}
                		}
                		else{
                			$prev_color_status = 'blue_import'; 
                			$prev_color_text = 'Potentially Submitted';
                			$prev_check_box_color = 'ps_td';
                		}
                	}
                }

                $check_reviews_curr = DB::table('vat_reviews')->where('client_id',$client->client_id)->where('month_year',$currr_month)->get();
                if(count($check_reviews_curr))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_curr as $curr)
                	{
                		if($curr->type == 1){ 
                			$ext = explode('.',$curr->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('assets/images/image.png').'" style="width:100px">';
                			}
                			$curr_attachment_div.= '<p><a href="'.URL::to($curr->url.'/'.$curr->filename).'" class="file_attachments" title="'.$curr->filename.'" download>'.$img.'</a> 
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$curr->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$currr_month.'"></a></p>'; 
                			$curr_t1 = $curr->t1;
                			$curr_t2 = $curr->t2;
                		}
                		if($curr->type == 2){ $curr_text_one = $curr->from_period.' to '.$curr->to_period; }

                		if($curr->type == 3){ 
                			$curr_text_two = $curr->textval; 
                			$curr_color_status = 'green_import'; 
                			$curr_color_text = 'Submitted'; 
                			$curr_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right"></a>';
                		}

                		if($curr->type == 4){ 
                			$get_attachment_download = DB::table('vat_reviews_import_attachment')->where('import_id',$curr->textval)->first();
                			if(count($get_attachment_download))
                			{
                				$currr_text_three = $curr->textval;
                				$curr_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="float:right" download>'.$curr->textval.'</a>'; 
                			}
                			
                			$j = $j + 1;
                		}
                		if($curr->type == 6)
                		{
                			$curr_checked = 'checked';
                			$k = $k + 1;
                		}
                	}

                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $currr_text_three)
                		{
                			if($current_str > $curr_str)
                			{
                				$curr_color_status = 'red_import'; 
                				$curr_color_text = 'Submission O/S';
                				$curr_check_box_color = 'os_td';
                			}
                			else if($current_str == $curr_str)
                			{
                				$curr_color_status = 'orange_import'; 
                				$curr_color_text = 'Submission Due';
                				$curr_check_box_color = 'due_td';
                			}
                			else if($current_str < $curr_str)
                			{
                				$curr_color_status = 'white_import'; 
                				$curr_color_text = 'Not Due';
                				$curr_check_box_color = 'not_due_td';
                			}
                		}
                		else{
                			$curr_color_status = 'blue_import'; 
                			$curr_color_text = 'Potentially Submitted';
                			$curr_check_box_color = 'ps_td';
                		}
                	}
                }

                $check_reviews_next = DB::table('vat_reviews')->where('client_id',$client->client_id)->where('month_year',$nextt_month)->get();
                if(count($check_reviews_next))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_next as $next)
                	{
                		if($next->type == 1){ 
                			$ext = explode('.',$next->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('assets/images/image.png').'" style="width:100px">';
                			}
                			$next_attachment_div.= '<p><a href="'.URL::to($next->url.'/'.$next->filename).'" class="file_attachments" title="'.$next->filename.'" download>'.$img.'</a>  
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$next->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'"></a></p>'; 

                			$next_t1 = $next->t1;
                			$next_t2 = $next->t2;
                		}
                		if($next->type == 2){ $next_text_one = $next->from_period.' to '.$next->to_period; }

                		if($next->type == 3){ 
                			$next_text_two = $next->textval; 
                			$next_color_status = 'green_import'; 
                			$next_color_text = 'Submitted'; 
                			$next_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$next_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'" style="float:right"></a>';
                		}

                		if($next->type == 4){ 
                			$get_attachment_download = DB::table('vat_reviews_import_attachment')->where('import_id',$next->textval)->first();
                			if(count($get_attachment_download))
                			{
                				$nextt_text_three = $next->textval;
                				$next_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="float:right" download>'.$next->textval.'</a>'; 
                			}
                			
                			$j = $j + 1;
                		}
                		if($next->type == 6)
                		{
                			$next_checked = 'checked';
                			$k = $k + 1;
                		}
                	}

                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $nextt_text_three)
                		{
                			if($current_str > $next_str)
                			{
                				$next_color_status = 'red_import'; 
                				$next_color_text = 'Submission O/S';
                				$next_check_box_color = 'os_td';
                			}
                			else if($current_str == $next_str)
                			{
                				$next_color_status = 'orange_import'; 
                				$next_color_text = 'Submission Due';
                				$next_check_box_color = 'due_td';
                			}
                			else if($current_str < $next_str)
                			{
                				$next_color_status = 'white_import'; 
                				$next_color_text = 'Not Due';
                				$next_check_box_color = 'not_due_td';
                			}
                		}
                		else{
                			$next_color_status = 'blue_import'; 
                			$next_color_text = 'Potentially Submitted';
                			$next_check_box_color = 'ps_td';
                		}
                	}
                }
				

				if($client->status == 1) { $fontcolor = 'red'; }
                elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
                elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
                elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
                else{$fontcolor = '#fff';}

				array_push($prev_cell, '<p style="text-align:center"><label class="import_icon '.$prev_color_status.'">'.$prev_color_text.'</label></p>
						<p>Import FIle ID: '.$prev_text_three.'</p>
						<p><input type="checkbox" class="check_records_received" id="check_records_received" data-month="'.$prevv_month.'" data-client="'.$client->client_id.'" '.$prev_checked.'><label for="" class="records_receive_label '.$prev_check_box_color.' '.$prev_checked.'">Records Received</label></p>
						<p>Period: &nbsp;&nbsp;<a href="javascript:" class="period_change" style="float:right;font-weight:800;margin-left: 10px;" data-month="'.$prevv_month.'" data-client="'.$client->client_id.'">...</a> <spam class="period_import" style="float:right">'.$prev_text_one.'</spam></p>
						<p>Submitted: '.$prev_remove_two.'<input type="text" class="submitted_import" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'" style="float:right" value="'.$prev_text_two.'"></p>
						<p>T1: <spam class="t1_spam">'.$prev_t1.'</spam></p>
						<p>T2: <spam class="t2_spam">'.$prev_t2.'</spam></p>
						<div>Submission: <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-element="'.$prevv_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a> <div class="attachment_div">'.$prev_attachment_div.'</div></div>');

				array_push($curr_cell, '<p style="text-align:center"><label class="import_icon '.$curr_color_status.'">'.$curr_color_text.'</label></p>
						<p>Import FIle ID: '.$curr_text_three.'</p>
						<p><input type="checkbox" class="check_records_received" id="check_records_received" data-month="'.$currr_month.'" data-client="'.$client->client_id.'" '.$curr_checked.'><label for="" class="records_receive_label '.$curr_check_box_color.' '.$curr_checked.'">Records Received</label></p>
						<p>Period: &nbsp;&nbsp;<a href="javascript:" class="period_change" style="float:right;font-weight:800;margin-left: 10px;" data-month="'.$currr_month.'" data-client="'.$client->client_id.'">...</a> <spam class="period_import" style="float:right">'.$curr_text_one.'</spam></p>
						<p>Submitted: '.$curr_remove_two.'<input type="text" class="submitted_import" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right" value="'.$curr_text_two.'"></p>
						<p>T1: <spam class="t1_spam">'.$curr_t1.'</spam></p>
						<p>T2: <spam class="t2_spam">'.$curr_t2.'</spam></p>
						<div>Submission: <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-element="'.$currr_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a> <div class="attachment_div">'.$curr_attachment_div.'</div></div>');

				array_push($next_cell, '<p style="text-align:center"><label class="import_icon '.$next_color_status.'">'.$next_color_text.'</label></p>
						<p>Import FIle ID: '.$next_text_three.'</p>
						<p><input type="checkbox" class="check_records_received" id="check_records_received" data-month="'.$nextt_month.'" data-client="'.$client->client_id.'" '.$next_checked.'><label for="" class="records_receive_label '.$next_check_box_color.' '.$next_checked.'">Records Received</label></p>
						<p>Period: &nbsp;&nbsp;<a href="javascript:" class="period_change" style="float:right;font-weight:800;margin-left: 10px;" data-month="'.$nextt_month.'" data-client="'.$client->client_id.'">...</a> <spam class="period_import" style="float:right">'.$next_text_one.'</spam></p>
						<p>Submitted: '.$next_remove_two.'<input type="text" class="submitted_import" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'" style="float:right" value="'.$next_text_two.'"></p>
						<p>T1: <spam class="t1_spam">'.$next_t1.'</spam></p>
						<p>T2: <spam class="t2_spam">'.$next_t2.'</spam></p>
						<div>Submission: <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-element="'.$nextt_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a> <div class="attachment_div">'.$next_attachment_div.'</div></div>');
			}
		}
		echo json_encode(array('prev' => $prev_cell,'curr' => $curr_cell,'next' => $next_cell));
	}
	public function show_next_month()
	{
		$get_date = '01';
		$month_year = explode("-",Input::get('month_year'));
		$get_full_date = $month_year[1].'-'.$month_year[0].'-'.$get_date;

		$prevv_month = date('m-Y',strtotime($get_full_date.' -1 month'));
		$currr_month = date('m-Y',strtotime($get_full_date));
		$nextt_month = date('m-Y',strtotime($get_full_date.' +1 month'));

		$current_str = strtotime(date('Y-m-01'));

		$prev_str = strtotime($get_full_date.' -1 month');
		$curr_str = strtotime($get_full_date);
		$next_str = strtotime($get_full_date.' +1 month');

		$prev_month = '<a href="javascript:" class="fa fa-arrow-circle-left show_prev_month" title="Extend to Prev Month" data-element="'.$prevv_month.'"></a>&nbsp;&nbsp;<a href="javascript:" class="show_month_in_overlay" data-element="'.date('m-Y',strtotime($get_full_date.' -1 month')).'">'.date('M-Y',strtotime($get_full_date.' -1 month')).'</a> <label class="submission_due_no">No of Submission Due: <spam class="no_sub_due no_sub_due_'.$prevv_month.'">0</spam></label>';
		$curr_month = '<a href="javascript:" class="show_month_in_overlay" data-element="'.date('m-Y',strtotime($get_full_date)).'">'.date('M-Y',strtotime($get_full_date)).'</a> <label class="submission_due_no">No of Submission Due: <spam class="no_sub_due no_sub_due_'.$currr_month.'">0</spam></label>';
		$next_month = '<a href="javascript:" class="show_month_in_overlay" data-element="'.date('m-Y',strtotime($get_full_date.' +1 month')).'">'.date('M-Y',strtotime($get_full_date.' +1 month')).'</a>&nbsp;&nbsp;<a href="javascript:" class="fa fa-arrow-circle-right show_next_month" title="Extend to Next Month" data-element="'.$nextt_month.'"></a> <label class="submission_due_no">No of Submission Due: <spam class="no_sub_due no_sub_due_'.$nextt_month.'">0</spam></label>';

		$prev_cell = array();
		array_push($prev_cell, $prev_month);

		$curr_cell = array();
		array_push($curr_cell, $curr_month);

		$next_cell = array();
		array_push($next_cell, $next_month);

		$clients = DB::table('vat_clients')->get();
		$output = '';
		if(count($clients))
		{
			foreach($clients as $client)
			{
				$prev_attachment_div = '';
				$prev_text_one = 'No Period';
				$prev_text_two = '';
				$prev_t1 = '';
				$prev_t2 = '';
				$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'" style="float:right;display:none"></a>';
				$prev_text_three = '';
				$prevv_text_three = '';
				$prev_color_status = '';
				$prev_color_text = '';
				$prev_check_box_color = 'blacK_td';
				$prev_checked = '';

				$curr_attachment_div = '';
				$curr_text_one = 'No Period';
				$curr_text_two = '';
				$curr_t1 = '';
				$curr_t2 = '';
				$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right;display:none"></a>';
				$curr_text_three = '';
				$currr_text_three = '';
				$curr_color_status = '';
				$curr_color_text = '';
				$curr_check_box_color = 'blacK_td';
				$curr_checked = '';

				$next_attachment_div = '';
				$next_text_one = 'No Period';
				$next_text_two = '';
				$next_t1 = '';
				$next_t2 = '';
				$next_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'" style="float:right;display:none"></a>';
				$next_text_three = '';
				$nextt_text_three = '';
				$next_color_status = '';
				$next_color_text = '';
				$next_check_box_color = 'blacK_td';
				$next_checked = '';

				$get_latest_import_file_id = DB::table('vat_reviews_import_attachment')->where('status',1)->orderBy('id','desc')->first();
            	if(count($get_latest_import_file_id))
            	{
            		$latest_import_id = $get_latest_import_file_id->import_id;
            	}

            	$check_reviews_prev = DB::table('vat_reviews')->where('client_id',$client->client_id)->where('month_year',$prevv_month)->get();
                if(count($check_reviews_prev))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_prev as $prev)
                	{
                		if($prev->type == 1){ 
                			$ext = explode('.',$prev->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('assets/images/image.png').'" style="width:100px">';
                			}
                			$prev_attachment_div.= '<p><a href="'.URL::to($prev->url.'/'.$prev->filename).'" class="file_attachments" title="'.$prev->filename.'" download>'.$img.'</a> 
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$prev->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'"></a></p>'; 

                			$prev_t1 = $prev->t1;
                			$prev_t2 = $prev->t2;
                		}
                		if($prev->type == 2){ $prev_text_one = $prev->from_period.' to '.$prev->to_period; }

                		if($prev->type == 3){ 
                			$prev_text_two = $prev->textval; 
                			$prev_color_status = 'green_import'; 
                			$prev_color_text = 'Submitted'; 
                			$prev_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$prev_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'" style="float:right"></a>';
                		}

                		if($prev->type == 4){ 
                			$get_attachment_download = DB::table('vat_reviews_import_attachment')->where('import_id',$prev->textval)->first();
                			if(count($get_attachment_download))
                			{
                				$prevv_text_three = $prev->textval;
                				$prev_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="float:right" download>'.$prev->textval.'</a>'; 
                			}
                			
                			$j = $j + 1;
                		}
                		if($prev->type == 6)
                		{
                			$prev_checked = 'checked';
                			$k = $k + 1;
                		}
                	}

                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $prevv_text_three)
                		{
                			if($current_str > $prev_str)
                			{
                				$prev_color_status = 'red_import'; 
                				$prev_color_text = 'Submission O/S';
                				$prev_check_box_color = 'os_td';
                			}
                			elseif($current_str == $prev_str)
                			{
                				$prev_color_status = 'orange_import'; 
                				$prev_color_text = 'Submission Due';
                				$prev_check_box_color = 'due_td';
                			}
                			else if($current_str < $prev_str)
                			{
                				$prev_color_status = 'white_import'; 
                				$prev_color_text = 'Not Due';
                				$prev_check_box_color = 'not_due_td';
                			}
                		}
                		else{
                			$prev_color_status = 'blue_import'; 
                			$prev_color_text = 'Potentially Submitted';
                			$prev_check_box_color = 'ps_td';
                		}
                	}
                }

                $check_reviews_curr = DB::table('vat_reviews')->where('client_id',$client->client_id)->where('month_year',$currr_month)->get();
                if(count($check_reviews_curr))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_curr as $curr)
                	{
                		if($curr->type == 1){ 
                			$ext = explode('.',$curr->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('assets/images/image.png').'" style="width:100px">';
                			}
                			$curr_attachment_div.= '<p><a href="'.URL::to($curr->url.'/'.$curr->filename).'" class="file_attachments" title="'.$curr->filename.'" download>'.$img.'</a> 
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$curr->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$currr_month.'"></a></p>'; 
                			$curr_t1 = $curr->t1;
                			$curr_t2 = $curr->t2;
                		}
                		if($curr->type == 2){ $curr_text_one = $curr->from_period.' to '.$curr->to_period; }

                		if($curr->type == 3){ 
                			$curr_text_two = $curr->textval; 
                			$curr_color_status = 'green_import'; 
                			$curr_color_text = 'Submitted'; 
                			$curr_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right"></a>';
                		}

                		if($curr->type == 4){ 
                			$get_attachment_download = DB::table('vat_reviews_import_attachment')->where('import_id',$curr->textval)->first();
                			if(count($get_attachment_download))
                			{
                				$currr_text_three = $curr->textval;
                				$curr_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="float:right" download>'.$curr->textval.'</a>'; 
                			}
                			
                			$j = $j + 1;
                		}
                		if($curr->type == 6)
                		{
                			$curr_checked = 'checked';
                			$k = $k + 1;
                		}
                	}

                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $currr_text_three)
                		{
                			if($current_str > $curr_str)
                			{
                				$curr_color_status = 'red_import'; 
                				$curr_color_text = 'Submission O/S';
                				$curr_check_box_color = 'os_td';
                			}
                			else if($current_str == $curr_str)
                			{
                				$curr_color_status = 'orange_import'; 
                				$curr_color_text = 'Submission Due';
                				$curr_check_box_color = 'due_td';
                			}
                			else if($current_str < $curr_str)
                			{
                				$curr_color_status = 'white_import'; 
                				$curr_color_text = 'Not Due';
                				$curr_check_box_color = 'not_due_td';
                			}
                		}
                		else{
                			$curr_color_status = 'blue_import'; 
                			$curr_color_text = 'Potentially Submitted';
                			$curr_check_box_color = 'ps_td';
                		}
                	}
                }

                $check_reviews_next = DB::table('vat_reviews')->where('client_id',$client->client_id)->where('month_year',$nextt_month)->get();
                if(count($check_reviews_next))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_next as $next)
                	{
                		if($next->type == 1){ 
                			$ext = explode('.',$next->filename);
                			if(end($ext) == "pdf") {
                				$img = '<img src="'.URL::to('assets/images/pdf.png').'" style="width:100px">';
                			}
                			if(end($ext) == "doc" || end($ext) == "docx") {
                				$img = '<img src="'.URL::to('assets/images/file.png').'" style="width:100px">';
                			}
                			if(end($ext) == "xls" || end($ext) == "xlsx") {
                				$img = '<img src="'.URL::to('assets/images/excel.png').'" style="width:100px">';
                			}
                			if(end($ext) == "jpg" || end($ext) == "jpeg" || end($ext) == "png" || end($ext) == "gif") {
                				$img = '<img src="'.URL::to('assets/images/image.png').'" style="width:100px">';
                			}
                			$next_attachment_div.= '<p><a href="'.URL::to($next->url.'/'.$next->filename).'" class="file_attachments" title="'.$next->filename.'" download>'.$img.'</a>  
                			<a href="'.URL::to('user/delete_vat_files?file_id='.$next->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'"></a></p>'; 
                			$next_t1 = $next->t1;
                			$next_t2 = $next->t2;
                		}
                		if($next->type == 2){ $next_text_one = $next->from_period.' to '.$next->to_period; }

                		if($next->type == 3){ 
                			$next_text_two = $next->textval; 
                			$next_color_status = 'green_import'; 
                			$next_color_text = 'Submitted'; 
                			$next_check_box_color = 'submitted_td';
                			$i = $i + 1; 
                			$next_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'" style="float:right"></a>';
                		}

                		if($next->type == 4){ 
                			$get_attachment_download = DB::table('vat_reviews_import_attachment')->where('import_id',$next->textval)->first();
                			if(count($get_attachment_download))
                			{
                				$nextt_text_three = $next->textval;
                				$next_text_three = '<a class="import_file_attachment_id" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="float:right" download>'.$next->textval.'</a>'; 
                			}
                			
                			$j = $j + 1;
                		}
                		if($next->type == 6)
                		{
                			$next_checked = 'checked';
                			$k = $k + 1;
                		}
                	}

                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $nextt_text_three)
                		{
                			if($current_str > $next_str)
                			{
                				$next_color_status = 'red_import'; 
                				$next_color_text = 'Submission O/S';
                				$next_check_box_color = 'os_td';
                			}
                			else if($current_str == $next_str)
                			{
                				$next_color_status = 'orange_import'; 
                				$next_color_text = 'Submission Due';
                				$next_check_box_color = 'due_td';
                			}
                			else if($current_str < $next_str)
                			{
                				$next_color_status = 'white_import'; 
                				$next_color_text = 'Not Due';
                				$next_check_box_color = 'not_due_td';
                			}
                		}
                		else{
                			$next_color_status = 'blue_import'; 
                			$next_color_text = 'Potentially Submitted';
                			$next_check_box_color = 'ps_td';
                		}
                	}
                }
				

				if($client->status == 1) { $fontcolor = 'red'; }
                elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
                elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
                elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
                else{$fontcolor = '#fff';}

				array_push($prev_cell, '<p style="text-align:center"><label class="import_icon '.$prev_color_status.'">'.$prev_color_text.'</label></p>
						<p>Import FIle ID: '.$prev_text_three.'</p>
						<p><input type="checkbox" class="check_records_received" id="check_records_received" data-month="'.$prevv_month.'" data-client="'.$client->client_id.'" '.$prev_checked.'><label for="" class="records_receive_label '.$prev_check_box_color.' '.$prev_checked.'">Records Received</label></p>
						<p>Period: &nbsp;&nbsp;<a href="javascript:" class="period_change" style="float:right;font-weight:800;margin-left: 10px;" data-month="'.$prevv_month.'" data-client="'.$client->client_id.'">...</a> <spam class="period_import" style="float:right">'.$prev_text_one.'</spam></p>
						<p>Submitted: '.$prev_remove_two.'<input type="text" class="submitted_import" data-client="'.$client->client_id.'" data-element="'.$prevv_month.'" style="float:right" value="'.$prev_text_two.'"></p>
						<p>T1: <spam class="t1_spam">'.$prev_t1.'</spam></p>
						<p>T2: <spam class="t2_spam">'.$prev_t2.'</spam></p>
						<div>Submission: <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-element="'.$prevv_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a> <div class="attachment_div">'.$prev_attachment_div.'</div></div>');

				array_push($curr_cell, '<p style="text-align:center"><label class="import_icon '.$curr_color_status.'">'.$curr_color_text.'</label></p>
						<p>Import FIle ID: '.$curr_text_three.'</p>
						<p><input type="checkbox" class="check_records_received" id="check_records_received" data-month="'.$currr_month.'" data-client="'.$client->client_id.'" '.$curr_checked.'><label for="" class="records_receive_label '.$curr_check_box_color.' '.$curr_checked.'">Records Received</label></p>
						<p>Period: &nbsp;&nbsp;<a href="javascript:" class="period_change" style="float:right;font-weight:800;margin-left: 10px;" data-month="'.$currr_month.'" data-client="'.$client->client_id.'">...</a> <spam class="period_import" style="float:right">'.$curr_text_one.'</spam></p>
						<p>Submitted: '.$curr_remove_two.'<input type="text" class="submitted_import" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right" value="'.$curr_text_two.'"></p>
						<p>T1: <spam class="t1_spam">'.$curr_t1.'</spam></p>
						<p>T2: <spam class="t2_spam">'.$curr_t2.'</spam></p>
						<div>Submission: <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-element="'.$currr_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a> <div class="attachment_div">'.$curr_attachment_div.'</div></div>');

				array_push($next_cell, '<p style="text-align:center"><label class="import_icon '.$next_color_status.'">'.$next_color_text.'</label></p>
						<p>Import FIle ID: '.$next_text_three.'</p>
						<p><input type="checkbox" class="check_records_received" id="check_records_received" data-month="'.$nextt_month.'" data-client="'.$client->client_id.'" '.$next_checked.'><label for="" class="records_receive_label '.$next_check_box_color.' '.$next_checked.'">Records Received</label></p>
						<p>Period: &nbsp;&nbsp;<a href="javascript:" class="period_change" style="float:right;font-weight:800;margin-left: 10px;" data-month="'.$nextt_month.'" data-client="'.$client->client_id.'">...</a> <spam class="period_import" style="float:right">'.$next_text_one.'</spam></p>
						<p>Submitted: '.$next_remove_two.'<input type="text" class="submitted_import" data-client="'.$client->client_id.'" data-element="'.$nextt_month.'" style="float:right" value="'.$next_text_two.'"></p>
						<p>T1: <spam class="t1_spam">'.$next_t1.'</spam></p>
						<p>T2: <spam class="t2_spam">'.$next_t2.'</spam></p>
						<div>Submission: <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-element="'.$nextt_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a> <div class="attachment_div">'.$next_attachment_div.'</div></div>');
			}
		}
		echo json_encode(array('prev' => $prev_cell,'curr' => $curr_cell,'next' => $next_cell));
	}
	public function show_month_in_overlay()
	{
		$get_date = '01';
		$month = Input::get('month');
		$month_year = explode("-",Input::get('month'));
		$get_full_date = $month_year[1].'-'.$month_year[0].'-'.$get_date;

		$currr_month = date('m-Y',strtotime($get_full_date));
		$current_str = strtotime(date('Y-m-01'));
		$curr_str = strtotime($get_full_date);

		$clients = DB::table('vat_clients')->get();
		$output = '<table class="table">
		<thead>
			<tr>
				<th>Client Code <i class="fa fa-sort code_sort"></i></th>
				<th>Client Name <i class="fa fa-sort client_overlay_sort"></i></th>
				<th>Create Task</th>
				<th style="width: 12%;">Status <i class="fa fa-sort status_sort"></i></th>
				<th>File ID <i class="fa fa-sort id_sort"></i></th>
				<th style="width: 15%;">Records Received <i class="fa fa-sort record_sort"></i></th>
				<th style="width: 10%;">Submitted Date <i class="fa fa-sort date_sort"></i></th>
				<th>Attachments</th>
			</tr>
		</thead>
		<tbody id="overlay_tbody">';
		if(count($clients))
		{
			foreach($clients as $client)
			{
				if($client->cm_client_id != "")
				{
					$client_details = DB::table('cm_clients')->where('client_id',$client->cm_client_id)->first();
					if(count($client_details))
					{
						$company_name = $client_details->company.' - '.$client_details->client_id;
						$cm_client_id = $client_details->client_id;
					}
					else{
						$company_name = '';
						$cm_client_id = '';
					}
				}
				else{
					$company_name = '';
					$cm_client_id = '';
				}
				$curr_attachment_div = '';
				$curr_text_one = 'No Period';
				$curr_text_two = '';
				$curr_t1 = '<p>T1: <spam class="t1_spam_overlay"></spam></p>';
				$curr_t2 = '<p>T2: <spam class="t2_spam_overlay"></spam></p>';
				$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:left;display:none"></a>';
				$curr_text_three = '';
				$currr_text_three = '';
				$curr_color_status = '';
				$curr_color_text = '';
				$curr_check_box_color = 'black_td';
				$curr_checked = '';

				$get_latest_import_file_id = DB::table('vat_reviews_import_attachment')->where('status',1)->orderBy('id','desc')->first();
            	if(count($get_latest_import_file_id))
            	{
            		$latest_import_id = $get_latest_import_file_id->import_id;
            	}

            	$check_reviews_curr = DB::table('vat_reviews')->where('client_id',$client->client_id)->where('month_year',$currr_month)->get();
                if(count($check_reviews_curr))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_curr as $curr)
                	{
                		if($curr->type == 1){ 
                			$curr_attachment_div.= '<p><a href="'.URL::to($curr->url.'/'.$curr->filename).'" class="file_attachments" download>'.$curr->filename.'</a> <a href="'.URL::to('user/delete_vat_files?file_id='.$curr->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$currr_month.'"></a></p>';
                			$curr_t1 ='<p>T1: <spam class="t1_spam_overlay">'.$curr->t1.'</spam></p>';
							$curr_t2 ='<p>T2: <spam class="t2_spam_overlay">'.$curr->t2.'</spam></p>'; 

                		}
                		if($curr->type == 2){ $curr_text_one = $curr->from_period.' to '.$curr->to_period; }

                		if($curr->type == 3){ 
                			$curr_text_two = $curr->textval; 
                			$curr_color_status = 'green_import_overlay'; 
                			$curr_color_text = 'Submitted'; 
                			$curr_check_box_color = 'submitted_td_overlay';
                			$i = $i + 1; 
                			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:left"></a>';
                		}

                		if($curr->type == 4){ 
                			$get_attachment_download = DB::table('vat_reviews_import_attachment')->where('import_id',$curr->textval)->first();
                			if(count($get_attachment_download))
                			{
                				$currr_text_three = $curr->textval;
                				$curr_text_three = '<a class="import_file_attachment_id_overlay" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="float:right" download>'.$curr->textval.'</a>'; 
                			}
                			
                			$j = $j + 1;
                		}
                		if($curr->type == 6)
                		{
                			$curr_checked = 'checked';
                			$k = $k + 1;
                		}
                	}

                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $currr_text_three)
                		{
                			if($current_str > $curr_str)
                			{
                				$curr_color_status = 'red_import_overlay'; 
                				$curr_color_text = 'Submission O/S';
                				$curr_check_box_color = 'os_td_overlay';
                			}
                			else if($current_str == $curr_str)
                			{
                				$curr_color_status = 'orange_import_overlay'; 
                				$curr_color_text = 'Submission Due';
                				$curr_check_box_color = 'due_td_overlay';
                			}
                			else if($current_str < $curr_str)
                			{
                				$curr_color_status = 'white_import_overlay'; 
                				$curr_color_text = 'Not Due';
                				$curr_check_box_color = 'not_due_td_overlay';
                			}
                		}
                		else{
                			$curr_color_status = 'blue_import_overlay'; 
                			$curr_color_text = 'Potentially Submitted';
                			$curr_check_box_color = 'ps_td_overlay';
                		}
                	}
                }

                if($client->status == 1) { $fontcolor = 'red'; }
                elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
                elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
                elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
                else{$fontcolor = '#fff';}

				$output.='<tr class="shown_tr shown_tr_'.$client->client_id.'_'.$currr_month.'">
					<td class="code_sort_val">'.$client->cm_client_id.'</td>
					<td class="client_overlay_sort_val">'.$client->name.'</td>
					<td>
						<a href="javascript:" class="common_black_button create_task_manager" data-client="'.$client->client_id.'" data-month="'.$currr_month.'" data-cmclient="'.$client->cm_client_id.'" data-element="'.$company_name.'" style="clear: both; float: left";>Create Task</a><br/>';
						$get_task_details = DB::table("taskmanager_vat")->where('client_id',$client->client_id)->where('month',$currr_month)->get();
						if(count($get_task_details))
						{
							foreach($get_task_details as $task_detail)
							{
								$taskmanager = DB::table('taskmanager')->where('id',$task_detail->task_id)->first();
								$output.='<p style="float: left;margin-top: 10px;font-weight: 600;">Task : '.$taskmanager->taskid.' - '.$taskmanager->subject.'</p>';
							}
						}
					$output.='</td>
					<td><label class="import_icon_overlay '.$curr_color_status.'">'.$curr_color_text.'</label></td>
					<td class="id_sort_val">'.$curr_text_three.'</td>
					<td><input type="checkbox" class="check_records_received_overlay" id="check_records_received_overlay" data-month="'.$currr_month.'" data-client="'.$client->client_id.'" '.$curr_checked.'><label for="" class="records_receive_label_overlay '.$curr_check_box_color.' '.$curr_checked.'">Records Received</label>
						<input type="hidden" name="record_sort_val" class="record_sort_val" value="'.$curr_checked.'">
					</td>
					<td>'.$curr_remove_two.'<input type="text" class="submitted_import_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right" value="'.$curr_text_two.'">
						<input type="hidden" name="date_sort_val" class="date_sort_val" value="'.$curr_text_two.'">
						
					</td>
					<td><a href="javascript:" class="fa fa-plus add_attachment_month_year_overlay" data-element="'.$currr_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a> <div class="attachment_div_overlay">'.$curr_attachment_div.' '.$curr_t1.' '.$curr_t2.'</div></td>
				</tr>';
			}
		}
		$output.='</tbody>
		</table>';
		echo $output;
	}
	public function export_month_in_overlay()
	{
		$get_date = '01';
		$month = Input::get('month');
		$month_year = explode("-",Input::get('month'));
		$get_full_date = $month_year[1].'-'.$month_year[0].'-'.$get_date;

		$currr_month = date('m-Y',strtotime($get_full_date));
		$currrr_month = date('d-M-Y',strtotime($get_full_date));
		$current_str = strtotime(date('Y-m-01'));
		$curr_str = strtotime($get_full_date);
		$filename = 'vat_review_for_'.$currrr_month.'.csv';
		$columns = array('Client Code','Client Name','Status','File ID','Records Received', 'Submitted Date','Attachments');
		$file = fopen('papers/vat_review_for_'.$currrr_month.'.csv', 'w');
		fputcsv($file, $columns);

		$clients = DB::table('vat_clients')->get();
		
		if(count($clients))
		{
			foreach($clients as $client)
			{
				$curr_attachment_div = '';
				$curr_text_one = 'No Period';
				$curr_text_two = '';
				$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:left;display:none"></a>';
				$curr_text_three = '';
				$currr_text_three = '';
				$curr_color_status = '';
				$curr_color_text = '';
				$curr_check_box_color = 'black_td';
				$curr_checked = '';

				$get_latest_import_file_id = DB::table('vat_reviews_import_attachment')->where('status',1)->orderBy('id','desc')->first();
            	if(count($get_latest_import_file_id))
            	{
            		$latest_import_id = $get_latest_import_file_id->import_id;
            	}

            	$check_reviews_curr = DB::table('vat_reviews')->where('client_id',$client->client_id)->where('month_year',$currr_month)->get();
                if(count($check_reviews_curr))
                {
                	$i= 0; $j=0;$k=0;
                	foreach($check_reviews_curr as $curr)
                	{
                		if($curr->type == 1){ 
                			if($curr_attachment_div == "")
                			{
                				$curr_attachment_div= $curr->filename; 
                			}
                			else{
                				$curr_attachment_div= $curr_attachment_div.' , '.$curr->filename; 
                			}
                		}
                		if($curr->type == 2){ $curr_text_one = $curr->from_period.' to '.$curr->to_period; }

                		if($curr->type == 3){ 
                			$curr_text_two = $curr->textval; 
                			$curr_color_status = 'green_import_overlay'; 
                			$curr_color_text = 'Submitted'; 
                			$curr_check_box_color = 'submitted_td_overlay';
                			$i = $i + 1; 
                			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:left"></a>';
                		}

                		if($curr->type == 4){ 
                			$get_attachment_download = DB::table('vat_reviews_import_attachment')->where('import_id',$curr->textval)->first();
                			if(count($get_attachment_download))
                			{
                				$currr_text_three = $curr->textval;
                				$curr_text_three = $curr->textval; 
                			}
                			
                			$j = $j + 1;
                		}
                		if($curr->type == 6)
                		{
                			$curr_checked = 'Received';
                			$k = $k + 1;
                		}
                	}

                	if($j > 0 && $i == 0)
                	{
                		if($latest_import_id == $currr_text_three)
                		{
                			if($current_str > $curr_str)
                			{
                				$curr_color_status = 'red_import_overlay'; 
                				$curr_color_text = 'Submission O/S';
                				$curr_check_box_color = 'os_td_overlay';
                			}
                			else if($current_str == $curr_str)
                			{
                				$curr_color_status = 'orange_import_overlay'; 
                				$curr_color_text = 'Submission Due';
                				$curr_check_box_color = 'due_td_overlay';
                			}
                			else if($current_str < $curr_str)
                			{
                				$curr_color_status = 'white_import_overlay'; 
                				$curr_color_text = 'Not Due';
                				$curr_check_box_color = 'not_due_td_overlay';
                			}
                		}
                		else{
                			$curr_color_status = 'blue_import_overlay'; 
                			$curr_color_text = 'Potentially Submitted';
                			$curr_check_box_color = 'ps_td_overlay';
                		}
                	}
                }

                if($client->status == 1) { $fontcolor = 'red'; }
                elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
                elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
                elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
                else{$fontcolor = '#fff';}

                $columns1 = array(preg_replace('/[^[:print:]]/','',$client->cm_client_id),$client->name,$curr_color_text,$curr_text_three,$curr_checked,$curr_text_two,$curr_attachment_div);
				fputcsv($file, $columns1);
			}
		}
		fclose($file);
		echo $filename;
	}
	public function vat_upload_images()
	{
		$client_id = Input::get('hidden_client_id_vat');
		$month_year = Input::get('hidden_month_year_vat');

		$get_vat_details = DB::table('vat_clients')->where('client_id',$client_id)->first();
		$tax_no = $get_vat_details->taxnumber;

		$upload_dir = 'uploads/vat_reviews';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.$client_id;
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.$month_year;
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.time();
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
			$fname = $fname;

			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);

			$download_url = URL::to($filename);
			
		 	echo json_encode(array('filename' => $fname,'upload_dir' => $upload_dir,'client_id' => $client_id,'month_year' => $month_year, 'download_url' => $download_url,'tax_no' => $tax_no));
		}
	}
	public function vat_commit_upload_images(){
		$client_id = Input::get('client_id');
		$month_year = Input::get('month_year');
		$filename = Input::get('filename');
		$url = Input::get('dir');
		$t1 = Input::get('t1');
		$t2 = Input::get('t2');
		$reg = Input::get('reg');

		$data['client_id'] = $client_id;
		$data['month_year'] = $month_year;
		$data['type'] = 1;
		$data['filename'] = $filename;
		$data['url'] = $url;
		$data['t1'] = $t1;
		$data['t2'] = $t2;
		$data['reg'] = $reg;

		$insertedid = DB::table('vat_reviews')->insertGetid($data);

		$filename = $url.'/'.$filename;
		$download_url = URL::to($filename);
		$delete_url = URL::to('user/delete_vat_files?file_id='.$insertedid.'');
		echo json_encode(array('id' => $insertedid, 'download_url' => $download_url, 'delete_url' => $delete_url));
	}
	public function vat_upload_csv()
	{
		$upload_dir = 'uploads/vat_reviews';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/ros_vat_due';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if (!empty($_FILES)) {
			$fname = time().'.csv';
			$tmpFile = $_FILES['file']['tmp_name'];
			$filename = $upload_dir.'/'.$fname;
			move_uploaded_file($tmpFile,$filename);

			$ufname = str_replace("#","",$_FILES['file']['name']);
			$ufname = str_replace("#","",$ufname);
			$ufname = str_replace("#","",$ufname);
			$ufname = str_replace("#","",$ufname);

			$ufname = str_replace("%","",$ufname);
			$ufname = str_replace("%","",$ufname);
			$ufname = str_replace("%","",$ufname);

			$data['filename'] = $fname;
			$data['uploaded_filename'] = $ufname;
			$data['url'] = $upload_dir;
			$data['import_date'] = date('d/m/Y');
			$data['import_time'] = date('H:i:s');

			$insertedid = DB::table('vat_reviews_import_attachment')->insertGetid($data);
			
		 	echo json_encode(array('id' => $insertedid,'filename' => $fname));
		}
	}
	public function save_vat_review_date()
	{
		$client_id = Input::get('client');
		$month_year = Input::get('month_year');
		$date = Input::get('date');

		$check_month_year_client = DB::table('vat_reviews')->where('client_id',$client_id)->where('month_year',$month_year)->where('type',3)->first();
		if(count($check_month_year_client))
		{
			$data['client_id']= $client_id;
			$data['month_year']= $month_year;
			$data['type']= "3";
			$data['textval'] = $date;
			$data['status'] = "0";

			DB::table('vat_reviews')->where('id',$check_month_year_client->id)->update($data);
		}
		else{
			$data['client_id']= $client_id;
			$data['month_year']= $month_year;
			$data['type']= "3";
			$data['textval'] = $date;
			$data['status'] = "0";

			DB::table('vat_reviews')->insert($data);
		}
	}
	public function save_textval_review()
	{
		$client_id = Input::get('client');
		$month_year = Input::get('month_year');
		$textval = Input::get('textval');
		$type = Input::get('type');

		$check_month_year_client = DB::table('vat_reviews')->where('client_id',$client_id)->where('month_year',$month_year)->where('type',$type)->first();
		if(count($check_month_year_client))
		{
			$data['client_id'] = $client_id;
			$data['month_year'] = $month_year;
			$data['type'] = $type;
			$data['textval'] = $textval;

			DB::table('vat_reviews')->where('id',$check_month_year_client->id)->update($data);
		}
		else{
			$data['client_id'] = $client_id;
			$data['month_year'] = $month_year;
			$data['type'] = $type;
			$data['textval'] = $textval;
			DB::table('vat_reviews')->insert($data);
		}
	}
	public function delete_vat_files()
	{
		$fileid = Input::get('file_id');
		DB::table("vat_reviews")->where('id',$fileid)->delete();
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
	public function getclient_salutation()
	{
		$clientid= Input::get('clientid');
		$client_details = DB::table('cm_clients')->where('client_id',$clientid)->first();
		echo $client_details->firstname;
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
		$get_email_dates = DB::table('task_email_sent')->where('task_created_id',$task_created_id)->where('options','!=','n')->get();
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
		$get_schemes = DB::table('schemes')->get();
		$arr_scheme = array();
		if(count($get_schemes))
		{
			foreach($get_schemes as $scheme)
			{
				array_push($arr_scheme,$scheme->scheme_name);
			}
		}
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
							$get_name = str_replace(".txt", "", $attachment->attachment);
							if (!in_array($get_name, $arr_scheme))
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
		  $datastarted['task_started'] = 1;
		  DB::table('task')->where('task_id',$task_id)->update($datastarted);
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
		  $datastarted['task_started'] = 1;
		  DB::table('task')->where('task_id',$task_id)->update($datastarted);
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
	public function secret_task_button()
	{
		$task_id = Input::get('task_id');
		$get_task_details = DB::table('task')->where('task_id',$task_id)->first();
		$data['enterhours'] = ($get_task_details->enterhours == "2")?"2":"1";
		$data['holiday'] = ($get_task_details->holiday == "2")?"2":"1";
		$data['process'] = ($get_task_details->process == "2")?"2":"1";
		$data['payslips'] = ($get_task_details->payslips == "2")?"2":"1";
		$data['email'] = ($get_task_details->email == "2")?"2":"1";
		$data['upload'] = ($get_task_details->upload == "2")?"2":"1";
		DB::table('task')->where('task_id',$task_id)->update($data);
	}
	public function get_pms_file_attachments()
	{
		$task_id = Input::get('task_id');
		$type = Input::get('type');
		if($type == "2")
		{
			$attachments = DB::table('task_attached')->where('task_id',$task_id)->where('network_attach',1)->get();
			$files_received = '';
	        if(count($attachments))
	        {
	          $files_received.='<h5 style="color:#000; font-weight:600">Files Received : <i class="fa fa-minus-square fadeleteall_attachments" data-element="'.$task_id.'" style="margin-top:10px;color:#fff" aria-hidden="true" title="Delete All Attachments"></i></h5>';
	          $files_received.='<div class="scroll_attachment_div">';
	              foreach($attachments as $attachment)
	              {
	                  $files_received.='<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
	              }
	          $files_received.='</div>';
	          	$data['network_attach'] = 1;          	
				$dataval['task_started'] = 1;
				$dataval['task_notify'] = 1;
				DB::table('task')->where('task_id',$task_id)->update($dataval);
	        }
	        echo $files_received;
		}
		else{
			$attachments = DB::table('task_attached')->where('task_id',$task_id)->where('network_attach',0)->get();
			$files_attached = '';
	          if(count($attachments))
	          {
	              $files_attached.= '<i class="fa fa-minus-square fadeleteall" data-element="'.$task_id.'" style="margin-top:-18px;margin-left: 21px;" aria-hidden="true" title="Delete All Attachments"></i>';
	              $files_attached.= '<h5 style="color:#000; font-weight:600">Attachments :</h5>';
	              $files_attached.= '<div class="scroll_attachment_div">';
	                foreach($attachments as $attachment)
	                {
	                    $files_attached.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon"><i class="fa fa-trash trash_image sample_trash" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
	                }
	              $files_attached.= '</div>';
	          }
	         echo $files_attached;
		}
	}
	public function current_payroll_list()
	{
		$current_week = DB::table('week')->orderBy('week_id','desc')->first();
		$current_month = DB::table('month')->orderBy('month_id','desc')->first();
		$columns = array('Task Name','Period','Network Location','Primary Email','Secondary Email', 'Number of times Email sent','PayePRSI liability');
		$file = fopen('papers/current_payroll_lists.csv', 'w');
		fputcsv($file, $columns);
		$tasks = DB::table('task')->where('task_week',$current_week->week_id)->orWhere('task_month',$current_month->month_id)->get();
		if(count($tasks))
		{
			foreach($tasks as $task)
			{
				if($task->task_week != 0) { $period = 'Weekly'; }
				else { $period = 'Monthly'; }
				$get_dates = DB::table('task_email_sent')->where('task_id',$task->task_id)->where('options','!=','n')->get();
				$columns1 = array($task->task_name,$period,$task->network,$task->task_email,$task->secondary_email,count($get_dates),$task->liability);
				fputcsv($file, $columns1);
			}
		}
		fclose($file);
		echo 'current_payroll_lists.csv';
	}
	public function start_rating()
	{
		$taskid = Input::get('taskid');
		$value = Input::get('value');
		$data['rating'] = $value;
		DB::table('task')->where('task_id',$taskid)->update($data);
	}
	public function load_dashboard_tiles()
	{
		$system = Input::get('system');
		$output = '';
		if($system == "cm")
		{
			$output.= '<div class="title">Cm System</div>
              <div class="ul_list">
                <ul>';
                $total_clients = DB::table('cm_clients')->count();
                $active_cm_clients = DB::table('cm_clients')->where('active',1)->count();
                
                  $output.='<li>Total  Clients : '.$total_clients.'</li>
                  <li>Active  Clients : '.$active_cm_clients.'</li>
                  <li><a href="'.URL::to('user/client_account_review').'" style="color:#fff">Client Account Review</a></li>
                </ul>
              </div>';
		}
		elseif($system == "week")
		{
			$output.= '<div class="title">Weekly Payroll</div>
              <div class="ul_list">';
              $current_week = DB::table('week')->orderBy('week_id','desc')->first();
              $current_year = DB::table('year')->where('year_id',$current_week->year)->first();
              $no_of_tasks = DB::table('task')->where('task_week',$current_week->week_id)->count();
              $week_completed = DB::table('task')->where('task_week',$current_week->week_id)->where('task_status',1)->count();
              $week_donot_completed = DB::table('task')->where('task_week',$current_week->week_id)->where('task_status',0)->where('task_complete_period',1)->count();
              $week_incompleted = DB::table('task')->where('task_week',$current_week->week_id)->where('task_status',0)->where('task_complete_period',0)->count();
             
                $output.='<div class="sub-title">Week #'.$current_week->week.', '.$current_year->year_name.' - '.$no_of_tasks.' Tasks</div>
                <ul>
                  <li>Completed Tasks : '.$week_completed.'</li>
                  <li>Donot Complete tasks : '.$week_donot_completed.'</li>
                  <li>Incomplete Tasks : '.$week_incompleted.'</li>
                </ul>
              </div>';
		}
		elseif($system == "month")
		{
			$output.= '
              <div class="title">Monthly Payroll</div>';
              $current_month = DB::table('month')->orderBy('month_id','desc')->first();
              $current_year = DB::table('year')->where('year_id',$current_month->year)->first();
              $no_of_tasks_month = DB::table('task')->where('task_month',$current_month->month_id)->count();
              $week_completed_month = DB::table('task')->where('task_month',$current_month->month_id)->where('task_status',1)->count();
              $week_donot_completed_month = DB::table('task')->where('task_month',$current_month->month_id)->where('task_status',0)->where('task_complete_period',1)->count();
              $week_incompleted_month = DB::table('task')->where('task_month',$current_month->month_id)->where('task_status',0)->where('task_complete_period',0)->count();
              $output.='<div class="ul_list">
                <div class="sub-title">Month #'.$current_month->month.', '.$current_year->year_name.' - '.$no_of_tasks_month.' Tasks</div>
                <ul>
                  <li>Completed Tasks : '.$week_completed_month.'</li>
                  <li>Donot Complete tasks : '.$week_donot_completed_month.'</li>
                  <li>Incomplete Tasks : '.$week_incompleted_month.'</li>
                </ul>
              </div>';
		}
		elseif($system == "p30")
		{
			$output.= '
              <div class="title">P30 system</div>
              <div class="ul_list">';
              $current_p30_month = DB::table('p30_month')->orderBy('month_id','desc')->first();
              $current_year = DB::table('year')->where('year_id',$current_p30_month->year)->first();
              $no_of_tasks_p30_month = DB::table('p30_task')->where('task_month',$current_p30_month->month_id)->count();
              $week_completed_p30_month = DB::table('p30_task')->where('task_month',$current_p30_month->month_id)->where('task_status',1)->count();
              $week_incompleted_p30_month = DB::table('p30_task')->where('task_month',$current_p30_month->month_id)->where('task_status',0)->count();
                $output.='<div class="sub-title">Month #'.$current_p30_month->month.', '.$current_year->year_name.' - '.$no_of_tasks_p30_month.' Tasks</div>
                <ul>
                  <li>Completed Tasks : '.$week_completed_p30_month.'</li>
                  <li>Incomplete Tasks : '.$week_incompleted_p30_month.'</li>
                </ul>
              </div>';
		}
		elseif($system == "vat")
		{
			$output.= '
              <div class="title">VAT system</div>';
              $disabled_clients = DB::table('vat_clients')->where('status',1)->count();
              $clients_email = DB::table('vat_clients')->where('status',0)->where('pemail','!=', '')->where('self_manage','no')->count();
              $clients_without_email = DB::table('vat_clients')->where('status',0)->where('pemail', '')->where('self_manage','no')->count();
              $self_manage = DB::table('vat_clients')->where('status',0)->where('self_manage','yes')->count();
              
              $output.='<div class="ul_list">                
                <ul>
                  <li>Disabled Clients : '.$disabled_clients.'</li>
                  <li>Clients With Email : '.$clients_email.'</li>
                  <li>Clients Without Email: '.$clients_without_email.'</li>
                  <li>Self Managed  : '.$self_manage.'</li>
                </ul>
              </div>';
		}
		elseif($system == "infile")
		{
			$output.= '
              <div class="title">In Files System</div>';
              $infile_client = DB::table('in_file')->select('id','client_id')->where('client_id','!=','')->get();
              $array_clientid = array();
              $file_count = 0;
              if(count($infile_client))
              {
              	foreach($infile_client as $infile)
              	{
              		if(!in_array($infile->client_id,$array_clientid))
              		{
              			$check_attachment = DB::table('in_file_attachment')->where('file_id',$infile->id)->count();
	              		if($check_attachment > 0)
	              		{
	              			$file_count++;
	              			array_push($array_clientid, $infile->client_id);
	              		}
              		}
              	}
              }
              $infile_complete = DB::table('in_file')->where('status', 1)->count();
              $infile_incomplete = DB::table('in_file')->where('status', 0)->count();
              
              $output.='<div class="ul_list">                
                <ul>
                  <li>No. of Clients with In Files : '.$file_count.'</li>
                  <li>No. of Complete In Files : '.$infile_complete.'</li>
                  <li>No. of InComplete In Files : '.$infile_incomplete.'</li>
                </ul>
              </div>';
		}
		elseif($system == "yearend")
		{
			$output.= '
              <div class="title">Yearend System</div>
                <div class="ul_list">';
                  $yearend_year = DB::table('year_end_year')->orderBy('id','desc')->limit(3)->get();
                  if(count($yearend_year))
                  {
                    foreach($yearend_year as $year)
                    {
                      $started = DB::table('year_client')->where('year',$year->year)->where('status',0)->count();
                      $in_progress = DB::table('year_client')->where('year',$year->year)->where('status',1)->count();
                      $completed = DB::table('year_client')->where('year',$year->year)->where('status',2)->count();
                      
                        $output.='<div class="col-md-4 col-lg-4">
                          <ul>
                          <li class="lifirst" style="text-decoration: underline;">Year : '.$year->year.'</li>
                          <li>Not Started :  '.$started.' Clients</li>
                          <li>Inprogress : '.$in_progress.' Clients</li>
                          <li>Completed : '.$completed.' Clients</li>
                          </ul>
                        </div>';
                    }
                    if(count($yearend_year) == 2)
                    {
                      $output.='<div class="col-md-4 col-lg-4">
                        <ul>
                          <li class="lifirst" style="text-decoration: underline;">Year : 2017</li>
                          <li>No Records found</li>
                        </ul>
                      </div>';
                    }
                    elseif(count($yearend_year) == 1)
                    { 
                      $output.='<div class="col-md-4 col-lg-4">
                        <ul>
                          <li class="lifirst" style="text-decoration: underline;">Year : 2017</li>
                          <li>No Records found</li>
                        </ul>
                      </div>
                      <div class="col-md-4 col-lg-4">
                        <ul>
                          <li class="lifirst" style="text-decoration: underline;">Year : 2016</li>
                          <li>No Records found</li>
                        </ul>
                      </div>';
                    } 
                  }
                $output.='</div>';
		}
		elseif($system == "crm")
		{
			$output.= '
              <div class="title">Client Request Manager</div>';
                $i=1;
                $clientlist = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();
                $countoutstanding = 0;
                $awaiting_request_count = 0;
                $request_count_count = 0;
                if(count($clientlist)){              
                  foreach($clientlist as $key => $client){
                    $awaiting_request = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 0)->count();
                    $request_count = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 1)->count();
                    $awaiting_request_count = $awaiting_request_count + $awaiting_request;
                    $request_count_count = $request_count_count + $request_count;
                    $get_req = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 1)->get();
                    if(count($get_req))
                    {
                      foreach($get_req as $req)
                      {
                          $check_received_purchase = DB::table('request_purchase_invoice')->where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_purchase_attached = DB::table('request_purchase_attached')->where('request_id',$req->request_id)->where('status',0)->count(); 
                          $check_received_sales = DB::table('request_sales_invoice')->where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_sales_attached = DB::table('request_sales_attached')->where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_bank = DB::table('request_bank_statement')->where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_cheque = DB::table('request_cheque')->where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_cheque_attached = DB::table('request_cheque_attached')->where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_others = DB::table('request_others')->where('request_id',$req->request_id)->where('status',0)->count();
                          $check_purchase = DB::table('request_purchase_invoice')->where('request_id',$req->request_id)->count();
                          $check_purchase_attached = DB::table('request_purchase_attached')->where('request_id',$req->request_id)->count(); 
                          $check_sales = DB::table('request_sales_invoice')->where('request_id',$req->request_id)->count();
                          $check_sales_attached = DB::table('request_sales_attached')->where('request_id',$req->request_id)->count();
                          $check_bank = DB::table('request_bank_statement')->where('request_id',$req->request_id)->count();
                          $check_cheque = DB::table('request_cheque')->where('request_id',$req->request_id)->count();
                          $check_cheque_attached = DB::table('request_cheque_attached')->where('request_id',$req->request_id)->count();
                          $check_others = DB::table('request_others')->where('request_id',$req->request_id)->count();
                          $countval_not_received = $check_received_purchase + $check_received_purchase_attached + $check_received_sales + $check_received_sales_attached + $check_received_bank + $check_received_cheque + $check_received_cheque_attached + $check_received_others;
                          $countval = $check_purchase + $check_purchase_attached + $check_sales + $check_sales_attached + $check_bank + $check_cheque + $check_cheque_attached + $check_others;
                          if($countval_not_received != 0)
                          {
                            $countoutstanding++;
                          }
                      }
                    }
                  }
                }
              $output.='<div class="ul_list">                
                <ul>
                  <li>Total Requests : '.$request_count_count.'</li>
                  <li>Total Outstanding Requests : '.$countoutstanding.'</li>
                  <li>Total Awaiting Approval : '.$awaiting_request_count.'</li>
                </ul>
              </div>';
		}
		echo $output;
	}
	public function load_all_dashboard_tiles()
	{
		$output = '<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle cmsystem">
			      <div class="content">
			        <div class="title">Cm System</div>
			        <div class="ul_list">
			          <ul>';
			          $total_clients = DB::table('cm_clients')->count();
			          $active_cm_clients = DB::table('cm_clients')->where('active',1)->count();
			          
			            $output.='<li>Total  Clients : '.$total_clients.'</li>
			            <li>Active  Clients : '.$active_cm_clients.'</li>
			            <li><a href="'.URL::to('user/client_account_review').'" style="color:#fff">Client Account Review</a></li>
			          </ul>
			        </div>
			      </div>
			      <div class="icon">
			      	<a href="javascript:" class="fa fa-refresh load_system" data-element="cm"></a> 
			      	<img src="'.URL::to('assets/images/icon_cm_system.jpg') .'">
			      </div>            
			    </div>
			    <div class="more morecmsystem">
			          <a href="'.URL::to('user/client_management').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle week">
			      <div class="content">
			        <div class="title">Weekly Payroll</div>
			        <div class="ul_list">';
			        $current_week = DB::table('week')->orderBy('week_id','desc')->first();
			        $current_year = DB::table('year')->where('year_id',$current_week->year)->first();
			        $no_of_tasks = DB::table('task')->where('task_week',$current_week->week_id)->count();
			        $week_completed = DB::table('task')->where('task_week',$current_week->week_id)->where('task_status',1)->count();
			        $week_donot_completed = DB::table('task')->where('task_week',$current_week->week_id)->where('task_status',0)->where('task_complete_period',1)->count();
			        $week_incompleted = DB::table('task')->where('task_week',$current_week->week_id)->where('task_status',0)->where('task_complete_period',0)->count();
			        
			          $output.='<div class="sub-title">Week #'.$current_week->week.', '.$current_year->year_name.' - '.$no_of_tasks.' Tasks</div>
			          <ul>
			            <li>Completed Tasks : '.$week_completed.'</li>
			            <li>Donot Complete tasks : '.$week_donot_completed.'</li>
			            <li>Incomplete Tasks : '.$week_incompleted.'</li>
			          </ul>
			        </div>
			      </div>
			      <div class="icon">
			      	<a href="javascript:" class="fa fa-refresh load_system" data-element="week"></a> 
			      	<img src="'.URL::to('assets/images/icon_week_task.jpg').'">
			      </div>            
			    </div>
			    <div class="more moreweek">
			          <a href="'.URL::to('user/manage_week').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle month">
			      <div class="content">
			        <div class="title">Monthly Payroll</div>';
			        
			        $current_month = DB::table('month')->orderBy('month_id','desc')->first();
			        $current_year = DB::table('year')->where('year_id',$current_month->year)->first();
			        $no_of_tasks_month = DB::table('task')->where('task_month',$current_month->month_id)->count();
			        $week_completed_month = DB::table('task')->where('task_month',$current_month->month_id)->where('task_status',1)->count();
			        $week_donot_completed_month = DB::table('task')->where('task_month',$current_month->month_id)->where('task_status',0)->where('task_complete_period',1)->count();
			        $week_incompleted_month = DB::table('task')->where('task_month',$current_month->month_id)->where('task_status',0)->where('task_complete_period',0)->count();
			        
			        $output.='<div class="ul_list">
			          <div class="sub-title">Month #'.$current_month->month.', '.$current_year->year_name.' - '.$no_of_tasks_month.' Tasks</div>
			          <ul>
			            <li>Completed Tasks : '.$week_completed_month.'</li>
			            <li>Donot Complete tasks : '.$week_donot_completed_month.'</li>
			            <li>Incomplete Tasks : '.$week_incompleted_month.'</li>
			          </ul>
			        </div>
			      </div>
			      <div class="icon">
			      	<a href="javascript:" class="fa fa-refresh load_system" data-element="month"></a> 
			      	<img src="'.URL::to('assets/images/icon_month_task.jpg').'">
			      </div>            
			    </div>
			    <div class="more moremonth">
			          <a href="'.URL::to('user/manage_month').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle p30">
			      <div class="content">
			        <div class="title">P30 system</div>
			        <div class="ul_list">';
			        
			        $current_p30_month = DB::table('p30_month')->orderBy('month_id','desc')->first();
			        $current_year = DB::table('year')->where('year_id',$current_p30_month->year)->first();
			        $no_of_tasks_p30_month = DB::table('p30_task')->where('task_month',$current_p30_month->month_id)->count();
			        $week_completed_p30_month = DB::table('p30_task')->where('task_month',$current_p30_month->month_id)->where('task_status',1)->count();
			        $week_incompleted_p30_month = DB::table('p30_task')->where('task_month',$current_p30_month->month_id)->where('task_status',0)->count();
			        
			          $output.='<div class="sub-title">Month #'.$current_p30_month->month.', '.$current_year->year_name.' - '.$no_of_tasks_p30_month.' Tasks</div>
			          <ul>
			            <li>Completed Tasks : '.$week_completed_p30_month.'</li>
			            <li>Incomplete Tasks : '.$week_incompleted_p30_month.'</li>
			          </ul>
			        </div>
			      </div>
			      <div class="icon">
			      	<a href="javascript:" class="fa fa-refresh load_system" data-element="p30"></a> 
			      	<img src="'.URL::to('assets/images/icon_p30.jpg').'">
			      </div>            
			    </div>
			    <div class="more morep30">
			          <a href="'.URL::to('user/p30').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="clearfix"></div>
			<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle vat">
			      <div class="content">
			        <div class="title">VAT system</div>';
			        
			        $disabled_clients = DB::table('vat_clients')->where('status',1)->count();
			        $clients_email = DB::table('vat_clients')->where('status',0)->where('pemail','!=', '')->where('self_manage','no')->count();
			        $clients_without_email = DB::table('vat_clients')->where('status',0)->where('pemail', '')->where('self_manage','no')->count();
			        $self_manage = DB::table('vat_clients')->where('status',0)->where('self_manage','yes')->count();
			        
			        $output.='<div class="ul_list">                
			          <ul>
			            <li>Disabled Clients : '.$disabled_clients.'</li>
			            <li>Clients With Email : '.$clients_email.'</li>
			            <li>Clients Without Email: '.$clients_without_email.'</li>
			            <li>Self Managed  : '.$self_manage.'</li>
			          </ul>
			        </div>
			      </div>
			      <div class="icon">
			      	<a href="javascript:" class="fa fa-refresh load_system" data-element="vat"></a> 
			      	<img src="'.URL::to('assets/images/icon_vat.jpg').'">
			      </div>            
			    </div>
			    <div class="more morevat">
			          <a href="'.URL::to('user/vat_clients').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle infile">
			      <div class="content">
			        <div class="title">In Files System</div>';
			        $infile_client = DB::table('in_file')->select('id','client_id')->where('client_id','!=','')->get();
			        $array_clientid = array();
			        $file_count = 0;
			        if(count($infile_client))
			        {
			        	foreach($infile_client as $infile)
			        	{
			        		if(!in_array($infile->client_id,$array_clientid))
			        		{
			        			$check_attachment = DB::table('in_file_attachment')->where('file_id',$infile->id)->count();
			          		if($check_attachment > 0)
			          		{
			          			$file_count++;
			          			array_push($array_clientid, $infile->client_id);
			          		}
			        		}
			        	}
			        }
			       
			        $infile_complete = DB::table('in_file')->where('status', 1)->count();
			        $infile_incomplete = DB::table('in_file')->where('status', 0)->count();
			        
			        $output.='<div class="ul_list">                
			          <ul>
			            <li>No. of Clients with In Files : '.$file_count.'</li>
			            <li>No. of Complete In Files : '.$infile_complete.'</li>
			            <li>No. of InComplete In Files : '.$infile_incomplete.'</li>
			          </ul>
			        </div>
			      </div>
			      <div class="icon">
			      	<a href="javascript:" class="fa fa-refresh load_system" data-element="infile"></a> 
			      	<img src="'.URL::to('assets/images/infile_icon.jpg').'">
			      </div>            
			    </div>
			    <div class="more moreinfile">
			          <a href="'.URL::to('user/in_file_advance').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="col-lg-5">
			  <div class="dashboard">
			    <div class="dashboard_signle yearend">
			      <div class="content" style="width:100%">
			        <div class="title">Yearend System</div>
			          <div class="ul_list">';
			            $yearend_year = DB::table('year_end_year')->orderBy('id','desc')->limit(3)->get();
			            if(count($yearend_year))
			            {
			              foreach($yearend_year as $year)
			              {
			                $started = DB::table('year_client')->where('year',$year->year)->where('status',0)->count();
			                $in_progress = DB::table('year_client')->where('year',$year->year)->where('status',1)->count();
			                $completed = DB::table('year_client')->where('year',$year->year)->where('status',2)->count();
			                
			                  $output.='<div class="col-md-4 col-lg-4">
			                    <ul>
			                    <li class="lifirst" style="text-decoration: underline;">Year : '.$year->year.'</li>
			                    <li>Not Started :  '.$started.' Clients</li>
			                    <li>Inprogress : '.$in_progress.' Clients</li>
			                    <li>Completed : '.$completed.' Clients</li>
			                    </ul>
			                  </div>';
			              }
			              if(count($yearend_year) == 2)
			              {
			                $output.'<div class="col-md-4 col-lg-4">
			                  <ul>
			                    <li class="lifirst" style="text-decoration: underline;">Year : 2017</li>
			                    <li>No Records found</li>
			                  </ul>
			                </div>';
			              }
			              elseif(count($yearend_year) == 1)
			              {
			                $output.='<div class="col-md-4 col-lg-4">
			                  <ul>
			                    <li class="lifirst" style="text-decoration: underline;">Year : 2017</li>
			                    <li>No Records found</li>
			                  </ul>
			                </div>
			                <div class="col-md-4 col-lg-4">
			                  <ul>
			                    <li class="lifirst" style="text-decoration: underline;">Year : 2016</li>
			                    <li>No Records found</li>
			                  </ul>
			                </div>';
			              } 
			            }
			          $output.='</div>
			      </div> 
			      <a href="javascript:" class="fa fa-refresh load_system" data-element="yearend"></a>         
			    </div>
			    <div class="more moreyearend">
			          <a href="'.URL::to('user/year_end_manager').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>
			<div class="col-lg-3">
			  <div class="dashboard">
			    <div class="dashboard_signle crm">
			      <div class="content crm_content">
			        <div class="title">Client Request Manager</div>';
			          $i=1;
			          $clientlist = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();
			          $countoutstanding = 0;
			          $awaiting_request_count = 0;
			          $request_count_count = 0;
			          if(count($clientlist)){              
			            foreach($clientlist as $key => $client){
			              $awaiting_request = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 0)->count();
			              $request_count = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 1)->count();
			              $awaiting_request_count = $awaiting_request_count + $awaiting_request;
			              $request_count_count = $request_count_count + $request_count;
			              $get_req = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 1)->get();
			              if(count($get_req))
			              {
			                foreach($get_req as $req)
			                {
			                    $check_received_purchase = DB::table('request_purchase_invoice')->where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_received_purchase_attached = DB::table('request_purchase_attached')->where('request_id',$req->request_id)->where('status',0)->count(); 
			                    $check_received_sales = DB::table('request_sales_invoice')->where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_received_sales_attached = DB::table('request_sales_attached')->where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_received_bank = DB::table('request_bank_statement')->where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_received_cheque = DB::table('request_cheque')->where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_received_cheque_attached = DB::table('request_cheque_attached')->where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_received_others = DB::table('request_others')->where('request_id',$req->request_id)->where('status',0)->count();
			                    $check_purchase = DB::table('request_purchase_invoice')->where('request_id',$req->request_id)->count();
			                    $check_purchase_attached = DB::table('request_purchase_attached')->where('request_id',$req->request_id)->count(); 
			                    $check_sales = DB::table('request_sales_invoice')->where('request_id',$req->request_id)->count();
			                    $check_sales_attached = DB::table('request_sales_attached')->where('request_id',$req->request_id)->count();
			                    $check_bank = DB::table('request_bank_statement')->where('request_id',$req->request_id)->count();
			                    $check_cheque = DB::table('request_cheque')->where('request_id',$req->request_id)->count();
			                    $check_cheque_attached = DB::table('request_cheque_attached')->where('request_id',$req->request_id)->count();
			                    $check_others = DB::table('request_others')->where('request_id',$req->request_id)->count();
			                    $countval_not_received = $check_received_purchase + $check_received_purchase_attached + $check_received_sales + $check_received_sales_attached + $check_received_bank + $check_received_cheque + $check_received_cheque_attached + $check_received_others;
			                    $countval = $check_purchase + $check_purchase_attached + $check_sales + $check_sales_attached + $check_bank + $check_cheque + $check_cheque_attached + $check_others;
			                    if($countval_not_received != 0)
			                    {
			                      $countoutstanding++;
			                    }
			                }
			              }
			            }
			          }
			        $output.='<div class="ul_list">                
			          <ul>
			            <li>Total Requests : '.$request_count_count.'</li>
			            <li>Total Outstanding Requests : '.$countoutstanding.'</li>
			            <li>Total Awaiting Approval : '.$awaiting_request_count.'</li>
			          </ul>
			        </div>
			      </div>
			      <a href="javascript:" class="fa fa-refresh load_system" data-element="crm"></a>      
			    </div>
			    <div class="more morecrm">
			          <a href="'.URL::to('user/client_request_system').'"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
			      </div>
			  </div>
			</div>';
			echo $output;
	}
	public function change_period_vat_reviews()
	{
		$month = Input::get('month');
		$client = Input::get('client');
		$from = Input::get('from');
		$to = Input::get('to');

		$check_reviews = DB::table('vat_reviews')->where('client_id',$client)->where('month_year',$month)->where('type',2)->first();
		$data['client_id']= $client;
		$data['month_year']= $month;
		$data['type']= "2";
		$data['from_period'] = $from;
		$data['to_period'] = $to;
		if(count($check_reviews))
		{
			DB::table('vat_reviews')->where('id',$check_reviews->id)->update($data);
		}
		else{
			DB::table('vat_reviews')->insert($data);	
		}
	}
	public function check_submitted_date_vat_reviews()
	{
		$month = Input::get('month');
		$client = Input::get('client');
		$curr_date = date('d/m/Y');
		$data['client_id']= $client;
		$data['month_year']= $month;
		$data['type']= "3";
		$data['textval'] = $curr_date;
		$data['status'] = "0";

		$check_reviews = DB::table('vat_reviews')->where('client_id',$client)->where('month_year',$month)->where('type',3)->first();
		if(count($check_reviews))
		{
			DB::table('vat_reviews')->where('id',$check_reviews->id)->update($data);
		}
		else{
			DB::table('vat_reviews')->insert($data);	
		}

		echo $curr_date;
	}
	public function check_valid_ros_due()
	{
		$filename = Input::get('filename');
		$uploads_dir = 'uploads/vat_reviews/ros_vat_due';
		$filepath = $uploads_dir.'/'.$filename;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);
		$i = 0;
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			$height = $highestRow;

			$client_name_header = $worksheet->getCellByColumnAndRow(0, 1); $client_name_header = trim($client_name_header->getValue());
			$ros_filer_header = $worksheet->getCellByColumnAndRow(1, 1); $ros_filer_header = trim($ros_filer_header->getValue());
			$tax_type_header = $worksheet->getCellByColumnAndRow(2, 1); $tax_type_header = trim($tax_type_header->getValue());
			$doc_type_header = $worksheet->getCellByColumnAndRow(3, 1); $doc_type_header = trim($doc_type_header->getValue());
			$tax_no_header = $worksheet->getCellByColumnAndRow(4, 1); $tax_no_header = trim($tax_no_header->getValue());
			$period_header = $worksheet->getCellByColumnAndRow(5, 1); $period_header = trim($period_header->getValue());
			$due_date_header = $worksheet->getCellByColumnAndRow(6, 1); $due_date_header = trim($due_date_header->getValue());
			if($client_name_header == "Customer Name" && $period_header == "Period" && $due_date_header == "Due Date")
			{
				$client_name = $worksheet->getCellByColumnAndRow(0, 2); $client_name = trim($client_name->getValue());
				$ros_filter = $worksheet->getCellByColumnAndRow(1, 2); $ros_filter = trim($ros_filter->getValue());
				$tax_type = $worksheet->getCellByColumnAndRow(2, 2); $tax_type = trim($tax_type->getValue());
				$doc_type = $worksheet->getCellByColumnAndRow(3, 2); $doc_type = trim($doc_type->getValue());
				$tax_no = $worksheet->getCellByColumnAndRow(4, 2);  $tax_no = trim($tax_no->getValue());
				$period = $worksheet->getCellByColumnAndRow(5, 2);  $period = trim($period->getValue());
				$due_date = $worksheet->getCellByColumnAndRow(6, 2);  $due_date = trim($due_date->getValue());
				if($tax_type == "VAT" && $doc_type == "VAT3")
				{
					$i = $i + 1;
				}
			}
		}
		$output = '';
		if($i > 0)
		{
			$output.='<tr>
				<td>'.$filename.'</td>
				<td>'.date('d/m/Y').'</td>
				<td>'.date('H:i:s').'</td>
			</tr>';
		}
		echo $output;
	}
	public function process_vat_reviews()
	{
		$filename = Input::get('filename');
		$load_all = Input::get('load_all');

		$get_id = DB::table('vat_reviews_import_attachment')->where('status',1)->get();
		$data['import_id'] = count($get_id) + 1;
		$data['status'] = 1;
		DB::table('vat_reviews_import_attachment')->where('filename',$filename)->update($data);

		$uploads_dir = 'uploads/vat_reviews/ros_vat_due';
		$filepath = $uploads_dir.'/'.$filename;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);
		$i = 0;
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

			$client_name_header = $worksheet->getCellByColumnAndRow(0, 1); $client_name_header = trim($client_name_header->getValue());
			$ros_filer_header = $worksheet->getCellByColumnAndRow(1, 1); $ros_filer_header = trim($ros_filer_header->getValue());
			$tax_type_header = $worksheet->getCellByColumnAndRow(2, 1); $tax_type_header = trim($tax_type_header->getValue());
			$doc_type_header = $worksheet->getCellByColumnAndRow(3, 1); $doc_type_header = trim($doc_type_header->getValue());
			$tax_no_header = $worksheet->getCellByColumnAndRow(4, 1); $tax_no_header = trim($tax_no_header->getValue());
			$period_header = $worksheet->getCellByColumnAndRow(5, 1); $period_header = trim($period_header->getValue());
			$due_date_header = $worksheet->getCellByColumnAndRow(6, 1); $due_date_header = trim($due_date_header->getValue());
			if($client_name_header == "Customer Name" && $period_header == "Period" && $due_date_header == "Due Date")
			{
				for ($row = 2; $row <= $height; ++ $row) {
					$client_name = $worksheet->getCellByColumnAndRow(0,$row); $client_name = trim($client_name->getValue());
					$ros_filter = $worksheet->getCellByColumnAndRow(1,$row); $ros_filter = trim($ros_filter->getValue());
					$tax_type = $worksheet->getCellByColumnAndRow(2,$row); $tax_type = trim($tax_type->getValue());
					$doc_type = $worksheet->getCellByColumnAndRow(3,$row); $doc_type = trim($doc_type->getValue());
					$tax_no = $worksheet->getCellByColumnAndRow(4,$row);  $tax_no = trim($tax_no->getValue());
					$period = $worksheet->getCellByColumnAndRow(5,$row);  $period = trim($period->getValue());
					$due_date = $worksheet->getCellByColumnAndRow(6,$row);  $due_date = trim($due_date->getValue());
					if($tax_type == "VAT" && $doc_type == "VAT3")
					{
						$check_tax = DB::table('vat_clients')->where('taxnumber',$tax_no)->first();
						if(count($check_tax))
						{
							$due_month_year = '';
							$exp_due_date = explode("/",$due_date);
							if(count($exp_due_date) > 2)
							{
								$due_month_year =  $exp_due_date[1].'-'.$exp_due_date[2];
							}
							else{
								$exp_due_date_hyphen = explode("-",$due_date);
								if(count($exp_due_date_hyphen) > 2)
								{
									$due_month_year =  $exp_due_date_hyphen[1].'-'.$exp_due_date_hyphen[2];
								}
							}
							$from_period = '';
							$to_period = '';

							$exp_full_period = explode("-",$period);
							if(count($exp_full_period) == 2)
							{
								$exp_from_period = explode("/",trim($exp_full_period[0]));
								if(count($exp_from_period) == 3)
								{
									if($exp_from_period[1] == "01") { $from_month = 'Jan'; }
									elseif($exp_from_period[1] == "02") { $from_month = 'Feb'; }
									elseif($exp_from_period[1] == "03") { $from_month = 'Mar'; }
									elseif($exp_from_period[1] == "04") { $from_month = 'Apr'; }
									elseif($exp_from_period[1] == "05") { $from_month = 'May'; }
									elseif($exp_from_period[1] == "06") { $from_month = 'Jun'; }
									elseif($exp_from_period[1] == "07") { $from_month = 'Jul'; }
									elseif($exp_from_period[1] == "08") { $from_month = 'Aug'; }
									elseif($exp_from_period[1] == "09") { $from_month = 'Sep'; }
									elseif($exp_from_period[1] == "10") { $from_month = 'Oct'; }
									elseif($exp_from_period[1] == "11") { $from_month = 'Nov'; }
									elseif($exp_from_period[1] == "12") { $from_month = 'Dec'; }

									$from_period = $from_month.'-'.$exp_from_period[2];
								}
								$exp_to_period = explode("/",trim($exp_full_period[1]));
								if(count($exp_from_period) == 3)
								{
									if($exp_to_period[1] == "01") { $to_month = 'Jan'; }
									elseif($exp_to_period[1] == "02") { $to_month = 'Feb'; }
									elseif($exp_to_period[1] == "03") { $to_month = 'Mar'; }
									elseif($exp_to_period[1] == "04") { $to_month = 'Apr'; }
									elseif($exp_to_period[1] == "05") { $to_month = 'May'; }
									elseif($exp_to_period[1] == "06") { $to_month = 'Jun'; }
									elseif($exp_to_period[1] == "07") { $to_month = 'Jul'; }
									elseif($exp_to_period[1] == "08") { $to_month = 'Aug'; }
									elseif($exp_to_period[1] == "09") { $to_month = 'Sep'; }
									elseif($exp_to_period[1] == "10") { $to_month = 'Oct'; }
									elseif($exp_to_period[1] == "11") { $to_month = 'Nov'; }
									elseif($exp_to_period[1] == "12") { $to_month = 'Dec'; }
									$to_period = $to_month.'-'.$exp_to_period[2];
								}
							}

							$check_submitted = DB::table('vat_reviews')->where('client_id',$check_tax->client_id)->where('month_year',$due_month_year)->where('type',2)->first();
							if(count($check_submitted))
							{
								$dataval['from_period'] = $from_period;
								$dataval['to_period'] = $to_period;
								DB::table('vat_reviews')->where('id',$check_submitted->id)->update($dataval);
							}
							else{
								$dataval['client_id'] = $check_tax->client_id;
								$dataval['month_year'] = $due_month_year;
								$dataval['type'] = 2;
								$dataval['from_period'] = $from_period;
								$dataval['to_period'] = $to_period;
								DB::table('vat_reviews')->insert($dataval);
							}

							$check_submitted = DB::table('vat_reviews')->where('client_id',$check_tax->client_id)->where('month_year',$due_month_year)->where('type',4)->first();

							if(count($check_submitted))
							{
								$dataid['textval'] = $data['import_id'];
								DB::table('vat_reviews')->where('id',$check_submitted->id)->update($dataid);
							}
							else{
								$dataid['client_id'] = $check_tax->client_id;
								$dataid['month_year'] = $due_month_year;
								$dataid['type'] = 4;
								$dataid['textval'] = $data['import_id'];
								DB::table('vat_reviews')->insert($dataid);
							}
						}
					}
				}
			}
		}
		if($height >= $highestRow)
		{
			if($load_all == "1")
			{
				return redirect('user/vat_review')->with('message_import', 'Please click on LOAD ALL CLIENTS  to view the result on the VAT Management System VAT Review Tab to See Successfully Imported ROS Files.')->with("load_all","1");
			}
			else{
				return redirect('user/vat_review')->with('message_import', 'Please click on LOAD ALL CLIENTS  to view the result on the VAT Management System VAT Review Tab to See Successfully Imported ROS Files.');
			}
		}
		else{
			return redirect('user/vat_review?filename='.$filename.'&height='.$height.'&round=2&highestrow='.$highestRow.'&import_type=1&load_all='.$load_all.'');
		}
	}
	public function process_vat_reviews_one()
	{
		$filename = Input::get('filename');
		$load_all = Input::get('load_all');

		$get_id = DB::table('vat_reviews_import_attachment')->where('status',1)->get();
		$data['import_id'] = count($get_id) + 1;
		DB::table('vat_reviews_import_attachment')->where('filename',$filename)->update($data);

		$uploads_dir = 'uploads/vat_reviews/ros_vat_due';
		$filepath = $uploads_dir.'/'.$filename;
		$objPHPExcel = PHPExcel_IOFactory::load($filepath);
		$i = 0;
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

			for ($row = $offsetcount; $row <= $height; ++ $row) {
				$client_name = $worksheet->getCellByColumnAndRow(0,$row); $client_name = trim($client_name->getValue());
				$ros_filter = $worksheet->getCellByColumnAndRow(1,$row); $ros_filter = trim($ros_filter->getValue());
				$tax_type = $worksheet->getCellByColumnAndRow(2,$row); $tax_type = trim($tax_type->getValue());
				$doc_type = $worksheet->getCellByColumnAndRow(3,$row); $doc_type = trim($doc_type->getValue());
				$tax_no = $worksheet->getCellByColumnAndRow(4,$row);  $tax_no = trim($tax_no->getValue());
				$period = $worksheet->getCellByColumnAndRow(5,$row);  $period = trim($period->getValue());
				$due_date = $worksheet->getCellByColumnAndRow(6,$row);  $due_date = trim($due_date->getValue());
				if($tax_type == "VAT" && $doc_type == "VAT3")
				{
					$check_tax = DB::table('vat_clients')->where('taxnumber',$tax_no)->first();
					if(count($check_tax))
					{
						$due_month_year = '';
						$exp_due_date = explode("/",$due_date);
						if(count($exp_due_date) > 2)
						{
							$due_month_year =  $exp_due_date[1].'-'.$exp_due_date[2];
						}
						else{
							$exp_due_date_hyphen = explode("-",$due_date);
							if(count($exp_due_date_hyphen) > 2)
							{
								$due_month_year =  $exp_due_date_hyphen[1].'-'.$exp_due_date_hyphen[2];
							}
						}
						$from_period = '';
						$to_period = '';

						$exp_full_period = explode("-",$period);
						if(count($exp_full_period) == 2)
						{
							$exp_from_period = explode("/",trim($exp_full_period[0]));
							if(count($exp_from_period) == 3)
							{
								if($exp_from_period[1] == "01") { $from_month = 'Jan'; }
								elseif($exp_from_period[1] == "02") { $from_month = 'Feb'; }
								elseif($exp_from_period[1] == "03") { $from_month = 'Mar'; }
								elseif($exp_from_period[1] == "04") { $from_month = 'Apr'; }
								elseif($exp_from_period[1] == "05") { $from_month = 'May'; }
								elseif($exp_from_period[1] == "06") { $from_month = 'Jun'; }
								elseif($exp_from_period[1] == "07") { $from_month = 'Jul'; }
								elseif($exp_from_period[1] == "08") { $from_month = 'Aug'; }
								elseif($exp_from_period[1] == "09") { $from_month = 'Sep'; }
								elseif($exp_from_period[1] == "10") { $from_month = 'Oct'; }
								elseif($exp_from_period[1] == "11") { $from_month = 'Nov'; }
								elseif($exp_from_period[1] == "12") { $from_month = 'Dec'; }

								$from_period = $from_month.'-'.$exp_from_period[2];
							}
							$exp_to_period = explode("/",trim($exp_full_period[1]));
							if(count($exp_from_period) == 3)
							{
								if($exp_to_period[1] == "01") { $to_month = 'Jan'; }
								elseif($exp_to_period[1] == "02") { $to_month = 'Feb'; }
								elseif($exp_to_period[1] == "03") { $to_month = 'Mar'; }
								elseif($exp_to_period[1] == "04") { $to_month = 'Apr'; }
								elseif($exp_to_period[1] == "05") { $to_month = 'May'; }
								elseif($exp_to_period[1] == "06") { $to_month = 'Jun'; }
								elseif($exp_to_period[1] == "07") { $to_month = 'Jul'; }
								elseif($exp_to_period[1] == "08") { $to_month = 'Aug'; }
								elseif($exp_to_period[1] == "09") { $to_month = 'Sep'; }
								elseif($exp_to_period[1] == "10") { $to_month = 'Oct'; }
								elseif($exp_to_period[1] == "11") { $to_month = 'Nov'; }
								elseif($exp_to_period[1] == "12") { $to_month = 'Dec'; }
								$to_period = $to_month.'-'.$exp_to_period[2];
							}
						}

						$check_submitted = DB::table('vat_reviews')->where('client_id',$check_tax->client_id)->where('month_year',$due_month_year)->where('type',2)->first();
						if(count($check_submited))
						{
							$dataval['from_period'] = $from_period;
							$dataval['to_period'] = $to_period;
							DB::table('vat_reviews')->where('id',$check_submited->id)->update($dataval);
						}
						else{
							$dataval['client_id'] = $check_tax->client_id;
							$dataval['month_year'] = $$due_month_year;
							$dataval['type'] = 2;
							$dataval['from_period'] = $from_period;
							$dataval['to_period'] = $to_period;
							DB::table('vat_reviews')->insert($dataval);
						}

						$check_submitted = DB::table('vat_reviews')->where('client_id',$check_tax->client_id)->where('month_year',$due_month_year)->where('type',4)->first();
						if(count($check_submited))
						{
							$dataid['textval'] = $data['import_id'];
							DB::table('vat_reviews')->where('id',$check_submited->id)->update($dataid);
						}
						else{
							$dataid['client_id'] = $check_tax->client_id;
							$dataid['month_year'] = $$due_month_year;
							$dataid['type'] = 4;
							$dataid['textval'] = $data['import_id'];
							DB::table('vat_reviews')->insert($dataid);
						}
					}
				}
			}
		}
		if($height >= $highestRow)
		{
			if($load_all == "1")
			{
				return redirect('user/vat_review')->with('message_import', 'Please click on LOAD ALL CLIENTS  to view the result on the VAT Management System VAT Review Tab to See Successfully Imported ROS Files.')->with("load_all","1");
			}
			else{
				return redirect('user/vat_review')->with('message_import', 'Please click on LOAD ALL CLIENTS  to view the result on the VAT Management System VAT Review Tab to See Successfully Imported ROS Files.');
			}
		}
		else{
			return redirect('user/vat_review?filename='.$filename.'&height='.$height.'&round='.$nextround.'&highestrow='.$highestRow.'&import_type=1&load_all='.$load_all.'');
		}
	}
	public function remove_vat_csv($id = '')
	{
		DB::table('vat_reviews_import_attachment')->where('id',$id)->delete();
	}
	public function delete_submitted_vat_review()
	{
		$month = Input::get('month');
		$client = Input::get('client');
		DB::table('vat_reviews')->where('month_year',$month)->where('client_id',$client)->where('type',3)->delete();
	}
	public function update_records_received()
	{
		$client = Input::get('client_id');
		$month = Input::get('month');
		$type = Input::get('type');
		if($type == "1")
		{
			$data['client_id'] = $client;
			$data['month_year'] = $month;
			$data['type'] = 6;
			$data['textval'] = 1;
			DB::table('vat_reviews')->insert($data);
		}
		else{
			DB::table('vat_reviews')->where('client_id',$client)->where('month_year',$month)->where('type',6)->delete();
		}
	}
	public function update_email_setting()
	{
		$email = Input::get('email');
		$ccemail = Input::get('ccemail');

		$taskccemail = Input::get('taskccemail');
		$p30ccemail = Input::get('p30ccemail');
		$cmccemail = Input::get('cmccemail');
		$vatccemail = Input::get('vatccemail');

		$deleteemail = Input::get('deleteemail');
		
		DB::table('admin')->where('id',1)->update(['email' =>$email, 'cc_email' =>$ccemail,'task_cc_email' =>$taskccemail,'p30_cc_email' =>$p30ccemail,'cm_cc_email' =>$cmccemail,'vat_cc_email' =>$vatccemail,'delete_email' =>$deleteemail]);
		return Redirect::back()->with('message', 'Email Settings Updated Successfully');
	}
	public function show_journal_viewer_by_journal_id()
	{
		$journal_id = Input::get('journal_id');
		$details = DB::table('journals')->where('connecting_journal_reference',$journal_id)->first();
		$get_details = DB::table('journals')->where('reference',$details->reference)->orderBy('connecting_journal_reference','asc')->get();
		$output = '';
		$total_debit_value = 0;
		$total_credit_value = 0;
		if(count($get_details))
		{
			foreach($get_details as $detail)
			{
				$nominal_des = DB::table('nominal_codes')->where('code',$detail->nominal_code)->first();
				$desval = '';
				if(count($nominal_des))
				{
					$desval = $nominal_des->description;
				}
				$output.='<tr>
					<td>'.$detail->connecting_journal_reference.'</td>
					<td>'.date('d-M-Y', strtotime($detail->journal_date)).'</td>
					<td>'.$detail->description.'</td>
					<td><a href="javascript:" class="journal_source_link">'.$detail->journal_source.'</a></td>
					<td>'.$detail->nominal_code.'</td>
					<td>'.$desval.'</td>
					<td style="text-align: right;">'.number_format_invoice_empty($detail->dr_value).'</td>
					<td style="text-align: right;">'.number_format_invoice_empty($detail->cr_value).'</td>
				</tr>';
				$total_debit_value = $total_debit_value + number_format_invoice_without_comma($detail->dr_value);
				$total_credit_value = $total_credit_value + number_format_invoice_without_comma($detail->cr_value);
			}
		}

		echo json_encode(array("output" => $output, "total_debit" => number_format_invoice_empty($total_debit_value), "total_credit" => number_format_invoice_empty($total_credit_value)));
	}
	public function download_journal_viewer_by_journal_id()
	{
		$journal_id = Input::get('journal_id');
		$details = DB::table('journals')->where('connecting_journal_reference',$journal_id)->first();
		$get_details = DB::table('journals')->where('reference',$details->reference)->get();
		$exp_journal = explode(".",$journal_id);
		$output = '
		<h5 style="text-align:center">Journal Viewer for Journal Reference ID - '.$exp_journal[0].'</h5>
		<table style="width: 100%;border-collapse:collapse">
          <tr>
            <td style="text-align: left;border:1px solid #000;padding:5px">Journal ID</td>
            <td style="text-align: left;border:1px solid #000;padding:5px">Date</td>
            <td style="text-align: left;border:1px solid #000;padding:5px">Journal Description</td>
            <td style="text-align: left;border:1px solid #000;padding:5px">Journal Source</td>
            <td style="text-align: left;border:1px solid #000;padding:5px">Nominal Code</td>
            <td style="text-align: left;border:1px solid #000;padding:5px">Nominal Description</td>
            <td style="text-align: right;border:1px solid #000;padding:5px">Debit Value</td>
            <td style="text-align: right;border:1px solid #000;padding:5px">Credit Value</td>
          </tr>';
		$total_debit_value = 0;
		$total_credit_value = 0;
		if(count($get_details))
		{
			foreach($get_details as $detail)
			{
				$nominal_des = DB::table('nominal_codes')->where('code',$detail->nominal_code)->first();
				$output.='<tr>
					<td style="text-align: left;border:1px solid #000;padding:5px">'.$detail->connecting_journal_reference.'</td>
					<td style="text-align: left;border:1px solid #000;padding:5px">'.date('d-M-Y', strtotime($detail->journal_date)).'</td>
					<td style="text-align: left;border:1px solid #000;padding:5px">'.$detail->description.'</td>
					<td style="text-align: left;border:1px solid #000;padding:5px">'.$detail->journal_source.'</td>
					<td style="text-align: left;border:1px solid #000;padding:5px">'.$detail->nominal_code.'</td>
					<td style="text-align: left;border:1px solid #000;padding:5px">'.$nominal_des->description.'</td>
					<td style="text-align: right;border:1px solid #000;padding:5px">'.number_format_invoice_empty($detail->dr_value).'</td>
					<td style="text-align: right;border:1px solid #000;padding:5px">'.number_format_invoice_empty($detail->cr_value).'</td>
				</tr>';
				$total_debit_value = $total_debit_value + number_format_invoice_without_comma($detail->dr_value);
				$total_credit_value = $total_credit_value + number_format_invoice_without_comma($detail->cr_value);
			}
		}
		$output.='<tr>
			<td style="text-align: left;border:1px solid #000;padding:5px"></td>
			<td style="text-align: left;border:1px solid #000;padding:5px"></td>
			<td style="text-align: left;border:1px solid #000;padding:5px"></td>
			<td style="text-align: left;border:1px solid #000;padding:5px"></td>
			<td style="text-align: left;border:1px solid #000;padding:5px"></td>
			<td style="text-align: left;border:1px solid #000;font-weight:800;padding:5px">TOTAL</td>
			<td style="text-align: right;border:1px solid #000;font-weight:800;padding:5px">'.number_format_invoice_empty($total_debit_value).'</td>
			<td style="text-align: right;border:1px solid #000;font-weight:800;padding:5px">'.number_format_invoice_empty($total_credit_value).'</td>
		</tr>';

		$pdf = PDF::loadHTML($output);
		$pdf->save('papers/Journal Viewer for Journal reference ID '.$journal_id.'.pdf');
		echo 'Journal Viewer for Journal reference ID '.$journal_id.'.pdf';
	}
	public function get_client_review_for_year()
	{
		$year = Input::get('year');
		$prev_year = $year - 1;
		$next_year = $year + 1;
		$client_id = Input::get('client_id');
		$output ='';
		$client = DB::table('vat_clients')->where('client_id',$client_id)->first();
		for($ival = 0; $ival <= 13; $ival++)
		{
			$month_year_val = '';
			if($ival == 0) { $month = '12-'.$prev_year; $month_year_val = 'Dec - '.$prev_year; }
			elseif($ival == 13) { $month = '01-'.$next_year; $month_year_val = 'Jan - '.$next_year; }
			else{ 
				if($ival == 1) { $month_year_val = 'Jan - '.$year; }
				elseif($ival == 2) { $month_year_val = 'Feb - '.$year; }
				elseif($ival == 3) { $month_year_val = 'Mar - '.$year; }
				elseif($ival == 4) { $month_year_val = 'Apr - '.$year; }
				elseif($ival == 5) { $month_year_val = 'May - '.$year; }
				elseif($ival == 6) { $month_year_val = 'Jun - '.$year; }
				elseif($ival == 7) { $month_year_val = 'Jul - '.$year; }
				elseif($ival == 8) { $month_year_val = 'Aug - '.$year; }
				elseif($ival == 9) { $month_year_val = 'Sep - '.$year; }
				elseif($ival == 10) { $month_year_val = 'Oct - '.$year; }
				elseif($ival == 11) { $month_year_val = 'Nov - '.$year; }
				elseif($ival == 12) { $month_year_val = 'Dec - '.$year; }

				if($ival < 10) { $ivali = '0'.$ival; }
				else { $ivali = $ival; }
				$month = $ivali.'-'.$year;
			}
			$get_date = '01';
			$month_year = explode("-",$month);
			$get_full_date = $month_year[1].'-'.$month_year[0].'-'.$get_date;

			$currr_month = date('m-Y',strtotime($get_full_date));
			$current_str = strtotime(date('Y-m-01'));
			$curr_str = strtotime($get_full_date);

			$curr_attachment_div = '';
			$curr_text_one = 'No Period';
			$curr_text_two = '';
			$curr_t1 = '<p>T1: <spam class="t1_spam_overlay"></spam></p>';
			$curr_t2 = '<p>T2: <spam class="t2_spam_overlay"></spam></p>';
			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:left;display:none"></a>';
			$curr_text_three = '';
			$currr_text_three = '';
			$curr_color_status = '';
			$curr_color_text = '';
			$curr_check_box_color = 'black_td';
			$curr_checked = '';
			$class_tr = 'non_returning';

			$get_latest_import_file_id = DB::table('vat_reviews_import_attachment')->where('status',1)->orderBy('id','desc')->first();
        	if(count($get_latest_import_file_id))
        	{
        		$latest_import_id = $get_latest_import_file_id->import_id;
        	}

        	$check_reviews_curr = DB::table('vat_reviews')->where('client_id',$client->client_id)->where('month_year',$currr_month)->get();
            if(count($check_reviews_curr))
            {
            	$i= 0; $j=0;$k=0;
            	foreach($check_reviews_curr as $curr)
            	{
            		if($curr->type == 1){ 
            			$curr_attachment_div.= '<p><a href="'.URL::to($curr->url.'/'.$curr->filename).'" class="file_attachments" download>'.$curr->filename.'</a> <a href="'.URL::to('user/delete_vat_files?file_id='.$curr->id.'').'" class="fa fa-trash delete_attachments" data-client="'.$client->client_id.'" data-element="'.$currr_month.'"></a></p>';
            			$curr_t1 ='<p>T1: <spam class="t1_spam_overlay">'.$curr->t1.'</spam></p>';
						$curr_t2 ='<p>T2: <spam class="t2_spam_overlay">'.$curr->t2.'</spam></p>'; 
            		}
            		if($curr->type == 2){ $curr_text_one = $curr->from_period.' to '.$curr->to_period; }

            		if($curr->type == 3){ 
            			$curr_text_two = $curr->textval; 
            			$curr_color_status = 'green_import_overlay'; 
            			$curr_color_text = 'Submitted';
            			$class_tr = ''; 
            			$curr_check_box_color = 'submitted_td_overlay';
            			$i = $i + 1; 
            			$curr_remove_two = '<a href="javascript:" class="fa fa-times delete_submitted_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:left"></a>';
            		}

            		if($curr->type == 4){ 
            			$get_attachment_download = DB::table('vat_reviews_import_attachment')->where('import_id',$curr->textval)->first();
            			if(count($get_attachment_download))
            			{
            				$currr_text_three = $curr->textval;
            				$curr_text_three = '<a class="import_file_attachment_id_overlay" href="'.URL::to($get_attachment_download->url.'/'.$get_attachment_download->filename).'" style="float:right" download>'.$curr->textval.'</a>'; 
            			}
            			
            			$j = $j + 1;
            		}
            		if($curr->type == 6)
            		{
            			$curr_checked = 'checked';
            			$k = $k + 1;
            		}
            	}
            	if($j > 0 && $i == 0)
            	{
            		if($latest_import_id == $currr_text_three)
            		{
            			if($current_str > $curr_str)
            			{
            				$curr_color_status = 'red_import_overlay'; 
            				$curr_color_text = 'Submission O/S';
            				$curr_check_box_color = 'os_td_overlay';
            			}
            			else if($current_str == $curr_str)
            			{
            				$curr_color_status = 'orange_import_overlay'; 
            				$curr_color_text = 'Submission Due';
            				$class_tr = ''; 
            				$curr_check_box_color = 'due_td_overlay';
            			}
            			else if($current_str < $curr_str)
            			{
            				$curr_color_status = 'white_import_overlay'; 
            				$curr_color_text = 'Not Due';
            				$curr_check_box_color = 'not_due_td_overlay';
            			}
            		}
            		else{
            			$curr_color_status = 'blue_import_overlay'; 
            			$curr_color_text = 'Potentially Submitted';
            			$curr_check_box_color = 'ps_td_overlay';
            		}
            	}
            }
            
            if($client->status == 1) { $fontcolor = 'red'; }
            elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
            elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = '#bd510a'; }
            elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
            else{$fontcolor = '#fff';}
            if($curr_attachment_div == "")
            {
            	$attach_disabled = 'disabled';
            }
            else{
            	$attach_disabled = '';
            }

			$output.='<tr class="shownn_tr shown_tr shown_tr_'.$client->client_id.'_'.$currr_month.' '.$class_tr.'">
				<td><input type="checkbox" name="month_download_checkbox" class="month_download_checkbox" id="cli_review_'.$client->client_id.'_'.$currr_month.'" data-client="'.$client->client_id.'" data-month="'.$currr_month.'" '.$attach_disabled.'><label for="cli_review_'.$client->client_id.'_'.$currr_month.'">'.$month_year_val.'</label><input type="hidden" name="month_year_sort_val" class="month_year_sort_val" value="'.strtotime($get_full_date).'"></td>
				<td><label class="import_icon_overlay '.$curr_color_status.'">'.$curr_color_text.'</label></td>
				<td class="period_sort_val">'.$curr_text_one.'</td>
				<td>'.$curr_remove_two.'<input type="text" class="form-control submitted_import_overlay" data-client="'.$client->client_id.'" data-element="'.$currr_month.'" style="float:right" value="'.$curr_text_two.'">
					<input type="hidden" name="date_sort_val" class="date_sort_val" value="'.$curr_text_two.'">
				</td>
				<td><a href="javascript:" class="fa fa-plus add_attachment_month_year_overlay" data-element="'.$currr_month.'" data-client="'.$client->client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a> <div class="attachment_div_overlay">'.$curr_attachment_div.' '.$curr_t1.' '.$curr_t2.'</div></td>
				<td><input type="checkbox" class="check_records_received_overlay" id="check_records_received_overlay" data-month="'.$currr_month.'" data-client="'.$client->client_id.'" '.$curr_checked.'><label for="" class="records_receive_label_overlay '.$curr_check_box_color.' '.$curr_checked.'">Records Received</label>
					<input type="hidden" name="record_sort_val" class="record_sort_val" value="'.$curr_checked.'">
				</td>
			</tr>';
		}
		echo $output;
	}
	public function download_selected_periods_vat_attachments()
	{
		$client_id = Input::get('client_id');
		$months = explode(",",Input::get('months'));
		$client_details = DB::table('vat_clients')->where('client_id',$client_id)->first();
		$files = DB::table('vat_reviews')->whereIn('month_year',$months)->where('client_id',$client_id)->where('type',1)->get();
		if(count($files))
		{
			$company_name = str_replace("/", "", $client_details->name);
			$company_name = str_replace("/", "", $company_name);
			$company_name = str_replace("/", "", $company_name);

			$company_name = str_replace("(", "", $company_name);
			$company_name = str_replace("(", "", $company_name);
			$company_name = str_replace("(", "", $company_name);
			$company_name = str_replace("(", "", $company_name);

			$company_name = str_replace(")", "", $company_name);
			$company_name = str_replace(")", "", $company_name);
			$company_name = str_replace(")", "", $company_name);
			$company_name = str_replace(")", "", $company_name);

			$company_name = str_replace("&", "", $company_name);
			$company_name = str_replace("&", "", $company_name);
			$company_name = str_replace("&", "", $company_name);
			$company_name = str_replace("&", "", $company_name);

			$company_name = str_replace("?", "", $company_name);
			$company_name = str_replace("?", "", $company_name);
			$company_name = str_replace("?", "", $company_name);
			$company_name = str_replace("?", "", $company_name);

			$public_dir=public_path();
			$zipFileName = 'Client Review Attachments for '.$company_name.'_'.time().'.zip';
			foreach($files as $file)
			{
	            $zip = new ZipArchive;
	            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
	            	// Add File in ZipArchive
	                $zip->addFile($file->url.'/'.$file->filename,$file->filename);
	                // Close ZipArchive     
	                $zip->close();
	            }
			}
			echo $zipFileName;
		}
	}
	public function payroll_settings()
	{
		return view('user/emailsettings', array('title' => 'Payroll Settings'));
	}
	public function save_payroll_settings()
	{
		$signature = Input::get('message_editor');
		$cc = Input::get('payroll_cc_input');

		$data['payroll_signature'] = $signature;
		$data['payroll_cc_email'] = $cc;

		DB::table('admin')->where('id',1)->update($data);

		return Redirect::back()->with('message',"Payroll Settings Saved successfully.");
	}
	public function update_practice_setting()
	{
		$data['practice_name'] = (Input::get('practice_name') != "")?Input::get('practice_name'):'';
		$data['address_1'] = (Input::get('address_1') != "")?Input::get('address_1'):'';
		$data['address_2'] = (Input::get('address_2') != "")?Input::get('address_2'):'';
		$data['address_3'] = (Input::get('address_3') != "")?Input::get('address_3'):'';
		$data['address_4'] = (Input::get('address_4') != "")?Input::get('address_4'):'';
		$data['link_1'] = (Input::get('link_1') != "")?Input::get('link_1'):'';
		$data['link_2'] = (Input::get('link_2') != "")?Input::get('link_2'):'';
		$data['link_3'] = (Input::get('link_3') != "")?Input::get('link_3'):'';
		$data['phone_no'] = (Input::get('phone_no') != "")?Input::get('phone_no'):'';


		$check_statement = DB::table('settings')->where('source','practice')->first();
		
		$dataval['source'] = 'practice';
		$dataval['settings'] = serialize($data);
		$dataval['fields'] = 'practice_name,address_1,address_2,address_3,address_4,link_1,link_2,link_3,phone_no';
		
		if(count($check_statement))
		{
			DB::table('settings')->where('id',$check_statement->id)->update($dataval);
		}
		else{
			DB::table('settings')->insert($dataval);
		}
		return Redirect::back()->with('message','Settings Saved Successfully');
	}
	public function reset_vat_reviews_folder() {
		$dir = 'uploads/vat_reviews';
		$folders = scandir($dir, 0);
		foreach($folders as $folder){
			if($folder != "." && $folder != ".." && $folder != "ros_vat_due"){
				$subfoldersdir = $dir.'/'.$folder;
				$subfolders = scandir($subfoldersdir, 0);
				foreach($subfolders as $subfolder){
					if($subfolder != "." && $subfolder != ".."){
						$filesdir = $subfoldersdir.'/'.$subfolder;
						$files = scandir($filesdir, 0);
						foreach($files as $file){
							if($file != "." && $file != ".."){
								if(!is_dir($filesdir.'/'.$file))
								{
									$fileexp = explode("_",$file);
									$get_new_folder =  $fileexp[0];
									if(!is_dir($filesdir.'/'.$get_new_folder)){
										mkdir($filesdir.'/'.$get_new_folder);
									}
									rename($filesdir.'/'.$file, $filesdir.'/'.$get_new_folder.'/'.$fileexp[1]);
									$check_db = DB::table('vat_reviews')->where('filename',$file)->first();
									if(count($check_db)){
										$dbval['filename'] = $fileexp[1];
										$dbval['url'] = $filesdir.'/'.$get_new_folder;
										DB::table('vat_reviews')->where('id',$check_db->id)->update($dbval);
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
