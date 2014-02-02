<?php

function xmldb_genossurvey_upgrade($oldversion=0) {

    global $CFG, $THEME, $DB;
    $dbman = $DB->get_manager();

    $result = true;

    if ($result && $oldversion < 2012052407) {

        $table = new XMLDBTable('genossurvey');
        $field = new XMLDBField('course');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'hashend');
        $result = $result && add_field($table, $field);

    }

    return $result;
}

?>