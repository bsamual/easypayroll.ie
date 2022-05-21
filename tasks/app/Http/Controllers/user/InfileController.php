<?php namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use Hash;
use ZipArchive;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class InfileController extends Controller {



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

	public function __construct()
	{
		$this->middleware('userauth');
		date_default_timezone_set("Europe/Dublin");
	}



	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */

	public function infile(){	
		if(Session::has('notepad_attach_add')) {
			Session::forget("notepad_attach_add");
		}
		if(Session::has('file_attach_add')) {
			Session::forget("file_attach_add");
		}

		$dir = "uploads/infile_image/temp";//"path/to/targetFiles";
	    
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }

		$infiles = DB::table('in_file')->get();
		$userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$internal = 0;
		return view('user/infile/infile', array('title' => 'InFile', 'infiles' => $infiles, 'userlist' => $userlist, 'internal_search_check' => $internal));
	}
	public function infile_advance(){	
		if(Session::has('notepad_attach_add'))
		{
			Session::forget("notepad_attach_add");
		}
		if(Session::has('file_attach_add'))
		{
			Session::forget("file_attach_add");
		}

		$dir = "uploads/infile_image/temp";//"path/to/targetFiles";
	    
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }

		$clients = DB::table('cm_clients')->get();
		$userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$internal = 0;
		return view('user/infile/infile_advance', array('title' => 'InFile', 'clientslist' => $clients, 'userlist' => $userlist, 'internal_search_check' => $internal));
	}
	
	public function infile_userupdate(){		
		$id = Input::get('id');	
		$data['complete_by'] = Input::get('users');
		DB::table('in_file')->where('id', $id)->update($data);		
	}
	public function infile_completedate(){
		$id = Input::get('id');	
		$dd= Input::get('dateval');
		if($dd != "")
		{
			$date = explode('-',Input::get('dateval'));
			if($date[1] == "Jan") { $month = '01'; }
			elseif($date[1] == "Feb") { $month = '02'; }
			elseif($date[1] == "Mar") { $month = '03'; }
			elseif($date[1] == "Apr") { $month = '04'; }
			elseif($date[1] == "May") { $month = '05'; }
			elseif($date[1] == "Jun") { $month = '06'; }
			elseif($date[1] == "Jul") { $month = '07'; }
			elseif($date[1] == "Aug") { $month = '08'; }
			elseif($date[1] == "Sep") { $month = '09'; }
			elseif($date[1] == "Oct") { $month = '10'; }
			elseif($date[1] == "Nov") { $month = '11'; }
			else{ $month = '12'; }
			$data['complete_date'] = $date[2].'-'.$month.'-'.$date[0];
			DB::table('in_file')->where('id', $id)->update($data);
		}
	}

	public function in_file_statusupdate(){
		$id = Input::get('id');
		

		$data['status'] = Input::get('status');
		DB::table('in_file')->where('id', $id)->update($data);

	}

	public function in_file_showincomplete(){

		$value = Input::get('value');


		if($value == 0){
			$infiles = DB::table('in_file')->get();
		}
		else{
			$infiles = DB::table('in_file')->where('status', 0)->get();
		}

		$userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$i=1;
      	$output='';
      	if(count($infiles)){
	        foreach ($infiles as $file) {

	          if($file->status == 0){
                $staus = 'fa-check'; 
                $statustooltip = 'Complete Infile';
                $disable = '';
                $disable_class = '';
                $color='';
              } 
              else{
                $staus = 'fa-times';
                $statustooltip = 'InComplete Infile';
                $disable = 'disabled';
                $disable_class = 'disable_class';
                $color = 'style="color:#f00;"';
              }

	          $companydetails = DB::table('cm_clients')->where('client_id', $file->client_id)->first();

	          $attachments = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',0)->where('notes_type', 0)->get();

	          $downloadfile='';

	          if(count($attachments)){                        
                foreach($attachments as $attachment){

                    $downloadfile.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $downloadfile ='';
              }

              if(count($attachments)){
                $deleteall = '<i class="fa fa-minus-square delete_all_image '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>';
              }
              else{
                $deleteall = '';
              }



              $notes_attachments = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',0)->where('notes_type', 1)->get();

              $download_notes='';

              if(count($notes_attachments)){                        
                foreach($notes_attachments as $attachment){

                    $download_notes.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $download_notes ='';
              }

              if(count($notes_attachments)){
                $delete_notes_all = '<i class="fa fa-minus-square delete_all_notes '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-download download_all_notes" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>
                ';
              }
              else{
                $delete_notes_all = '';
              }


              $attach_notes_only = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',1)->get();

              $notes_only='';

              if(count($attach_notes_only)){                        
                foreach($attach_notes_only as $attachment){

                    $notes_only.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $notes_only ='';
              }

              if(count($attach_notes_only)){
                $delete_notes_only = '<i class="fa fa-minus-square delete_all_notes_only '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-download download_all_notes_only" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';
              }
              else{
                $delete_notes_only = '';
              }




              $userdrop='';

              if(count($userlist)){
                foreach ($userlist as $user) {
                  if($user->user_id == $file->complete_by ){ $selected = 'selected';} else{$selected = '';}
                  $userdrop.='<option value="'.$user->user_id.'" '.$selected.'>'.$user->firstname.'</option>';
                }
              }

	          
              $complete_date = ($file->complete_date != '0000-00-00')?date('d-M-Y', strtotime($file->complete_date)):'';

              $hard_files = ($file->hard_files != 0)?'YES':'NO';
	      $output.='
	        <tr id="infile_'.$file->id.'">
	          <td '.$color.' valign="top" >'.$i.'</td>
	          <td '.$color.' valign="top">'.$companydetails->company.'</td>
	          <td '.$color.' valign="top">Received:'.date('d-M-Y', strtotime($file->data_received)).'<br/>Added: '.date('d-M-Y', strtotime($file->date_added)).'</td>
	          <td '.$color.'>
	          
	          '.$downloadfile.'
	          <i class="fa fa-plus faplus '.$disable_class.'" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>              
	          '.$deleteall.'<br/><br/>

	          <div style="width:100%; height:auto; float:left; padding-bottom:10px; border-bottom:1px solid #000;color: #0300c1;">Notes:</div>

	          <div class="clearfix"></div>

	          '.$download_notes.'
	          <i class="fa fa-pencil-square fanotepad '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Notepad"></i>
	          '.$delete_notes_all.'




	              <div class="img_div" style="z-index:9999999">
	                <form name="image_form" id="image_form" action="'.URL::to('user/infile_image_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
	                  <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
	                  <input type="hidden" name="hidden_id" value="'.$file->id.'">
	                  <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                  <spam class="error_files"></spam>
	                </form>
	                <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
	                <div class="image_div_attachments">
	                  <form action="'.URL::to('user/infile_upload_images?file_id='.$file->id).'" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                          <input name="_token" type="hidden" value="'.$file->id.'">
                       
                      </form>
	                  <a href="'.URL::to('user/in_file/').'" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
	                </div>
	              </div>


	              <div class="notepad_div" style="z-index:9999; position:absolute">

	                <form name="notepad_form" id="notepad_form" action="'.URL::to('user/infile_notepad_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
	                  
	                  <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
	                  <input type="hidden" name="hidden_id" value="'.$file->id.'">
	                  <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

	                  <spam class="error_files_notepad"></spam>

	                </form>
	              </div>

	              
	              

	          
	          </td>
	          <td '.$color.' valign="top">
	          <select class="form-control user_select '.$disable_class.'" data-element="'.$file->id.'" '.$disable.'>
	          <option value="">Select User</option>'.$userdrop.'</select>
	          </td>
	          <td '.$color.' valign="top">
	          
	              <label class="input-group datepicker-only-init">
	                  <input type="text" class="form-control complete_date '.$disable_class.'" '.$disable.' value="'.$complete_date.'" data-element="'.$file->id.'" placeholder="Select Date" name="date" style="font-weight: 500;" required="">
	                  <span class="input-group-addon">
	                      <i class="glyphicon glyphicon-calendar"></i>
	                  </span>
	              </label>
	          
	          </td>
	          <td '.$color.' valign="top">

	          '.$notes_only.'
	          <i class="fa fa-pencil-square fanotepad_notes '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Notepad"></i>
	          '.$delete_notes_only.'

	          <div class="notepad_div_notes" style="z-index:9999; position:absolute">

	            <form name="notepad_form_notes" id="notepad_form_notes" action="'.URL::to('user/infile_notepad_upload_notes').'" method="post" enctype="multipart/form-data" style="text-align: left;">
	              
	              <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
	              <input type="hidden" name="hidden_id" value="'.$file->id.'">
	              <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

	              <spam class="error_files_notepad"></spam>

	            </form>

	          </div>

	          </td>
	          <td '.$color.' align="center" valign="top">'.$hard_files.'</td>
	          <td '.$color.' align="center" valign="top"><a href="javascript:"><i class="fa '.$staus.'" aria-hidden="true" data-element="'.$file->id.'" data-toggle="tooltip" title="'.$statustooltip.'"></i></a></td>
	        </tr>';          
	        $i++;
	        }
	      }
      echo $output;	
	}

	public function infile_client_search()
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

	public function infile_clientsearchselect(){

		$id = Input::get('value');
		$client_details = DB::table('cm_clients')->where('client_id', $id)->first();
		echo json_encode(["client_id" => $client_details->client_id]);

	}


	public function infile_imageupload()
	{
		$total = count($_FILES['image_file']['name']);
		$id = Input::get('hidden_id');	

		for($i=0; $i<$total; $i++) {
		 	$filename = str_replace("#","",$_FILES['image_file']['name'][$i]);
		 	 $filename = str_replace("#","",$filename);
			 $filename = str_replace("#","",$filename);
			 $filename = str_replace("#","",$filename);

			 $filename = str_replace("&","",$filename);
			 $filename = str_replace("&","",$filename);
			 $filename = str_replace("&","",$filename);
			 $filename = str_replace("&","",$filename);

			 $filename = str_replace("%","",$filename);
			 $filename = str_replace("%","",$filename);
			 $filename = str_replace("%","",$filename);

			$data_img = DB::table('in_file')->where('id',$id)->first();
			$tmp_name = $_FILES['image_file']['tmp_name'][$i];
			$upload_dir = 'uploads/infile_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.time();
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}

			move_uploaded_file($tmp_name, $upload_dir.'/'.$filename);	
			$data['file_id'] = $data_img->id;
			$data['attachment'] = $filename;
			$data['url'] = $upload_dir;
			$data['textstatus'] = 1;
			DB::table('in_file_attachment')->insert($data);

		}
		$dataval['task_notify'] = 1;
		DB::table('in_file')->where('id',$id)->update($dataval);

		$item = DB::table('in_file')->where('id',$id)->first();
		if(count($item))
		{
			$client_id = $item->client_id;
		}
		else{
			$client_id = '';
		}

		return redirect('user/infile_search?client_id='.$client_id.'&infile_item='.$id);
	}

	public function infile_upload_images()
	{
		$id = $_GET['file_id'];

		$dataval['task_notify'] = 1;
		DB::table('in_file')->where('id',$id)->update($dataval);
		
		$data_img = DB::table('in_file')->where('id',$id)->first();
		$upload_dir = 'uploads/infile_image';
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

			 $fname = str_replace("&","",$fname);
			 $fname = str_replace("&","",$fname);
			 $fname = str_replace("&","",$fname);
			 $fname = str_replace("&","",$fname);

			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);
			 $filename = $upload_dir.'/'.$fname;
			 

		 	if(move_uploaded_file($tmpFile,$filename))
		 	{
		 		$data['file_id'] = $data_img->id;

		 		if(isset($_POST['hidden_infile_type']))
		 		{
		 			$type = $_POST['hidden_infile_type'];
		 			if($type == 1){ $data['b'] = 1; }
		 			if($type == 2){ $data['p'] = 1; }
		 			if($type == 3){ $data['s'] = 1; }
		 			if($type == 4){ $data['o'] = 1; }
		 		}
				$data['attachment'] = $fname;
				$data['url'] = $upload_dir;
				$data['textstatus'] = 1;
				$data['status'] = 1;
				$id = DB::table('in_file_attachment')->insertGetId($data);

				$infile_item = DB::table('in_file')->where('id',$data_img->id)->first();
				if(count($infile_item)) {
					if($infile_item->integrity_check == 1) {
						$datafile['integrity_check'] = 2;
						DB::table('in_file')->where('id',$data_img->id)->update($datafile);
					}
				}
				echo json_encode(array('id' => $id,'filename' => $fname,'file_id' => $data_img->id));
		 	}
		}
		
	}

	public function remove_dropzone_attachment()
	{
		$attachment_id = $_POST['attachment_id'];
		$file_id = $_POST['file_id'];
		$check_network = DB::table('in_file_attachment')->where('id',$attachment_id)->first();

		DB::table('in_file_attachment')->where('id',$attachment_id)->delete();
		if($check_network->status == 0)
		{

			$count = DB::table('in_file_attachment')->where('file_id',$check_network->file_id)->where('status',0)->count();

			if($count > 0)

			{

				

			}
			else{

				$dataval['task_notify'] = 0;
				DB::table('in_file')->where('id',$check_network->file_id)->update($dataval);

			}

		}	
	}

	public function infile_delete_image()
	{
		$imgid = Input::get('imgid');			
		$check_network = DB::table('in_file_attachment')->where('id',$imgid)->first();

		$url = '';
		DB::table('in_file_attachment')->where('id',$imgid)->delete();
		if($check_network->status == 0)
		{
			$file_id = $check_network->file_id;
			$file_details = DB::table('in_file')->where('id',$file_id)->first();
			if(count($file_details))
			{
				$url = URL::to('user/infile_search?client_id='.$file_details->client_id.'&infile_item='.$file_id);
			}

			$count = DB::table('in_file_attachment')->where('file_id',$check_network->file_id)->where('status',0)->count();
			if($count > 0)
			{
			}
			else{
				$dataval['task_notify'] = 0;
				DB::table('in_file')->where('id',$check_network->file_id)->update($dataval);
			}
		}
		echo $url;

	}
	public function infile_delete_all_image()
	{
		$id = Input::get('id');	
		DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where('notes_type', 0)->delete();
		$count = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->count();
		if($count > 0)
		{

		}
		else{
			$dataval['task_notify'] = 0;
			DB::table('in_file')->where('id',$id)->update($dataval);
		}
	}
	public function infile_download_all_image()
	{
		$id = Input::get('id');	
		$details = DB::table('in_file')->where('id',$id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
		$files = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where('notes_type', 0)->get();
		if(count($files))
		{
			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.$details->date_added.'.zip';
			foreach($files as $file)
			{
	            $zip = new ZipArchive;
	            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
	            	// Add File in ZipArchive
	                $zip->addFile($file->url.'/'.$file->attachment,$file->attachment);
	                // Close ZipArchive     
	                $zip->close();
	            }
			}
			echo $zipFileName;
		}
	}
	public function infile_download_rename_all_image()
	{
		$id = Input::get('id');
		$details = DB::table('in_file')->where('id',$id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
		$files = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where('notes_type', 0)->get();
		if(count($files))
		{
			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.date('d M Y',strtotime($details->date_added)).'.zip';
			$zip = new ZipArchive;
	       	if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
				foreach($files as $file)
				{
					if($file->textval != "" && $file->textstatus == 1)
					{
						$filename = "QuickID_".$file->textval."_".$file->attachment;
					}
					else{
						$filename = $file->attachment;
					}
		            $zip->addFile($file->url.'/'.$file->attachment,$filename);
				}
				$zip->close();
			}
			echo $zipFileName;
		}
	}
	
	public function infile_notepad_upload()
	{
		$id = Input::get('hidden_id');
		$data_img = DB::table('in_file')->where('id',$id)->first();

		$company_detail = DB::table('cm_clients')->where('client_id', $data_img->client_id)->first();

		$count = DB::table('in_file_attachment')->where('file_id',$id)->where('status',0)->where('notes_type',1)->count();
		$counts = $count + 1;
		$file_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $company_detail->company); 

		
		$filename = $file_name.'-'.$counts;	

		
		$contents = Input::get('notepad_contents');

		

		$upload_dir = 'uploads/infile_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.base64_encode($data_img->id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);

		$data['file_id'] = $data_img->id;
		$data['attachment'] = $filename.".txt";
		$data['url'] = $upload_dir;
		$data['status'] = 0;
		$data['notes_type'] = 1;
		$data['textstatus'] = 1;
		DB::table('in_file_attachment')->insert($data);

		$dataval['task_notify'] = 1;
		DB::table('in_file')->where('id',$id)->update($dataval);

		return redirect::back();

	}

	public function infile_notepad_upload_notes()
	{
		$id = Input::get('hidden_id');
		$data_img = DB::table('in_file')->where('id',$id)->first();

		$company_detail = DB::table('cm_clients')->where('client_id', $data_img->client_id)->first();

		$count = DB::table('in_file_attachment')->where('file_id',$id)->where('status',1)->where('notes_type',1)->count();
		$counts = $count + 1;
		$file_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $company_detail->company); 

		
		$filename = $file_name.' - infile - '.$counts;	

		
		$contents = Input::get('notepad_contents');

		

		$upload_dir = 'uploads/infile_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		
		$upload_dir = $upload_dir.'/'.base64_encode($data_img->id);
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);

		$data['file_id'] = $data_img->id;
		$data['attachment'] = $filename.".txt";
		$data['url'] = $upload_dir;
		$data['status'] = 1;
		$data['notes_type'] = 1;
		$data['textstatus'] = 1;
		DB::table('in_file_attachment')->insert($data);

		return redirect::back();

	}

	public function infile_delete_all_notes_only()
	{
		$id = Input::get('id');	

		DB::table('in_file_attachment')->where('file_id',$id)->where('status', 1)->where('notes_type', 1)->delete();
	}
	public function infile_download_all_notes_only()
	{
		$id = Input::get('id');	

		$details = DB::table('in_file')->where('id',$id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
		$files = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 1)->where('notes_type', 1)->get();
		if(count($files))
		{
			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.$details->date_added.'.zip';
			foreach($files as $file)
			{
	            $zip = new ZipArchive;
	            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
	            	// Add File in ZipArchive
	                $zip->addFile($file->url.'/'.$file->attachment,$file->attachment);
	                // Close ZipArchive     
	                $zip->close();
	            }
			}
			echo $zipFileName;
		}

		
	}

	public function infile_delete_all_notes()
	{
		$id = Input::get('id');	

		DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where('notes_type', 1)->delete();
		$count = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->count();
		if($count > 0)
		{

		}
		else{
			$dataval['task_notify'] = 0;
			DB::table('in_file')->where('id',$id)->update($dataval);
		}
	}
	public function infile_download_all_notes()
	{
		$id = Input::get('id');	
		$details = DB::table('in_file')->where('id',$id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
		$files = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where('notes_type', 1)->get();
		if(count($files))
		{
			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.$details->date_added.'.zip';
			foreach($files as $file)
			{
	            $zip = new ZipArchive;
	            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
	            	// Add File in ZipArchive
	                $zip->addFile($file->url.'/'.$file->attachment,$file->attachment);
	                // Close ZipArchive     
	                $zip->close();
	            }
			}
			echo $zipFileName;
		}
	}

	public function infile_commonclient_search()
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

	public function infile_commonclientsearchselect(){

		$id = Input::get('value');
		$infiles = DB::table('in_file')->where('client_id', $id)->get();
		

		$userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$i=1;
	    $output='';
	    if(count($infiles)){
	       foreach ($infiles as $file) {
	          if($file->status == 0){
                $staus = 'fa-check'; 
                $statustooltip = 'Complete Infile';
                $disable = '';
                $disable_class = '';
                $color='';
              } 
              else{
                $staus = 'fa-times';
                $statustooltip = 'InComplete Infile';
                $disable = 'disabled';
                $disable_class = 'disable_class';
                $color = 'style="color:#f00;"';
              }

	          $companydetails = DB::table('cm_clients')->where('client_id', $file->client_id)->first();

	          $attachments = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',0)->where('notes_type', 0)->get();

	          $downloadfile='';

	          if(count($attachments)){                        
                foreach($attachments as $attachment){
                	if($attachment->textstatus == 1) { $texticon="display:none"; $hide = 'display:initial'; } else { $texticon="display:initial"; $hide = 'display:none'; }
                    if($attachment->check_file == 1) { $textdisabled ='disabled'; $checked = 'checked'; } else { $textdisabled =''; $checked = ''; }
                    $downloadfile.= '<div class="file_attachment_div"><input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'><label for="fileattach_'.$attachment->id.'">&nbsp;</label> 
                    	<input type="text" name="add_text" class="add_text '.$disable_class.'" data-element="'.$attachment->id.'" value="'.$attachment->textval.'" placeholder="Add Text" '.$textdisabled.' style="'.$hide.'">
                    	<a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a>
                    	<a href="javascript:" class="fileattachment file_attach_bpso" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'" '.$color.'>'.$attachment->attachment.'</a>
                    </div>';
                }
              }
              else{
                $downloadfile ='';
              }
              /*<i class="fa fa-download download_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>*/
              if(count($attachments)){
                $deleteall = '<i class="fa fa-minus-square delete_all_image '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                
                <i class="fa fa-cloud-download download_rename_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download & Rename All Attachments"></i>

                <i class="fa fa-file-text-o summary_infile_attachments" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Summary"></i>';
              }
              else{
                $deleteall = '';
              }
              if(count($attachments))
              {
                $span = '<span style="color:#000">There are '.count($attachments).' file(s)</span>';
              }
              else{
                $span = '';
              }


              $notes_attachments = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',0)->where('notes_type', 1)->get();

              $download_notes='';

              if(count($notes_attachments)){                        
                foreach($notes_attachments as $attachment){
                	if($attachment->check_file == 1) { $checked = 'checked'; } else { $checked = ''; }
                    $download_notes.= '<input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'><label for="fileattach_'.$attachment->id.'">&nbsp;</label><a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $download_notes ='';
              }

              if(count($notes_attachments)){
                $delete_notes_all = '<i class="fa fa-minus-square delete_all_notes '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-download download_all_notes" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';
              }
              else{
                $delete_notes_all = '';
              }


              $attach_notes_only = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',1)->get();

              $notes_only='';

              if(count($attach_notes_only)){                        
                foreach($attach_notes_only as $attachment){

                    $notes_only.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';
                }
              }
              else{
                $notes_only ='';
              }

              if(count($attach_notes_only)){
                $delete_notes_only = '<i class="fa fa-minus-square delete_all_notes_only '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>
                <i class="fa fa-download download_all_notes_only" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';
              }
              else{
                $delete_notes_only = '';
              }




              $userdrop='';

              if(count($userlist)){
                foreach ($userlist as $user) {
                  if($user->user_id == $file->complete_by ){ $selected = 'selected';} else{$selected = '';}
                  $userdrop.='<option value="'.$user->user_id.'" '.$selected.'>'.$user->firstname.'</option>';
                }
              }

              $complete_date = ($file->complete_date != '0000-00-00')?date('d-M-Y', strtotime($file->complete_date)):'';
              $hard_files = ($file->hard_files != 0)?'YES':'NO';

	      $output.='
	        <tr id="infile_'.$file->id.'">
              <td '.$color.' valign="top" >'.$i.'</td>
              <td '.$color.' valign="top">'.$companydetails->company.'</td>
              <td '.$color.' valign="top">Received:'.date('d-M-Y', strtotime($file->data_received)).'<br/>Added: '.date('d-M-Y', strtotime($file->date_added)).'</td>
              <td '.$color.'>
              
              '.$downloadfile.'
              <i class="fa fa-plus faplus '.$disable_class.'" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>              
              '.$deleteall.'<br/><br/>

              <div style="width:100%; height:auto; float:left; padding-bottom:10px; border-bottom:1px solid #000">Notes</div>

              <div class="clearfix"></div>

              '.$download_notes.'
              <i class="fa fa-pencil-square fanotepad '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Notepad"></i>
              '.$delete_notes_all.'




                  <div class="img_div" style="z-index:9999999">
                    <form name="image_form" id="image_form" action="'.URL::to('user/infile_image_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
                      <input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
                      <input type="hidden" name="hidden_id" value="'.$file->id.'">
                      <input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files"></spam>
                    </form>
                    <div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
                    <div class="image_div_attachments">
                      <form action="'.URL::to('user/infile_upload_images?file_id='.$file->id).'" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                          <input name="_token" type="hidden" value="'.$file->id.'">
                       
                      </form>
                      <a href="'.URL::to('user/in_file/').'" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
                    </div>
                  </div>


                  <div class="notepad_div" style="z-index:9999; position:absolute">

                    <form name="notepad_form" id="notepad_form" action="'.URL::to('user/infile_notepad_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
                      
                      <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
                      <input type="hidden" name="hidden_id" value="'.$file->id.'">
                      <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                      <spam class="error_files_notepad"></spam>

                    </form>
                  </div>

                  
                  

              
              </td>
              <td '.$color.' valign="top">
              <select class="form-control user_select '.$disable_class.'" data-element="'.$file->id.'" '.$disable.'>
              <option value="">Select User</option>'.$userdrop.'</select>
              </td>
              <td '.$color.' valign="top">
              
                  <label class="input-group auto_save_date">
                      <input type="text" class="form-control complete_date '.$disable_class.'" '.$disable.' value="'.$complete_date.'" data-element="'.$file->id.'" placeholder="Select Date" name="date" style="font-weight: 500;" required="">
                      <span class="input-group-addon">
                          <i class="glyphicon glyphicon-calendar"></i>
                      </span>
                  </label>
              
              </td>
              <td '.$color.' valign="top">

              '.$notes_only.'
              <i class="fa fa-pencil-square fanotepad_notes '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Notepad"></i>
              '.$delete_notes_only.'

              <div class="notepad_div_notes" style="z-index:9999; position:absolute">

                <form name="notepad_form_notes" id="notepad_form_notes" action="'.URL::to('user/infile_notepad_upload_notes').'" method="post" enctype="multipart/form-data" style="text-align: left;">
                  
                  <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
                  <input type="hidden" name="hidden_id" value="'.$file->id.'">
                  <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                  <spam class="error_files_notepad"></spam>

                </form>

              </div>

              </td>
              <td '.$color.' align="center" valign="top">'.$hard_files.'</td>
              <td '.$color.' align="center" valign="top"><a href="javascript:"><i class="fa '.$staus.'" aria-hidden="true" data-element="'.$file->id.'" data-toggle="tooltip" title="'.$statustooltip.'"></i></a></td>
            </tr>';      
	        $i++;
	        }
	      }
      echo json_encode(array('result_row' => $output));	


	}

	public function add_notepad_contents()
	{
		$contents = Input::get('contents');
		$clientid = Input::get('clientid');

		$company_detail = DB::table('cm_clients')->where('client_id', $clientid)->first();
		$upload_dir = 'uploads/infile_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/temp';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if(Session::has('notepad_attach_add'))
		{
			$count = count(Session::get('notepad_attach_add'));
			$counts = $count + 1;
			$file_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $company_detail->company);
			$filename = $file_name.'-'.$counts;	

			$arrayval = array('attachment' => $filename.".txt",'url' => $upload_dir);
			$getsession = Session::get('notepad_attach_add');
			array_push($getsession,$arrayval);

		}
		else{
			$count = 0;
			$counts = $count + 1;
			$file_name =  preg_replace('/[^A-Za-z0-9 \-]/', '', $company_detail->company);
			$filename = $file_name.'-'.$counts;	

			$arrayval = array('attachment' => $filename.".txt",'url' => $upload_dir);
			$getsession = array();
			array_push($getsession,$arrayval);
		}

		

		$myfile = fopen($upload_dir."/".$filename.".txt", "w") or die("Unable to open file!");
		fwrite($myfile, $contents);
		fclose($myfile);

		$sessn=array('notepad_attach_add' => $getsession);
		Session::put($sessn);
		echo $filename.".txt";
	}
	public function infile_upload_images_add()
	{
		$upload_dir = 'uploads/infile_image';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/temp';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}

		if (!empty($_FILES)) {
			 $tmpFile = $_FILES['file']['tmp_name'];
			 $fname = str_replace("#","",$_FILES['file']['name']);
			 $fname = str_replace("#","",$fname);
			 $fname = str_replace("#","",$fname);
			 $fname = str_replace("#","",$fname);

			 $fname = str_replace("&","",$fname);
			 $fname = str_replace("&","",$fname);
			 $fname = str_replace("&","",$fname);
			 $fname = str_replace("&","",$fname);

			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);
			 $fname = str_replace("%","",$fname);

			 $filename = $upload_dir.'/'.$fname;

			 $arrayval = array('attachment' => $fname,'url' => $upload_dir);

	 		if(Session::has('file_attach_add'))
			{
				$getsession = Session::get('file_attach_add');
			}
			else{
				$getsession = array();
			}
			
			array_push($getsession,$arrayval);

			$sessn=array('file_attach_add' => $getsession);
			Session::put($sessn);

			move_uploaded_file($tmpFile,$filename);

		 	echo json_encode(array('id' => 0,'filename' => $fname,'file_id' => 0,'count'=>count($getsession)));
		}
	}
	public function create_new_file()
	{
		$total_count = Input::get("total_count_files");
		$clientid= Input::get('clientid');
		$received_date = date('Y-m-d', strtotime(Input::get("received_date"))); //explode('-',Input::get('received_date'));
		$search_type = Input::get('hidden_client_id');
		//$received_date = $date_received[2].'-'.$date_received[0].'-'.$date_received[1];

		//$date_added = explode('-',Input::get('added_date'));
		$added_date = date('Y-m-d', strtotime(Input::get("added_date"))); //$date_added[2].'-'.$date_added[0].'-'.$date_added[1];

		$data['client_id'] = $clientid;
		$data['data_received'] = $received_date;
		$data['date_added'] = $added_date;
		$data['description'] = Input::get('description');
		$data['hard_files'] = (Input::get("hard_files_checkbox") != 0)?1:0;

		$data['percent_one'] = '0.00';
		$data['percent_two'] = '9.00';
		$data['percent_three'] = '13.50';
		$data['percent_four'] = '23.00';
		$data['percent_five'] = '21.00';

		$file_id = DB::table('in_file')->insertGetId($data);

		if(Session::has('file_attach_add'))
		{
			$files = Session::get('file_attach_add');

			$upload_dir = 'uploads/infile_image';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.base64_encode($file_id);
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}

     		$dir = "uploads/infile_image/temp";//"path/to/targetFiles";
		    $dirNew = $upload_dir;//path/to/destination/files
		    // Open a known directory, and proceed to read its contents
		    if (is_dir($dir)) {
		        if ($dh = opendir($dir)) {
		            while (($file = readdir($dh)) !== false) {
			            if ($file==".") continue;
			            if ($file=="..")continue;
			            if(file_exists($dir.'/'.$file))
			            {
			            	rename($dir.'/'.$file,$dirNew.'/'.$file);
			            }
		            }
		            closedir($dh);
		        }
		    }
     		
			foreach($files as $file)
			{
				//rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
				$dataval['file_id'] = $file_id;
     			$dataval['url'] = $upload_dir;
				$dataval['attachment'] = $file['attachment'];
				$dataval['textstatus'] = 1;
				Db::table('in_file_attachment')->insert($dataval);
			}
			$datavalinfile['task_notify'] = 1;
			DB::table('in_file')->where('id',$file_id)->update($datavalinfile);
		}
		if(Session::has('notepad_attach_add'))
		{
			$files = Session::get('notepad_attach_add');
			foreach($files as $file)
			{
				$upload_dir = 'uploads/infile_image';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.base64_encode($file_id);
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				if(file_exists("uploads/infile_image/temp/".$file['attachment']))
				{
					rename("uploads/infile_image/temp/".$file['attachment'], $upload_dir.'/'.$file['attachment']);
				}

				$dataval['file_id'] = $file_id;
				$dataval['attachment'] = $file['attachment'];
				$dataval['url'] = $upload_dir;
				$dataval['notes_type'] = 1;
				$dataval['textstatus'] = 1;
				Db::table('in_file_attachment')->insert($dataval);
			}
			$datavalinfile['task_notify'] = 1;
			DB::table('in_file')->where('id',$file_id)->update($datavalinfile);
		}

		$client_details = DB::table('cm_clients')->where('client_id', $clientid)->first();
		if(Session::has('file_attach_add'))
		{
			$countupdated = Db::table('in_file_attachment')->where('file_id',$file_id)->where('notes_type',0)->count();
			return redirect::back()->with('message', 'InFiles for "'.$client_details->company.'" is Created Sucessfully.')->with('countupdated', $countupdated)->with('total_count', $total_count)->with('client_session_id', $clientid)->with('file_id', $file_id)->with('view_button', '<a href="'.URL::to('user/infile_search').'?client_id='.$clientid.'" class="fa fa-eye common_black_button" style="margin-right:13px;margin-top:-9px" title="View Client Infiles"></a>')->with('search_type',$search_type);
		}
		else{
			return redirect::back()->with('message', 'InFiles for "'.$client_details->company.'" is Created Sucessfully.')->with('client_session_id', $clientid)->with('view_button', '<a href="'.URL::to('user/infile_search').'?client_id='.$clientid.'" class="fa fa-eye common_black_button" style="margin-right:13px;margin-top:-9px" title="View Client Infiles"></a>')->with('search_type',$search_type);
		}
	}
	public function clear_session_attachments()
	{
		if(Session::has('notepad_attach_add'))
		{
			Session::forget("notepad_attach_add");
		}
		if(Session::has('file_attach_add'))
		{
			Session::forget("file_attach_add");
		}
		$dir = "uploads/infile_image/temp";//"path/to/targetFiles";
	    
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }
	}
	public function delete_file_link()
	{
		$file = 'public/'.Input::get('result');
		if (!unlink($file))
		  {
		  echo ("Error deleting $file");
		  }
		else
		  {
		  echo ("Deleted $file");
		  }
	}

	public function infile_search(){
		$clientid = Input::get('client_id');
		if(Session::has('notepad_attach_add'))
		{
			Session::forget("notepad_attach_add");
		}
		if(Session::has('file_attach_add'))
		{
			Session::forget("file_attach_add");
		}

		// $infile_items = DB::table('in_file')->where('integrity_check',0)->get();
		// if(count($infile_items)) {
		// 	foreach($infile_items as $item) {
		// 		$get_id = $item->id;
		// 		$path = 'papers/infile_report/'.$get_id;
		// 		$mtime = file_exists($path)?filemtime($path):'';
		// 		if($mtime != "") {
		// 			$dataval['integrity_check'] = 1;
		// 			$dataval['check_date_time'] = date('Y-m-d H:i:s', $mtime);
		// 			DB::table('in_file')->where('id',$get_id)->update($dataval);
		// 		}
		// 	}
		// }

		$dir = "uploads/infile_image/temp";//"path/to/targetFiles";
	    
	    // Open a known directory, and proceed to read its contents
	    if (is_dir($dir)) {
	        if ($dh = opendir($dir)) {
	            while (($file = readdir($dh)) !== false) {
		            if ($file==".") continue;
		            if ($file=="..")continue;
		            unlink($dir.'/'.$file);
	            }
	            closedir($dh);
	        }
	    }

		$infiles = DB::table('in_file')->where('client_id', $clientid)->get();
		$userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
		$internal = 1;
		return view('user/infile/infile', array('title' => 'InFile', 'infiles' => $infiles, 'userlist' => $userlist, 'internal_search_check' => $internal));
	}

	public function infile_internal(){
		$id = Input::get('id');	
		$data['internal'] =  Input::get('internal');		
		DB::table('in_file')->where('id', $id)->update($data);
	}
	public function fileattachment_status()
	{
		$id = Input::get('id');	
		$status = Input::get('status');	
		$data['check_file'] =  $status;		
		DB::table('in_file_attachment')->where('id', $id)->update($data);
	}
	public function infile_email_notify_tasks_pdf()
	{
		$email = Input::get('email');
		$file_id = Input::get('file_id');
		$client_id = Input::get('clientid');
		$time = Input::get('timeval');

		$user_details = DB::table('user')->where('user_id',$client_id)->first();

		$file_details = DB::table('in_file')->where('id',$file_id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$file_details->client_id)->first();
		

		$admin_details = Db::table('admin')->where('id',1)->first();



		$admin_cc = $admin_details->task_cc_email;

		$from = $admin_details->email;

		$to = trim($email);

		$cc = $admin_cc;



		$data['sentmails'] = $to.' , '.$cc;

		$data['logo'] = URL::to('assets/images/easy_payroll_logo.png');





		$data['file_details'] = $file_details;
		$data['username'] = $user_details->firstname;

		$data['client_name'] = $client_details->company;



		$contentmessage = view('user/file_email_notify_tasks', $data);

		



		$subject = 'Easypayroll.ie: Infile Task Notification';



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
	public function change_attachment_text_status()
	{
		$id = $_GET['id'];
		$dataval['textstatus'] = 1;
		DB::table('in_file_attachment')->where('id',$id)->update($dataval);
	}
	public function remove_attachment_text_status()
	{
		$id = $_GET['id'];
		$dataval['textstatus'] = 0;
		$dataval['textval'] = '';
		DB::table('in_file_attachment')->where('id',$id)->update($dataval);
	}
	public function update_fileattachment_textval()
	{
		$id = $_GET['id'];
		$dataval['textval'] = $_GET['input'];
		DB::table('in_file_attachment')->where('id',$id)->update($dataval);
	}
	public function get_attachment_details()
	{
		$id = $_GET['id'];
		$src = $_GET['element'];
		$details = DB::table('in_file_attachment')->where('id',$id)->first();
		if($details->textval != "" && $details->textstatus == 1)
		{
			$filename = "QuickID_".$details->textval."_".$details->attachment;
		}
		else{
			$filename = '';
		}
		echo $filename;
	}


	public function report_infile(){
		$id = Input::get('id');
		$infilelist = DB::table('in_file')->groupBy('client_id')->where('status',0)->get();
		if($id == 0){				
				$output = '';
				$i=1;
				if(count($infilelist)){ 
					foreach($infilelist as $infile){ 
						$client_details = DB::table('cm_clients')->where('client_id', $infile->client_id)->first();						
						$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="radio" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</span></td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
						';
						$i++;
					}
				}
				if($i == 1){
		          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';			
		        }
				echo $output;
		}
		else{				
				$output = '';
				$i=1;
				if(count($infilelist)){ 
					foreach($infilelist as $infile){ 
						$client_details = DB::table('cm_clients')->where('client_id', $infile->client_id)->first();
						$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="checkbox" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
						';
						$i++;
					}
				}
				if($i == 1){
		          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';			
		        }
				echo $output;
		}

	}


	public function infile_report_incomplete(){
		$id = Input::get('id');	
		$type = Input::get('type');	
		
		if($id == 0){
				$infilelist = DB::table('in_file')->groupBy('client_id')->where('status', $id)->get();				
				$output = '';
				$i=1;
				if(count($infilelist)){ 
					foreach($infilelist as $infile){ 
						$client_details = DB::table('cm_clients')->where('client_id', $infile->client_id)->first();	
						if($type == 1){
						$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="radio" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</span></td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
						';
						}
						else{
							$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="checkbox" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</span></td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
						';
						}
						$i++;
					}
				}
				if($i == 1){
		          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';			
		        }
				echo $output;
		}
		else{	$infilelist = DB::table('in_file')->groupBy('client_id')->get();
				$output = '';
				$i=1;
				if(count($infilelist)){ 
					foreach($infilelist as $infile){ 
						$client_details = DB::table('cm_clients')->where('client_id', $infile->client_id)->first();
						if($type == 1){
						$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="radio" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</span></td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
						';
						}
						else{
							$output.='
							<tr>
								<td>'.$i.'</td>
								<td><input type="checkbox" name="select_client" class="select_client class_'.$client_details->client_id.'" data-element="'.$client_details->client_id.'" value="'.$client_details->client_id.'"><label>&nbsp</label></td>
								<td align="left">'.$client_details->client_id.'</span></td>
								<td align="left">'.$client_details->firstname.'</td>
								<td align="left">'.$client_details->company.'</td>
							</tr>
						';
						}
						$i++;
					}
				}
				if($i == 1){
		          $output.='<tr><td colspan="5" align="center">Empty</td></tr>';			
		        }
				echo $output;
		}

	}


	public function infile_report_pdf()
	{
		$ids = explode(",",Input::get('value'));
		$status = Input::get('status');

		if($status == 1){
			$infile_client = DB::table('in_file')->join('cm_clients', 'in_file.client_id', '=', 'cm_clients.client_id')->select('in_file.*', 'cm_clients.company')->whereIn('in_file.client_id', $ids)->where('in_file.status', 0)->orderBy('cm_clients.firstname','asc')->get();
			//$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->where('status', 0)->get();
		}
		else{
			$infile_client = DB::table('in_file')->join('cm_clients', 'in_file.client_id', '=', 'cm_clients.client_id')->select('in_file.*', 'cm_clients.company')->whereIn('in_file.client_id', $ids)->orderBy('cm_clients.firstname','asc')->get();
			//$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->get();
		}
		
		$output = '';
		$i=1;
		if(count($infile_client)){
			foreach ($infile_client as $file) {				
				
				$client_details = DB::table('cm_clients')->where('client_id', $file->client_id)->first();

				/*$file_count='';

				if(count($infile_details)){
					foreach ($infile_details as $single_file) {						
						$file_count.= DB::table('in_file_attachment')->where('file_id', $single_file->id)->count();
					}
				}*/

				$file_count = DB::table('in_file_attachment')->where('file_id', $file->id)->count();


				if(count($client_details)){
					$clientname = $client_details->firstname.'&nbsp;'.$client_details->surname;
				}
				else{
					$clientname = '';
				}			

				$output.='
					<tr>
						<td style="text-align: left;border:1px solid #000;">'.$i.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$clientname.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$file->data_received.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$file->date_added.'</td>
						<td style="text-align: left;border:1px solid #000;">'.$file_count.'</td>

					</tr>
				';
				$i++;
			}
		}			
		else{
			$output='<td colspan="4" align="center">Empty</td>';
		}
		

		echo $output;
	}
	public function download_infile_report_pdf()
	{
		$htmlval = Input::get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		
		$pdf->save('infile_report/Infile Report.pdf');
		echo 'Infile Report.pdf';
	}

	public function infile_report_csv($id=""){		

		$ids = explode(",",Input::get('value'));
		$status = INput::get('status');

		if($status == 1){
			$infile_client = DB::table('in_file')->join('cm_clients', 'in_file.client_id', '=', 'cm_clients.client_id')->select('in_file.*', 'cm_clients.company')->whereIn('in_file.client_id', $ids)->where('in_file.status', 0)->orderBy('cm_clients.firstname','asc')->get();

			//$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->where('status', 0)->get();
		}
		else{
			$infile_client = DB::table('in_file')->join('cm_clients', 'in_file.client_id', '=', 'cm_clients.client_id')->select('in_file.*', 'cm_clients.company')->whereIn('in_file.client_id', $ids)->orderBy('cm_clients.firstname','asc')->get();

			//$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->get();
		}

		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );
	    
      	
		$columns_1 = array('#', 'Client Name', 'Date Received', 'Date Added', 'No. of Files');
		

		$callback = function() use ($infile_client, $columns_1)
    	{
	       	$file = fopen('infile_report/Infile_Report.csv', 'w');
		    fputcsv($file, $columns_1);
			$i=1;
			foreach ($infile_client as $singlefile) {
				$file_count = DB::table('in_file_attachment')->where('file_id', $singlefile->id)->count();
				$client_details = DB::table('cm_clients')->where('client_id', $singlefile->client_id)->first();				
				
		      	$columns_2 = array($i, $client_details->firstname.' '.$client_details->surname, $singlefile->data_received, $singlefile->date_added, $file_count);

				fputcsv($file, $columns_2);
				$i++;
			}
			fclose($file);	
		};
		return Response::stream($callback, 200, $headers);
	}

	public function infile_report_csv_single($id=""){		

		$ids = explode(",",Input::get('value'));
		$status = Input::get('status');

		if($status == 1){
			$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->where('status',0)->orderBy('data_received','asc')->get();
		}
		else{
			$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->orderBy('data_received','asc')->get();
		}
		
		$headers = array(
	        "Content-type" => "text/csv",
	        "Content-Disposition" => "attachment; filename=CM_Report.csv",
	        "Pragma" => "no-cache",
	        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
	        "Expires" => "0"
	    );

      	
		$columns_1 = array('#', 'Client Name', 'Date Received', 'Date Added', 'Complete By', 'Complete Date', 'Description', 'No. of Files', 'Attachments', 'Status');
		

		$callback = function() use ($infile_client, $columns_1)
    	{
	       	$file = fopen('infile_report/Infile_Report.csv', 'w');
		    fputcsv($file, $columns_1);
			$i=1;
			foreach ($infile_client as $singlefile) {
				$file_count = DB::table('in_file_attachment')->where('file_id', $singlefile->id)->count();
				$client_details = DB::table('cm_clients')->where('client_id', $singlefile->client_id)->first();
				$file_name = DB::table('in_file_attachment')->where('file_id', $singlefile->id)->orderBy('id','asc')->get();

				

				$user_details = DB::table('user')->where('user_id', $singlefile->complete_by)->first();

				if(count($user_details)){
					$user = $user_details->lastname.' '.$user_details->firstname;
				}
				else{
					$user = 'N/A';
				}

				if($singlefile->complete_date == '0000-00-00'){
					$complete_date = 'N/A';
				}
				else{
					$complete_date = $singlefile->complete_date;
				}

				if($singlefile->status == 0){
					$status = 'Incomplete';
				}
				else{
					$status = 'Completed';
				}
				


				$first_file_name = DB::table('in_file_attachment')->where('file_id', $singlefile->id)->orderBy('id','asc')->first();
				if(count($first_file_name))
				{
					if($first_file_name->textstatus == 0){
						$first_file = $first_file_name->attachment;
					}
					else{
						$first_file = $first_file_name->attachment.'('.$first_file_name->textval.')';
					}
					
				}
				else{
					$first_file = '';
				}

				if($singlefile->description == ''){
					$description = 'Description Not Available';
				}
				else{
					$description = $singlefile->description;
				}


				


		      	$columns_2 = array($i, $client_details->firstname.' '.$client_details->surname, $singlefile->data_received, $singlefile->date_added, $user, $complete_date,$description, $file_count, $first_file, $status);
				fputcsv($file, $columns_2);

				if(count($file_name))
				{
					$isingle = 0;
					foreach($file_name as $single_file)
					{
						if($isingle != 0)
						{
							if($single_file->textstatus==0){
								$columns_3 = array('','','','','','','','',$single_file->attachment,'');
							}
							else{
								$columns_3 = array('','','','','','','','',$single_file->attachment.'('.$single_file->textval.')','');
							}
							
							fputcsv($file, $columns_3);
						}
						$isingle++;
					}
				}


				$i++;
			}
			fclose($file);	
		};
		return Response::stream($callback, 200, $headers);
	}

	public function infile_report_pdf_single()
	{
		$ids = explode(",",Input::get('value'));	
		$status = Input::get('status');

		

		if($status == 1){
			$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->where('status', 0)->orderBy('data_received','asc')->get();
		}
		else{
			$infile_client = DB::table('in_file')->whereIn('client_id', $ids)->orderBy('data_received','asc')->get();
		}

		


		
		
		$output = '';
		$i=1;
		if(count($infile_client)){
			foreach ($infile_client as $file) {
				$file_count = DB::table('in_file_attachment')->where('file_id', $file->id)->count();
				$client_details = DB::table('cm_clients')->where('client_id', $file->client_id)->first();

				$infile_name = DB::table('in_file_attachment')->where('file_id', $file->id)->get();

				$username = DB::table('user')->where('user_id', $file->complete_by)->first();

				$infile_attached='';

				if(count($infile_name)){
					foreach ($infile_name as $file_single) {
						if($file_single->status == 0){
							if($file_single->notes_type == 0){
								if($file_single->textstatus == 0){
									$infile_attached.= $file_single->attachment.'<br/>';
								}
								else{
									$infile_attached.= $file_single->attachment.'('.$file_single->textval.')'.'<br/>';
								}
							}
						}
					}
				}
				else{
					$infile_attached ='';
				}



				$infile_attached_notes='';

				if(count($infile_name)){
					foreach ($infile_name as $file_single) {
						if($file_single->status == 0){
							if($file_single->notes_type == 1){
								if($file_single->textstatus == 0){
									$infile_attached_notes.= $file_single->attachment.'<br/>';
								}
								else{
									$infile_attached_notes.= $file_single->attachment.'('.$file_single->textval.')'.'<br/>';
								}
							}
						}
					}
				}
				else{
					$infile_attached_notes ='';
				}




				$compete_notes ='';

				if(count($infile_name)){
					foreach ($infile_name as $file_single) {
						if($file_single->status == 1){							
							$compete_notes.= $file_single->attachment.'<br/>';
						}
					}
				}
				else{
					$compete_notes ='';
				}


				if(count($client_details)){
					$clientname = $client_details->firstname.'&nbsp;'.$client_details->surname;
				}
				else{
					$clientname = 'N/A';
				}

				if($file->complete_date == '0000-00-00'){
					$complete_date = 'N/A';
				}
				else{
					$complete_date = $file->complete_date;
				}

				if(count($username)){
					$user = $username->lastname.' '.$username->firstname;
				}
				else{
					$user = 'N/A';
				}

				if($file->status == 0){
					$status = 'Incomplete';
				}
				else{
					$status = 'Completed';
				}


				if($file->description == ''){
					$description = 'Description Not Available';
				}
				else{
					$description = $file->description;
				}

				$output.='
					<style>									
					.table_td_class_left{text-align: left;border:1px solid #00; line-height:30px; height:20px; width:30%; float:left; padding:3px;}
					.table_td_class_right{text-align: left;border:1px solid #00; line-height:30px; height:20px; width:70%; float:left; padding:3px;}
					</style>
					<div style="width:100%; height:auto; margin-bottom:100px;">
						<table cellspacing="0" cellpadding="0" border="0px" style="width:80%; padding-left:10%; ">
						<tr>
							<td class="table_td_class_left">S.No</td>
							<td class="table_td_class_right">'.$i.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Client Name</td>
							<td class="table_td_class_right">'.$clientname.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Date Received</td>
							<td class="table_td_class_right">'.$file->data_received.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Date Added</td>
							<td class="table_td_class_right">'.$file->date_added.'</td>						
						</tr>
						<tr>
							<td class="table_td_class_left">Complete By</td>
							<td class="table_td_class_right">'.$user.'</td>						
						</tr>
						<tr>
							<td class="table_td_class_left">Complete date</td>
							<td class="table_td_class_right">'.$complete_date.'</td>						
						</tr>
						<tr>
							<td class="table_td_class_left">Description</td>
							<td class="table_td_class_right">'.$description.'</td>						
						</tr>
						<tr>
							<td class="table_td_class_left">No. of Files</td>
							<td class="table_td_class_right">'.$file_count.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Files List</td>
							<td class="table_td_class_right">Attachment(s):<br/>'.$infile_attached.'<br/>Note(s):<br/>'.$infile_attached_notes.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Completion Notes:</td>
							<td class="table_td_class_right">'.$compete_notes.'</td>
						</tr>
						<tr>
							<td class="table_td_class_left">Status</td>
							<td class="table_td_class_right">'.$status.'</td>
						</tr>
							
						</table>
					</div>			
				';
				$i++;
			}
		}			
		else{
			$output='<tr><td colspan="2" align="center">Empty</td><td>';
		}
		

		echo $output;
	}
	public function download_infile_report_pdf_single()
	{
		$htmlval = Input::get('htmlval');
		$pdf = PDF::loadHTML($htmlval);
		$pdf->setPaper('A4', 'landscape');
		
		$pdf->save('infile_report/Infile Report.pdf');
		echo 'Infile Report.pdf';
	}

	public function infile_task_client_search()
	{
		$value = Input::get('client_id');
		$single = DB::table('cm_clients')->Where('client_id',$value)->first();
		if(count($single))
		{
			if($single->company != "")
			{
				$company = $single->company;
			}
			else{
				$company = $single->firstname.' '.$single->surname;
			}
			echo json_encode(array('value'=>$company.'-'.$single->client_id,'id'=>$single->client_id));
		}
		else{
			echo json_encode(array('value'=>'No Data Found','id'=>''));
		}
	}
	public function change_attachment_bpso_status()
	{
		$type = Input::get('type');
		//$status = Input::get('status');
		$attach_id = Input::get('attachment_id');
		//$data[$type] = $status;

		if($type == 1){
			$data['b'] = 1;
			$data['p'] = 0;
			$data['s'] = 0;
			$data['o'] = 0;
		}
		if($type == 2){
			$data['b'] = 0;
			$data['p'] = 1;
			$data['s'] = 0;
			$data['o'] = 0;
		}
		if($type == 3){
			$data['b'] = 0;
			$data['p'] = 0;
			$data['s'] = 1;
			$data['o'] = 0;
		}
		if($type == 4){
			$data['b'] = 0;
			$data['p'] = 0;
			$data['s'] = 0;
			$data['o'] = 1;
		}


		DB::table('in_file_attachment')->where('id',$attach_id)->update($data);
		DB::table('in_file_attachment')->where('attach_id',$attach_id)->update($data);
	}
	public function infile_download_bpso_all_image()
	{
		$type = Input::get('type');

		$id = Input::get('id');
		$details = DB::table('in_file')->where('id',$id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
		$files = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where($type, 1)->where('notes_type', 0)->get();
		if(count($files))
		{
			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.date('d M Y',strtotime($details->date_added)).'.zip';
			$zip = new ZipArchive;
	       	if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
				foreach($files as $file)
				{
					if($file->textval != "" && $file->textstatus == 1)
					{
						$filename = "QuickID_".$file->textval."_".$file->attachment;
					}
					else{
						$filename = $file->attachment;
					}
		            $zip->addFile($file->url.'/'.$file->attachment,$filename);
				}
				$zip->close();
			}
			echo $zipFileName;
		}
	}

	public function bpso_all_check(){
		$id = Input::get('id');
		$type = Input::get('type');

		$infile_list = DB::table('in_file_attachment')->where('file_id', $id)->where('notes_type',0)->get();
		if(count($infile_list)){
			foreach ($infile_list as $file) {
				$count=$file->b+$file->p+$file->s+$file->o;
				if($count == 0){
					if($type == "1"){
						$data['b'] = 1;
						$data['p'] = 0;
						$data['s'] = 0;
						$data['o'] = 0;
					}
					if($type == "2"){
						$data['b'] = 0;
						$data['p'] = 1;
						$data['s'] = 0;
						$data['o'] = 0;					
					}
					if($type == "3"){
						$data['b'] = 0;
						$data['p'] = 0;
						$data['s'] = 1;
						$data['o'] = 0;					
					}
					if($type == "4"){
						$data['b'] = 0;
						$data['p'] = 0;
						$data['s'] = 0;
						$data['o'] = 1;						
					}
					DB::table('in_file_attachment')->where('id', $file->id)->update($data);				
										
				}				
			}


		}
		$attachments = DB::table('in_file_attachment')->where('file_id', $id)->where('status',0)->where('notes_type', 0)->where('secondary',0)->get();
		$file = DB::table('in_file')->where('id', $id)->first();
		if($file->status == 0){
	        $staus = 'fa-check'; 
	        $statustooltip = 'Complete Infile';
	        $disable = '';
	        $disable_class = '';
	        $color='';
	      }
	      else{
	        $staus = 'fa-times';
	        $statustooltip = 'InComplete Infile';
	        $disable = 'disabled';
	        $disable_class = 'disable_class';
	        $color = 'style="color:#f00;"';
	      }
		$downloadfile ='<div class="col-md-12">
	              		<table class="table_bspo table-fixed-header" id="bspo_id_'.$file->id.'" style="width:100%;">
	              			<thead>
				                <tr>
				                  <th style="width:18%;text-align:left">
				                  	<a data-toggle="popover" data-container="body" data-placement="right" type="button" data-html="true" href="javascript:" id="login_'.$file->id.'" class="auto_increment" data-element="'.$file->id.'" style="margin-left: 18%;">AutoQue</a>

				                  	<div id="popover-content-login_'.$file->id.'" class="hide">
										<input type="radio" name="item_auto_num" class="item_auto_num item_auto_num_'.$file->id.'" id="purchase_item_auto_'.$file->id.'" value="1"><label for="purchase_item_auto">Purchase Item</label><br/>
										<input type="radio" name="item_auto_num" class="item_auto_num item_auto_num_'.$file->id.'" id="sales_item_auto_'.$file->id.'" value="2"><label for="sales_item_auto">Sales Item</label>
										<div class="form-data">
											<h4>Enter Number:</h4>
											<input type="number" class="form-control auto_number_value_'.$file->id.'" id="auto_number_value" value="">

											<h4>Enter Increment Value:</h4>
											<input type="number" class="form-control inc_number_value_'.$file->id.'" id="inc_number_value" value="">
											<input type="button" name="submit_auto_value" class="submit_auto_value common_black_button" data-element="'.$file->id.'" value="Number Now" style="margin-top: 10px;">
											<input type="button" name="submit_re_number" class="submit_re_number common_black_button" data-element="'.$file->id.'" value="RE-Number">
										</div>
									</div>
				                  </th>
				                  <th style="width:2%">
				                    <div style="width:100%; text-align:center">
				                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in B Category" id="'.$file->id.'" data-element="1">@</a>
				                    </div>
				                    <div style="width:100%; text-align:center">
				                    <i class="fa fa-cloud-download download_b_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in B Category"></i>
				                    </div>
				                  </th>
				                  <th style="width:2%">
				                    <div style="width:100%; text-align:center">
				                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in P Category" id="'.$file->id.'" data-element="2">@</a>
				                    </div>
				                    <div style="width:100%; text-align:center">
				                    <i class="fa fa-cloud-download download_p_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in P Category"></i>
				                    </div>
				                  </th>
				                  <th style="width:2%">
				                    <div style="width:100%; text-align:center">
				                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in S Category" id="'.$file->id.'" data-element="3">@</a>
				                    </div>
				                    <div style="width:100%; text-align:center">
				                    <i class="fa fa-cloud-download download_s_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in S Category"></i>
				                    </div>
				                  </th>
				                  <th style="width:2%">
				                    <div style="width:100%; text-align:center">
				                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in O Category" id="'.$file->id.'" data-element="4">@</a>
				                    </div>
				                    <div style="width:100%; text-align:center">
				                    <i class="fa fa-cloud-download download_o_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in O Category"></i>
				                    </div>
				                  </th>
				                  <th class="td_input td_supplier" style="width:10%;font-weight:600;text-align:center" data-element="'.$file->id.'">Supplier/Customer</th>
				                  <th class="td_input td_code" style="font-weight:600;text-align:center;width:5%;">Code</th>
				                  <th class="td_input td_date" style="font-weight:600;text-align:center;width:7%;">Date</th>
				                  <th class="td_input td_percent_one" style="font-weight:600;text-align:center;width:5%;">
				                  	% <br/><spam class="percent_one_text">'.$file->percent_one.'</spam>
				                  	<div class="percent_one_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
				                  		<input type="number" name="change_percent_one" class="change_percent_one form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
				                  		<input type="button" name="submit_percent_one" class="common_black_button submit_percent_one" value="Submit" data-element="'.$file->id.'">
				                  	</div>
				                  </th>
				                  <th class="td_input td_percent_two" style="font-weight:600;text-align:center;width:5%;">
				                  	% <br/><spam class="percent_two_text">'.$file->percent_two.'</spam>
				                  	<div class="percent_two_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
				                  		<input type="number" name="change_percent_two" class="change_percent_two form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
				                  		<input type="button" name="submit_percent_two" class="common_black_button submit_percent_two" value="Submit" data-element="'.$file->id.'">
				                  	</div>
				                  </th>
				                  <th class="td_input td_percent_three" style="font-weight:600;text-align:center;width:5%;">
				                  	% <br/><spam class="percent_three_text">'.$file->percent_three.'</spam>
				                  	<div class="percent_three_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
				                  		<input type="number" name="change_percent_three" class="change_percent_three form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
				                  		<input type="button" name="submit_percent_three" class="common_black_button submit_percent_three" value="Submit" data-element="'.$file->id.'">
				                  	</div>
				                  </th>
				                  <th class="td_input td_percent_five" style="font-weight:600;text-align:center;width:5%;">
				                  	% <br/><spam class="percent_five_text">'.$file->percent_five.'</spam>
				                  	<div class="percent_five_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
				                  		<input type="number" name="change_percent_five" class="change_percent_five form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
				                  		<input type="button" name="submit_percent_five" class="common_black_button submit_percent_five" value="Submit" data-element="'.$file->id.'">
				                  	</div>
				                  </th>
				                  <th class="td_input td_percent_four" style="font-weight:600;text-align:center;width:5%;">
				                  	% <br/><spam class="percent_four_text">'.$file->percent_four.'</spam>
				                  	<div class="percent_four_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
				                  		<input type="number" name="change_percent_four" class="change_percent_four form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
				                  		<input type="button" name="submit_percent_four" class="common_black_button submit_percent_four" value="Submit" data-element="'.$file->id.'">
				                  	</div>
				                  </th>
				                  <th class="td_input" style="font-weight:600;text-align:center;border-left:1px solid #b5b3b3;width:5%;">Net</th>
				                  <th class="td_input" style="font-weight:600;text-align:center;width:5%;">VAT</th>
				                  <th class="td_input" style="font-weight:600;text-align:center;border-right:1px solid #b5b3b3;width:5%;">Gross</th>
				                  <th class="td_input" style="font-weight:600;text-align:center;width:6%;">Currency</th>
				                  <th class="td_input" style="font-weight:600;text-align:center;width:6%;">Value</th>
				                  <th class="td_input" style="width:20px;font-weight:600;text-align:center;width:2%;"></th>
				                </tr>
			                </thead>
			                <tbody>';                   
			                foreach($attachments as $attachment){
			                	$get_sub_attachments = DB::table('in_file_attachment')->where('attach_id',$attachment->id)->where('secondary',1)->orderBy('id','asc')->get();
			                	if($attachment->textstatus == 1) { $texticon="display:none"; $hide = 'display:initial'; } else { $texticon="display:initial"; $hide = 'display:none'; }
								if($attachment->check_file == 1) { $textdisabled ='disabled'; $checked = 'checked'; } else { $textdisabled =''; $checked = ''; }
								if($attachment->b == 1) {  $bchecked = 'checked'; } else { $bchecked = ''; }
								if($attachment->p == 1) {  $pchecked = 'checked'; } else { $pchecked = ''; }
								if($attachment->s == 1) {  $schecked = 'checked'; } else { $schecked = ''; }
								if($attachment->o == 1) {  $ochecked = 'checked'; } else { $ochecked = ''; }

								if($attachment->p == 1) { $attach_disabled = ''; }
								elseif($attachment->s == 1) { $attach_disabled = ''; }
								else { $attach_disabled = 'disabled'; }
								if($attachment->flag == 0) { $flag = '<i class="fa fa-flag flag_gray fileattachment"></i>'; }
				                elseif($attachment->flag == 1) { $flag = '<i class="fa fa-flag flag_orange fileattachment"></i>'; }
				                elseif($attachment->flag == 2) { $flag = '<i class="fa fa-flag flag_red fileattachment"></i>'; }

								$downloadfile.= '<tr class="attachment_tr attachment_tr_main" data-element="'.$file->id.'">
									<td style="min-width:300px;max-width:300px;">
										<div class="file_attachment_div" style="width:100%">
										  	<input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'><label for="fileattach_'.$attachment->id.'">&nbsp;</label>
										  	<input type="text" name="add_text" class="add_text '.$disable_class.'" data-element="'.$attachment->id.'" value="'.$attachment->textval.'" placeholder="Add Text" '.$textdisabled.' style="'.$hide.'">
										  	'.$flag.'
										  	<a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a>
											<a href="javascript:" class="fileattachment file_attach_bpso" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'" '.$color.' data-toggle="tooltip" title="'.$attachment->attachment.'">'.substr($attachment->attachment,0,15).'</a>
											
										</div>
									</td>
									<td>
										<input type="radio" name="check_'.$attachment->id.'" class="b_check" id="b_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$bchecked.' title="Bank"><label for="b_check_'.$attachment->id.'" title="Bank">B</label> 
									</td>
									<td>
										<input type="radio" name="check_'.$attachment->id.'" class="p_check" id="p_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$pchecked.' title="Purchases"><label for="p_check_'.$attachment->id.'" title="Purchases">P</label> 
									</td>
									<td>
										<input type="radio" name="check_'.$attachment->id.'" class="s_check" id="s_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$schecked.' title="Sales"><label for="s_check_'.$attachment->id.'" title="Sales">S</label> 
									</td>
									<td>
										<input type="radio" name="check_'.$attachment->id.'" class="o_check" id="o_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$ochecked.' title="Other Sundry"><label for="o_check_'.$attachment->id.'" title="Other Sundry">O</label> 
									</td>';
									if($file->show_previous == 1)
									{
										$downloadfile.='<td class="td_input">
											<input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$attachment->id.'" value="'.$attachment->supplier.'" data-value="'.$attachment->supplier.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$attachment->id.'" value="'.$attachment->code.'" data-value="'.$attachment->code.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="5" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$attachment->id.'" value="'.$attachment->date_attachment.'" data-value="'.$attachment->date_attachment.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$file->id.'" value="'.number_format_invoice_empty($attachment->percent_one).'" data-value="'.number_format_invoice_empty($attachment->percent_one).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$file->id.'" value="'.number_format_invoice_empty($attachment->percent_two).'" data-value="'.number_format_invoice_empty($attachment->percent_two).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$file->id.'" value="'.number_format_invoice_empty($attachment->percent_three).'" data-value="'.number_format_invoice_empty($attachment->percent_three).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$file->id.'" value="'.number_format_invoice_empty($attachment->percent_five).'" data-value="'.number_format_invoice_empty($attachment->percent_five).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$file->id.'" value="'.number_format_invoice_empty($attachment->percent_four).'" data-value="'.number_format_invoice_empty($attachment->percent_four).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input" style="border-left:1px solid #b5b3b3">
											<input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$attachment->id.'" value="'.number_format_invoice_empty($attachment->net).'" data-value="'.number_format_invoice_empty($attachment->net).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
										</td>
										<td class="td_input">
											<input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$attachment->id.'" value="'.number_format_invoice_empty($attachment->vat).'" data-value="'.number_format_invoice_empty($attachment->vat).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
										</td>
										<td class="td_input" style="border-right:1px solid #b5b3b3">
											<input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$attachment->id.'" value="'.number_format_invoice_empty($attachment->gross).'" data-value="'.number_format_invoice_empty($attachment->gross).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
										</td>
										<td class="td_input">
											<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$attachment->id.'" value="'.$attachment->currency.'" data-value="'.$attachment->currency.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$attachment->id.'" value="'.$attachment->value.'" data-value="'.$attachment->value.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>';
									}
									else{
										$downloadfile.='<td class="td_input">
											<input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$attachment->id.'" value="" data-value="'.$attachment->supplier.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$attachment->id.'" value="" data-value="'.$attachment->code.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="5" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$attachment->id.'" value="" data-value="'.$attachment->date_attachment.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_one).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_two).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_three).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_five).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_four).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
										</td>
										<td class="td_input" style="border-left:1px solid #b5b3b3">
											<input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$attachment->id.'" value="" data-value="'.number_format_invoice_empty($attachment->net).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
										</td>
										<td class="td_input">
											<input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$attachment->id.'" value="" data-value="'.number_format_invoice_empty($attachment->vat).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
										</td>
										<td class="td_input" style="border-right:1px solid #b5b3b3">
											<input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$attachment->id.'" value="" data-value="'.number_format_invoice_empty($attachment->gross).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
										</td>
										<td class="td_input">
											<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$attachment->id.'" value="" data-value="'.$attachment->currency.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'">
										</td>
										<td class="td_input">
											<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$attachment->id.'" value="" data-value="'.$attachment->value.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'">
										</td>';
									}
									$downloadfile.='<td class="td_input">
										<a href="javascript:" class="fa fa-download download_rename" data-src="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-element="'.$attachment->id.'" title="Download & Rename" style="'.$hide.'"></a>
										<a href="javascript:" class="fa fa-plus-circle add_secondary" data-element="'.$attachment->id.'" title="Add Seconday Line"></a>
										<i class="fa fa-circle" aria-hidden="true" style="display:none"></i>
									</td>
								</tr>';
								if(count($get_sub_attachments))
				                {
				                  foreach($get_sub_attachments as $sub)
				                  {
				                    if($sub->p == 1) { $attach_sub_disabled = ''; }
				                    elseif($sub->s == 1) { $attach_sub_disabled = ''; }
				                    else { $attach_sub_disabled = 'disabled'; }
				                    if($sub->textstatus == 1) { $texticonsub="display:none"; $hidesub = 'display:initial'; } else { $texticonsub="display:initial"; $hidesub = 'display:none'; }

				                    if($sub->flag == 0) { $flag_sub = '<i class="fa fa-flag flag_gray_sub fileattachment"></i>'; }
					                elseif($sub->flag == 1) { $flag_sub = '<i class="fa fa-flag flag_orange_sub fileattachment"></i>'; }
					                elseif($sub->flag == 2) { $flag_sub = '<i class="fa fa-flag flag_red_sub fileattachment"></i>'; }

				                    $downloadfile.= '<tr class="sub_attachment attachment_tr attachment_tr_'.$attachment->id.'" data-element="'.$file->id.'" data-value="'.$sub->id.'">
				                      <td colspan="5">
			                        	<input type="text" name="add_text" class="add_text '.$disable_class.'" data-element="'.$sub->id.'" value="'.$sub->textval.'" placeholder="Add Text" style="margin-left:7.5%;'.$hidesub.'">
			                        	'.$flag_sub.'
				                      </td>';
				                      if($file->show_previous == 1)
									  {
					                      $downloadfile.= '<td class="td_input">
					                        <input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$sub->id.'" value="'.$sub->supplier.'" data-value="'.$sub->supplier.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$sub->id.'" value="'.$sub->code.'" data-value="'.$sub->code.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$sub->id.'" value="'.$sub->date_attachment.'" data-value="'.$sub->date_attachment.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$file->id.'" value="'.number_format_invoice_empty($sub->percent_one).'" data-value="'.number_format_invoice_empty($sub->percent_one).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$file->id.'" value="'.number_format_invoice_empty($sub->percent_two).'" data-value="'.number_format_invoice_empty($sub->percent_two).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$file->id.'" value="'.number_format_invoice_empty($sub->percent_three).'" data-value="'.number_format_invoice_empty($sub->percent_three).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$file->id.'" value="'.number_format_invoice_empty($sub->percent_five).'" data-value="'.number_format_invoice_empty($sub->percent_five).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$file->id.'" value="'.number_format_invoice_empty($sub->percent_four).'" data-value="'.number_format_invoice_empty($sub->percent_four).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
					                      </td>
					                      <td class="td_input" style="border-left:1px solid #b5b3b3">
					                        <input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$sub->id.'" value="'.number_format_invoice_empty($sub->net).'" data-value="'.number_format_invoice_empty($sub->net).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
					                      </td>
					                      <td class="td_input">
					                        <input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$sub->id.'" value="'.number_format_invoice_empty($sub->vat).'" data-value="'.number_format_invoice_empty($sub->vat).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
					                      </td>
					                      <td class="td_input" style="border-right:1px solid #b5b3b3">
					                        <input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$sub->id.'" value="'.number_format_invoice_empty($sub->gross).'" data-value="'.number_format_invoice_empty($sub->gross).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
					                      </td>
					                      <td class="td_input">
												<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$sub->id.'" value="'.$sub->currency.'" data-value="'.$sub->currency.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
											</td>
											<td class="td_input">
												<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$sub->id.'" value="'.$sub->value.'" data-value="'.$sub->value.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
											</td>';
				                  	  }
				                  	  else{
				                  	  	$downloadfile.= '<td class="td_input">
				                        <input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$sub->id.'" value="" data-value="'.$sub->supplier.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input">
											<input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$sub->id.'" value="" data-value="'.$sub->code.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="5" '.$attach_sub_disabled.'>
										</td>
				                      <td class="td_input">
				                        <input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$sub->id.'" value="" data-value="'.$sub->date_attachment.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input">
				                        <input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_one).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input">
				                        <input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_two).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input">
				                        <input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_three).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input">
				                        <input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_five).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input">
				                        <input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_four).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
				                      </td>
				                      <td class="td_input" style="border-left:1px solid #b5b3b3">
				                        <input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$sub->id.'" value="" data-value="'.number_format_invoice_empty($sub->net).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
				                      </td>
				                      <td class="td_input">
				                        <input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$sub->id.'" value="" data-value="'.number_format_invoice_empty($sub->vat).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
				                      </td>
				                      <td class="td_input" style="border-right:1px solid #b5b3b3">
				                        <input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$sub->id.'" value="" data-value="'.number_format_invoice_empty($sub->gross).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
				                      </td>
				                      <td class="td_input">
											<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$sub->id.'" value="" data-value="'.$sub->currency.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
										</td>
										<td class="td_input">
											<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$sub->id.'" value="" data-value="'.$sub->value.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
										</td>';
				                  	  }
				                      $downloadfile.= '<td class="td_input">
				                        <i class="fa fa-circle" aria-hidden="true" style="display:none"></i>
				                      </td>
				                    </tr>';
				                  }
				                }
				                $downloadfile.='<tr class="show_iframe show_iframe_'.$attachment->id.'" style="display:none;">
			                    	<td colspan="15">
				                    	<a href="javascript:" class="show_iframe_prev common_black_button">Previous</a> 
						          		<a href="javascript:" class="show_iframe_next common_black_button">Next</a> 
						          		<a href="javascript:" class="show_iframe_hide common_black_button">Hide</a>
						          		<label class="pdf_multipage" style="margin-left: 10px;">Note: Multipage</label>
						          		<a href="javascript:" class="show_iframe_download common_black_button" style="float: right;margin-top: -7px;" download>Download</a> 
						          		<div style="width:100%;background:#b0a8a8;height:1200px;margin-top: 13px;">
						          			<iframe name="attachment_pdf" class="attachment_pdf" src="" style="width:100%;height: 1200px;"></iframe>
						          		</div>
						          	</td>
						          	<td colspan="3"></td>
			                    </tr>';
			                }
			            $downloadfile.='</tbody></table>
	              	</div>';
	    echo $downloadfile;

		//echo json_encode(array('id' => $id, 'table_content' => $downloadfile));
	}
	public function infile_incomplete_status()
	{
		$status = Input::get('status');
		$data['infile_incomplete'] = $status;
		DB::table('user_login')->where('id',1)->update($data);
	}
	public function get_supplier_names_from_infile()
	{
		$fileid = Input::get('fileid');
		$file = DB::table('in_file')->where('id',$fileid)->first();
		echo $file->supplier;
	}
	public function build_supplier_names_for_client_id()
	{
		$client_id = Input::get('client_id');
		$suppliers = DB::table('in_file')->where('client_id',$client_id)->where('supplier','!=',"")->get();
		$supplier_text = "";
		$arr_supp = array();
		if(count($suppliers))
		{
			foreach($suppliers as $supplier)
			{
				$exp_supp = explode(',',$supplier->supplier);
				if(count($exp_supp))
				{
					foreach($exp_supp as $supp)
					{
						if(trim($supp) != "")
						{
							if(!in_array(strtolower(trim($supp)), $arr_supp))
							{
								if($supplier_text == "")
								{
									$supplier_text = trim($supp);
								}
								else{
									$supplier_text = $supplier_text.','.trim($supp);
								}
								array_push($arr_supp,strtolower(trim($supp)));
							}
						}
					}
				}
			}
			
		}
		$data['supplier'] = $supplier_text;
		DB::table('in_file')->where('client_id',$client_id)->update($data);
		echo $supplier_text;
	}
	public function set_supplier_names_from_infile()
	{
		$fileid = Input::get('fileid');
		$supplier = explode(",",Input::get('supplier'));
		$supplier_text = '';
		if(count($supplier))
		{
			foreach($supplier as $supp)
			{
				if($supp == "")
				{

				}
				else{
					if($supplier_text == "")
					{
						$supplier_text = trim($supp);
					}
					else{
						$supplier_text = $supplier_text.','.trim($supp);
					}
				}
			}
		}
		$data['supplier'] = $supplier_text;
		DB::table('in_file')->where('id',$fileid)->update($data);
		echo $supplier_text;
	}
	public function change_percent_value()
	{
		$fileid = Input::get('fileid');
		$value = Input::get('value');
		$type = Input::get('type');
		if($type == "one")
		{
			$data['percent_one'] = number_format_invoice_without_comma($value);
		}
		if($type == "two")
		{
			$data['percent_two'] = number_format_invoice_without_comma($value);
		}
		if($type == "three")
		{
			$data['percent_three'] = number_format_invoice_without_comma($value);
		}
		if($type == "four")
		{
			$data['percent_four'] = number_format_invoice_without_comma($value);
		}
		if($type == "five")
		{
			$data['percent_five'] = number_format_invoice_without_comma($value);
		}

		DB::table('in_file')->where('id',$fileid)->update($data);
		echo number_format_invoice($value);
	}
	public function infile_supplier_search()
	{
		$value = Input::get('term');
		$fileid = Input::get('fileid');
		$details = DB::table('in_file')->where('id',$fileid)->first();
		$supplier_array = explode(",",$details->supplier);
		$data=array();
		if(count($supplier_array))
		{
			foreach($supplier_array as $supplier)
			{
				$supplierlower = strtolower($supplier);
				$valuelower = strtolower($value);
				if (strpos($supplierlower, $valuelower) !== false) {
					$data[]=array('value'=>$supplier,'id'=>$fileid);
				}
			}
		}
        if(count($data))
             return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
	}
	public function infile_supplier_search_select()
	{
		$fileid = Input::get('fileid');
		$value = Input::get('value');
		$attachmentid = Input::get('attachment_id');
		$data['supplier'] = $value;
		DB::table('in_file_attachment')->where('id',$attachmentid)->update($data);
	}
	public function update_supplier_infile_attachment()
	{
		$fileid = Input::get('fileid');
		$value = Input::get('input');
		$attachmentid = Input::get('attachmentid');
		$type = Input::get('type');
		if($type == "1")
		{
			$get_suppliers = DB::table('in_file')->where('id',$fileid)->first();
			if(count($get_suppliers))
			{
				$suppliers = explode(",",strtolower($get_suppliers->supplier));
				if (!in_array(strtolower(trim($value)), $suppliers))
				{
					if(trim($get_suppliers->supplier) == "")
					{
						$dataval['supplier'] = trim($value);
					}
					else{
						$dataval['supplier'] = $get_suppliers->supplier.','.trim($value);
					}
					DB::table('in_file')->where('id',$fileid)->update($dataval);
				}
			}
		}
		$data['supplier'] = trim($value);
		DB::table('in_file_attachment')->where('id',$attachmentid)->update($data);
	}
	public function update_percent_one_infile_attachment()
	{
		$fileid = Input::get('fileid');
		$value = Input::get('input');

		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);

		$attachmentid = Input::get('attachmentid');
		if($value != "")
		{
			$data['percent_one'] = number_format_invoice_without_comma($value);
		}
		else{
			$data['percent_one'] = '';
		}
		DB::table('in_file_attachment')->where('id',$attachmentid)->update($data);
		$infile_attachment = DB::table('in_file_attachment')->where('id',$attachmentid)->first();
		if(count($infile_attachment))
		{
			$one = $infile_attachment->percent_one;
			$two = $infile_attachment->percent_two;
			$three = $infile_attachment->percent_three;
			$four = $infile_attachment->percent_four;
			$five = $infile_attachment->percent_five;
			if($one == "" && $two == "" && $three == "" && $four =="" && $five =="")
			{
				$netvalue = "";
				$vatvalue = "";
				$grossvalue = "";
			}
			else{
					$netvalue = number_format_invoice_without_comma($one + $two + $three + $four + $five);
				$file = DB::table('in_file')->where('id',$fileid)->first();
				$percent_one = $file->percent_one;
				$percent_two = $file->percent_two;
				$percent_three = $file->percent_three;
				$percent_four = $file->percent_four;
				$percent_five = $file->percent_five;
				if($one != "") { $one_vat = ($one * $percent_one) / 100; } else { $one_vat = '0.00'; }
				if($two != "") { $two_vat = ($two * $percent_two) / 100; } else { $two_vat = '0.00'; }
				if($three != "") { $three_vat = ($three * $percent_three) / 100; } else { $three_vat = '0.00'; }
				if($four != "") { $four_vat = ($four * $percent_four) / 100; } else { $four_vat = '0.00'; }
				if($five != "") { $five_vat = ($five * $percent_five) / 100; } else { $five_vat = '0.00'; }

					$vatvalue = number_format_invoice_without_comma($one_vat + $two_vat + $three_vat + $four_vat + $five_vat);
					$grossvalue = number_format_invoice_without_comma($netvalue + $vatvalue);
			}
				$dataval['net'] = $netvalue;
				$dataval['vat'] = $vatvalue;
				$dataval['gross'] = $grossvalue;

				DB::table('in_file_attachment')->where('id',$attachmentid)->update($dataval);
				if($value == "")
				{
					$dataval['value'] = "";
				}
				else{
					$dataval['value'] = number_format_invoice($value);
				}
		}
		else{
			$dataval['net'] = '';
			$dataval['vat'] = '';
			$dataval['gross'] = '';
			if($value == "")
			{
				$dataval['value'] = "";
			}
			else{
				$dataval['value'] = number_format_invoice($value);
			}
		}
		echo json_encode($dataval);
	}
	public function update_percent_two_infile_attachment()
	{
		$fileid = Input::get('fileid');
		$value = Input::get('input');
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$attachmentid = Input::get('attachmentid');
		if($value != "")
		{
			$data['percent_two'] = number_format_invoice_without_comma($value);
		}
		else{
			$data['percent_two'] = '';
		}
		DB::table('in_file_attachment')->where('id',$attachmentid)->update($data);

		$infile_attachment = DB::table('in_file_attachment')->where('id',$attachmentid)->first();
		if(count($infile_attachment))
		{
			$one = $infile_attachment->percent_one;
			$two = $infile_attachment->percent_two;
			$three = $infile_attachment->percent_three;
			$four = $infile_attachment->percent_four;
			$five = $infile_attachment->percent_five;
			if($one == "" && $two == "" && $three == "" && $four =="" && $five =="")
			{
				$netvalue = "";
				$vatvalue = "";
				$grossvalue = "";
			}
			else{
					$netvalue = number_format_invoice_without_comma($one + $two + $three + $four + $five);
				$file = DB::table('in_file')->where('id',$fileid)->first();
				$percent_one = $file->percent_one;
				$percent_two = $file->percent_two;
				$percent_three = $file->percent_three;
				$percent_four = $file->percent_four;
				$percent_five = $file->percent_five;
				if($one != "") { $one_vat = ($one * $percent_one) / 100; } else { $one_vat = '0.00'; }
				if($two != "") { $two_vat = ($two * $percent_two) / 100; } else { $two_vat = '0.00'; }
				if($three != "") { $three_vat = ($three * $percent_three) / 100; } else { $three_vat = '0.00'; }
				if($four != "") { $four_vat = ($four * $percent_four) / 100; } else { $four_vat = '0.00'; }
				if($five != "") { $five_vat = ($five * $percent_five) / 100; } else { $five_vat = '0.00'; }

					$vatvalue = number_format_invoice_without_comma($one_vat + $two_vat + $three_vat + $four_vat + $five_vat);
					$grossvalue = number_format_invoice_without_comma($netvalue + $vatvalue);
			}
				$dataval['net'] = $netvalue;
				$dataval['vat'] = $vatvalue;
				$dataval['gross'] = $grossvalue;

				DB::table('in_file_attachment')->where('id',$attachmentid)->update($dataval);
				if($value == "")
				{
					$dataval['value'] = "";
				}
				else{
					$dataval['value'] = number_format_invoice($value);
				}
		}
		else{
			$dataval['net'] = '';
			$dataval['vat'] = '';
			$dataval['gross'] = '';
			if($value == "")
			{
				$dataval['value'] = "";
			}
			else{
				$dataval['value'] = number_format_invoice($value);
			}
		}
		echo json_encode($dataval);
	}
	public function update_percent_three_infile_attachment()
	{
		$fileid = Input::get('fileid');
		$value = Input::get('input');
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$attachmentid = Input::get('attachmentid');
		if($value != "")
		{
			$data['percent_three'] = number_format_invoice_without_comma($value);
		}
		else{
			$data['percent_three'] = '';
		}
		DB::table('in_file_attachment')->where('id',$attachmentid)->update($data);

		$infile_attachment = DB::table('in_file_attachment')->where('id',$attachmentid)->first();
		if(count($infile_attachment))
		{
			$one = $infile_attachment->percent_one;
			$two = $infile_attachment->percent_two;
			$three = $infile_attachment->percent_three;
			$four = $infile_attachment->percent_four;
			$five = $infile_attachment->percent_five;
			if($one == "" && $two == "" && $three == "" && $four =="" && $five =="")
			{
				$netvalue = "";
				$vatvalue = "";
				$grossvalue = "";
			}
			else{
					$netvalue = number_format_invoice_without_comma($one + $two + $three + $four + $five);
				$file = DB::table('in_file')->where('id',$fileid)->first();
				$percent_one = $file->percent_one;
				$percent_two = $file->percent_two;
				$percent_three = $file->percent_three;
				$percent_four = $file->percent_four;
				$percent_five = $file->percent_five;
				if($one != "") { $one_vat = ($one * $percent_one) / 100; } else { $one_vat = '0.00'; }
				if($two != "") { $two_vat = ($two * $percent_two) / 100; } else { $two_vat = '0.00'; }
				if($three != "") { $three_vat = ($three * $percent_three) / 100; } else { $three_vat = '0.00'; }
				if($four != "") { $four_vat = ($four * $percent_four) / 100; } else { $four_vat = '0.00'; }
				if($five != "") { $five_vat = ($five * $percent_five) / 100; } else { $five_vat = '0.00'; }

					$vatvalue = number_format_invoice_without_comma($one_vat + $two_vat + $three_vat + $four_vat + $five_vat);
					$grossvalue = number_format_invoice_without_comma($netvalue + $vatvalue);
			}
				$dataval['net'] = $netvalue;
				$dataval['vat'] = $vatvalue;
				$dataval['gross'] = $grossvalue;

				DB::table('in_file_attachment')->where('id',$attachmentid)->update($dataval);
				if($value == "")
				{
					$dataval['value'] = "";
				}
				else{
					$dataval['value'] = number_format_invoice($value);
				}
		}
		else{
			$dataval['net'] = '';
			$dataval['vat'] = '';
			$dataval['gross'] = '';
			if($value == "")
			{
				$dataval['value'] = "";
			}
			else{
				$dataval['value'] = number_format_invoice($value);
			}
		}
		echo json_encode($dataval);
	}
	public function update_percent_four_infile_attachment()
	{
		$fileid = Input::get('fileid');
		$value = Input::get('input');
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$attachmentid = Input::get('attachmentid');
		if($value != "")
		{
			$data['percent_four'] = number_format_invoice_without_comma($value);
		}
		else{
			$data['percent_four'] = '';
		}
		DB::table('in_file_attachment')->where('id',$attachmentid)->update($data);

		$infile_attachment = DB::table('in_file_attachment')->where('id',$attachmentid)->first();
		if(count($infile_attachment))
		{
			$one = $infile_attachment->percent_one;
			$two = $infile_attachment->percent_two;
			$three = $infile_attachment->percent_three;
			$four = $infile_attachment->percent_four;
			$five = $infile_attachment->percent_five;
			if($one == "" && $two == "" && $three == "" && $four =="" && $five =="")
			{
				$netvalue = "";
				$vatvalue = "";
				$grossvalue = "";
			}
			else{
					$netvalue = number_format_invoice_without_comma($one + $two + $three + $four + $five);
				$file = DB::table('in_file')->where('id',$fileid)->first();
				$percent_one = $file->percent_one;
				$percent_two = $file->percent_two;
				$percent_three = $file->percent_three;
				$percent_four = $file->percent_four;
				$percent_five = $file->percent_five;
				if($one != "") { $one_vat = ($one * $percent_one) / 100; } else { $one_vat = '0.00'; }
				if($two != "") { $two_vat = ($two * $percent_two) / 100; } else { $two_vat = '0.00'; }
				if($three != "") { $three_vat = ($three * $percent_three) / 100; } else { $three_vat = '0.00'; }
				if($four != "") { $four_vat = ($four * $percent_four) / 100; } else { $four_vat = '0.00'; }
				if($five != "") { $five_vat = ($five * $percent_five) / 100; } else { $five_vat = '0.00'; }

					$vatvalue = number_format_invoice_without_comma($one_vat + $two_vat + $three_vat + $four_vat + $five_vat);
					$grossvalue = number_format_invoice_without_comma($netvalue + $vatvalue);
			}
				$dataval['net'] = $netvalue;
				$dataval['vat'] = $vatvalue;
				$dataval['gross'] = $grossvalue;

				DB::table('in_file_attachment')->where('id',$attachmentid)->update($dataval);
				if($value == "")
				{
					$dataval['value'] = "";
				}
				else{
					$dataval['value'] = number_format_invoice($value);
				}
		}
		else{
			$dataval['net'] = '';
			$dataval['vat'] = '';
			$dataval['gross'] = '';
			if($value == "")
			{
				$dataval['value'] = "";
			}
			else{
				$dataval['value'] = number_format_invoice($value);
			}
		}
		echo json_encode($dataval);
	}
	public function update_percent_five_infile_attachment()
	{
		$fileid = Input::get('fileid');
		$value = Input::get('input');
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$value = str_replace(",","",$value);
		$attachmentid = Input::get('attachmentid');
		if($value != "")
		{
			$data['percent_five'] = number_format_invoice_without_comma($value);
		}
		else{
			$data['percent_five'] = '';
		}
		DB::table('in_file_attachment')->where('id',$attachmentid)->update($data);

		$infile_attachment = DB::table('in_file_attachment')->where('id',$attachmentid)->first();
		if(count($infile_attachment))
		{
			$one = $infile_attachment->percent_one;
			$two = $infile_attachment->percent_two;
			$three = $infile_attachment->percent_three;
			$four = $infile_attachment->percent_four;
			$five = $infile_attachment->percent_five;
			if($one == "" && $two == "" && $three == "" && $four =="" && $five =="")
			{
				$netvalue = "";
				$vatvalue = "";
				$grossvalue = "";
			}
			else{
					$netvalue = number_format_invoice_without_comma($one + $two + $three + $four + $five);
				$file = DB::table('in_file')->where('id',$fileid)->first();
				$percent_one = $file->percent_one;
				$percent_two = $file->percent_two;
				$percent_three = $file->percent_three;
				$percent_four = $file->percent_four;
				$percent_five = $file->percent_five;
				if($one != "") { $one_vat = ($one * $percent_one) / 100; } else { $one_vat = '0.00'; }
				if($two != "") { $two_vat = ($two * $percent_two) / 100; } else { $two_vat = '0.00'; }
				if($three != "") { $three_vat = ($three * $percent_three) / 100; } else { $three_vat = '0.00'; }
				if($four != "") { $four_vat = ($four * $percent_four) / 100; } else { $four_vat = '0.00'; }
				if($five != "") { $five_vat = ($five * $percent_five) / 100; } else { $five_vat = '0.00'; }

					$vatvalue = number_format_invoice_without_comma($one_vat + $two_vat + $three_vat + $four_vat + $five_vat);
					$grossvalue = number_format_invoice_without_comma($netvalue + $vatvalue);
			}
				$dataval['net'] = $netvalue;
				$dataval['vat'] = $vatvalue;
				$dataval['gross'] = $grossvalue;

				DB::table('in_file_attachment')->where('id',$attachmentid)->update($dataval);
				if($value == "")
				{
					$dataval['value'] = "";
				}
				else{
					$dataval['value'] = number_format_invoice($value);
				}
		}
		else{
			$dataval['net'] = '';
			$dataval['vat'] = '';
			$dataval['gross'] = '';
			if($value == "")
			{
				$dataval['value'] = "";
			}
			else{
				$dataval['value'] = number_format_invoice($value);
			}
		}
		echo json_encode($dataval);
	}
	public function infile_attachment_date_filled()
	{
		$id = Input::get('id');	
		$dd= Input::get('dateval');
		$data['date_attachment'] = $dd;
		DB::table('in_file_attachment')->where('id', $id)->update($data);
	}
	public function save_imported_status()
	{
		$file_id = Input::get('file_id');	
		$status= Input::get('status');
		$data['imported_status'] = $status;
		DB::table('in_file')->where('id', $file_id)->update($data);
	}
	public function save_imported_date()
	{
		$file_id = Input::get('file_id');	
		$date= Input::get('date');
		$data['imported_date'] = $date;
		DB::table('in_file')->where('id', $file_id)->update($data);
	}
	public function infile_attachment_code_filled()
	{
		$id = Input::get('id');	
		$dd= Input::get('code');
		$data['code'] = $dd;
		DB::table('in_file_attachment')->where('id', $id)->update($data);
	}
	public function infile_attachment_currency_filled()
	{
		$id = Input::get('id');	
		$dd= Input::get('currency');
		$data['currency'] = $dd;
		DB::table('in_file_attachment')->where('id', $id)->update($data);
	}
	public function infile_attachment_value_filled()
	{
		$id = Input::get('id');	
		$dd= Input::get('value');
		$data['value'] = $dd;
		DB::table('in_file_attachment')->where('id', $id)->update($data);
	}
	public function infile_download_bpso_all_image_csv()
	{
		$type = Input::get('type');
		$id = Input::get('id');
		$details = DB::table('in_file')->where('id',$id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
		$files = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where($type, 1)->where('notes_type', 0)->where('secondary',0)->get();
		if(count($files))
		{
			$columns_1 = array('Attachment Text', 'P/S Date', 'Supplier/Customer','Code', $details->percent_one.'%', $details->percent_two.'%', $details->percent_three.'%', $details->percent_five.'%', $details->percent_four.'%', 'Net', 'Vat', 'Gross', 'Currency', 'Value', 'Filename');
			$fileopen = fopen('public/Infile_'.$type.'_attachments.csv', 'w');
		    fputcsv($fileopen, $columns_1);

			foreach($files as $file)
			{
				$columns_2 = array($file->textval, $file->date_attachment, $file->supplier,$file->code, $file->percent_one, $file->percent_two, $file->percent_three, $file->percent_five, $file->percent_four, $file->net, $file->vat, $file->gross, $file->currency, $file->value, $file->attachment);
				fputcsv($fileopen, $columns_2);
				$get_subfiles = DB::table('in_file_attachment')->where('attach_id',$file->id)->where('secondary',1)->orderBy('id','asc')->get();
				if(count($get_subfiles))
				{
					foreach($get_subfiles as $subfile)
					{
						$columns_3 = array($subfile->textval, $subfile->date_attachment, $subfile->supplier,$subfile->code, $subfile->percent_one, $subfile->percent_two, $subfile->percent_three, $subfile->percent_five, $subfile->percent_four, $subfile->net, $subfile->vat, $subfile->gross, $subfile->currency, $subfile->value, $file->attachment);
						fputcsv($fileopen, $columns_3);
					}
				}
			}
			fclose($fileopen);
			echo 'Infile_'.$type.'_attachments.csv';
		}
	}
	public function infile_download_bpso_all_image_both()
	{
		$type = Input::get('type');
		$id = Input::get('id');
		$details = DB::table('in_file')->where('id',$id)->first();
		$client_details = DB::table('cm_clients')->where('client_id',$details->client_id)->first();
		$files = DB::table('in_file_attachment')->where('file_id',$id)->where('status', 0)->where($type, 1)->where('notes_type', 0)->where('secondary',0)->get();
		if(count($files))
		{
			$columns_1 = array('Attachment Text', 'P/S Date', 'Supplier/Customer','Code', $details->percent_one.'%', $details->percent_two.'%', $details->percent_three.'%', $details->percent_five.'%', $details->percent_four.'%', 'Net', 'Vat', 'Gross', 'Currency', 'Value', 'Filename');
			$fileopen = fopen('public/Infile_'.$type.'_attachments.csv', 'w');
		    fputcsv($fileopen, $columns_1);

			foreach($files as $file)
			{
				$columns_2 = array($file->textval, $file->date_attachment, $file->supplier, $file->code, $file->percent_one, $file->percent_two, $file->percent_three, $file->percent_five, $file->percent_four, $file->net, $file->vat, $file->gross, $file->currency, $file->value, $file->attachment);
				fputcsv($fileopen, $columns_2);
				$get_subfiles = DB::table('in_file_attachment')->where('attach_id',$file->id)->where('secondary',1)->orderBy('id','asc')->get();
				if(count($get_subfiles))
				{
					foreach($get_subfiles as $subfile)
					{
						$columns_3 = array($subfile->textval, $subfile->date_attachment, $subfile->supplier, $subfile->code, $subfile->percent_one, $subfile->percent_two, $subfile->percent_three, $subfile->percent_five, $subfile->percent_four, $subfile->net, $subfile->vat, $subfile->gross, $subfile->currency, $subfile->value, $file->attachment);
						fputcsv($fileopen, $columns_3);
					}
				}
			}

			fclose($fileopen);

			$public_dir=public_path();
			$zipFileName = $client_details->company.'_'.date('d M Y',strtotime($details->date_added)).'.zip';
			$zip = new ZipArchive;
	       	if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
				foreach($files as $file)
				{
					if($file->textval != "" && $file->textstatus == 1)
					{
						$filename = "QuickID_".$file->textval."_".$file->attachment;
					}
					else{
						$filename = $file->attachment;
					}
		            $zip->addFile($file->url.'/'.$file->attachment,$filename);
				}
				$zip->addFile('public/Infile_'.$type.'_attachments.csv','Infile_'.$type.'_attachments.csv');
				$zip->close();
			}
		}
		echo $zipFileName;
	}
	public function change_show_hide_ps_status()
	{
		$status = Input::get('status');
		$fileid = Input::get('fileid');
		$data['show_previous'] = $status;
		DB::table('in_file')->where('id',$fileid)->update($data);
	}
	public function file_not_supported()
	{
		return view('welcome');
	}
	public function check_pdf_pages()
	{
 		$path = Input::get('src');
 		$pdf = file_get_contents($path); 
		$number = preg_match_all("/\/Page\W/", $pdf, $dummy); 
		echo $number;
	}
	public function change_flag_status()
	{
		$data['flag'] = Input::get('flag_status');
		$fileid = Input::get('fileid');
		DB::table('in_file_attachment')->where('id',$fileid)->update($data);
	}
	public function add_new_secondary_line()
	{
		$attach_id = Input::get('attach_id');
		$get_last_attachment = DB::table('in_file_attachment')->where('attach_id',$attach_id)->orderBy('id','desc')->first();
		$getattachment = DB::table('in_file_attachment')->where('id',$attach_id)->first();
		if(count($get_last_attachment))
		{
			$get_textval = $get_last_attachment->textval;
			if($get_textval == "")
			{
				$add_textval = "";
			}
			else{
				if(is_numeric($get_textval))
				{
					$add_textval = $get_textval + .001;
				}
				else{
					$add_textval = '';
				}
			}
		}
		else{
			$get_textval = $getattachment->textval;
			if($get_textval == "")
			{
				$add_textval = "";
			}
			else{
				if(is_numeric($get_textval))
				{
					$add_textval = $get_textval + .001;
				}
				else{
					$add_textval = '';
				}
			}
		}
		$data['attach_id'] = $attach_id;
		$data['file_id'] = $getattachment->file_id;
		$data['secondary'] = 1;
		$data['check_file'] = 0;
		$data['attachment'] = "";
		$data['url'] = "";
		$data['textval'] = $add_textval;
		$data['b'] = $getattachment->b;
		$data['p'] = $getattachment->p;
		$data['s'] = $getattachment->s;
		$data['o'] = $getattachment->o;
		$data['supplier'] = "";
		$data['date_attachment'] = "";
		$data['code'] = "";
		$data['percent_one'] = "";
		$data['percent_two'] = "";
		$data['percent_three'] = "";
		$data['percent_four'] = "";
		$data['percent_five'] = "";
		$data['net'] = "";
		$data['vat'] = "";
		$data['gross'] = "";
		$data['flag'] = 0;
		$data['textstatus'] = 1;
		$data['status'] = 0;
		$data['notes_type'] = 0;
		$new_id = DB::table('in_file_attachment')->insertGetId($data);

		if($getattachment->p == 1) { $attach_disabled = ''; }
		elseif($getattachment->s == 1) { $attach_disabled = ''; }
		else { $attach_disabled = 'disabled'; }

		$flag_sub = '<i class="fa fa-flag flag_gray_sub fileattachment"></i>';

		$output = '<tr class="sub_attachment attachment_tr attachment_tr_'.$getattachment->id.'" data-element="'.$getattachment->file_id.'" data-value="'.$new_id.'">
			<td colspan="5">
            	<input type="text" name="add_text" class="add_text" data-element="'.$new_id.'" value="'.$add_textval.'" placeholder="Add Text" style="margin-left:7.5%;">
            	'.$flag_sub.'
			</td>
			<td class="td_input">
				<input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" maxlength="50" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" maxlength="50" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" maxlength="50" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$getattachment->file_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$getattachment->file_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$getattachment->file_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$getattachment->file_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$getattachment->file_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input" style="border-left:1px solid #b5b3b3">
				<input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" disabled>
			</td>
			<td class="td_input">
				<input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" disabled>
			</td>
			<td class="td_input" style="border-right:1px solid #b5b3b3">
				<input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" disabled>
			</td>
			<td class="td_input">
				<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$new_id.'" value="" data-value="" data-element="'.$new_id.'" data-file="'.$getattachment->file_id.'" '.$attach_disabled.'>
			</td>
			<td class="td_input">
				<i class="fa fa-circle" aria-hidden="true" style="display:none"></i>
			</td>
		</tr>';
		echo $output;
	}
	public function check_integrity_files()
	{
		$fileid = Input::get('fileid');
		$infile_details = DB::table('in_file')->where('id',$fileid)->first();

		$company = '';
		if(count($infile_details))
		{
			$client_details = DB::table('cm_clients')->where('client_id',$infile_details->client_id)->first();
			$company = $client_details->company;
		}
		$infile_attacments = DB::table('in_file_attachment')->where('file_id',$fileid)->where('secondary',0)->where('notes_type',0)->get();
		$output = '';

		if(count($infile_attacments))
		{
			foreach($infile_attacments as $key => $attachment)
			{
				$key = $key + 1;
				$output.='<tr>
					<td>'.$company.'</td>
					<td>'.$infile_details->client_id.'</td>
					<td class="integrity_attachment overflow-wrap-hack" data-element="'.$attachment->id.'" data-file="'.$attachment->file_id.'" data-key="'.$key.'"><div class="content_check">'.$attachment->attachment.'</div></td>
					<td class="integrity_status integrity_status_'.$attachment->id.'"> - </td>
					<td class="action_status action_status_'.$attachment->id.'"> - </td>
					<td>'.$infile_details->description.'</td>
					<td>'.date("d-M-Y", strtotime($infile_details->date_added)).'</td>
				</tr>';

				
			}
		}

		$dir = "papers/infile_report/".$fileid;
		$files = '<h5>Download</h5>';
		$url = '';
		// Open a directory, and read its contents
		if (is_dir($dir)){
		  if ($dh = opendir($dir)){
		    while (($file = readdir($dh)) !== false){
		    	if($file != '.' && $file != '..'){
		    		$files.='<p><a href="'.URL::to($dir.'/'.$file).'" download="">'.$file.'</a></p>';
		    		$url = URL::to($dir.'/'.$file);
		    	}
		    }
		    closedir($dh);
		  }
		}

		echo json_encode(array("output" => $output,"files" => $files,'url' => $url));
	}
	public function check_integrity_files_client_id()
	{
		$client_id = Input::get('client_id');
		$client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
		$company = $client_details->company;
		$infile_details = DB::table('in_file')->where('client_id',$client_id)->get();
		$output = '';

		$type = Input::get('type');
		$filepath = Input::get('filepath');
		$filename = Input::get('filename');

		if($type == "0")
		{
			$columns = array("Client","Client ID", "Filename", "Status", "Description", "Date Added");

			$upload_dir = 'papers/infile_report';
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}
			$upload_dir = $upload_dir.'/'.$client_id;
			if (!file_exists($upload_dir)) {
				mkdir($upload_dir);
			}

			$filename = "IntegrityCheckReport".date('d-M-Y')."_".time().".csv";
			$file = fopen($upload_dir."/".$filename,"w");
			fputcsv($file, $columns);

			$filename_url = URL::to($upload_dir."/".$filename);
			$ffname = $filename;
			$filepath = $upload_dir."/".$filename;
		}
		else{
			$file = fopen($filepath,"a");

			$filename_url = URL::to($filepath);
			$ffname = $filename;
			$filepath = $filepath;
		}
		if(count($infile_details))
		{
			foreach($infile_details as $infile)
			{
				$fileid = $infile->id;
				$infile_attacments = DB::table('in_file_attachment')->where('file_id',$fileid)->where('secondary',0)->where('notes_type',0)->get();
				
				if(count($infile_attacments))
				{
					foreach($infile_attacments as $key => $attachment)
					{
						if(!file_exists($attachment->url.'/'.$attachment->attachment))
						{
							$status = 'Missing';
						}
						else{
							$status = 'Ok';
						}
						$key = $key + 1;
						$output.='<tr>
							<td>'.$company.'</td>
							<td>'.$infile->client_id.'</td>
							<td class="integrity_attachment overflow-wrap-hack" data-element="'.$attachment->id.'" data-file="'.$attachment->file_id.'" data-key="'.$key.'"><div class="content_check">'.$attachment->attachment.'</div></td>
							<td class="integrity_status integrity_status_'.$attachment->id.'">'.$status.'</td>
							<td class="action_status action_status_'.$attachment->id.'"> - </td>
							<td>'.$infile->description.'</td>
							<td>'.date("d-M-Y", strtotime($infile->date_added)).'</td>
						</tr>';


						if($type == "0")
						{
							$columns1 = array($company,$infile->client_id, $attachment->attachment, $status, $infile->description, date("d-M-Y", strtotime($infile->date_added)));
							fputcsv($file, $columns1);
						}
						else{
							$columns1 = array($company,$infile->client_id, $attachment->attachment, $status, $infile->description, date("d-M-Y", strtotime($infile->date_added)));
							fputcsv($file, $columns1);
						}
					}
				}
			}
		}
		else{
			$output.='<tr>
							<td>'.$company.'</td>
							<td>'.$client_id.'</td>
							<td>No Infile Items Found</td>
							<td>-</td>
							<td> - </td>
							<td> - </td>
							<td> - </td>
						</tr>';
			$columns1 = array($company,$client_id, 'No Infile Items Found', '-', '-', '-');
			fputcsv($file, $columns1);
		}
		fclose($file);

		echo json_encode(array("output" => $output, "url" => $filename_url, "filename" => $ffname, 'filepath' => $filepath));
	}
	public function check_files_in_files()
	{
		$fileid = Input::get('fileid');
		$type = Input::get('type');
		$round = Input::get('round');
		$filepath = Input::get('filepath');
		$filename = Input::get('filename');

		$check_file = DB::table('in_file_attachment')->where('id',$fileid)->first();
		if(count($check_file))
		{
			$check_datetime = date('Y-m-d H:i:s');
			$datavalll['integrity_check'] = 1;
			$datavalll['check_date_time'] = $check_datetime;
			DB::table('in_file')->where('id',$check_file->file_id)->update($datavalll);

			if(!file_exists($check_file->url.'/'.$check_file->attachment))
			{
				$status = 'Missing';
			}
			else{
				$status = 'Ok';
			}

			if($type == "0")
			{
				$item_details = DB::table('in_file')->where('id',$check_file->file_id)->first();
				$client_details = DB::table('cm_clients')->where('client_id',$item_details->client_id)->first();
				$columns = array("Client","Client ID", "Filename", "Status", "Description", "Date Added");

				$upload_dir = 'papers/infile_report';
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}
				$upload_dir = $upload_dir.'/'.$check_file->file_id;
				if (!file_exists($upload_dir)) {
					mkdir($upload_dir);
				}

				$filename = "IntegrityCheckReport".date('d-M-Y')."_".time().".csv";

				$data['url'] = $upload_dir."/".$filename;
				$data['filename'] = $filename;
				$data['fileid'] = $check_file->file_id;

				DB::table('infile_check_report')->insert($data);

				$file = fopen($upload_dir."/".$filename,"w");
				fputcsv($file, $columns);

				$columns1 = array($client_details->company,$item_details->client_id, $check_file->attachment, $status, $item_details->description, date("d-M-Y", strtotime($item_details->date_added)));
				fputcsv($file, $columns1); //@Optimist

				fclose($file);

				$filename_url = URL::to($upload_dir."/".$filename);
				$ffname = $filename;
				$filepath = $upload_dir."/".$filename;
			}
			else{
				$item_details = DB::table('in_file')->where('id',$check_file->file_id)->first();
				$client_details = DB::table('cm_clients')->where('client_id',$item_details->client_id)->first();
				$file = fopen($filepath,"a");
				//fwrite($fp, $row1.",".$row2); //Append row,row to file
				$columns = array($client_details->company,$item_details->client_id, $check_file->attachment, $status, $item_details->description, date("d-M-Y", strtotime($item_details->date_added)));

				fputcsv($file, $columns); //@Optimist
				fclose($file); //Close the file to free memory.

				$filename_url = URL::to($filepath);
				$ffname = $filename;
				$filepath = $filepath;
			}

			if(!file_exists($check_file->url.'/'.$check_file->attachment)) {
				$status = '<spam class="files_spam missing_spam" style="color:#f00;font-weight:600"><spam class="hide_attach" style="display:none">'.strtolower($check_file->attachment).'</spam>Missing</spam><input type="hidden" name="hidden_file_missing" id="hidden_file_missing" class="hidden_file_missing" value="">';
			}
			else{
				$status = '<spam class="files_spam ok_spam" style="color:green;font-weight:600">Ok</spam>';
			}

			echo json_encode(array("status" => $status, "url" => $filename_url, "filename" => $ffname, 'filepath' => $filepath, 'fileitem_id' => $check_file->file_id));
		}

	}
	public function show_attachments_infile()
	{
		$fileid = Input::get('fileid');
		$file = DB::table('in_file')->where('id',$fileid)->first();
		$attachments = DB::table('in_file_attachment')->where('file_id',$fileid)->where('status',0)->where('notes_type', 0)->where('secondary',0)->get();
		if($file->status == 0){
	        $staus = 'fa-check edit_status'; 
	        $statustooltip = 'Complete Infile';
	        $disable = '';
	        $disable_class = '';
	        $color='';
	        $color_last='style="border-top:0px solid;border-bottom:1px solid #6a6a6a"';
	    }
	    else{
	        $staus = 'fa-times edit_status incomplete_status';
	        $statustooltip = 'InComplete Infile';
	        $disable = 'disabled';
	        $disable_class = 'disable_class';
	        $color = 'style="color:#f00;"';
	        $color_last = 'style="color:#f00;border-top:0px solid;border-bottom:1px solid #6a6a6a"';
	    }
        $ips_data = 0;
        $downloadfile='';
        if(count($attachments)){  
          $downloadfile.='
          <style>
            .bpso_all_check{font-size:20px; font-weight:700; margin-left:10px;}
            .bpso_all_check:hover{text-decoration:none}
           	.table_bspo .td_input { padding:3px !important; }
          </style>
          <div class="row infile_inner_table_row">
          	<div class="col-md-12">
          		<table class="table_bspo table-fixed-header" id="bspo_id_'.$file->id.'" style="width:100%;">
          			<thead>
		                <tr>
		                  <th style="width:18%;text-align:left">
		                  	<a data-toggle="popover" data-container="body" data-placement="right" type="button" data-html="true" href="javascript:" id="login_'.$file->id.'" class="auto_increment" data-element="'.$file->id.'" style="margin-left: 18%;">AutoQue</a>

		                  	<div id="popover-content-login_'.$file->id.'" class="hide">
								<input type="radio" name="item_auto_num" class="item_auto_num item_auto_num_'.$file->id.'" id="purchase_item_auto_'.$file->id.'" value="1"><label for="purchase_item_auto">Purchase Item</label><br/>
								<input type="radio" name="item_auto_num" class="item_auto_num item_auto_num_'.$file->id.'" id="sales_item_auto_'.$file->id.'" value="2"><label for="sales_item_auto">Sales Item</label>
								<div class="form-data">
									<h4>Enter Number:</h4>
									<input type="number" class="form-control auto_number_value_'.$file->id.'" id="auto_number_value" value="">

									<h4>Enter Increment Value:</h4>
									<input type="number" class="form-control inc_number_value_'.$file->id.'" id="inc_number_value" value="">
									<input type="button" name="submit_auto_value" class="submit_auto_value common_black_button" data-element="'.$file->id.'" value="Number Now" style="margin-top: 10px;">
									<input type="button" name="submit_re_number" class="submit_re_number common_black_button" data-element="'.$file->id.'" value="RE-Number">
								</div>
							</div>
		                  </th>
		                  <th style="width:2%">
		                    <div style="width:100%; text-align:center">
		                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in B Category" id="'.$file->id.'" data-element="1">@</a>
		                    </div>
		                    <div style="width:100%; text-align:center">
		                    <i class="fa fa-cloud-download download_b_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in B Category"></i>
		                    </div>
		                  </th>
		                  <th style="width:2%">
		                    <div style="width:100%; text-align:center">
		                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in P Category" id="'.$file->id.'" data-element="2">@</a>
		                    </div>
		                    <div style="width:100%; text-align:center">
		                    <i class="fa fa-cloud-download download_p_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in P Category"></i>
		                    </div>
		                  </th>
		                  <th style="width:2%">
		                    <div style="width:100%; text-align:center">
		                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in S Category" id="'.$file->id.'" data-element="3">@</a>
		                    </div>
		                    <div style="width:100%; text-align:center">
		                    <i class="fa fa-cloud-download download_s_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in S Category"></i>
		                    </div>
		                  </th>
		                  <th style="width:2%">
		                    <div style="width:100%; text-align:center">
		                      <a href="javascript:" class="bpso_all_check" data-toggle="tooltip" title="Select Missed Items in O Category" id="'.$file->id.'" data-element="4">@</a>
		                    </div>
		                    <div style="width:100%; text-align:center">
		                    <i class="fa fa-cloud-download download_o_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments in O Category"></i>
		                    </div>
		                  </th>
		                  <th class="td_input td_supplier" style="width:10%;font-weight:600;text-align:center" data-element="'.$file->id.'">Supplier/Customer</th>
		                  <th class="td_input td_code" style="font-weight:600;text-align:center;width:5%;">Code</th>
		                  <th class="td_input td_date" style="font-weight:600;text-align:center;width:7%;">Date</th>
		                  <th class="td_input td_percent_one" style="font-weight:600;text-align:center;width:5%;">
		                  	% <br/><spam class="percent_one_text">'.$file->percent_one.'</spam>
		                  	<div class="percent_one_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
		                  		<input type="number" name="change_percent_one" class="change_percent_one form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
		                  		<input type="button" name="submit_percent_one" class="common_black_button submit_percent_one" value="Submit" data-element="'.$file->id.'">
		                  	</div>
		                  </th>
		                  <th class="td_input td_percent_two" style="font-weight:600;text-align:center;width:5%;">
		                  	% <br/><spam class="percent_two_text">'.$file->percent_two.'</spam>
		                  	<div class="percent_two_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
		                  		<input type="number" name="change_percent_two" class="change_percent_two form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
		                  		<input type="button" name="submit_percent_two" class="common_black_button submit_percent_two" value="Submit" data-element="'.$file->id.'">
		                  	</div>
		                  </th>
		                  <th class="td_input td_percent_three" style="font-weight:600;text-align:center;width:5%;">
		                  	% <br/><spam class="percent_three_text">'.$file->percent_three.'</spam>
		                  	<div class="percent_three_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
		                  		<input type="number" name="change_percent_three" class="change_percent_three form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
		                  		<input type="button" name="submit_percent_three" class="common_black_button submit_percent_three" value="Submit" data-element="'.$file->id.'">
		                  	</div>
		                  </th>
		                  <th class="td_input td_percent_five" style="font-weight:600;text-align:center;width:5%;">
		                  	% <br/><spam class="percent_five_text">'.$file->percent_five.'</spam>
		                  	<div class="percent_five_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
		                  		<input type="number" name="change_percent_five" class="change_percent_five form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
		                  		<input type="button" name="submit_percent_five" class="common_black_button submit_percent_five" value="Submit" data-element="'.$file->id.'">
		                  	</div>
		                  </th>
		                  <th class="td_input td_percent_four" style="font-weight:600;text-align:center;width:5%;">
		                  	% <br/><spam class="percent_four_text">'.$file->percent_four.'</spam>
		                  	<div class="percent_four_div" style="position: absolute;width: 200px;background: #bfbfbf;padding: 10px;display:none">
		                  		<input type="number" name="change_percent_four" class="change_percent_four form-control" data-element="'.$file->id.'" value="" style="width: 80px;float: left;">
		                  		<input type="button" name="submit_percent_four" class="common_black_button submit_percent_four" value="Submit" data-element="'.$file->id.'">
		                  	</div>
		                  </th>
		                  <th class="td_input" style="font-weight:600;text-align:center;border-left:1px solid #b5b3b3;width:5%;">Net</th>
		                  <th class="td_input" style="font-weight:600;text-align:center;width:5%;">VAT</th>
		                  <th class="td_input" style="font-weight:600;text-align:center;border-right:1px solid #b5b3b3;width:5%;">Gross</th>
		                  <th class="td_input" style="font-weight:600;text-align:center;width:6%;">Currency</th>
		                  <th class="td_input" style="font-weight:600;text-align:center;width:6%;">Value</th>
		                  <th class="td_input" style="width:20px;font-weight:600;text-align:center;width:2%;"></th>
		                </tr>
	                </thead>
	                <tbody>';     
              		$ips_data = 0;              
	                foreach($attachments as $attachment){
                		$get_sub_attachments = DB::table('in_file_attachment')->where('attach_id',$attachment->id)->where('secondary',1)->orderBy('id','asc')->get();
	                	if($attachment->textstatus == 1) { $texticon="display:none"; $hide = 'display:initial'; } else { $texticon="display:initial"; $hide = 'display:none'; }
						if($attachment->check_file == 1) { $textdisabled ='disabled'; $checked = 'checked'; } else { $textdisabled =''; $checked = ''; }
						if($attachment->b == 1) {  $bchecked = 'checked'; } else { $bchecked = ''; }
						if($attachment->p == 1) {  $pchecked = 'checked'; } else { $pchecked = ''; }
						if($attachment->s == 1) {  $schecked = 'checked'; } else { $schecked = ''; }
						if($attachment->o == 1) {  $ochecked = 'checked'; } else { $ochecked = ''; }

						if($attachment->p == 1) { $attach_disabled = ''; }
						elseif($attachment->s == 1) { $attach_disabled = ''; }
						else { $attach_disabled = 'disabled'; }
        				if($attachment->supplier != "" || $attachment->date_attachment != "" || $attachment->percent_one != "" || $attachment->percent_two != "" || $attachment->percent_three != "" || $attachment->percent_four != "" || $attachment->percent_five != "")
		                {
		                  $ips_data++;
		                }
		                if($attachment->flag == 0) { $flag = '<i class="fa fa-flag flag_gray fileattachment"></i>'; }
		                elseif($attachment->flag == 1) { $flag = '<i class="fa fa-flag flag_orange fileattachment"></i>'; }
		                elseif($attachment->flag == 2) { $flag = '<i class="fa fa-flag flag_red fileattachment"></i>'; }

						$downloadfile.= '<tr class="attachment_tr attachment_tr_main" data-element="'.$file->id.'">
							<td style="min-width:300px;max-width:300px;">
								<div class="file_attachment_div" style="width:100%">
								  	<input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'>
								  	<label for="fileattach_'.$attachment->id.'">&nbsp;</label>
								  	<input type="text" name="add_text" class="add_text '.$disable_class.'" data-element="'.$attachment->id.'" value="'.$attachment->textval.'" placeholder="Add Text" '.$textdisabled.' style="'.$hide.'">
                					'.$flag.'
                					<a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a>
									<a href="javascript:" class="fileattachment file_attach_bpso" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" '.$color.' data-toggle="tooltip" title="'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.substr($attachment->attachment,0,15).'</a>
								</div>
							</td>
							<td>
								<input type="radio" name="check_'.$attachment->id.'" class="b_check" id="b_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$bchecked.' title="Bank"><label for="b_check_'.$attachment->id.'" title="Bank">B</label> 
							</td>
							<td>
								<input type="radio" name="check_'.$attachment->id.'" class="p_check" id="p_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$pchecked.' title="Purchases"><label for="p_check_'.$attachment->id.'" title="Purchases">P</label> 
							</td>
							<td>
								<input type="radio" name="check_'.$attachment->id.'" class="s_check" id="s_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$schecked.' title="Sales"><label for="s_check_'.$attachment->id.'" title="Sales">S</label> 
							</td>
							<td>
								<input type="radio" name="check_'.$attachment->id.'" class="o_check" id="o_check_'.$attachment->id.'" value="'.$attachment->id.'" '.$ochecked.' title="Other Sundry"><label for="o_check_'.$attachment->id.'" title="Other Sundry">O</label> 
							</td>';
							$downloadfile.='<td class="td_input">
								<input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$attachment->id.'" value="" data-value="'.$attachment->supplier.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$attachment->id.'" value="" data-value="'.$attachment->code.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="5" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$attachment->id.'" value="" data-value="'.$attachment->date_attachment.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_one).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_two).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_three).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_five).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($attachment->percent_four).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>
							<td class="td_input" style="border-left:1px solid #b5b3b3">
								<input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$attachment->id.'" value="" data-value="'.number_format_invoice_empty($attachment->net).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
							</td>
							<td class="td_input">
								<input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$attachment->id.'" value="" data-value="'.number_format_invoice_empty($attachment->vat).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
							</td>
							<td class="td_input" style="border-right:1px solid #b5b3b3">
								<input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$attachment->id.'" value="" data-value="'.number_format_invoice_empty($attachment->gross).'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" disabled>
							</td>
							<td class="td_input">
								<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$attachment->id.'" value="" data-value="'.$attachment->currency.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>
							<td class="td_input">
								<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$attachment->id.'" value="" data-value="'.$attachment->value.'" data-element="'.$attachment->id.'" data-file="'.$file->id.'" '.$attach_disabled.'>
							</td>';
							$downloadfile.='<td class="td_input">
								<a href="javascript:" class="fa fa-download download_rename" data-src="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-element="'.$attachment->id.'" title="Download & Rename" style="'.$hide.'"></a>
								<a href="javascript:" class="fa fa-plus-circle add_secondary '.$disable_class.'" data-element="'.$attachment->id.'" title="Add Seconday Line"></a>
								<i class="fa fa-circle" aria-hidden="true" style="display:none"></i>
							</td>
						</tr>';
		                if(count($get_sub_attachments))
		                {
		                  foreach($get_sub_attachments as $sub)
		                  {
		                    if($sub->p == 1) { $attach_sub_disabled = ''; }
		                    elseif($sub->s == 1) { $attach_sub_disabled = ''; }
		                    else { $attach_sub_disabled = 'disabled'; }
		                    if($sub->textstatus == 1) { $texticonsub="display:none"; $hidesub = 'display:initial'; } else { $texticonsub="display:initial"; $hidesub = 'display:none'; }

		                    if($sub->flag == 0) { $flag_sub = '<i class="fa fa-flag flag_gray_sub fileattachment"></i>'; }
			                elseif($sub->flag == 1) { $flag_sub = '<i class="fa fa-flag flag_orange_sub fileattachment"></i>'; }
			                elseif($sub->flag == 2) { $flag_sub = '<i class="fa fa-flag flag_red_sub fileattachment"></i>'; }

		                    $downloadfile.= '<tr class="sub_attachment attachment_tr attachment_tr_'.$attachment->id.'" data-element="'.$file->id.'" data-value="'.$sub->id.'">
		                      <td colspan="5">
		                        	<input type="text" name="add_text" class="add_text '.$disable_class.'" data-element="'.$sub->id.'" value="'.$sub->textval.'" placeholder="Add Text" style="margin-left:7.5%;'.$hidesub.'">
		                        	'.$flag_sub.'
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="supplier" class="form-control ps_data supplier supplier_'.$sub->id.'" value="" data-value="'.$sub->supplier.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="code_attachment" class="form-control ps_data code_attachment code_attachment_'.$sub->id.'" value="" data-value="'.$sub->code.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="date_attachment" class="form-control ps_data date_attachment date_attachment_'.$sub->id.'" value="" data-value="'.$sub->date_attachment.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" maxlength="50" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="percent_one_value" class="form-control ps_data percent_one_value percent_one_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_one).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="percent_two_value" class="form-control ps_data percent_two_value percent_two_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_two).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="percent_three_value" class="form-control ps_data percent_three_value percent_three_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_three).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="percent_five_value" class="form-control ps_data percent_five_value percent_five_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_five).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="percent_four_value" class="form-control ps_data percent_four_value percent_four_value_'.$file->id.'" value="" data-value="'.number_format_invoice_empty($sub->percent_four).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
		                      </td>
		                      <td class="td_input" style="border-left:1px solid #b5b3b3">
		                        <input type="text" name="net_value" class="form-control ps_data net_value net_value_'.$sub->id.'" value="" data-value="'.number_format_invoice_empty($sub->net).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
		                      </td>
		                      <td class="td_input">
		                        <input type="text" name="vat_value" class="form-control ps_data vat_value vat_value_'.$sub->id.'" value="" data-value="'.number_format_invoice_empty($sub->vat).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
		                      </td>
		                      <td class="td_input" style="border-right:1px solid #b5b3b3">
		                        <input type="text" name="gross_value" class="form-control ps_data gross_value gross_value_'.$sub->id.'" value="" data-value="'.number_format_invoice_empty($sub->gross).'" data-element="'.$sub->id.'" data-file="'.$file->id.'" disabled>
		                      </td>
		                        <td class="td_input">
									<input type="text" name="currency_value" class="form-control ps_data currency_value currency_value_'.$sub->id.'" value="" data-value="'.$sub->currency.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
								</td>
								<td class="td_input">
									<input type="text" name="value_value" class="form-control ps_data value_value value_value_'.$sub->id.'" value="" data-value="'.$sub->value.'" data-element="'.$sub->id.'" data-file="'.$file->id.'" '.$attach_sub_disabled.'>
								</td>
		                      <td class="td_input">
		                        <i class="fa fa-circle" aria-hidden="true" style="display:none"></i>
		                      </td>
		                    </tr>';
		                  }
		                }
		                $downloadfile.='<tr class="show_iframe show_iframe_'.$attachment->id.'" style="display:none;">
	                    	<td colspan="15">
		                    	<a href="javascript:" class="show_iframe_prev common_black_button">Previous</a> 
				          		<a href="javascript:" class="show_iframe_next common_black_button">Next</a> 
				          		<a href="javascript:" class="show_iframe_hide common_black_button">Hide</a>
				          		<label class="pdf_multipage" style="margin-left: 10px;">Note: Multipage</label>
				          		<a href="javascript:" class="show_iframe_download common_black_button" style="float: right;margin-top: -7px;" download>Download</a> 
				          		<div style="width:100%;background:#b0a8a8;height:1200px;margin-top: 13px;">
				          			<iframe name="attachment_pdf" class="attachment_pdf" src="" style="width:100%;height: 1200px;"></iframe>
				          		</div>
				          	</td>
				          	<td colspan="3"></td>
	                    </tr>';
	                }
			    $downloadfile.='</tbody></table>
	        </div>
	      </div>';
	    }
	    else{
	        $downloadfile ='';
	    }
		if(count($attachments)){
			$deleteall = '<i class="fa fa-minus-square delete_all_image '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>

			<i class="fa fa-cloud-download download_rename_all_image" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download & Rename All Attachments"></i>

			<i class="fa fa-file-text-o summary_infile_attachments" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Summary"></i>';
		}
		else{
			$deleteall = '';
		}

		if(count($attachments))
		{
			$span = '<span style="color:#000">There are '.count($attachments).' file(s)</span>';
		}
		else{
			$span = '';
		}
		if($ips_data > 0)
		{
			$ps_data_btn = '<i class="fa fa-dot-circle-o" style="color:green;font-size: 20px;"></i>';
		}
		else{
			$ps_data_btn = '';
		}

		$output = $downloadfile.'
			<i class="fa fa-plus faplus '.$disable_class.'" style="margin-top:10px" aria-hidden="true" title="Add Attachment"></i>              
				'.$deleteall.'<br/><br/>
			<div style="width:100%; height:auto; float:left; padding-bottom:10px; color: #0300c1;">Notes:
				  <br/> '.$span.'
			</div>
			<div class="clearfix"></div>';
      	echo json_encode(array("output" => mb_convert_encoding($output,'UTF-8','UTF-8'),"ps_data_btn" => mb_convert_encoding($ps_data_btn,'UTF-8','UTF-8')));
	}
	public function check_missing_files()
	{
		$missing_files = strtolower(Input::get('missing_files'));
		$path = Input::get('path');
		$files = glob(dirname($path));
		print_r($files);
		exit;
		print_r(getDirContents($path,$missing_files));
	}
	public function import_available_files()
	{
		$filename = Input::get('filename');
		$fileid = Input::get('fileid');
		$fileval = Input::get('fileval');


	    $image_parts = explode(";base64,", $fileval);
	    $image_base64 = base64_decode($image_parts[1]);
	    $file = 'uploads/infile_image/'.base64_encode($fileid).'/'.$filename;
	    if(file_put_contents($file, $image_base64))
	    {
			echo "0";
		}
		else{
			echo "1";
		}
	}
	public function get_infile_check_reports()
	{
		$fileid = Input::get('fileid');
		$get_reports = DB::table('infile_check_report')->where('fileid',$fileid)->get();
		$output = '<h5>Download:</h5>';
		if(count($get_reports))
		{
			foreach($get_reports as $report)
			{
				$output.='<p><a href="'.URL::to($report->url).'" download="">'.$report->filename.'</a></p>';
			}
		}
		echo $output;
	}
	public function update_infile_textvalue_item()
	{
		$file_id = Input::get('file_id');
		$radio = Input::get('radio');
		$value = Input::get('value');
		$inc = Input::get('inc');

		if($radio == "1")
		{
			$attachments = DB::table('in_file_attachment')->where('file_id',$file_id)->where('textval','')->where('p',1)->where('secondary',0)->get();
		}
		else{
			$attachments = DB::table('in_file_attachment')->where('file_id',$file_id)->where('textval','')->where('s',1)->where('secondary',0)->get();
		}
		if(count($attachments))
		{
			foreach($attachments as $attach)
			{
				$data['textval'] = $value;
				DB::table('in_file_attachment')->where('id',$attach->id)->update($data);
				$get_sub_attachments = DB::table('in_file_attachment')->where('attach_id',$attach->id)->where('secondary',1)->orderBy('id','asc')->get();
				if(count($get_sub_attachments))
				{
					$sub_attach_value = $value;
					foreach($get_sub_attachments as $sub_attach)
					{
						$sub_attach_value = $sub_attach_value + .001;
						$subdata['textval'] = $sub_attach_value;
						DB::table('in_file_attachment')->where('id',$sub_attach->id)->update($subdata);
					}
				}
				$value = $value + $inc;
			}
		}
		
	}
	public function renumber_infile_textvalue_item()
	{
		$file_id = Input::get('file_id');
		$radio = Input::get('radio');
		$value = Input::get('value');
		$inc = Input::get('inc');

		if($radio == "1")
		{
			$attachments = DB::table('in_file_attachment')->where('file_id',$file_id)->where('p',1)->where('secondary',0)->get();
		}
		else{
			$attachments = DB::table('in_file_attachment')->where('file_id',$file_id)->where('s',1)->where('secondary',0)->get();
		}
		if(count($attachments))
		{
			foreach($attachments as $attach)
			{
				$data['textval'] = $value;
				DB::table('in_file_attachment')->where('id',$attach->id)->update($data);
				$get_sub_attachments = DB::table('in_file_attachment')->where('attach_id',$attach->id)->where('secondary',1)->orderBy('id','asc')->get();
				if(count($get_sub_attachments))
				{
					$sub_attach_value = $value;
					foreach($get_sub_attachments as $sub_attach)
					{
						$sub_attach_value = $sub_attach_value + .001;
						$subdata['textval'] = $sub_attach_value;
						DB::table('in_file_attachment')->where('id',$sub_attach->id)->update($subdata);
					}
				}
				$value = $value + $inc;
			}
		}
		
	}
	public function check_secondary_line_has_value()
	{
		$fileid = Input::get('file_id');
		$radio = Input::get('radio');
		if($radio == "1")
		{
			$primary_attachments = DB::table('in_file_attachment')->where('file_id',$fileid)->where('textval','')->where('secondary',0)->where('p',1)->get();
		}
		else{
			$primary_attachments = DB::table('in_file_attachment')->where('file_id',$fileid)->where('textval','')->where('secondary',0)->where('s',1)->get();
		}

		$alertmsg = 0;
		if(count($primary_attachments))
		{
			foreach($primary_attachments as $primary)
			{
				$secondary_attachments = DB::table('in_file_attachment')->where('attach_id',$primary->id)->where('secondary',1)->where('textval','!=','')->get();
				$countval = count($secondary_attachments);
				if($countval > 0)
				{
					$alertmsg = $alertmsg + 1;
				}
			}
		}
		echo $alertmsg;
	}
	public function load_all_clients_infile_advanced()
	{
		$output='';
		$i=1;
		$clientslist = DB::table('cm_clients')->get();
		if(count($clientslist)){
		foreach ($clientslist as $client) {
		  $clientid = $client->client_id;
		  $firstname = $client->firstname;
		  $lastname = $client->surname;
		  $company = $client->company;
		  if($i < 10)
		  {
		    $i = '0'.$i;
		  }
		  
		  $received = DB::table('in_file')->where('client_id',$client->client_id)->count();
		  $complete = DB::table('in_file')->where('client_id',$client->client_id)->where('status',1)->count();
		  $incomplete = DB::table('in_file')->where('client_id',$client->client_id)->where('status',0)->count();
		  if($incomplete > 0) { $incomplete_cls = 'incomplete_cls'; } else { $incomplete_cls = 'complete_cls'; }
		  if($client->active == 2) { $inactive_cls = 'inactive_cls'; } else { $inactive_cls = 'active_cls'; }
		  if($client->active == 2) { $color = 'color:#F00 !important'; }
		  else { $color = 'color:#000 !important'; }
		  $view_url = URL::to('user/infile_search').'?client_id='.$client->client_id;
		  $output.='
		  <tr class="task_tr client_'.$client->status.' '.$incomplete_cls.' '.$inactive_cls.'">
		    <td class="sno_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$i.'</td>
		    <td class="clientid_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$clientid.'</td>
		    <td class="company_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$company.'</td>
		    <td class="firstname_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$firstname.'</td>
		    <td class="lastname_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$lastname.'</td>
		    <td class="received_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$received.'</td>
		    <td class="complete_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$complete.'</td>
		    <td class="incomplete_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$incomplete.'</td>
		    <td class="lastname_sort_val" style="text-align:left;font-weight:600;'.$color.'">
		      <a href="javascript:" class="fa fa-plus common_black_button create_new" title="Add New Batch File" data-element="'.$clientid.'" style="padding: 3px 7px;"></a>
		      <a href="'.$view_url.'" class="fa fa-eye common_black_button view_client" title="View Client InFiles" style="padding: 3px 7px;"></a>';
		      $infiles = DB::table('in_file')->where('client_id', $client->client_id)->get();
		      if(count($infiles))
		      {
		        foreach($infiles as $infile)
		        {
		          $output.='<input type="button" class="common_black_button integrity_check" value="Integrity Check" data-element="'.$infile->id.'" style="display:none">';
		        }
		      }
		    $output.='</td>
		  </tr>';
		  $i++;
		}
		}
		else{
		$output.='
		  <tr>
		    <td align="center"></td>
		    <td align="center"></td>
		    <td align="center"></td>
		    <td align="center"></td>
		    <td align="center">Empty</td>
		    <td align="center"></td>
		    <td align="center"></td>
		    <td align="center"></td>
		    <td align="center"></td>
		  </tr>
		';
		}
		echo $output;
	}
	public function load_single_client_infile_advanced()
	{
		$client_id = Input::get('client_id');
		$output='';
		$i=1;
		$client = DB::table('cm_clients')->where('client_id',$client_id)->first();
		if(count($client)){
		  $clientid = $client->client_id;
		  $firstname = $client->firstname;
		  $lastname = $client->surname;
		  $company = $client->company;
		  if($i < 10)
		  {
		    $i = '0'.$i;
		  }
		  
		  $received = DB::table('in_file')->where('client_id',$client->client_id)->count();
		  $complete = DB::table('in_file')->where('client_id',$client->client_id)->where('status',1)->count();
		  $incomplete = DB::table('in_file')->where('client_id',$client->client_id)->where('status',0)->count();
		  if($incomplete > 0) { $incomplete_cls = 'incomplete_cls'; } else { $incomplete_cls = 'complete_cls'; }
		  if($client->active == 2) { $inactive_cls = 'inactive_cls'; } else { $inactive_cls = 'active_cls'; }
		  if($client->active == 2) { $color = 'color:#F00 !important'; }
		  else { $color = 'color:#000 !important'; }
		  $view_url = URL::to('user/infile_search').'?client_id='.$client->client_id;
		  $output.='
		  <tr class="task_tr client_'.$client->status.' '.$incomplete_cls.' '.$inactive_cls.'">
		    <td class="sno_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$i.'</td>
		    <td class="clientid_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$clientid.'</td>
		    <td class="company_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$company.'</td>
		    <td class="firstname_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$firstname.'</td>
		    <td class="lastname_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$lastname.'</td>
		    <td class="received_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$received.'</td>
		    <td class="complete_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$complete.'</td>
		    <td class="incomplete_sort_val" style="text-align:left;font-weight:600;'.$color.'">'.$incomplete.'</td>
		    <td class="lastname_sort_val" style="text-align:left;font-weight:600;'.$color.'">
		      <a href="javascript:" class="fa fa-plus common_black_button create_new" title="Add New Batch File" data-element="'.$clientid.'" style="padding: 3px 7px;"></a>
		      <a href="'.$view_url.'" class="fa fa-eye common_black_button view_client" title="View Client InFiles" style="padding: 3px 7px;"></a>';
		      $infiles = DB::table('in_file')->where('client_id', $client->client_id)->get();
		      if(count($infiles))
		      {
		        foreach($infiles as $infile)
		        {
		          $output.='<input type="button" class="common_black_button integrity_check" value="Integrity Check" data-element="'.$infile->id.'" style="display:none">';
		        }
		      }
		    $output.='</td>
		  </tr>';
		  $i++;
		}
		else{
			$output.='
			  <tr>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center">Empty</td>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center"></td>
			    <td align="center"></td>
			  </tr>
			';
		}
		echo $output;
	}
	public function infile_edit_description()
	{
		$fileid = Input::get('fileid');
		$details = DB::table('in_file')->where('id',$fileid)->first();
		$description = '';
		if(count($details))
		{
			$description = $details->description;
			
		}
		echo $description;
	}
	public function update_edit_description()
	{
		$fileid = Input::get('fileid');
		$value = Input::get('value');
		$data['description'] = $value;
		DB::table('in_file')->where('id',$fileid)->update($data);
	}
	public function get_summary_infile_attachments(){
		$file_id = Input::get('file_id');
		$file_details = DB::table('in_file')->where('id',$file_id)->first();

		$client_details = DB::table('cm_clients')->where('client_id',$file_details->client_id)->first();

		$client_id = $file_details->client_id;
		$client_name = $client_details->company;
		$description = $file_details->description;

		$filecount_purchase = DB::table('in_file_attachment')->where('file_id',$file_id)->where('p',1)->get();
		$filecount_sales = DB::table('in_file_attachment')->where('file_id',$file_id)->where('s',1)->get();
		$filecount_bank = DB::table('in_file_attachment')->where('file_id',$file_id)->where('b',1)->get();
		$filecount_others = DB::table('in_file_attachment')->where('file_id',$file_id)->where('o',1)->get();

		$filecount_unspecified = DB::table('in_file_attachment')->where('file_id',$file_id)->where('b',0)->where('p',0)->where('s',0)->where('o',0)->where('status',0)->where('notes_type',0)->get();
		$filecount_totals = count($filecount_purchase) + count($filecount_sales) + count($filecount_bank) + count($filecount_others) + count($filecount_unspecified);


		$net_purchase = DB::table('in_file_attachment')->where('file_id',$file_id)->where('p',1)->sum('net');
		$net_sales = DB::table('in_file_attachment')->where('file_id',$file_id)->where('s',1)->sum('net');
		$net_others = DB::table('in_file_attachment')->where('file_id',$file_id)->where('o',1)->sum('net');

		$net_unspecified = DB::table('in_file_attachment')->where('file_id',$file_id)->where('b',0)->where('p',0)->where('s',0)->where('o',0)->where('status',0)->where('notes_type',0)->sum('net');
		$net_totals = DB::table('in_file_attachment')->where('file_id',$file_id)->where('status',0)->where('notes_type',0)->sum('net');


		$vat_purchase = DB::table('in_file_attachment')->where('file_id',$file_id)->where('p',1)->sum('vat');
		$vat_sales = DB::table('in_file_attachment')->where('file_id',$file_id)->where('s',1)->sum('vat');
		$vat_others = DB::table('in_file_attachment')->where('file_id',$file_id)->where('o',1)->sum('vat');

		$vat_unspecified = DB::table('in_file_attachment')->where('file_id',$file_id)->where('b',0)->where('p',0)->where('s',0)->where('o',0)->where('status',0)->where('notes_type',0)->sum('vat');
		$vat_totals = DB::table('in_file_attachment')->where('file_id',$file_id)->where('status',0)->where('notes_type',0)->sum('vat');

		$gross_purchase = DB::table('in_file_attachment')->where('file_id',$file_id)->where('p',1)->sum('gross');
		$gross_sales = DB::table('in_file_attachment')->where('file_id',$file_id)->where('s',1)->sum('gross');
		$gross_others = DB::table('in_file_attachment')->where('file_id',$file_id)->where('o',1)->sum('gross');

		$gross_unspecified = DB::table('in_file_attachment')->where('file_id',$file_id)->where('b',0)->where('p',0)->where('s',0)->where('o',0)->where('status',0)->where('notes_type',0)->sum('gross');
		$gross_totals = DB::table('in_file_attachment')->where('file_id',$file_id)->where('status',0)->where('notes_type',0)->sum('gross');

		$outputresult = '<div class="col-md-12" style="padding:0px">
			<div class="col-md-5" style="padding:0px">
				<label>Client Name:</label> '.$client_name.'
			</div>
			<div class="col-md-3" style="padding:0px">
				<label>Client Code:</label> '.$client_id.'
			</div>
			<div class="col-md-4" style="padding:0px">
				<label>Description:</label> '.$description.'
			</div>

			<table class="table" style="margin-top:20px">
				<thead>
					<th></th>
					<th>Purchase</th>
					<th>Sales</th>
					<th>Bank</th>
					<th>Other</th>
					<th>Unspecified</th>
					<th>NonEuro</th>
					<th>Total</th>
				</thead>
				<tbody>
					<tr>
						<td>FileCount</td>
						<td>'.count($filecount_purchase).'</td>
						<td>'.count($filecount_sales).'</td>
						<td>'.count($filecount_bank).'</td>
						<td>'.count($filecount_others).'</td>
						<td>'.count($filecount_unspecified).'</td>
						<td></td>
						<td>'.$filecount_totals.'</td>
					</tr>
					<tr>
						<td>Net</td>
						<td>'.number_format_invoice($net_purchase).'</td>
						<td>'.number_format_invoice($net_sales).'</td>
						<td></td>
						<td>'.number_format_invoice($net_others).'</td>
						<td>'.number_format_invoice($net_unspecified).'</td>
						<td></td>
						<td>'.number_format_invoice($net_totals).'</td>
					</tr>
					<tr>
						<td>VAT</td>
						<td>'.number_format_invoice($vat_purchase).'</td>
						<td>'.number_format_invoice($vat_sales).'</td>
						<td></td>
						<td>'.number_format_invoice($vat_others).'</td>
						<td>'.number_format_invoice($vat_unspecified).'</td>
						<td></td>
						<td>'.number_format_invoice($vat_totals).'</td>
					</tr>
					<tr>
						<td>Gross</td>
						<td>'.number_format_invoice($gross_purchase).'</td>
						<td>'.number_format_invoice($gross_sales).'</td>
						<td></td>
						<td>'.number_format_invoice($gross_others).'</td>
						<td>'.number_format_invoice($gross_unspecified).'</td>
						<td></td>
						<td>'.number_format_invoice($gross_totals).'</td>
					</tr>
				</tbody>
			</table>
		</div>';

		echo $outputresult;
	}
	public function change_file_status_to_zero(){
		$fileid = Input::get('fileid');
		$status = Input::get('status');

		if($status == 1){
			DB::table('in_file_attachment')->where('file_id',$fileid)->where('status',1)->where('secondary',0)->delete();
		}
		else{
			$data['status'] = 0;
			DB::table('in_file_attachment')->where('file_id',$fileid)->where('status',1)->where('secondary',0)->update($data);
		}
	}
}


