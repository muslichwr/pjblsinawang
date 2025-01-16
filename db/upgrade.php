<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade function for the PJBL Sinawang module.
 *
 * @param int $oldversion the old version of the module
 * @return bool true on success, false on failure
 */
// function xmldb_pjblsinawang_upgrade($oldversion) {
//     global $DB;
//     $dbman = $DB->get_manager(); // Loads the DB manager

//     // Upgrade to version 2024080605
//     if ($oldversion < 2024080605) {

//         // Define table pjblsinawang_sintaks_empat to be created
//         $table = new xmldb_table('pjblsinawang_sintaks_empat');

//         // Adding fields to table pjblsinawang_sintaks_empat
//         $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null); // Auto increment handled automatically
//         $table->add_field('groupid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_UNSIGNED, null, null);
//         $table->add_field('student_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_UNSIGNED, null, null);
//         $table->add_field('task_name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
//         $table->add_field('task_status', XMLDB_TYPE_CHAR, '20', null, XMLDB_UNSIGNED, null, 'incomplete'); // Ganti task_status
//         $table->add_field('teacher_comment', XMLDB_TYPE_TEXT, 'big', null, null, null, null);
//         $table->add_field('update_time', XMLDB_TYPE_INTEGER, '20', null, null, null, null); // Ganti update_time

//         // Adding primary key for the 'id' field
//         $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

//         // Conditionally launch create table for pjblsinawang_sintaks_empat
//         $dbman->create_table($table);

//         // PJBL Sinawang savepoint reached
//         upgrade_plugin_savepoint(true, 2024080605, 'mod', 'pjblsinawang');
//     }

//     return true;
// }
