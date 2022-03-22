<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Salution;
use Session;
class SalutionController extends Controller {

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
	public function __construct(salution $salution)
	{
		$this->middleware('adminauth');
		$this->salution = $salution;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function manage_salution()
	{
		$user = DB::table('email_salution')->get();
		return view('admin/rct/salution', array('title' => 'Email Salution', 'userlist' => $user));
	}
	
	
	


}
