<?php namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use App\Letterpad;
use Session;
use URL;
class LetterController extends Controller {

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
	public function __construct(letterpad $letterpad)
	{
		$this->middleware('adminauth');
		$this->letterpad = $letterpad;
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function manage_letterpad()
	{
		$user = DB::table('letterpad')->get();
		return view('admin/rct/letterpad', array('title' => 'Letterpad Background Image', 'userlist' => $user));
	}
	
	
	


}
