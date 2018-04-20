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

// Load Report Libaries
   require(__DIR__.'/../../config.php');
   require_once($CFG->dirroot.'/report/categorymanager/lib.php');
   require_once($CFG->libdir.'/adminlib.php');


// Get Parameter
    $categoryid = required_param('categoryid', PARAM_INT);


// Build Page
   $PAGE->set_category_by_id($categoryid);
   $PAGE->set_context(context_coursecat::instance($categoryid));
   $PAGE->set_url(new moodle_url('/report/categorymanager/index.php', array('categoryid' => $categoryid)));
   $PAGE->set_pagelayout('report');
   $PAGE->set_title(get_string('title', 'report_categorymanager')   . ': ' . $PAGE->category->name);
   $PAGE->set_heading(get_string('title', 'report_categorymanager') . ': ' . $PAGE->category->name);


// Check Login & Capabilities
   require_login();
   require_capability('report/categorymanager:view', context_coursecat::instance($categoryid));


// Output
   $output = $PAGE->get_renderer('report_categorymanager');
   echo $output->header();

   $headings = array(0 => get_string('col_user', 'report_categorymanager'),
                     1 => get_string('col_username', 'report_categorymanager'),
                     2 => get_string('col_email', 'report_categorymanager'));


// DB Query
    $categoryPath = $DB->get_records_sql("SELECT path FROM {course_categories} WHERE id=?", array($PAGE->category->id));

    foreach ($categoryPath as $pathObject) {
        $parents = explode("/", $pathObject->path);
    }


    $content = array();

    for($i=1; $i<count($parents); $i++) {
        $contentDB[$i] = $DB->get_records_sql("SELECT DISTINCT concat(u.firstname, ' ', u.lastname) as name, u.lastname, u.username, u.email FROM {role} r, {role_assignments} ra, {context} cx, {user} u, {course_categories} cc WHERE (cc.id = ?) AND (cx.instanceid = cc.id) AND (cx.contextlevel = 40) AND (ra.contextid = cx.id) AND (r.shortname = 'categorymanager') AND (ra.roleid = r.id) AND (ra.userid = u.id)", array($parents[$i]));


        /*
            To avoid duplicate entries the numeric key (0,1,2,3) has to be switched with the username. The array_merge overwrites existing entries.
        */
        foreach ($contentDB[$i] as $key => $value) {

            $contentDB2[$i][$value->username] = $contentDB[$i][$key];
            unset($contentDB[$i][$key]);

            $contentDB[$i][$value->username] = $contentDB2[$i][$value->username];
            unset($contentDB2);
        }


        $content = array_merge($content, $contentDB[$i]);
    }

/*
    foreach ($content as $key => $row) {
        $realname[$key] = $row['name'];
    }
*/

    //array_multisort($jahrgang, SORT_DESC, $nachname, SORT_ASC, SORT_STRING, $data);
    usort($content, "cmp");

    //Sort array by name
    //array_multisort($realname, SORT_ASC, $content);


      //If no users were found
      if(count($content) == 0) {
         $content = array();
            $content[0] = new stdClass();
            $content[0]->name = get_string('NoUserFound', 'report_categorymanager');
            $content[0]->username = "";
            $content[0]->email = "";
      } else {

         //If user has no right to see the realname
         if (!has_capability('report/categorymanager:viewName', context_coursecat::instance($categoryid))) {

            foreach ($content as $key) {
               $key->name = get_string('NoRightToViewName', 'report_categorymanager');
            }
         }

         //If user has no right to see the username
         if (!has_capability('report/categorymanager:viewAccount', context_coursecat::instance($categoryid))) {

            foreach ($content as $key) {
               $key->username = get_string('NoRightToViewAccount', 'report_categorymanager');
            }
         }

         //If user has no right to see the e-mail address
         if (!has_capability('report/categorymanager:viewEmail', context_coursecat::instance($categoryid))) {

            foreach ($content as $key) {
               $key->email = get_string('NoRightToViewEmail', 'report_categorymanager');
            }
         }
      }


   $indexpage = new \report_categorymanager\output\indexpage($headings, $content);

   echo $output->render_indexpage($indexpage);
   echo $output->footer();
