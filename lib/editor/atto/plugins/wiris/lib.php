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
 * Library functions for WIRIS plugin for Atto.
 *
 * @package    atto
 * @subpackage wiris
 * @copyright  Maths for More S.L. <info@wiris.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Initialise the js strings required for this module.
 */
function atto_wiris_strings_for_js() {
    global $PAGE;
    $PAGE->requires->strings_for_js(
      array(
        'wiris_editor_title',
        'wiris_cas_title',
        'wiris_chem_editor_title',
      ),
      'atto_wiris');
}

/**
 * Set parameters to be passed to the js plugin constructor.
 */
function atto_wiris_params_for_js() {
    global $COURSE;
    // We need to know if  WIRIS filter are active in the context of the course.
    // If not WIRIS plugin should be disabled.
    $filterwirisactive = true;
    if (!get_config('filter_wiris', 'allow_editorplugin_active_course')) {
        $context = context_course::instance($COURSE->id);
        $activefilters = filter_get_active_in_context($context);
        $filterwirisactive = array_key_exists('wiris', $activefilters);
    }
    // Atto js plugin checks if the filter is - or not - active.
    return array('lang' => current_language(), 'filter_enabled' => $filterwirisactive);
}
