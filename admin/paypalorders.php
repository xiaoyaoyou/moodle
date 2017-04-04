<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Provides an overview of installed reports
 *
 * Displays the list of found reports, their version (if found) and
 * a link to uninstall the report.
 *
 * The code is based on admin/localplugins.php by David Mudrak.
 *
 * @package   admin
 * @copyright 2011 Petr Skoda {@link http://skodak.org}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/tablelib.php');

admin_externalpage_setup('paypalorderreports');

$page         = optional_param('page', 0, PARAM_INT);
$perpage      = optional_param('perpage', 2, PARAM_INT);        // how many per page

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('paypalorders', 'admin'));

$table = new flexible_table('report_paypalorders_table');
$table->define_columns(array('quizpayuser', 'paypalreceiveemail', 'paydesc', 'paytime'));
$table->define_headers(array(get_string('quizpayuser',  'admin'), get_string('paypalreceiveemail',  'admin'), get_string('paydesc',  'admin'),get_string('paytime',  'admin')));
$table->define_baseurl($PAGE->url);
$table->set_attribute('id', 'reportplugins');
$table->set_attribute('class', 'admintable generaltable');
$table->setup();

$select = 'payment_status = ?';
$params = array('Completed');
$orderby = 'id DESC';
$paypalorders = $DB->get_records_select('availability_paypal_tnx', $select, $params, $orderby, '*', $page*$perpage, $perpage);

foreach ($paypalorders as $theorder) {
    $table->add_data(array($theorder->userid, $theorder->receiver_email, $theorder->item_name, $theorder->timeupdated));
}

$ordercount = $DB->count_records('availability_paypal_tnx', array('payment_status' => 'Completed'));

$table->print_html();
$baseurl = new moodle_url('/admin/paypalorders.php', array('perpage' => $perpage, 'page'=>$page));
echo $OUTPUT->paging_bar($ordercount, $page, $perpage, $baseurl);

echo $OUTPUT->footer();
