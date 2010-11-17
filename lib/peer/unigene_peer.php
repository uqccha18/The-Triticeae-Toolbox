<?php
/**
 * Auto Generated Class
 * Contains methods for extracting rows from the table 'unigene'
 */
class unigene_peer
{
  # IMPORTANT: Do not modify this file
  /* begin-auto-gen */
    protected static $base_sql = 'select unigene_uid, unigene_name, access_id, synonyms, gene_class, updated_on, created_on from unigene';


    // auto-generated method
  // get all records from db
  public static function get_all() {
    $results = array();
    $query = mysql_query(self::$base_sql);
    if (mysql_num_rows($query) <= 0) return $results;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $results[] =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
    }
    return $results;
  }

  
  # auto-generated function
  public static function get_by_unigene_uid($arg0) {
    $sql = self::$base_sql.' where unigene_uid = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_unigene_uid_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where unigene_uid in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  # auto-generated function
  public static function get_by_unigene_name($arg0) {
    $sql = self::$base_sql.' where unigene_name = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_unigene_name_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where unigene_name in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  # auto-generated function
  public static function get_by_access_id($arg0) {
    $sql = self::$base_sql.' where access_id = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_access_id_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where access_id in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  # auto-generated function
  public static function get_by_synonyms($arg0) {
    $sql = self::$base_sql.' where synonyms = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_synonyms_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where synonyms in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  # auto-generated function
  public static function get_by_gene_class($arg0) {
    $sql = self::$base_sql.' where gene_class = \''.$arg0.'\' limit 1';
    $query = mysql_query($sql);
    if (mysql_num_rows($query) <= 0) return null;
    $row = mysql_fetch_assoc($query);
    $modelname = substr(__CLASS__, 0, -5);
    $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
    return $temp;
  }

  # auto-generated function
  public static function get_by_gene_class_array(array $arg0) {
    if (empty($arg0)) return null;
    $sql = self::$base_sql.' where gene_class in ('.implode(',', $arg0).')';
    $query = mysql_query($sql);
    $results = null;
    while ($row = mysql_fetch_assoc($query)) {
      $modelname = substr(__CLASS__, 0, -5);
      $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
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
    $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
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
      $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
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
    $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
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
      $temp =& new $modelname($row['unigene_uid'], $row['unigene_name'], $row['access_id'], $row['synonyms'], $row['gene_class'], $row['updated_on'], $row['created_on']);
      $results[] = $temp;
    }
    return $results;
  }

  /* end-auto-gen */
}