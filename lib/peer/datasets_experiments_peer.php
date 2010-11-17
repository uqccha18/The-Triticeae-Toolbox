<?php
/**
 * Auto Generated Class
 * Contains methods for extracting rows from the table 'datasets_experiments'
 */
class datasets_experiments_peer
{
  # IMPORTANT: Do not modify this file
  /* begin-auto-gen */
    protected static $base_sql = 'select datasets_experiments_uid, experiment_uid, datasets_uid, updated_on, created_on from datasets_experiments';


    // auto-generated method
  // get all records from db
  public static function get_all() {
    $results = array();
    $query = mysql_query(self::$base_sql);
    if (mysql_num_rows($query) <= 0) return $results;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $results[] =& new $modelname($row['datasets_experiments_uid'], $row['experiment_uid'], $row['datasets_uid'], $row['updated_on'], $row['created_on']);
    }
    return $results;
  }

  
  # auto-generated function
  public static function get_by_datasets_experiments_uid($arg0) {
    $sql = self::$base_sql.' where datasets_experiments_uid = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['datasets_experiments_uid'], $row['experiment_uid'], $row['datasets_uid'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_datasets_experiments_uid_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where datasets_experiments_uid in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['datasets_experiments_uid'], $row['experiment_uid'], $row['datasets_uid'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  # auto-generated function
  public static function get_by_experiment_uid($arg0) {
    $sql = self::$base_sql.' where experiment_uid = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['datasets_experiments_uid'], $row['experiment_uid'], $row['datasets_uid'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_experiment_uid_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where experiment_uid in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['datasets_experiments_uid'], $row['experiment_uid'], $row['datasets_uid'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  # auto-generated function
  public static function get_by_datasets_uid($arg0) {
    $sql = self::$base_sql.' where datasets_uid = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['datasets_experiments_uid'], $row['experiment_uid'], $row['datasets_uid'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_datasets_uid_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where datasets_uid in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['datasets_experiments_uid'], $row['experiment_uid'], $row['datasets_uid'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  # auto-generated function
  public static function get_by_updated_on($arg0) {
    $sql = self::$base_sql.' where updated_on = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['datasets_experiments_uid'], $row['experiment_uid'], $row['datasets_uid'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_updated_on_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where updated_on in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['datasets_experiments_uid'], $row['experiment_uid'], $row['datasets_uid'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  # auto-generated function
  public static function get_by_created_on($arg0) {
    $sql = self::$base_sql.' where created_on = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['datasets_experiments_uid'], $row['experiment_uid'], $row['datasets_uid'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_created_on_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where created_on in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['datasets_experiments_uid'], $row['experiment_uid'], $row['datasets_uid'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  /* end-auto-gen */
}