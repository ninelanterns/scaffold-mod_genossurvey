<?php

require_once('../../config.php');
require_once('lib.php');

$id = required_param('id', PARAM_INT);    // Course Module ID
$action = optional_param('action', '', PARAM_ALPHA);

if (! $cm = get_coursemodule_from_id('genossurvey', $id)) {
    error('Course Module ID was incorrect');
}
if (! $course = $DB->get_record('course', array('id'=> $cm->course))) {
    error('course is misconfigured');
}
if (! $genossurvey = $DB->get_record('genossurvey', array('id'=> $cm->instance))) {
    error('course module is incorrect');
}

require_login($course->id, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
require_capability('mod/genossurvey:view', $context);

// log update
add_to_log($course->id, 'genossurvey', 'view', "view.php?id=$cm->id", $genossurvey->id, $cm->id);
$completion=new completion_info($course);
$completion->set_module_viewed($cm);

// Initialize $PAGE, compute blocks
$PAGE->set_url('/mod/genossurvey/view.php', array('id' => $cm->id));
$PAGE->set_context($context);
$PAGE->set_cm($cm);
$PAGE->set_pagelayout('course');

$genossurvey->time = time();
$genossurvey->sha1 = sha1($genossurvey->hashstart.$genossurvey->time.$genossurvey->hashend);

$url = $CFG->genossurvey_url;
$get = array(
    'time' => $genossurvey->time,
    'hash' => $genossurvey->sha1,
    'first' => $USER->firstname,
    'last' => $USER->lastname,
    'email' => $USER->email
);

$result = json_decode(curl_post($url, $get));

if ($result && $result->status == 'OK') {
    $content = "";
    $content .= "<br /><br /><br />";
    $content .= get_string('instructions', 'genossurvey');
    $content .= "<br /><br /><br />";

    $id = html_writer::random_id('genossurvey');
    $OUTPUT->add_action_handler(new popup_action('click', $result->link), $id);
    
    $content .= html_writer::link($result->link, get_string('linktext', 'genossurvey'), array('id' => $id));

} elseif ($result && $result->status == 'COMPLETE') {
    $content = "<br /><br /><br />";
    $content .= get_string('complete', 'genossurvey');
    $content .= "<br /><br /><br />";
} else {
    error_log('[MOD_GENOSSURVEY] WebService request error $result object = '.var_export($result, true).', $url = '.var_export($url, true).', $get = '.var_export($get, true));
    $content = "<br /><br /><br />";
    $content .= get_string('error', 'genossurvey');
    $content .= "<br /><br /><br />";
}

echo $OUTPUT->header();
echo $content;
echo $OUTPUT->footer();

?>
