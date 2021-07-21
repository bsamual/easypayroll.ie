<?php



/*

|--------------------------------------------------------------------------

| Application Routes

|--------------------------------------------------------------------------

|

| Here is where you can register all of the routes for an application.

| It's a breeze. Simply tell Laravel the URIs it should respond to

| and give it the controller to call when that URI is requested.

|

*/



Route::get('/', 'user\UserauthenticateController@login');



Route::get('home', 'user\UserauthenticateController@login');



Route::controllers([

	'auth' => 'Auth\AuthController',

	'password' => 'Auth\PasswordController',

]);





Route::get('/admin', 'admin\AdminauthenticateController@login');

Route::post('/admin/login', 'admin\AdminauthenticateController@postLogin');



Route::get('/admin/logout', 'admin\AdminController@logout');



Route::get('/admin/profile', 'admin\AdminController@profile');

Route::get('/admin/vat_profile', 'admin\AdminController@vatprofile');

Route::get('/admin/email_settings', 'admin\AdminController@email_settings');

Route::get('/admin/p30_profile', 'admin\AdminController@p30profile');



Route::post('/admin/update_admin_setting', 'admin\AdminController@update_admin_setting');

Route::post('/admin/update_user_notification', 'admin\AdminController@update_user_notification');

Route::post('/admin/update_user_signature', 'admin\AdminController@update_user_signature');



Route::post('/admin/update_user_setting', 'admin\AdminController@update_user_setting');

Route::post('/admin/update_email_setting', 'admin\AdminController@update_email_setting');



Route::get('/admin/manage_year', 'admin\YearController@manageyear');

Route::get('/admin/deactive_year/{id?}', 'admin\YearController@deactiveyear');

Route::get('/admin/active_year/{id?}', 'admin\YearController@activeyear');

Route::post('/admin/add_year/', 'admin\YearController@addyear');

Route::get('/admin/delete_year/{id?}', 'admin\YearController@deleteyear');

Route::post('/admin/edit_year/{id?}', 'admin\YearController@edityear');

Route::post('/admin/update_year/', 'admin\YearController@updateyear');

Route::post('/admin/check_year/', 'admin\YearController@checkyear');





Route::get('/admin/manage_user', 'admin\UserController@manageuser');

Route::get('/admin/deactive_user/{id?}', 'admin\UserController@deactiveuser');

Route::get('/admin/active_user/{id?}', 'admin\UserController@activeuser');

Route::post('/admin/add_user/', 'admin\UserController@adduser');

Route::get('/admin/delete_user/{id?}', 'admin\UserController@deleteuser');

Route::post('/admin/edit_user/{id?}', 'admin\UserController@edituser');

Route::post('/admin/update_user/', 'admin\UserController@updateuser');



Route::get('/admin/central_locations', 'admin\AdminController@central_locations');

Route::post('/admin/update_central_locations', 'admin\AdminController@update_central_locations');

Route::post('/admin/update_central_locations_form', 'admin\AdminController@update_central_locations_form');





Route::get('/admin/manage_task/', 'admin\TaskyearController@taskyear');

Route::post('/admin/add_taskyear/', 'admin\TaskyearController@addtaskyear');

Route::get('/admin/deactive_taskyear/{id?}', 'admin\TaskyearController@deactivetaskyear');

Route::get('/admin/active_taskyear/{id?}', 'admin\TaskyearController@activetaskyear');

Route::get('/admin/delete_taskyear/{id?}', 'admin\TaskyearController@deletetaskyear');

Route::get('/admin/edit_taskyear/{id?}', 'admin\TaskyearController@edittaskyear');

Route::post('/admin/update_taskyear/', 'admin\TaskyearController@updatetaskyear');



Route::post('/user/login', 'user\UserauthenticateController@postLogin');

Route::get('/user/logout', 'user\UserController@logout');

Route::get('/user/manage_week', 'user\UserController@manageweek');

Route::get('/user/downloadpdf', 'user\UserController@downloadpdf');

Route::get('/user/week_manage/{id?}', 'user\UserController@weekmanage');

Route::get('/user/select_week/{id?}', 'user\UserController@selectweek');

Route::post('/user/add_new_task/', 'user\UserController@addnewtask');

Route::get('/user/delete_task/{id?}', 'user\UserController@deletetask');



Route::get('/user/task_enterhours', 'user\UserController@task_enterhours');

Route::get('/user/task_started_checkbox', 'user\UserController@task_started_checkbox');



Route::get('/user/notify_tasks', 'user\UserController@notify_tasks');

Route::get('/user/notify_tasks_month', 'user\UserController@notify_tasks_month');
Route::post('/user/taskmanager_upload_images', 'user\UserController@taskmanager_upload_images');
Route::post('/user/remove_dropzone_attachment', 'user\UserController@remove_dropzone_attachment');

Route::get('/user/task_holiday', 'user\UserController@task_holiday');

Route::get('/user/task_process', 'user\UserController@task_process');

Route::get('/user/task_payslips', 'user\UserController@task_payslips');

Route::get('/user/task_email', 'user\UserController@task_email');

Route::get('/user/task_upload', 'user\UserController@task_upload');

Route::get('/user/task_date_update', 'user\UserController@task_date_update');

Route::get('/user/task_email_update', 'user\UserController@task_email_update');

Route::get('/user/task_users_update', 'user\UserController@task_users_update');

Route::get('/user/task_comments_update', 'user\UserController@task_comments_update');

Route::get('/user/task_classified_update', 'user\UserController@task_classified_update');

Route::post('/user/task_image_upload', 'user\UserController@task_image_upload');

Route::post('/user/task_notepad_upload', 'user\UserController@task_notepad_upload');

Route::post('/user/copy_task', 'user\UserController@copy_task');

Route::get('/user/task_delete_image', 'user\UserController@task_delete_image');

Route::get('/user/task_delete_all_image', 'user\UserController@task_delete_all_image');

Route::get('/user/task_delete_all_image_attachments', 'user\UserController@task_delete_all_image_attachments');

Route::get('/user/task_status_update', 'user\UserController@task_status_update');



Route::get('/user/get_week_by_year', 'user\UserController@get_week_by_year');

Route::get('/user/get_month_by_year', 'user\UserController@get_month_by_year');

Route::post('/user/email_unsent_files', 'user\UserController@email_unsent_files');

Route::get('/user/email_report_pdf', 'user\UserController@email_report_pdf');

Route::get('/user/email_notify_pdf', 'user\UserController@email_notify_pdf');

Route::get('/user/email_notify_tasks_pdf', 'user\UserController@email_notify_tasks_pdf');

Route::get('/user/email_notify_pdf_month', 'user\UserController@email_notify_pdf_month');

Route::post('/user/email_report_send', 'user\UserController@email_report_send');

Route::get('/user/close_create_new_week/{id?}', 'user\UserController@close_create_new_week');

Route::get('/user/close_create_new_month/{id?}', 'user\UserController@close_create_new_month');



Route::get('/user/manage_month', 'user\UserController@managemonth');

Route::get('/user/month_manage/{id?}', 'user\UserController@monthmanage');

Route::get('/user/select_month/{id?}', 'user\UserController@selectmonth');

Route::post('/user/add_new_task_month/', 'user\UserController@addnewtask_month');

Route::get('/user/email_report_pdf_month', 'user\UserController@email_report_pdf_month');



Route::get('/user/alltask_report_pdf_month', 'user\UserController@alltask_report_pdf_month');

Route::get('/user/task_complete_report_pdf_month', 'user\UserController@task_complete_report_pdf_month');

Route::get('/user/task_incomplete_report_pdf_month', 'user\UserController@task_incomplete_report_pdf_month');



Route::get('/user/alltask_report_pdf', 'user\UserController@alltask_report_pdf');

Route::get('/user/task_complete_report_pdf', 'user\UserController@task_complete_report_pdf');

Route::get('/user/task_incomplete_report_pdf', 'user\UserController@task_incomplete_report_pdf');





Route::get('/user/edit_task_name', 'user\UserController@edit_task_name');

Route::get('/user/edit_email_unsent_files', 'user\UserController@edit_email_unsent_files');



Route::post('/user/edit_task_details', 'user\UserController@edit_task_details');



Route::post('/user/update_incomplete_status', 'user\UserController@update_incomplete_status');

Route::post('/user/update_incomplete_status_month', 'user\UserController@update_incomplete_status_month');



Route::get('/user/vat_clients', 'user\UserController@vatclients');

Route::get('/user/deactive_vat_clients/{id?}', 'user\UserController@deactivevatclients');

Route::get('/user/active_vat_clients/{id?}', 'user\UserController@activevatclients');

Route::post('/user/edit_vat_clients/{id?}', 'user\UserController@editvatclients');

Route::post('/user/add_vat_clients', 'user\UserController@addvatclients');

Route::post('/user/update_vat_clients/', 'user\UserController@updatevatclients');



Route::get('/user/check_client_email', 'user\UserController@checkclientemail');

Route::get('/user/check_client_taxnumber', 'user\UserController@checkclienttaxnumber');

Route::post('/user/import_form','user\UserController@import_form');

Route::get('/user/import_form_one','user\UserController@import_form_one');



Route::post('/user/compare_form','user\UserController@compare_form');

Route::get('/user/compare_form_one','user\UserController@compare_form_one');



Route::get('/user/vat_notifications','user\UserController@vat_notifications');



Route::get('/user/import_sessions','user\UserController@import_sessions');

Route::get('/user/import_sessions_one','user\UserController@import_sessions_one');



Route::get('/user/email_vatnotifications', 'user\UserController@email_vatnotifications');

Route::get('/user/email_sents', 'user\UserController@email_sents');

Route::get('/user/email_sents_save_pdf', 'user\UserController@email_sents_save_pdf');



Route::get('/user/pdf_without_email','user\UserController@pdf_without_email');

Route::get('/user/pdf_with_email','user\UserController@pdf_with_email');

Route::get('/user/pdf_disabled','user\UserController@pdf_disabled');



Route::post('/user/getclientcompanyname','user\UserController@getclientcompanyname');

Route::post('/user/getclientemail','user\UserController@getclientemail');

Route::post('/user/getclientemail_secondary','user\UserController@getclientemail_secondary');



/*----------------------P30 START----------------------*/

Route::get('/user/p30', 'user\P30Controller@p30');

Route::get('/user/p30month_manage/{id?}', 'user\P30Controller@p30monthmanage');

Route::get('/user/p30_select_month/{id?}', 'user\P30Controller@p30selectmonth');

Route::get('/user/p30_review_month/{id?}', 'user\P30Controller@review_month');

Route::get('/user/p30_close_create_new_month/{id?}', 'user\P30Controller@close_create_new_month');

Route::post('/user/p30_tasklevel_update/{id?}', 'user\P30Controller@p30tasklevelupdate');

Route::post('/user/p30_period_update/{id?}', 'user\P30Controller@p30periodupdate');



Route::get('/user/pay_p30', 'user\P30Controller@payp30');

Route::get('/user/na_p30', 'user\P30Controller@nap30');



Route::get('/user/email_p30', 'user\P30Controller@emailp30');

Route::post('/user/p30_task_image_upload', 'user\P30Controller@p30_task_image_upload');

Route::post('/user/p30_task_automatic_image_upload', 'user\P30Controller@p30_task_automatic_image_upload');

Route::get('/user/p30_task_delete_image', 'user\P30Controller@p30_task_delete_image');

Route::get('/user/p30_task_delete_xml', 'user\P30Controller@p30_task_delete_xml');



Route::get('/user/p30_task_liability_update', 'user\P30Controller@task_liability_update');

Route::get('/user/p30_edit_email_unsent_files', 'user\P30Controller@p30_edit_email_unsent_files');

Route::post('/user/p30_email_unsent_files', 'user\P30Controller@p30_email_unsent_files');

Route::get('/user/p30_task_status_update', 'user\P30Controller@p30_task_status_update');

Route::post('/user/p30_report_task', 'user\P30Controller@p30_report_task');

Route::post('/user/download_p30_pdf_report', 'user\P30Controller@download_p30_pdf_report');

Route::post('/user/import_p30_review_due', 'user\P30Controller@import_p30_review_due');

Route::get('/user/download_p30_review', 'user\P30Controller@download_p30_review');

Route::post('/user/update_p30_incomplete_status_month', 'user\P30Controller@update_p30_incomplete_status_month');

Route::post('/user/update_p30_na_status_month', 'user\P30Controller@update_p30_na_status_month');





Route::get('/admin/p30_task_leval', 'admin\P30Controller@tasklevel');

Route::get('/admin/deactive_p30_tasklevel/{id?}', 'admin\P30Controller@deactivetasklevel');

Route::get('/admin/active_p30_tasklevel/{id?}', 'admin\P30Controller@activetasklevel');

Route::post('/admin/add_p30_tasklevel/', 'admin\P30Controller@addtasklevel');

Route::post('/admin/edit_p30_tasklevel/{id?}', 'admin\P30Controller@edittasklevel');

Route::post('/admin/update_p30_tasklevel/', 'admin\P30Controller@updatetasklevel');



Route::get('/admin/p30_period', 'admin\P30Controller@period');

Route::get('/admin/deactive_p30_period/{id?}', 'admin\P30Controller@deactiveperiod');

Route::get('/admin/active_p30_period/{id?}', 'admin\P30Controller@activeperiod');

Route::post('/admin/add_p30_period/', 'admin\P30Controller@addperiod');

Route::post('/admin/edit_p30_period/{id?}', 'admin\P30Controller@editperiod');

Route::post('/admin/update_p30_period/', 'admin\P30Controller@updateperiod');



Route::get('/admin/period_sort_order/', 'admin\P30Controller@period_sort_order');



Route::get('/admin/p30_due_date', 'admin\P30Controller@duedate');

Route::post('/admin/update_p30_duedate/', 'admin\P30Controller@updateduedate');





/*----------------------GBSCO P30 START----------------------*/

Route::get('/user/gbs_p30', 'user\Gbs_P30Controller@gbs_p30');

Route::get('/user/gbs_p30month_manage/{id?}', 'user\Gbs_P30Controller@gbs_p30monthmanage');

Route::get('/user/gbs_p30_select_month/{id?}', 'user\Gbs_P30Controller@gbs_p30selectmonth');

Route::get('/user/gbs_p30_review_month/{id?}', 'user\Gbs_P30Controller@review_month');

Route::get('/user/gbs_p30_close_create_new_month/{id?}', 'user\Gbs_P30Controller@close_create_new_month');

Route::post('/user/gbs_p30_tasklevel_update/{id?}', 'user\Gbs_P30Controller@gbs_p30tasklevelupdate');

Route::post('/user/gbs_p30_period_update/{id?}', 'user\Gbs_P30Controller@gbs_p30periodupdate');



Route::get('/user/pay_gbs_p30', 'user\Gbs_P30Controller@paygbs_p30');

Route::get('/user/na_gbs_p30', 'user\Gbs_P30Controller@nagbs_p30');



Route::get('/user/email_gbs_p30', 'user\Gbs_P30Controller@emailgbs_p30');

Route::post('/user/gbs_p30_task_image_upload', 'user\Gbs_P30Controller@gbs_p30_task_image_upload');

Route::post('/user/gbs_p30_task_automatic_image_upload', 'user\Gbs_P30Controller@gbs_p30_task_automatic_image_upload');

Route::get('/user/gbs_p30_task_delete_image', 'user\Gbs_P30Controller@gbs_p30_task_delete_image');

Route::get('/user/gbs_p30_task_delete_xml', 'user\Gbs_P30Controller@gbs_p30_task_delete_xml');



Route::get('/user/gbs_p30_task_liability_update', 'user\Gbs_P30Controller@task_liability_update');

Route::get('/user/gbs_p30_edit_email_unsent_files', 'user\Gbs_P30Controller@gbs_p30_edit_email_unsent_files');

Route::post('/user/gbs_p30_email_unsent_files', 'user\Gbs_P30Controller@gbs_p30_email_unsent_files');

Route::get('/user/gbs_p30_task_status_update', 'user\Gbs_P30Controller@gbs_p30_task_status_update');

Route::post('/user/gbs_p30_report_task', 'user\Gbs_P30Controller@gbs_p30_report_task');

Route::post('/user/download_gbs_p30_pdf_report', 'user\Gbs_P30Controller@download_gbs_p30_pdf_report');

Route::post('/user/import_gbs_p30_review_due', 'user\Gbs_P30Controller@import_gbs_p30_review_due');

Route::get('/user/download_gbs_p30_review', 'user\Gbs_P30Controller@download_gbs_p30_review');

Route::post('/user/update_gbs_p30_incomplete_status_month', 'user\Gbs_P30Controller@update_gbs_p30_incomplete_status_month');



Route::get('/admin/gbs_p30_task_leval', 'admin\Gbs_P30Controller@tasklevel');

Route::get('/admin/deactive_gbs_p30_tasklevel/{id?}', 'admin\Gbs_P30Controller@deactivetasklevel');

Route::get('/admin/active_gbs_p30_tasklevel/{id?}', 'admin\Gbs_P30Controller@activetasklevel');

Route::post('/admin/add_gbs_p30_tasklevel/', 'admin\Gbs_P30Controller@addtasklevel');

Route::post('/admin/edit_gbs_p30_tasklevel/{id?}', 'admin\Gbs_P30Controller@edittasklevel');

Route::post('/admin/update_gbs_p30_tasklevel/', 'admin\Gbs_P30Controller@updatetasklevel');



Route::get('/admin/gbs_p30_period', 'admin\Gbs_P30Controller@period');

Route::get('/admin/deactive_gbs_p30_period/{id?}', 'admin\Gbs_P30Controller@deactiveperiod');

Route::get('/admin/active_gbs_p30_period/{id?}', 'admin\Gbs_P30Controller@activeperiod');

Route::post('/admin/add_gbs_p30_period/', 'admin\Gbs_P30Controller@addperiod');

Route::post('/admin/edit_gbs_p30_period/{id?}', 'admin\Gbs_P30Controller@editperiod');

Route::post('/admin/update_gbs_p30_period/', 'admin\Gbs_P30Controller@updateperiod');



Route::get('/admin/gbs_period_sort_order/', 'admin\Gbs_P30Controller@gbs_period_sort_order');



Route::get('/admin/gbs_p30_due_date', 'admin\Gbs_P30Controller@duedate');

Route::post('/admin/update_gbs_p30_duedate/', 'admin\Gbs_P30Controller@updateduedate');



/*----------------------RCT START----------------------*/

Route::get('/admin/manage_rctclients', 'admin\RctClientsController@managerctclients');

Route::get('/admin/deactive_rctclients/{id?}', 'admin\RctClientsController@deactiverctclients');

Route::get('/admin/active_rctclients/{id?}', 'admin\RctClientsController@activerctclients');

Route::post('/admin/add_rctclients/', 'admin\RctClientsController@addrctclients');

Route::get('/admin/delete_rctclients/{id?}', 'admin\RctClientsController@deleterctclients');

Route::post('/admin/edit_rctclients/{id?}', 'admin\RctClientsController@editrctclients');

Route::post('/admin/update_rctclients/', 'admin\RctClientsController@updaterctclients');

Route::get('/admin/rctclient_checkemail/', 'admin\RctClientsController@client_checkemail');

Route::get('/admin/rctclient_checktax/', 'admin\RctClientsController@client_checktax');



Route::get('/user/rctclient_checkemail/', 'user\RctController@client_checkemail');

Route::get('/user/rctclient_checktax/', 'user\RctController@client_checktax');



Route::get('/admin/manage_rctemail_salution', 'admin\SalutionController@manage_salution');

Route::post('/admin/edit_rctsalution/{id?}', 'admin\SalutionController@editsalution');

Route::post('/admin/update_rctsalution/', 'admin\SalutionController@updatesalution');



Route::get('/admin/manage_rctbackground', 'admin\LetterController@manage_letterpad');

Route::post('/admin/edit_rctletterpad/{id?}', 'admin\LetterController@editletterpad');

Route::post('/admin/update_rctletterpad/', 'admin\LetterController@updateletterpad');



Route::get('/user/rctclients', 'user\RctController@rctclients');

Route::post('/user/add_rctclients/', 'user\RctController@addrctclients');

Route::get('/user/rctclient_hidden/{id?}', 'user\RctController@clienthidden');

Route::post('/user/edit_rctclients/{id?}', 'user\RctController@editrctclients');

Route::post('/user/update_rctclients/', 'user\RctController@updaterctclients');



Route::get('/user/expand_rctclient/{id?}', 'user\RctController@expandclient');

Route::post('/user/rct_add_new_item/', 'user\RctController@addnewitem');



Route::post('/user/rctpaginate_response', 'user\RctController@paginate_response');



Route::post('/user/rctclient_expand_type_update', 'user\RctController@clientexpandtypeupdate');

Route::post('/user/rctclient_expand_sub_update', 'user\RctController@clientexpandsubupdate');

Route::post('/user/rctclient_expand_sub_rctno', 'user\RctController@clientexpandsubrctno');

Route::post('/user/rctclient_expand_reference', 'user\RctController@clientexpandreference');

Route::post('/user/rctclient_expand_gross', 'user\RctController@clientexpandgross');

Route::post('/user/rctclient_expand_add_gross', 'user\RctController@clientexpandaddgross');

Route::post('/user/rctclient_expand_add_deduction', 'user\RctController@clientexpandadddeduction');

Route::post('/user/rctclient_expand_check_reference', 'user\RctController@clientexpandcheckreference');



Route::post('/user/rctclient_expand_deduction', 'user\RctController@clientexpanddeduction');

Route::post('/user/rctclient_expand_invoice', 'user\RctController@clientexpandinvoice');

Route::post('/user/rctclient_expand_date_update', 'user\RctController@clientexpanddateupdate');



Route::get('/user/rctclient_expad_delete_item/{id?}', 'user\RctController@clientexpaddeleteitem');

Route::post('/user/rctclient_item_view/{id?}', 'user\RctController@clientitemview');

Route::post('/user/rctclient_item_email/{id?}', 'user\RctController@clientitememail');



Route::post('/user/rctexport_all_pdf/{id?}', 'user\RctController@exportallpdf');

Route::post('/user/rctexport_pdf_rctc/{id?}', 'user\RctController@exportpdfrctc');

Route::post('/user/rctexport_pdf_pctc/{id?}', 'user\RctController@exportpdfpctc');

Route::post('/user/rctexport_pdf_home/{id?}', 'user\RctController@exportpdfhome');



Route::get('/user/rctexport_all_csv/{id?}', 'user\RctController@exportallcsv');

Route::get('/user/rctexport_csv_rctc/{id?}', 'user\RctController@exportcsvrctc');

Route::get('/user/rctexport_csv_pctc/{id?}', 'user\RctController@exportcsvpctc');

Route::get('/user/rctexport_csv_home/{id?}', 'user\RctController@exportcsvhome');

Route::get('/user/rctdownloadpdf', 'user\RctController@downloadpdf');



Route::get('/user/rctclient_search', 'user\RctController@rctclientsearch');

Route::get('/user/rctclient_search_select', 'user\RctController@rctclientsearchselect');

Route::get('/user/rctclient_tax_search', 'user\RctController@clienttaxsearch');

Route::get('/user/rctclient_tax_search_select', 'user\RctController@clienttaxsearchselect');

Route::get('/user/rctclient_email_search', 'user\RctController@clientemailsearch');

Route::get('/user/rctclient_email_search_select', 'user\RctController@clientemailsearchselect');

Route::post('/user/rctclient_email_form', 'user\RctController@clientemailform');

Route::post('/user/rctemail_report_form', 'user\RctController@emailreportform');



Route::get('/user/rctcontractor_search', 'user\RctController@contractorsearch');

Route::get('/user/rctcontractor_search_select', 'user\RctController@contractorsearchselect');



Route::get('/user/rctsub_rct_search', 'user\RctController@subrctsearch');

Route::get('/user/rctsub_rct_select', 'user\RctController@subrctselect');



Route::get('/user/rctreference_search', 'user\RctController@referencesearch');

Route::get('/user/rctreference_select', 'user\RctController@referenceselect');



Route::get('/admin/rctclient_search', 'admin\RctClientsController@rctclientsearch');

Route::get('/admin/rctclient_search_select', 'admin\RctClientsController@rctclientsearchselect');

Route::get('/admin/rctclient_tax_search', 'admin\RctClientsController@clienttaxsearch');

Route::get('/admin/rctclient_tax_search_select', 'admin\RctClientsController@clienttaxsearchselect');

Route::get('/admin/rctclient_email_search', 'admin\RctClientsController@clientemailsearch');

Route::get('/admin/rctclient_email_search_select', 'admin\RctClientsController@clientemailsearchselect');



Route::post('/user/rctimport_form','user\RctController@import_form');

Route::get('/user/rctimport_form_one','user\RctController@import_form_one');

/*----------------------RCT END----------------------*/





/*----------------------CM Stystem START----------------------*/



Route::get('/user/client_management','user\CmController@clientmanagement');

Route::get('/user/clientmanagement_paginate','user\CmController@clientmanagement_paginate');



Route::post('/user/add_cm_clients','user\CmController@addcmclients');

Route::post('/user/edit_cm_client/{id?}', 'user\CmController@editcmclient');

Route::post('/user/copy_cm_client/{id?}', 'user\CmController@copycmclient');

Route::post('/user/cm_status_clients/', 'user\CmController@cm_status_clients');

Route::post('/user/save_image/', 'user\CmController@save_image');

Route::post('/user/cm_print_details/', 'user\CmController@cm_print_details');



Route::post('/user/update_cm_clients/', 'user\CmController@updatecmclients');

Route::get('/user/cm_search_clients','user\CmController@cm_search_clients');

Route::post('/user/update_cm_incomplete_status', 'user\CmController@update_cm_incomplete_status');

Route::post('/user/cm_report_pdf', 'user\CmController@cm_report_pdf');

Route::post('/user/cm_report_pdf_type_2', 'user\CmController@cm_report_pdf_type_2');

Route::post('/user/download_report_pdfs', 'user\CmController@download_report_pdfs');



Route::post('/user/cm_bulkreport_pdf', 'user\CmController@cm_bulkreport_pdf');



Route::post('/user/cm_report_csv', 'user\CmController@cm_report_csv');

Route::post('/user/cm_upload', 'user\CmController@cm_upload');

Route::post('/user/cm_bulk_email', 'user\CmController@cm_bulk_email');

Route::post('/user/email_check_crypt_pin', 'user\CmController@email_check_crypt_pin');

Route::get('/user/get_cm_report_clients', 'user\CmController@get_cm_report_clients');

Route::get('/user/get_cm_bulk_clients', 'user\CmController@get_cm_bulk_clients');

Route::get('/user/get_cm_import_clients', 'user\CmController@get_cm_import_clients');

Route::post('/user/import_new_clients', 'user\CmController@import_new_clients');

Route::get('/user/import_new_clients_one', 'user\CmController@import_new_clients_one');



Route::post('/user/import_existing_clients', 'user\CmController@import_existing_clients');

Route::get('/user/import_existing_clients_one', 'user\CmController@import_existing_clients_one');

Route::post('/user/cm_statement_update', 'user\CmController@cm_statement_update');





Route::post('/user/cm_client_invoice', 'user\CmController@cm_client_invoice');

Route::post('/user/cm_client_payroll', 'user\CmController@cm_client_payroll');



Route::post('/user/cm_invoice_report_csv', 'user\CmController@cm_invoice_report_csv');

Route::post('/user/cm_get_csv_filename', 'user\CmController@cm_get_csv_filename');

Route::post('/user/cm_invoice_report_pdf', 'user\CmController@cm_invoice_report_pdf');

Route::post('/user/cm_invoice_download_report_pdfs', 'user\CmController@cm_invoice_download_report_pdfs');







/*----------------------CM Stystem END----------------------*/







Route::get('/user/invoice_management','user\UserController@unavailable');

Route::get('/user/client_statements','user\UserController@unavailable');

Route::get('/user/receipt_management','user\UserController@unavailable');

Route::get('/user/time_management','user\UserController@unavailable');



Route::get('/user/year_end_manager','user\UserController@unavailable');



Route::get('/user/time_me','user\UserController@unavailable');



Route::get('/user/vat_client_search', 'user\UserController@vat_client_search');

Route::get('/user/vat_client_search_select', 'user\UserController@vat_clientsearchselect');



/*----------------------Invoice System START----------------------*/



Route::get('/user/invoice_management','user\InvoiceController@invoicemanagement');

Route::get('/user/invoice_search','user\InvoiceController@invoice_search');

Route::post('/user/show_statement', 'user\InvoiceController@show_statement');

Route::get('/user/invoicemanagement_paginate','user\InvoiceController@invoicemanagement_paginate');

Route::post('/user/report_client_invoice', 'user\InvoiceController@report_client_invoice');

Route::post('/user/invoice_report_csv', 'user\InvoiceController@invoice_report_csv');

Route::post('/user/invoice_report_pdf', 'user\InvoiceController@invoice_report_pdf');

Route::post('/user/invoice_download_report_pdfs', 'user\InvoiceController@invoice_download_report_pdfs');

Route::post('/user/import_new_invoice', 'user\InvoiceController@import_new_invoice');

Route::get('/user/import_new_invoice_one', 'user\InvoiceController@import_new_invoice_one');



Route::post('/user/report_client_invoice_date_filter', 'user\InvoiceController@report_client_invoice_date_filter');

Route::get('/user/invoicemanagement_paginate','user\InvoiceController@invoicemanagement_paginate');

Route::post('/user/invoices_print_view', 'user\InvoiceController@invoicesprintview');

Route::post('/user/invoice_saveas_pdf', 'user\InvoiceController@invoice_saveas_pdf');
Route::post('/user/invoice_print_pdf', 'user\InvoiceController@invoice_print_pdf');






/*----------------------Invoice Stystem END----------------------*/



/*----------------------Time Me Start----------------------*/



Route::get('/user/time_task','user\TimemeController@time_task');

Route::post('/user/time_task_client_details', 'user\TimemeController@time_task_client_details');

Route::post('/user/time_task_add', 'user\TimemeController@time_task_add');

Route::post('/user/time_task_update', 'user\TimemeController@time_task_update');

Route::post('/user/time_task_client_counts', 'user\TimemeController@time_task_client_counts');

Route::post('/user/timetasklock_unlock', 'user\TimemeController@timetasklock_unlock');

Route::post('/user/timetask_edit', 'user\TimemeController@timetask_edit');

Route::post('/user/time_task_review', 'user\TimemeController@time_task_review');

Route::post('/user/time_task_review_all', 'user\TimemeController@time_task_review_all');


/*----------------------Time Me END----------------------*/


/*----------------------Time System Start----------------------*/

Route::get('/user/timesystem_client_search', 'user\TimejobController@timesystem_client_search');
Route::get('/user/timesystem_client_search_select', 'user\TimejobController@timesystem_clientsearchselect');
Route::get('/user/timesystem_client_search_select_tasks', 'user\TimejobController@timesystem_client_search_select_tasks');

Route::post('/user/time_job_add', 'user\TimejobController@timejobadd');
Route::post('/user/time_job_edit', 'user\TimejobController@time_job_edit');

Route::post('/user/time_job_stop', 'user\TimejobController@timejobstop');
Route::post('/user/time_job_stop_quick', 'user\TimejobController@timejobstopquick');

Route::post('/user/edit_time_job_update', 'user\TimejobController@edit_time_job_update');

Route::get('/user/stop_job_details', 'user\TimejobController@stop_job_details');
Route::post('/user/job_add_break', 'user\TimejobController@jobaddbreak');
Route::get('/user/break_time_details', 'user\TimejobController@breaktimedetails');
Route::get('/user/job_user_filter', 'user\TimejobController@jobuserfilter');

Route::get('/user/time_me_overview', 'user\TimejobController@time_active_job');
Route::get('/user/time_me_joboftheday', 'user\TimejobController@time_joboftheday');
Route::get('/user/time_me_client_review', 'user\TimejobController@time_client_review');
Route::get('/user/time_me_all_job', 'user\TimejobController@time_all_job');

Route::post('/user/job_time_count_refresh', 'user\TimejobController@jobtimecountrefresh');

Route::post('/user/active_job_report_csv', 'user\TimejobController@active_job_report_csv');
Route::post('/user/active_job_report_pdf', 'user\TimejobController@active_jobreportpdf');
Route::post('/user/active_job_report_pdf_download', 'user\TimejobController@active_jobreportpdfdownload');

Route::post('/user/all_job_report_csv', 'user\TimejobController@all_job_report_csv');
Route::post('/user/all_job_report_pdf', 'user\TimejobController@all_jobreportpdf');
Route::post('/user/all_job_report_pdf_download', 'user\TimejobController@all_jobreportpdfdownload');


Route::post('/user/joboftheday_report_csv', 'user\TimejobController@joboftheday_report_csv');
Route::post('/user/joboftheday_report_pdf', 'user\TimejobController@joboftheday_reportpdf');
Route::post('/user/joboftheday_report_pdf_download', 'user\TimejobController@joboftheday_report_pdf_download');

Route::post('/user/clientreview_report_csv', 'user\TimejobController@clientreview_report_csv');
Route::post('/user/clientreview_report_pdf', 'user\TimejobController@clientreview_report_pdf');
Route::post('/user/clientreview_report_pdf_download', 'user\TimejobController@clientreview_report_pdf_download');



Route::get('/user/search_job_of_day', 'user\TimejobController@searchjobofday');
Route::get('/user/search_client_review', 'user\TimejobController@search_client_review');

Route::get('/user/get_job_details', 'user\TimejobController@get_job_details');







/*----------------------Time System END----------------------*/




Route::get('/user/dashboard', 'user\UserController@dashboard');

Route::get('/user/task_client_search', 'user\UserController@task_client_search');

Route::get('/user/task_client_search_select', 'user\UserController@task_clientsearchselect');




/*----------------------CM START----------------------*/

Route::get('/admin/cm_profile', 'admin\CmsystemController@cmprofile');

Route::post('/admin/update_cm_crypt', 'admin\CmsystemController@updatecmcrypt');





Route::get('/admin/cm_clients_list', 'admin\CmsystemController@cm_clients_list');

Route::get('/admin/manage_cm_class', 'admin\CmsystemController@cmclass');

Route::post('/admin/add_cm_class', 'admin\CmsystemController@addclass');

Route::post('/admin/edit_cm_class/{id?}', 'admin\CmsystemController@editcmclass');

Route::post('/admin/update_cm_class/', 'admin\CmsystemController@updatecmclass');

Route::get('/admin/deactive_cm_class/{id?}', 'admin\CmsystemController@deactivecmclass');

Route::get('/admin/active_cm_class/{id?}', 'admin\CmsystemController@activecmclass');



Route::get('/admin/manage_cm_paper', 'admin\CmsystemController@cmpaper');

Route::post('/admin/add_cm_paper', 'admin\CmsystemController@addpaper');

Route::post('/admin/edit_cm_paper/{id?}', 'admin\CmsystemController@editcmpaper');

Route::post('/admin/update_cm_paper/', 'admin\CmsystemController@updatecmpaper');

Route::get('/admin/deactive_cm_paper/{id?}', 'admin\CmsystemController@deactivecmpaper');

Route::get('/admin/active_cm_paper/{id?}', 'admin\CmsystemController@activecmpaper');



Route::get('/admin/manage_cm_fields', 'admin\CmsystemController@cmfields');

Route::post('/admin/add_cm_field', 'admin\CmsystemController@addfield');

Route::post('/admin/edit_cm_field/{id?}', 'admin\CmsystemController@editfield');

Route::post('/admin/update_cm_field/', 'admin\CmsystemController@updatecmfield');

Route::get('/admin/deactive_cm_field/{id?}', 'admin\CmsystemController@deactivefield');

Route::get('/admin/active_cm_field/{id?}', 'admin\CmsystemController@activefield');

Route::get('/admin/cm_client_checkfield/', 'admin\CmsystemController@cm_client_checkfield');

Route::get('/admin/cm_search_clients','admin\CmsystemController@cm_search_clients');

Route::post('/admin/update_cm_incomplete_status', 'admin\CmsystemController@update_cm_incomplete_status');

Route::post('/admin/change_cm_client_class', 'admin\CmsystemController@change_cm_client_class');



/*----------------------CM END----------------------*/

Route::get('/user/resendedit_email_unsent_files', 'user\UserController@resendedit_email_unsent_files');
Route::get('/user/task_complete_update/', 'user\UserController@task_complete_update');

Route::get('/user/time_track', 'user\UserController@time_track');
Route::get('/user/get_quick_break_details', 'user\TimejobController@get_quick_break_details');
Route::get('/user/calculate_job_time', 'user\TimejobController@calculate_job_time');
Route::get('/user/calculate_break_time', 'user\TimejobController@calculate_break_time');


Route::get('/user/in_file/', 'user\InfileController@infile');
Route::get('/user/infile_user_update/', 'user\InfileController@infile_userupdate');
Route::get('/user/infile_complete_date/', 'user\InfileController@infile_completedate');
Route::get('/user/in_file_status_update/', 'user\InfileController@in_file_statusupdate');
Route::post('/user/in_file_show_incomplete/', 'user\InfileController@in_file_showincomplete');

Route::get('/user/infile_client_search', 'user\InfileController@infile_client_search');
Route::get('/user/infile_client_search_select', 'user\InfileController@infile_clientsearchselect');
Route::post('/user/infile_image_upload', 'user\InfileController@infile_imageupload');
Route::post('/user/infile_upload_images', 'user\InfileController@infile_upload_images');
Route::post('/user/infile_remove_dropzone_attachment', 'user\InfileController@remove_dropzone_attachment');
Route::get('/user/infile_delete_image', 'user\InfileController@infile_delete_image');
Route::get('/user/infile_delete_all_image', 'user\InfileController@infile_delete_all_image');

Route::get('/user/infile_download_image', 'user\InfileController@infile_download_image');
Route::get('/user/infile_download_all_image', 'user\InfileController@infile_download_all_image');
Route::get('/user/infile_download_rename_all_image', 'user\InfileController@infile_download_rename_all_image');



Route::post('/user/infile_notepad_upload', 'user\InfileController@infile_notepad_upload');
Route::post('/user/infile_notepad_upload_notes', 'user\InfileController@infile_notepad_upload_notes');
Route::get('/user/infile_delete_all_notes_only', 'user\InfileController@infile_delete_all_notes_only');

Route::get('/user/infile_delete_all_notes', 'user\InfileController@infile_delete_all_notes');

Route::get('/user/infile_download_all_notes_only', 'user\InfileController@infile_download_all_notes_only');
Route::get('/user/infile_download_all_notes', 'user\InfileController@infile_download_all_notes');

Route::get('/user/task_client_common_search', 'user\InfileController@infile_commonclient_search');
Route::get('/user/task_client_common_search_select', 'user\InfileController@infile_commonclientsearchselect');
Route::post('/user/add_notepad_contents', 'user\InfileController@add_notepad_contents');
Route::post('/user/infile_upload_images_add', 'user\InfileController@infile_upload_images_add');
Route::post('/user/create_new_file', 'user\InfileController@create_new_file');\
Route::post('/user/clear_session_attachments', 'user\InfileController@clear_session_attachments');

Route::post('/user/delete_file_link', 'user\InfileController@delete_file_link');
Route::get('/user/infile_search', 'user\InfileController@infile_search');
Route::get('/user/infile_internal', 'user\InfileController@infile_internal');

Route::post('/user/fileattachment_status', 'user\InfileController@fileattachment_status');
Route::get('/user/infile_email_notify_tasks_pdf', 'user\InfileController@infile_email_notify_tasks_pdf');
Route::get('/user/change_attachment_text_status', 'user\InfileController@change_attachment_text_status');
Route::get('/user/remove_attachment_text_status', 'user\InfileController@remove_attachment_text_status');
Route::get('/user/update_fileattachment_textval', 'user\InfileController@update_fileattachment_textval');
Route::get('/user/get_attachment_details', 'user\InfileController@get_attachment_details');

Route::get('/user/email_report_generator', 'user\UserController@email_report_generator');

Route::post('/user/report_infile', 'user\InfileController@report_infile');
Route::post('/user/infile_report_pdf', 'user\InfileController@infile_report_pdf');
Route::post('/user/download_infile_report_pdf', 'user\InfileController@download_infile_report_pdf');
Route::post('/user/infile_report_csv', 'user\InfileController@infile_report_csv');
Route::post('/user/infile_report_csv_single', 'user\InfileController@infile_report_csv_single');

Route::post('/user/infile_report_pdf_single', 'user\InfileController@infile_report_pdf_single');
Route::post('/user/download_infile_report_pdf_single', 'user\InfileController@download_infile_report_pdf_single');

Route::post('/user/infile_report_incomplete', 'user\InfileController@infile_report_incomplete');

Route::get('/user/donot_complete_task_details/', 'user\UserController@donot_complete_task_details');
Route::get('/user/task_complete_update_new/', 'user\UserController@task_complete_update_new');

Route::get('/user/edit_time_job', 'user\TimejobController@edit_time_job');

Route::post('/user/cm_note_update','user\CmController@cm_note_update');








