<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\p30_period;
use App\p30_tasklevel;
use App\p30_due_date;

use Session;
class P30adminController extends Controller {

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
	public function __construct(p30_period $p30_period, p30_tasklevel $p30_tasklevel, p30_due_date $p30_due_date)
	{
		$this->middleware('userauth');
		$this->p30_period = $p30_period;
		$this->p30_tasklevel = $p30_tasklevel;
		$this->p30_due_date = $p30_due_date;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function period()
	{
		$period = DB::table('p30_period')->get();
		return view('user/p30/period', array('title' => 'P30 Period', 'periodlist' => $period));
	}
	public function deactiveperiod($id=""){
		$id = base64_decode($id);
		$deactive =  1;
		DB::table('p30_period')->where('id', $id)->update(['status' => $deactive ]);
		return redirect::back()->with('message','Deactive Success');
	}
	public function activeperiod($id=""){
		$id = base64_decode($id);
		$active =  0;
		DB::table('p30_period')->where('id', $id)->update(['status' => $active ]);
		return redirect::back()->with('message','Active Success');
	}
	public function addperiod(){
		$name = Input::get('name');		
		$count = DB::table('p30_period')->count();
		$next_count = $count + 1;
		DB::table('p30_period')->insert(['name' => $name,'sort' => $next_count]);
		return redirect::back()->with('message','Add Success');
	}
	public function editperiod($id=""){
		$id = base64_decode($id);
		$result = DB::table('p30_period')->where('id', $id)->first();
		echo json_encode(array('name' => $result->name, 'id' => $result->id));
	}
	public function updateperiod(){
		$name = Input::get('name');
		$id = Input::get('id');
		
		DB::table('p30_period')->where('id', $id)->update(['name' => $name]);
		return redirect::back()->with('message','Update Success');
	}
	public function tasklevel()
	{
		$tasklevel = DB::table('p30_tasklevel')->get();
		return view('user/p30/tasklevel', array('title' => 'P30 Task Level', 'tasklevellist' => $tasklevel));
	}
	public function deactivetasklevel($id=""){
		$id = base64_decode($id);
		$deactive =  1;
		DB::table('p30_tasklevel')->where('id', $id)->update(['status' => $deactive ]);
		return redirect::back()->with('message','Deactive Success');
	}
	public function activetasklevel($id=""){
		$id = base64_decode($id);
		$active =  0;
		DB::table('p30_tasklevel')->where('id', $id)->update(['status' => $active ]);
		return redirect::back()->with('message','Active Success');
	}
	public function addtasklevel(){
		$name = Input::get('name');		
		DB::table('p30_tasklevel')->insert(['name' => $name]);
		return redirect::back()->with('message','Add Success');
	}
	public function edittasklevel($id=""){
		$id = base64_decode($id);
		$result = DB::table('p30_tasklevel')->where('id', $id)->first();
		echo json_encode(array('name' => $result->name, 'id' => $result->id));
	}
	public function updatetasklevel(){
		$name = Input::get('name');
		$id = Input::get('id');
		
		DB::table('p30_tasklevel')->where('id', $id)->update(['name' => $name]);
		return redirect::back()->with('message','Update Success');
	}

	public function duedate()
	{
		$duedate = DB::table('p30_due_date')->where('id', 1)->first();
		return view('user/p30/duedate', array('title' => 'P30 Due Date', 'duedate' => $duedate));
	}

	public function updateduedate(){
		$date = Input::get('date');
		$id = 1;
		
		DB::table('p30_due_date')->where('id', $id)->update(['date' => $date]);
		return redirect::back()->with('message','Update Success');
	}
	public function period_sort_order()
	{
		$currentid = Input::get('currentid');
		$currentval = Input::get('currentval');
		$previd = Input::get('previd');
		$prevval = Input::get('prevval');

		DB::table('p30_period')->where('id',$currentid)->update(['sort' => $currentval]);
		DB::table('p30_period')->where('id',$previd)->update(['sort' => $prevval]);
	}

}
