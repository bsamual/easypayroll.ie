<?php namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Schema;

use DB;

use Input;

use Redirect;

use App\Admin;

use Session;

use Hash;

class AdminController extends Controller {



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

	public function __construct(Admin $admin)

	{

		$this->middleware('adminauth');

		$this->admin = $admin;

	}



	/**

	 * Show the application welcome screen to the user.

	 *

	 * @return Response

	 */

	public function manageyear()

	{

		return view('admin/year', array('title' => 'Dashboard'));

	}

	public function profile()

	{

		$admin_details = DB::table('admin')->where('id',1)->first();

		$user_details = DB::table('user_login')->where('userid',1)->first();

		return view('admin/profile', array('admin_details' => $admin_details,'user_details' => $user_details));

	}

	public function vatprofile()

	{

		$admin_details = DB::table('admin')->where('id',1)->first();

		$user_details = DB::table('user_login')->where('userid',1)->first();

		return view('admin/vatprofile', array('admin_details' => $admin_details,'user_details' => $user_details));

	}

	public function email_settings()

	{

		$admin_details = DB::table('admin')->where('id',1)->first();

		$user_details = DB::table('user_login')->where('userid',1)->first();

		return view('admin/emailsettings', array('admin_details' => $admin_details,'user_details' => $user_details));

	}

	public function logout(){

		Session::forget("admin_userid");

		return redirect('/admin');

	}

	public function update_admin_setting()

	{

		$username=Input::get('admin_username');

		$currentpassword=Input::get('admin_password');

		$password=Hash::make(Input::get('newadmin_password'));

		$passwordd=base64_encode(Input::get('newadmin_password'));

		$admin = DB::table('admin')->first();



		if(Hash::check($currentpassword,$admin->password))

		{

			DB::table('admin')->where('id',1)->update(['username'=>$username,'password'=>$password,'pass_base'=>$passwordd]);

			return Redirect::back()->with('message', 'Settings Updated Successfully');

		}

		else{

			return Redirect::back()->with('message', 'Current Password is wrong. please try again later');

		}

		

	}

	public function update_user_notification()

	{

		$notification=Input::get('user_notification');

		DB::table('admin')->where('id',1)->update(['notify_message'=>$notification]);

		return Redirect::back()->with('message', 'Settings Updated Successfully');

	}

	public function update_user_signature()

	{

		$signature=Input::get('user_signature');

		DB::table('admin')->where('id',1)->update(['signature'=>$signature]);

		return Redirect::back()->with('message', 'Settings Updated Successfully');

	}

	public function update_user_setting()

	{

		$username=Input::get('user_username');

		$currentpassword=Input::get('user_password');

		$password=Hash::make(Input::get('newuser_password'));

		$user = DB::table('user_login')->first();



		if(Hash::check($currentpassword,$user->password))

		{

			DB::table('user_login')->where('userid',1)->update(['username'=>$username,'password'=>$password]);

			return Redirect::back()->with('message', 'Settings Updated Successfully');

		}

		else{

			return Redirect::back()->with('message', 'Current Password is wrong. please try again later');

		}

	}

	public function update_email_setting(){

		$email = Input::get('email');

		$ccemail = Input::get('ccemail');



		$taskccemail = Input::get('taskccemail');

		$p30ccemail = Input::get('p30ccemail');

		$cmccemail = Input::get('cmccemail');

		$vatccemail = Input::get('vatccemail');



		$deleteemail = Input::get('deleteemail');

		

		DB::table('admin')->where('id',1)->update(['email' =>$email, 'cc_email' =>$ccemail,'task_cc_email' =>$taskccemail,'p30_cc_email' =>$p30ccemail,'cm_cc_email' =>$cmccemail,'vat_cc_email' =>$vatccemail,'delete_email' =>$deleteemail]);

		return Redirect::back()->with('emailmessage', 'Email Settings Updated Successfully');

	}

	public function central_locations()

	{

		$central_locations = DB::table('central_locations')->first();

		return view('admin/central_locations', array('selectval' => $central_locations));

	}

	public function update_central_locations()

	{

		$status = Input::get('id');

		DB::table('admin')->where('id',1)->update(["central_locations" => $status]);

	}

	public function update_central_locations_form()

	{

		$data['client_management'] = Input::get('client_management');

		$data['invoice_management'] = Input::get('invoice_management');

		$data['client_statements'] = Input::get('client_statements');

		$data['weekly_monthly'] = Input::get('weekly_monthly');

		$data['p30'] = Input::get('p30');

		$data['vat'] = Input::get('vat');

		$data['rct'] = Input::get('rct');

		$data['year_end'] = Input::get('year_end');

		$data['time_location'] = Input::get('time_location');



		DB::table('central_locations')->where('id',1)->update($data);

		return Redirect::back()->with('message', 'Central Locations Updated Successfully');

	}

	public function manage_cro()

	{

		$cro = DB::table('cro_credentials')->first();

		return view('admin/manage_cro', array('cro' => $cro));

	}

	public function update_cro_setting(){

		$username = Input::get('username');

		$api_key = Input::get('api_key');

		

		DB::table('cro_credentials')->where('id',1)->update(['username' =>$username, 'api_key' =>$api_key]);

		return Redirect::back()->with('emailmessage', 'CRO Settings Updated Successfully');

	}

	public function clear_opening_balance()

	{

		$client = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();

		return view('admin/clear_opening_balance', array('clientlist' => $client));

	}

	public function clear_all_opening_balance()

	{

		$data['opening_balance'] = '';

		$data['opening_date'] = '0000-00-00';



		$dataval['import_balance'] = '';

		$dataval['balance_remaining'] = '';



		DB::table('opening_balance')->update($data);

		DB::table('opening_balance_import')->delete();

		DB::table('invoice_system')->update($dataval);

	}

	public function clear_opening_balance_for_client()

	{

		$client_id = Input::get('client_id');

		$data['opening_balance'] = '';

		$data['opening_date'] = '0000-00-00';



		$dataval['import_balance'] = '';

		$dataval['balance_remaining'] = '';



		DB::table('opening_balance')->where('client_id',$client_id)->update($data);

		DB::table('opening_balance_import')->where('client_id',$client_id)->delete();

		DB::table('invoice_system')->where('client_id',$client_id)->update($dataval);

	}
	public function clear_receipt_system()
	{
		DB::table('receipts')->truncate();
		DB::table('receipt_nominal_codes')->truncate();

		return Redirect::back()->with('message', 'Receipt System Cleared Successfully');
	}
	public function clear_payment_system()
	{
		DB::table('payments')->truncate();
		DB::table('payment_nominal_codes')->truncate();

		return Redirect::back()->with('message', 'Payment System Cleared SUccessfully');
	}
	public function table_viewer(){
		$tables = DB::select('SHOW TABLES');
		$tables = array_map('current',$tables);
		unset($tables[0]);
		unset($tables[5]);
		unset($tables[10]);
		unset($tables[18]);
		unset($tables[19]);
		unset($tables[20]);
		unset($tables[21]);
		unset($tables[22]);
		unset($tables[23]);
		unset($tables[24]);
		unset($tables[25]);
		unset($tables[109]);
		return view('admin/table_viewer', array('tablelist' => $tables));
	}
	public function get_table_notes(){
		$table = Input::get('value');
		$get_notes = DB::table('table_notes')->where('table_name',$table)->first();
		$notes = '';
		if(count($get_notes))
		{
			$notes = $get_notes->notes;
		}
		echo $notes;
	}
	public function update_table_notes(){
		$table = Input::get('table');
		$notes = Input::get('notes');
		$get_notes = DB::table('table_notes')->where('table_name',$table)->first();

		if(count($get_notes))
		{
			$data['notes'] = $notes;
			DB::table('table_notes')->where('id',$get_notes->id)->update($data);
		}
		else{
			$data['notes'] = $notes;
			$data['table_name'] = $table;
			DB::table('table_notes')->insert($data);
		}
	}
	public function show_table_viewer()
	{
		$table = Input::get('table_name');
		$page = Input::get('page');

		$limit = 50;
		$offpage = $page - 1;
		$offset = $offpage * 50;

		$fields = DB::getSchemaBuilder()->getColumnListing($table);
		$rows = DB::table($table)->skip($offset)->take($limit)->get();

		$total_array = DB::table($table)->get();
		if(count($total_array))
		{
			$total_rows = ceil(count($total_array) / 50);
		}
		else{
			$total_rows = 0;
		}

		$output = '
		<table class="table own_table_white" style="margin-top: 20px;min-width:100%;float:left">
			<thead>';
				if(count($fields))
				{
					foreach($fields as $field){
						$output.='<th>'.$field.'</th>';
					}
				}
			$output.='</thead>
			<tbody id="table_viewer_tbody">';
				if(count($rows))
				{
					foreach($rows as $row){
						$output.='<tr>';
						if(count($fields))
						{
							foreach($fields as $field){
								$output.='<td>'.$row->$field.'</td>';
							}
						}
						$output.='</tr>';
					}
				}
			$output.='</tbody>
		</table>
		<div class="col-md-12">
		<input type="hidden" name="hidden_page" id="hidden_page" value="1">';
		if($total_rows > 1){
			$output.='<input type="button" class="common_black_button common_btn load_more_content" id="load_more_content" value="Load More Content">';
		}else{
			$output.='<input type="button" class="common_black_button common_btn" value="Load More Content" style="display:none">';
		}
		$output.='</div>';

		echo json_encode(array("output" => $output));
	}
	public function show_table_viewer_append()
	{
		$table = Input::get('table_name');
		$page = Input::get('page');

		$limit = 50;
		$offpage = $page - 1;
		$offset = $offpage * 50;

		$fields = DB::getSchemaBuilder()->getColumnListing($table);
		$rows = DB::table($table)->skip($offset)->take($limit)->get();

		$total_array = DB::table($table)->get();
		if(count($total_array))
		{
			$total_rows = ceil(count($total_array) / 50);
		}
		else{
			$total_rows = 0;
		}

		$output = '';
		if(count($rows))
		{
			foreach($rows as $row){
				$output.='<tr>';
				if(count($fields))
				{
					foreach($fields as $field){
						$output.='<td>'.$row->$field.'</td>';
					}
				}
				$output.='</tr>';
			}
		}

		$nextpage = $page + 1;
		if($total_rows > $page)
		{
			$show_load_btn = 1;
		}
		else{
			$show_load_btn = 0;
		}

		echo json_encode(array("show_load_btn" => $show_load_btn, "output" => $output));
	}
}

