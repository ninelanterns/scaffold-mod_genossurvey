<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/genossurvey/lib.php');

class mod_genossurvey_mod_form extends moodleform_mod {

    function definition() {
        global $CFG, $COURSE;
        $mform    =& $this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('genossurveyname', 'genossurvey'), array('size'=>'64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('text', 'hashstart', get_string('hashstart', 'genossurvey'), array('size'=>'64'));
        $mform->addElement('text', 'hashend', get_string('hashend', 'genossurvey'), array('size'=>'64'));

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }
}

