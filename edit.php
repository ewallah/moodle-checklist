<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

global $DB;

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$checklist  = optional_param('checklist', 0, PARAM_INT);  // checklist instance ID

$url = new moodle_url('/mod/checklist/view.php');
if ($id) {
    //UT
    if (! $cm = get_coursemodule_from_id('checklist', $id)) {
        error('Course Module ID was incorrect');
    }

    if (! $course = $DB->get_record('course', array('id' => $cm->course) )) {
        error('Course is misconfigured');
    }

    if (! $checklist = $DB->get_record('checklist', array('id' => $cm->instance) )) {
        error('Course module is incorrect');
    }
    $url->param('id', $id);

} else if ($checklist) {
    //UT
    if (! $checklist = $DB->get_record('checklist', array('id' => $checklist) )) {
        error('Course module is incorrect');
    }
    if (! $course = $DB->get_record('course', array('id' => $checklist->course) )) {
        error('Course is misconfigured');
    }
    if (! $cm = get_coursemodule_from_instance('checklist', $checklist->id, $course->id)) {
        error('Course Module ID was incorrect');
    }
    $url->param('checklist', $checklistid);

} else {
    error('You must specify a course_module ID or an instance ID');
}

$PAGE->set_url($url);
require_login($course, true, $cm);

if ($chk = new checklist_class($cm->id, 0, $checklist, $cm, $course)) {
    //UT
    $chk->edit();
}

?>