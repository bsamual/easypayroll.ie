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
class TwobillController extends Controller {
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
	public function two_bill_manager()
	{
		$tasks = DB::table('taskmanager')->where('two_bill', 1)->get();
		$user = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		return view('user/two_bill/two_bill_manager', array('title' => 'Easypayroll - Two Bill Manager', 'userlist' => $user, 'taskslist' => $tasks));
	}
	public function get_tasks_invoices()
	{
		$taskid = Input::get('taskid');
		$client_id = Input::get('client_id');
		if($client_id == "")
		{
			$invoices = DB::table('invoice_system')->orderBy('id','DESC')->get();
		}
		else{
			$invoices = DB::table('invoice_system')->where('client_id',$client_id)->orderBy('id','DESC')->get();
		}
		$output = '<table class="table">
			<thead>
				<th>S.No</th>
				<th>Invoice No</th>
				<th>Date</th>
				<th>Net Amount</th>
			</thead>
			<tbody id="invoice_tbody_tr">';
			$i = 1;
			if(count($invoices))
			{
				foreach($invoices as $invoice)
				{
					$output.='<tr>
						<td><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$i.'</a></td>
						<td><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>
						<td><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.date('d-m-Y', strtotime($invoice->invoice_date)).'</a></td>
						<td><a href="javascript:" class="invoice_class" data-element="'.$invoice->invoice_number.'">'.$invoice->inv_net.'</a></td>
					</tr>';
					$i++;
				}
			}
			$output.='</tbody>
		</table>';
		echo $output;
	}
	public function update_invoice_for_task()
	{
		$taskid = Input::get('taskid');
		$invoiceno = Input::get('invoiceno');
		$data['invoice'] = $invoiceno;
		DB::table('taskmanager')->where('id',$taskid)->update($data);
	}
	public function remove_2bill_status()
	{
		$taskid = Input::get('taskid');
		$data['two_bill'] = 0;
		DB::table('taskmanager')->where('id',$taskid)->update($data);
	}
	public function change_billing_status()
	{
		$taskid = Input::get('taskid');
		$data['billing_status'] = Input::get('status');
		DB::table('taskmanager')->where('id',$taskid)->update($data);
	}
}

