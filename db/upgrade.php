<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade the Codepix plugin
 *
 * @param int $oldversion The previous version of the plugin
 * @return bool True if upgrade was successful
 */
// function xmldb_codepix_upgrade($oldversion) {
//     global $DB;

//     $dbman = $DB->get_manager();

//     // 2024080616 upgrade
//     if ($oldversion < 2024080616) {
//         // Define the table 'codepix_tigaplus' to be created
//         $table = new xmldb_table('codepix_tigaplus');

//         // Add fields
//         $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, '0', '0');
//         $table->add_field('student_id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, '0', 'id');
//         $table->add_field('group_id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, '0', 'student_id');
//         $table->add_field('course_id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, '0', 'group_id');
//         $table->add_field('rancangan_program', XMLDB_TYPE_TEXT, null, null, null, null, null, '0', 'course_id');
//         $table->add_field('feedback', XMLDB_TYPE_TEXT, null, null, null, null, null, '0', 'rancangan_program');
//         $table->add_field('status', XMLDB_TYPE_TEXT, null, null, null, null, null, '0', 'feedback');

//         // Add primary key
//         $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

//         // Add the table to the database
//         $dbman->create_table($table);

//         // Codepix savepoint reached
//         upgrade_plugin_savepoint(true, 2024080616, 'mod', 'codepix_tigaplus');
//     }

//     return true;
// }
