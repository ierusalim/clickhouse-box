<?php
/*  ClickHouse Read-Only API requests:
 *
 * chver
 * getVersion
 * getUptime
 * getSystemSettings
 * getDatabasesList
 * getTablesList  (table , [db])
 * getTableInfo   (table , [db])
 * getTableFields (table , [db])
 */

    require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

    global $ch, $clickhouse_url;

    $ch = new \ierusalim\ClickHouse\ClickHouseFunctions($clickhouse_url);

/* php-ClickHouse Functions:
 * - >createTableQuick($table, $fields_arr) - create table with specified fields
 * - >sendFileInsert($file, $table) - send TabSeparated-file into table
 * - >dropTable($table [, $sess]) - drop table
 * - >clearTable($table [, $sess]) - clear table (DROP and re-create)
 * - >renameTable($from_name_or_arr [, $to_name] [, $sess]) - rename tables
 + ->getTableFields($table [, $sess]) - returns [field_name=>field_type] array
 + ->getTableInfo($table [, $extended]) - returns array with info about table
 *
 + ->getTablesList([$db] [,$pattern]) - returns tables list by SHOW TABLES request
 *
 * - >createDatabase($db) - create new database with specified name
 * - >dropDatabase($db) - drop specified database and remove all tables inside
 + ->getDatabasesList() - returns array contained names of existing Databases
 * - >setCurrentDatabase($db [, $sess]) - set current database by 'USE db' request
 * - >getCurrentDatabase([$sess]) - return results of 'SELECT currentDatabase()'
 *
 + ->getVersion() - return version of ClickHouse server (function moved to ClickHouseAPI)
 + ->getUptime() - return server uptime in seconds
 + ->getSystemSettings() - get information from system.settings as array [name=>value]
*/

function chver() {
    global $ch;
    $result = "ClickHouse version: " . $ch->getVersion();

    if (!$ch->isSupported('query')) {
        return ['err' => 'Server not ready'];
    }
    $result .= "\nServer uptime: " . $ch->getUptime() . " sec.";

    return ['result'=>$result];
}
function getVersion() {
    return ['result' => $GLOBALS['ch']->getVersion()];
}
function getUptime() {
    return ['result' => $GLOBALS['ch']->getUptime()];
}
function getSystemSettings() {
    return ['result' => print_r($GLOBALS['ch']->getSystemSettings(), true)];
}
function getDatabasesList() {
    $result = $GLOBALS['ch']->getDatabasesList();
    $result = count($result) . "\n" . implode("\n",$result);
    return ['result'=>$result];
}
function getTablesList() {
    $db = isset($_REQUEST['db']) ? $_REQUEST['db'] : 'default';
    $result = $GLOBALS['ch']->getTablesList($db);
    $result = count($result) . "\n" . implode("\n",$result);
    return ['result'=>$result];
}
function getTableInfo() {
    $table = isset($_REQUEST['table']) ? $_REQUEST['table'] : null;
    if (empty($table)) {
        return ['error' => 'table parameter required'];
    }
    if (!\strpos($table, '.')) {
        $db = isset($_REQUEST['db']) ? $_REQUEST['db'] : 'default';
        $table = $db . '.' . $table;
    }
    $result = $GLOBALS['ch']->getTableInfo($table,7);
    if (is_array($result)) $result = print_r($result, true);
    return ['result'=>$result];
}
function getTableFields() {
    $table = isset($_REQUEST['table']) ? $_REQUEST['table'] : null;
    if (empty($table)) {
        return ['error' => 'table parameter required'];
    }
    if (!\strpos($table, '.')) {
        $db = isset($_REQUEST['db']) ? $_REQUEST['db'] : 'default';
        $table = $db . '.' . $table;
    }
    $result = $GLOBALS['ch']->getTableFields($table);
    if (is_array($result)) $result = print_r($result, true);
    return ['result'=>$result];
}