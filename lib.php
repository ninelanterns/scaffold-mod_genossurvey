<?php

/**
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_GROUPMEMBERSONLY
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function genossurvey_supports($feature) {
    switch($feature) {
        case FEATURE_GROUPS:                  return true;
        case FEATURE_GROUPINGS:               return true;
        case FEATURE_GROUPMEMBERSONLY:        return true;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        default: return null;
    }
}

// will be called during the installation of the module
    function genossurvey_install(){
    }

// code to add a new instance of widget
function genossurvey_add_instance($genossurvey){
    global $DB;
    $genossurvey->timemodified = time();

    if ($returnid = $DB->insert_record('genossurvey', $genossurvey)) {
        $genossurvey->id = $returnid;

        $event = NULL;
        $event->name        = $genossurvey->name;
        $event->description = '';
        $event->courseid    = $genossurvey->course;
        $event->groupid     = 0;
        $event->userid      = 0;
        $event->eventtype = 'course';
        $event->modulename  = 'genossurvey';
        $event->instance    = $returnid;
        add_event($event);
    }

    return $returnid;
}

// code to update an existing instance
function genossurvey_update_instance($genossurvey){
    global $DB;
    $genossurvey->timemodified = time();
    $genossurvey->id = $genossurvey->instance;

    if ($returnid = $DB->update_record('genossurvey', $genossurvey)) {

        if ($event->id = $DB->get_field('event', 'id', array('modulename'=> 'genossurvey', 'instance'=> $genossurvey->id))) {
            $event->name        = $genossurvey->name;
            update_event($event);
        } else {
            $event = NULL;
            $event->name        = $genossurvey->name;
            $event->description = '';
            $event->courseid    = $genossurvey->course;
            $event->groupid     = 0;
            $event->userid      = 0;
            $event->modulename  = 'genossurvey';
            $event->instance    = $genossurvey->id;

            add_event($event);
        }
    } else {
        $DB->delete_records('event', array('modulename'=>'genossurvey', 'instance'=> $genossurvey->id));
    }

    return $returnid;

}

// code to delete an instance
function genossurvey_delete_instance(){
    global $CFG, $DB, $USER;

    if (!$genossurvey = $DB->get_record('genossurvey', array('id'=> $id))) {
        return false;
    }

    // Prepare file record object
    if (!$cm = get_coursemodule_from_instance('genossurvey', $id)) {
        return false;
    }

    $result = true;

    if (!$DB->delete_records('genossurvey', array('id'=> $id))) {
        $result = false;
    }

    $context = get_context_instance(CONTEXT_MODULE, $cm->id);

    $fs = get_file_storage();

    $fs->delete_area_files($context->id);

    return $result;
}

function genossurvey_delete_course($course, $feedback=true) {
    return true;
}

# widget_user_outline() - given an instance, return a summary of a user's contribution
    function genossurvey_user_online(){

    }

# widget_user_complete() - given an instance, print details of a user's contribution
    function genossurvey_user_complete(){

    }

# widget_get_view_actions() / widget_get_post_actions() - Used by the participation report (course/report/participation/index.php) to classify actions in the logs table.
# Other functions available but not required are:
# * widget_delete_course() - code to clean up anything that would be leftover after all instances are deleted
# * widget_process_options() - code to pre-process the form data from module settings
# * widget_reset_course_form() and widget_delete_userdata() - used to implement Reset course feature.

function curl_get($url, array $get = NULL, array $options = array())
{
    $getParams = array();
    foreach($get as $param => $value) {
       $getParams[] = "$param=".urlencode($value);
    }

    $defaults = array(
        CURLOPT_URL => $url.(strpos($url, '?') === FALSE ? '?' : '').implode('&', $getParams),
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 4
    );

    $ch = curl_init();

    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch))
    {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);
    return $result;
} 

/**
 * Send a POST requst using cURL
 * @param string $url to request
 * @param array $post values to send
 * @param array $options for cURL
 * @return string
 */
function curl_post($url, array $post = NULL, array $options = array())
{
    $defaults = array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $url,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 4,
        CURLOPT_POSTFIELDS => $post
    );

    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    if( ! $result = curl_exec($ch))
    {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);
    return $result;
} 
?>
