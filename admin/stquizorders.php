<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/tablelib.php');

admin_externalpage_setup('stquizorderreports');

$page         = optional_param('page', 0, PARAM_INT);
$perpage      = optional_param('perpage', 10, PARAM_INT);        // how many per page

echo $OUTPUT->header();
echo $OUTPUT->heading('quiz orders');

$table = new flexible_table('report_stquizorders_table');
$table->define_columns(array('quizpayuser', 'paypalreceiveemail', 'quizname', 'paytime'));
$table->define_headers(array(get_string('quizpayuser',  'admin'), get_string('paypalreceiveemail',  'admin'), get_string('quizname',  'admin'),get_string('paytime',  'admin')));
$table->define_baseurl($PAGE->url);
$table->set_attribute('id', 'reportplugins');
$table->set_attribute('class', 'admintable generaltable');
$table->setup();

$wheres = array("ep.payment_status = :paystatus");
$wheres = implode(" AND ", $wheres);
$orderby = "ORDER BY ep.id desc";
$sql = "SELECT ep.id, ep.userid, ep.receiver_email, ep.item_name, ep.courseid, ep.timeupdated, u.username
              FROM {enrol_paypal} ep
              INNER JOIN {user} u ON (ep.userid = u.id)
             WHERE $wheres
          $orderby";
$params = array('paystatus'=>'Completed');

$paypalorders = $DB->get_records_sql($sql, $params, $page*$perpage, $perpage);

foreach ($paypalorders as $theorder) {
    $table->add_data(array("<a href=\"../user/view.php?id=$theorder->userid\">$theorder->username</a>", $theorder->receiver_email, "<a href=\"$CFG->wwwroot/course/view.php?id=$theorder->courseid\">$theorder->item_name</a>", $theorder->timeupdated));
}

$ordercount = $DB->count_records('enrol_paypal', array('payment_status' => 'Completed'));

$table->print_html();
$baseurl = new moodle_url('/admin/stquizorders.php', array('perpage' => $perpage, 'page'=>$page));
echo $OUTPUT->paging_bar($ordercount, $page, $perpage, $baseurl);

echo $OUTPUT->footer();
