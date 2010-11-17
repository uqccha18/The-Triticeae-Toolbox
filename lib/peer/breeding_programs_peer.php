<?php
/**
 * Auto Generated Class
 * Contains methods for extracting rows from the table 'breeding_programs'
 */
class breeding_programs_peer
{
  # IMPORTANT: Do not modify this file
  /* begin-auto-gen */
    protected static $base_sql = 'select breeding_programs_uid, institutions_uid, breeding_programs_name, description, updated_on, created_on from breeding_programs';


    // auto-generated method
  // get all records from db
  public static function get_all() {
    $results = array();
    $query = mysql_query(self::$base_sql);
    if (mysql_num_rows($query) <= 0) return $results;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $results[] =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
    }
    return $results;
  }

  
  # auto-generated function
  public static function get_by_breeding_programs_uid($arg0) {
    $sql = self::$base_sql.' where breeding_programs_uid = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_breeding_programs_uid_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where breeding_programs_uid in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  # auto-generated function
  public static function get_by_institutions_uid($arg0) {
    $sql = self::$base_sql.' where institutions_uid = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_institutions_uid_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where institutions_uid in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  # auto-generated function
  public static function get_by_breeding_programs_name($arg0) {
    $sql = self::$base_sql.' where breeding_programs_name = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_breeding_programs_name_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where breeding_programs_name in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  # auto-generated function
  public static function get_by_description($arg0) {
    $sql = self::$base_sql.' where description = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_description_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where description in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
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
    $temp =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
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
      $temp =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
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
    $temp =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
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
      $temp =& new $modelname($row['breeding_programs_uid'], $row['institutions_uid'], $row['breeding_programs_name'], $row['description'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  /* end-auto-gen */
}