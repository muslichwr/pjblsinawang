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

    if ($oldversion < 2024080602) {
        // Define table pjblsinawang_sintaks_1
        $table = new xmldb_table('pjblsinawang_sintaks_satu');

        // Adding fields to table pjblsinawang_sintaks_1
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, '0', '0');
        $table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_UNSIGNED, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_UNSIGNED, null, null);
        $table->add_field('groupid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_UNSIGNED, null, null);
        $table->add_field('orientasi_masalah', XMLDB_TYPE_TEXT, 'big', null, null, null, null);
        $table->add_field('rumusan_masalah', XMLDB_TYPE_TEXT, 'big', null, null, null, null);
        $table->add_field('indikator', XMLDB_TYPE_TEXT, 'big', null, null, null, null);
        $table->add_field('analisis', XMLDB_TYPE_TEXT, 'big', null, null, null, null);
        $table->add_field('feedback', XMLDB_TYPE_TEXT, 'big', null, null, null, null);
        $table->add_field('status', XMLDB_TYPE_CHAR, '20', null, XMLDB_UNSIGNED, null, 'incomplete');

        // Adding keys
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for pjblsinawang_sintaks_1
        $dbman->create_table($table);


        // pjblsinawang savepoint reached
        upgrade_plugin_savepoint(true, 2024080602, 'mod', 'pjblsinawang_sintaks_satu');
    }

    return true;
}
