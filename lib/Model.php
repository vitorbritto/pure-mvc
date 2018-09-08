<?php

namespace lib;

class Model extends Config {
  protected $dbconn;

  public function __construct() {
    try {
      $this->dbconn = new PDO('mysql:host'.self::DB_HOST.';dbname='.self::DB_NAME, self::DB_USER, self::DB_PASS);
      $this->dbconn->exec('set names ' . self::DB_CHARSET);
      $this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  public function findAll($query) {
    try {
      $state = $this->dbconn->prepare($query);
      $state->execute();
    } catch (PDOException $e) {
      die($e->getMessage() . ' ' . $query);
    }
    $list = array();
    while ($row = $state->fetchObject()) {
      $list[] = $row;
    }
    return $list;
  }

  public function insertInto($obj, $table) {
    try {
      $sql = "INSERT INTO {$table} (" . implode(",", array_keys((array) $obj )) . ") VALUES ('" . implode(',', array_keys((array) $obj )) . "')";
      $state = $this->dbconn->prepare($sql);
      $state->execute(array('widgets'));
    } catch (PDOException $e) {
      die($e->getMessage() . '' . $sql);
    }
    return array('success' => true, 'message' => '', 'data' => $this->getLastInsertedId($table));
  }

  public function findAndUpdate() {}
  
  public function findAndRemove() {}
  
  public function getLastInsertedId() {}
  
  public function findById() {}
  
  public function setObject() {}

}