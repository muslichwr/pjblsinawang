<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade function for the PJBL Sinawang module.
 *
 * @param int $oldversion the old version of the module
 * @return bool true on success, false on failure
 */
function xmldb_pjblsinawang_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager(); // Loads the DB manager

    if ($oldversion < 2024080603) {
        // Define table pjblsinawang_sintaks_dua (Sintaks 2)
        $table = new xmldb_table('pjblsinawang_sintaks_dua');

        // Adding fields to table pjblsinawang_sintaks_dua
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, 'auto', '0');
        $table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_UNSIGNED, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_UNSIGNED, null, null);
        $table->add_field('groupid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_UNSIGNED, null, null);
        $table->add_field('project_title', XMLDB_TYPE_TEXT, 'big', null, null, null, null);
        $table->add_field('project_schedule', XMLDB_TYPE_TEXT, 'big', null, null, null, null);
        $table->add_field('tasks', XMLDB_TYPE_TEXT, 'big', null, null, null, null);
        $table->add_field('status', XMLDB_TYPE_CHAR, '20', null, XMLDB_UNSIGNED, null, 'incomplete');
        $table->add_field('feedback', XMLDB_TYPE_TEXT, 'big', null, null, null, null);

        // Adding keys
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for pjblsinawang_sintaks_dua
        $dbman->create_table($table);

        // pjblsinawang savepoint reached
        upgrade_plugin_savepoint(true, 2024080603, 'mod', 'pjblsinawang_sintaks_dua');
    }

    return true;
}
