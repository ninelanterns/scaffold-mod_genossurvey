<?php
defined('MOODLE_INTERNAL') || die;
if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext('genossurvey_url', get_string('genossurveyurl', 'genossurvey'),
                           get_string('genossurveyurldesc', 'genossurvey'), 'http://test.genosinternational.net/kaplan.php', PARAM_RAW, 50));
}