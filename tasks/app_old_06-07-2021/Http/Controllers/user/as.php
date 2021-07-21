<?php
foreach($user_tasks as $task)
{
  if($task->status == 1){ $disabled = 'disabled'; $disabled_icon = 'disabled_icon'; }
  else{ $disabled = ''; $disabled_icon = ''; }

  $taskfiles = DB::table('taskmanager_files')->where('task_id',$task->id)->get();
  $tasknotepad = DB::table('taskmanager_notepad')->where('task_id',$task->id)->get();
  $taskinfiles = DB::table('taskmanager_infiles')->where('task_id',$task->id)->get();
  $taskyearend = DB::table('taskmanager_yearend')->where('task_id',$task->id)->get();
  $two_bill_icon = '';
    if($task->two_bill == "1")
    {
      $two_bill_icon = '<img src="'.URL::to('assets/2bill.png').'" style="width:32px;margin-left:10px" title="this is a 2Bill Task">';
    }
  if($task->client_id == "")
  {
    $title_lable = 'Task Name:';
    $task_details = DB::table('time_task')->where('id', $task->task_type)->first();
    if(count($task_details))
    {
    	$title = '<spam class="task_name_'.$task->id.'">'.$task_details->task_name.'</spam>'.$two_bill_icon;
    }
    else{
    	$title = '<spam class="task_name_'.$task->id.'"></spam>'.$two_bill_icon;
    }
  }
  else{
    $title_lable = 'Client:';
    $client_details = DB::table('cm_clients')->where('client_id', $task->client_id)->first();
    if(count($client_details))
    {
      $title = $client_details->company.' ('.$task->client_id.')'.$two_bill_icon;
    }
    else{
      $title = ''.$two_bill_icon;
    }
  }
  $author = DB::table('user')->where('user_id',$task->author)->first();
  $task_specifics_val = strip_tags($task->task_specifics);
  if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
  else{ $subject = $task->subject; }

  if($task->allocated_to == 0) { $allocated_to = ''; }
  else{ $allocated = DB::table('user')->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }
  if(Session::has('taskmanager_user'))
  {
    if(Session::get('taskmanager_user') == $task->author) {
        if(Session::get('taskmanager_user') == $task->allocated_to)
        {
          $author_cls = 'author_tr allocated_tr'; $hidden_author_cls = 'hidden_author_tr hidden_allocated_tr'; 
        }
        else{
          $author_cls = 'author_tr'; $hidden_author_cls = 'hidden_author_tr'; 
        }
    }
    else{ 
        $author_cls = 'allocated_tr'; $hidden_author_cls = 'hidden_allocated_tr'; 
    }
  }
  else{
    $author_cls = '';
    $hidden_author_cls = '';
  }
  if($task->auto_close == 1)
    {
      $close_task = 'auto_close_task_complete';
    }
    else{
      $close_task = '';
    }
  $open_tasks.='<tr class="tasks_tr '.$author_cls.'" id="task_tr_'.$task->id.'">
    <td style="vertical-align: baseline;background: #2fd9ff;width:35%;padding:0px">';
      $statusi = 0;
      if(Session::has('taskmanager_user'))
      {
        if(Session::get('taskmanager_user') == $task->author) { 
          if($task->author_spec_status == "1")
          {
            $open_tasks.='<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
            $statusi++;
          }
        }
        else{
          if($task->allocated_spec_status == "1")
          {
            $open_tasks.='<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
            $statusi++;
          }
        }
      }
      if($statusi == 0)
      {
        $open_tasks.='<p class="redlight_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;display:none"></p>';
      }
        $open_tasks.='<table class="table">
        <tr>
          <td style="width:25%;background:#2fd9ff;font-weight:700;text-decoration: underline;">'.$title_lable.'</td>
          <td style="width:75%;background:#2fd9ff">'.$title.'';
          if($task->recurring_task > 0)
          {
            $open_tasks.='<img src="'.URL::to('assets/images/recurring.png').'" style="width:30px;" title="This is a Recurring Task">';
          }
          $open_tasks.='</td>
        </tr>
        <tr>
          <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Subject:</td>
          <td style="background: #2fd9ff">'.$subject.'</td>
        </tr>
        <tr>';
            $date1=date_create(date('Y-m-d'));
            $date2=date_create($task->due_date);
            $diff=date_diff($date1,$date2);
            $diffdays = $diff->format("%R%a");

            if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
            elseif($diffdays < 0) { $due_color = '#f00'; }
            elseif($diffdays > 7) { $due_color = '#000'; }
            elseif($diffdays <= 7) { $due_color = '#00a91d'; }
            else{ $due_color = '#000'; }
          $open_tasks.='<td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Due Date:</td>
          <td style="background: #2fd9ff" class="'.$disabled_icon.'">
            <spam style="color:'.$due_color.' !important;font-weight:800" id="due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
            <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-value="'.date('d-M-Y', strtotime($task->due_date)).'" data-duedate="'.$task->due_date.'" data-color="'.$due_color.'" class="fa fa-edit edit_due_date edit_due_date_'.$task->id.' '.$disabled.'" style="font-weight:800"></a>
          </td>
        </tr>
        <tr>
            <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Date Created:</td>
            <td style="background: #2fd9ff">
              <spam>'.date('d-M-Y', strtotime($task->creation_date)).'</spam>
            </td>
        </tr>
        <tr>
          <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Task Specifics:</td>
          <td style="background: #2fd9ff"><a href="javascript:" class="link_to_task_specifics" data-element="'.$task->id.'">'.substr($task_specifics_val,0,30).'...</a></td>
        </tr>
        <tr>
          <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Task files:</td>
          <td style="background: #2fd9ff">';
            $fileoutput = '';
            if(count($taskfiles))
            {
              foreach($taskfiles as $file)
              {
                if($file->status == 0)
                {
                  $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                }
              }
            }
            if(count($tasknotepad))
            {
              foreach($tasknotepad as $note)
              {
                if($note->status == 0)
                {
                  $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                }
              }
            }
            if(count($taskinfiles))
            {
              $i=1;
              foreach($taskinfiles as $infile)
              {
                if($infile->status == 0)
                {
                  if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
                  $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
                  $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
                  $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
                  <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
                  <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

                  <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
                  </p>';
                  $i++;
                }
              }
            }
            $open_tasks.=$fileoutput;
          $open_tasks.='</td>
        </tr>
      </table>
    </td>
    <td style="vertical-align: baseline;background: #dcdcdc;width:30%">
      <table class="table">
        <tr>
          <td style="width:25%;font-weight:700;text-decoration: underline;">Author:</td>
          <td style="width:75%">'.$author->lastname.' '.$author->firstname.'';
          if($task->avoid_email == 0) {
            $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
          }
          else{
            $open_tasks.='<a href="javascript:" class="fa fa-envelope avoid_email retain_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
          }
          $open_tasks.='</td>
        </tr>
        <tr>
          <td style="font-weight:700;text-decoration: underline;">Allocated to:</td>
          <td id="allocated_to_name_'.$task->id.'">'.$allocated_to.'</td>
        </tr>
        <tr>
          <td colspan="2" class="'.$disabled_icon.'">
            <spam style="font-weight:700;text-decoration: underline;">Allocations:</spam>&nbsp;
            <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-sitemap edit_allocate_user edit_allocate_user_'.$task->id.' '.$close_task.' '.$disabled.'" title="Allocate User" style="font-weight:800"></a>
            &nbsp;
            <a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-history show_task_allocation_history show_task_allocation_history_'.$task->id.'" title="Allocation history" style="font-weight:800"></a>
            &nbsp;
            <a href="javascript:" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" title="Request Update" '.$disabled.' style="font-weight:800">
            	<img src="'.URL::to('assets/images/request.png').'" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" '.$disabled.' style="width:16px;">
            </a>
          </td>
        </tr>
        <tr>
          <td colspan="2" id="allocation_history_div_'.$task->id.'">';
            $allocations = DB::table('taskmanager_specifics')->where('task_id',$task->id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
            $output = '';
            if(count($allocations))
            {
              foreach($allocations as $allocate)
              {
                $output.='<p data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task->id.' '.$close_task.' '.$disabled.'" title="Allocate User">';
                  $fromuser = DB::table('user')->where('user_id',$allocate->from_user)->first();
                  $touser = DB::table('user')->where('user_id',$allocate->to_user)->first();
                  $output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
                $output.='</p>';
              }
            }
            $open_tasks.=$output;
          $open_tasks.='</td>
        </tr>
      </table>
    </td>
    <td style="vertical-align: baseline;background: #dcdcdc;width:20%">
      <table class="table">
        <tr>
          <td style="font-weight:700;text-decoration: underline;">Progress Files:</td>
          <td></td>
        </tr>
        <tr>
          <td class="'.$disabled_icon.'">
            <a href="javascript:" class="fa fa-plus faplus_progress '.$disabled.'" data-element="'.$task->id.'" style="padding:5px;background: #dfdfdf;"></a>
            <a href="javascript:" class="fa fa-edit fanotepad_progress '.$disabled.'" style="padding:5px;background: #dfdfdf;"></a>';
            if($task->client_id != "")
            {
              $open_tasks.='<a href="javascript:" class="infiles_link_progress '.$disabled.'" data-element="'.$task->id.'">Infiles</a>';
            }
            $open_tasks.='<input type="hidden" name="hidden_progress_client_id" id="hidden_progress_client_id_'.$task->id.'" value="'.$task->client_id.'">
            <input type="hidden" name="hidden_infiles_progress_id" id="hidden_infiles_progress_id_'.$task->id.'" value="">
            
            <div class="notepad_div_progress_notes" style="z-index:9999; position:absolute">
              <textarea name="notepad_contents_progress" class="form-control notepad_contents_progress" placeholder="Enter Contents"></textarea>
              <input type="hidden" name="hidden_task_id_progress_notepad" id="hidden_task_id_progress_notepad" value="'.$task->id.'">
              <input type="button" name="notepad_progress_submit" class="btn btn-sm btn-primary notepad_progress_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
              <spam class="error_files_notepad"></spam>
            </div>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="2" class="'.$disabled_icon.'">';
            $fileoutput ='<div id="add_files_attachments_progress_div_'.$task->id.'">';
            if(count($taskfiles))
            {
              foreach($taskfiles as $file)
              {
                if($file->status == 1)
                {
                  $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                }
              }
            }
            $fileoutput.='</div>';
            $fileoutput.='<div id="add_notepad_attachments_progress_div_'.$task->id.'">';
            if(count($tasknotepad))
            {
              foreach($tasknotepad as $note)
              {
                if($note->status == 1)
                {
                  $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                }
              }
            }
            $fileoutput.='</div>';
            $fileoutput.='<div id="add_infiles_attachments_progress_div_'.$task->id.'">';
            if(count($taskinfiles))
            {
              $i=1;
                foreach($taskinfiles as $infile)
                {
                  if($infile->status == 1)
                  {
                    if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
                    $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
                    $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
                    $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile '.$disabled.'" data-element="'.$ele.'">'.$i.'</a>
                    <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
                    <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

                    <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
                    </p>';
                    $i++;
                  }
                }
            }
            $fileoutput.='</div>';
            $open_tasks.= $fileoutput;
          $open_tasks.='</td>
        </tr>
      </table>
    </td>
    <td style="vertical-align: baseline;background: #2fd9ff;width:15%">
      <table class="table" style="margin-bottom: 105px;">
        <tr>
          <td style="background:#2fd9ff" class="'.$disabled_icon.'">
              <spam style="font-weight:700;text-decoration: underline;font-size: 16px;">Task ID:</spam> <spam style="font-size: 16px;">'.$task->taskid.'</spam>
              <a href="javascript:" class="fa fa-files-o copy_task" data-element="'.$task->id.'" title="Copy this Task" style="padding:5px;font-size:20px;font-weight: 800;float: right"></a>
              <a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="'.$task->id.'" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800;float: right">
                  </a> 
          </td>

        </tr>
        <tr>
          <td style="background:#2fd9ff">
          		<spam style="font-weight:700;text-decoration: underline;float:left">Progress:</spam> 
                  <a href="javascript:" class="fa fa-sliders" title="Set progress" data-placement="bottom" data-popover-content="#a1_'.$task->id.'" data-toggle="popover" data-trigger="click" tabindex="0" data-original-title="Set Progress"  style="padding:5px;font-weight:700;float:left"></a>
                  <div class="hidden" id="a1_'.$task->id.'">
                    <div class="popover-heading">
                      Set Progress Percentage
                    </div>
                    <div class="popover-body">
                      <input type="number" class="form-control input-sm progress_value" id="progress_value_'.$task->id.'" value="" style="width:60%;float:left">
                      <a href="javascript:" class="common_black_button set_progress" data-element="'.$task->id.'" style="font-size: 11px;line-height: 29px;">Set</a>
                    </div>
                  </div>
                  <div class="progress progress_'.$task->id.'" style="width:60%;margin-bottom:5px">
                    <div class="progress-bar" role="progressbar" aria-valuenow="'.$task->progress.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$task->progress.'%">
                      '.$task->progress.'%
                    </div>
                  </div>
          </td>
        </tr>
        <tr>
          <td style="background:#2fd9ff">';
            if($task->status == 1)
            {
              $open_tasks.='<a href="javascript:" class="common_black_button mark_as_incomplete" data-element="'.$task->id.'" style="font-size:12px">Completed</a>';
            }
            elseif($task->status == 1)
            {
              if(Session::has('taskmanager_user'))
                {
                    $allocated_person = Session::get('taskmanager_user');
                    if($task->author == $allocated_person)
                    {
                      $complete_button = 'mark_as_complete_author';
                    }
                    else{
                      $complete_button = 'mark_as_complete';
                    }
                }
                else{
                    $complete_button = 'mark_as_complete';
                }
                
              $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.' '.$close_task.'" data-element="'.$task->id.'" style="font-size:12px">Mark Complete</a>
              <a href="javascript:" class="common_black_button activate_task_button" data-element="'.$task->id.'" style="font-size:12px">Activate</a>';
            }
            else{
            	if(Session::has('taskmanager_user'))
                {
                    $allocated_person = Session::get('taskmanager_user');
                    if($task->author == $allocated_person)
                    {
                      $complete_button = 'mark_as_complete_author';
                    }
                    else{
                      $complete_button = 'mark_as_complete';
                    }
                }
                else{
                    $complete_button = 'mark_as_complete';
                }
                
              $open_tasks.='<a href="javascript:" class="common_black_button '.$complete_button.' '.$close_task.'" data-element="'.$task->id.'" style="font-size:12px">Mark Complete</a>
              <a href="javascript:" class="common_black_button park_task_button" data-element="'.$task->id.'" style="font-size:12px">Park Task</a>';
            }
          $open_tasks.='</td>
        </tr>
        <tr>
          <td style="background:#2fd9ff" class="'.$disabled_icon.'">
            <spam style="font-weight:700;text-decoration: underline;">Completion Files:</spam><br/>
            <a href="javascript:" class="fa fa-plus faplus_completion '.$disabled.'" data-element="'.$task->id.'" style="padding:5px"></a>
            <a href="javascript:" class="fa fa-plus faplus '.$disabled.'" style="padding:5px"></a>
            <a href="javascript:" class="fa fa-edit fanotepad_completion '.$disabled.'" style="padding:5px"></a>';
            if($task->client_id != "")
            {
              $open_tasks.='<a href="javascript:" class="infiles_link_completion '.$disabled.'" data-element="'.$task->id.'">Infiles</a>
              <a href="javascript:" class="yearend_link_completion '.$disabled.'" data-element="'.$task->id.'">Yearend</a>';
            }
            $get_infiles = DB::table('taskmanager_infiles')->where('task_id',$task->id)->get();
              $get_yearend = DB::table('taskmanager_yearend')->where('task_id',$task->id)->get();
              $idsval = '';
              $idsval_yearend = '';
              if(count($get_infiles))
              {
              	foreach($get_infiles as $set_infile)
              	{
              		if($idsval == "")
              		{
              			$idsval = $set_infile->infile_id;
              		}
              		else{
              			$idsval = $idsval.','.$set_infile->infile_id;
              		}
              	}
              }
              if(count($get_yearend))
              {
              	foreach($get_yearend as $set_yearend)
              	{
              		if($idsval_yearend == "")
              		{
              			$idsval_yearend = $set_yearend->setting_id;
              		}
              		else{
              			$idsval_yearend = $idsval_yearend.','.$set_yearend->setting_id;
              		}
              	}
              }

            $open_tasks.='<input type="hidden" name="hidden_completion_client_id" id="hidden_completion_client_id_'.$task->id.'" value="'.$task->client_id.'">
            <input type="hidden" name="hidden_infiles_completion_id" id="hidden_infiles_completion_id_'.$task->id.'" value="'.$idsval.'">
            <input type="hidden" name="hidden_yearend_completion_id" id="hidden_yearend_completion_id_'.$task->id.'" value="'.$idsval_yearend.'">

            
            <div class="notepad_div_completion_notes" style="z-index:9999; position:absolute">
              <textarea name="notepad_contents_completion" class="form-control notepad_contents_completion" placeholder="Enter Contents"></textarea>
              <input type="hidden" name="hidden_task_id_completion_notepad" id="hidden_task_id_completion_notepad" value="'.$task->id.'">
              <input type="button" name="notepad_completion_submit" class="btn btn-sm btn-primary notepad_completion_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
              <spam class="error_files_notepad"></spam>
            </div>';
            $fileoutput ='<div id="add_files_attachments_completion_div_'.$task->id.'">';
            if(count($taskfiles))
            {
              foreach($taskfiles as $file)
              {
                if($file->status == 2)
                {
                  $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                }
              }
            }
            $fileoutput.='</div>';
            $fileoutput.='<div id="add_notepad_attachments_completion_div_'.$task->id.'">';
            if(count($tasknotepad))
            {
              foreach($tasknotepad as $note)
              {
                if($note->status == 2)
                {
                  $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                }
              }
            }
            $fileoutput.='</div>';
            $fileoutput.='<div id="add_infiles_attachments_completion_div_'.$task->id.'">';
            if(count($taskinfiles))
            {
              $i=1;
                foreach($taskinfiles as $infile)
                {
                  if($infile->status == 2)
                  {
                    if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
                    $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
                    $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
                    $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
                    <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
                    <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

                    <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
                    </p>';
                    $i++;
                  }
                }
            }
            $fileoutput.='</div>';
            $fileoutput.='<div id="add_yearend_attachments_completion_div_'.$task->id.'">';
                  if(count($taskyearend))
                  {
                    $i=1;
                      foreach($taskyearend as $yearend)
                      {
                        if($yearend->status == 2)
                        {
                          if($i == 1) { $fileoutput.='Linked Yearend:<br/>'; }
                          $file = DB::table('year_setting')->where('id',$yearend->setting_id)->first();
                          $get_client_id = DB::table('taskmanager')->where('id',$task->id)->first();
                          $year_client_id = $get_client_id->client_id;
                          $yearend_id = DB::table('year_client')->where('client_id',$year_client_id)->orderBy('id','desc')->first();

                          $ele = URL::to('user/yearend_individualclient/'.base64_encode($yearend_id->id).'');
                          $fileoutput.='<p class="link_yearend_p">
                          <a href="'.$ele.'" target="_blank">'.$i.'</a>
                          <a href="'.$ele.'" target="_blank">'.$file->document.'</a>
                          <a href="'.URL::to('user/delete_taskmanager_yearend?file_id='.$yearend->id.'').'" class="fa fa-trash delete_attachments"></a>
                          </p>';
                          $i++;
                        }
                      }
                  }
                  $fileoutput.='</div>';
            $open_tasks.= $fileoutput;
          $open_tasks.='</td>
        </tr>
        <tr>
            <td style="background:#2fd9ff">
            </td>
          </tr>
      </table>
    </td>
  </tr>
  <tr class="empty_tr" style="background: #fff;height:30px">
    <td style="padding:0px;background: #fff;"></td>
    <td colspan="3" style="background: #fff;height:30px"></td>
  </tr>';

  $layout.= '<tr class="hidden_tasks_tr '.$hidden_author_cls.'" id="hidden_tasks_tr_'.$task->id.'" data-element="'.$task->id.'" style="display:none">
        <td style="background: #dcdcdc;padding:0px;">
        	<table style="width:100%">
            	<tr>';
            	$statusi = 0;
                  if(Session::has('taskmanager_user'))
                  {
                    if(Session::get('taskmanager_user') == $task->author) { 
                      if($task->author_spec_status == "1")
                      {
                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
                        $redlight_value = 1;
                        $statusi++;
                      }
                    }
                    else{
                      if($task->allocated_spec_status == "1")
                      {
                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
                        $redlight_value = 1;
                        $statusi++;
                      }
                    }
                  }
                  if($statusi == 0)
                  {
                    $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800;display:none"></spam>';
                    $redlight_value = 0;
                  }
            		$layout.= '
            		<td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="redlight_sort_val">
            		<spam class="hidden_redlight_value" style="display:none">'.$redlight_value.'</spam>
            		'.$redlight_indication_layout.'
            		</td>
            		<td style="width:45%;padding:10px; font-size:14px; font-weight:800;" class="taskname_sort_val">'.$title.'</td>
            		<td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="2bill_sort_val">
                        '.$two_bill_icon.'
                    </td>
            		<td style="width:45%;padding:10px; font-size:14px; font-weight:800" class="subject_sort_val">'.$subject.'</td>
            	</tr>
            </table>
        </td>
        <td style="background: #dcdcdc;padding:0px;">
        	<table style="width:100%">
            	<tr>
            		<td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="author_sort_val">'.$author->lastname.' '.$author->firstname.'</td>
            		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="allocated_sort_val">'.$allocated_to.'</td>
            	</tr>
        	</table>
        </td>
        <td style="background: #dcdcdc;padding:0px;">
        	<table style="width:100%">
            	<tr>
            		<td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="duedate_sort_val">
            			<spam class="hidden_due_date_layout" style="display:none">'.strtotime($task->due_date).'</spam>
            			<spam class="layout_due_date_task" style="color:'.$due_color.' !important;font-weight:800" id="layout_due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
            		</td>
            		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="createddate_sort_val">
            		<spam class="hidden_created_date_layout" style="display:none">'.strtotime($task->creation_date).'</spam>
            		'.date('d-M-Y', strtotime($task->creation_date)).'
            		</td>
            	</tr>
            </table>
        </td>
        <td style="background: #dcdcdc;padding:0px;">
        	<table style="width:100%">
            	<tr>
            		<td style="width:40%;padding:10px; font-size:14px; font-weight:800;" class="taskid_sort_val">'.$task->taskid.'</td>
            		<td class="layout_progress_'.$task->id.'" style="width:40%;padding:10px; font-size:14px; font-weight:800;"><spam class="progress_sort_val" style="display:none">'.$task->progress.'</spam>'.$task->progress.'%</td>
            		<td style="width:20%;padding:10px; font-size:14px; font-weight:800">
            			<a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="'.$task->id.'" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800">
                  		</a> 
            		</td>
            	</tr>
            </table>
        </td>
      </tr>';
}