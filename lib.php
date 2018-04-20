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
 * @package    report_categorymanager
 * @copyright  2018, University of Wuppertal (Sebastian Sennewald)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function report_categorymanager_extend_navigation_category_settings($parentnode, $context) {

    if (has_capability('report/categorymanager:viewName', $context)) {

        $url = new moodle_url('/report/categorymanager/index.php', array('categoryid' => $context->instanceid));
        $parentnode->add(get_string('navlink', 'report_categorymanager'), $url, navigation_node::TYPE_SETTING, null, null, new pix_icon('icon', '', 'report_categorymanager'));

    }
}

function cmp($a, $b) {
    return strcmp($a->lastname, $b->lastname);
}
