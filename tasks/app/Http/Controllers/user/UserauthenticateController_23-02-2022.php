<?php namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use DB;
use Response;
use Mail;
use Session;
use PHPMailer;
use Hash;
use Auth;
use Redirect;

class UserauthenticateController extends Controller {

	public function __construct()
	{
	    $this->flag = 0;
		$this->middleware('userredirect', ['except' => 'getLogout']);
	}
	public function login()
	{	
		return view('user/login', array('title' => 'Welcome'));
	}

	public function postLogin()
	{
		

		  $validator = Validator::make(Input::all(),
		                            ['userid' => 'required',
									 'password' => 'required']
									);
									
		if($validator->fails())
		{
			
			return redirect('/')->withInput(Input::all())->with('login_error',$validator->errors());
		}
		else
		{
			$username = Input::get('userid');
			$password = Input::get('password');
			if(Auth::attempt(['username' => $username, 'password' => $password]))
			{
				$details = [];
				
				$user = DB::table('user_login')->where('userid', Auth::id())->first();
				if(!empty($user))
				{
					$details = $user;
				}
				if(!empty($details))
				{	
					$sessn=array('userid' => $details->userid);
					Session::put($sessn); 
					return redirect('/user/dashboard');
				}
				else
				{
					return redirect('/')->withInput()->with('message','Invalid Username or Password');
				}
			}
			else{
				return redirect('/')->withInput()->with('message','Invalid Username or Password');
			}
		}
	}
	public function user_registration()
	{
		$name = Input::get('practice_name');
		$address1 = Input::get('address1');
		$address2 = Input::get('address2');
		$address3 = Input::get('address3');
		$address4 = Input::get('address4');
		$logo = $_FILES['practice_logo']['name'];
		$tmp_name = $_FILES['practice_logo']['tmp_name'];
		$telephone = Input::get('telephone');
		$admin_user = Input::get('admin_user');

		$data['practice_name'] = $name;
		$data['address1'] = $address1;
		$data['address2'] = $address2;
		$data['address3'] = $address3;
		$data['address4'] = $address4;
		$data['telephone_no'] = $telephone;
		$data['admin_user'] = $admin_user;

		$upload_dir = 'uploads/profile_logo';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.time();
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		move_uploaded_file($tmp_name, $upload_dir.'/'.$logo);
		$data['logo_name'] = $logo;
		$data['logo_url'] = $upload_dir;

		DB::table('bubble_user')->insert($data);

		return redirect::back()->with('message', 'User Registered Successfully');
	}
}
