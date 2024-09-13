<?php

namespace src\auth\models;

use src\core\db;

class repositoryModel
{
  private static $conn;

  public static function gteTableNames()
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $sql = 'SELECT table_name FROM information_schema.tables WHERE table_schema = "' . db::get('DB_NAME') . '"';
      $stmt = self::$conn->prepare($sql);

      if ($stmt->execute())
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    return false;
  }

  public static function getAllData(string $table)
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $sql = 'SELECT * FROM ' . db::get('DB_NAME') . '.' . $table;
      $stmt = self::$conn->prepare($sql);

      if ($stmt->execute())
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    return false;
  }

  public static function getAllDataFiltered(string $table, $data)
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $params = '';
      foreach ($data as $key => $value)
        $params .= $key . ' =:' . $key . ' AND ';
      $params = substr($params, 0, -5);

      $stmt = self::$conn->prepare('SELECT * FROM ' . db::get('DB_NAME') . '.' . $table . ' WHERE ' . $params);
      foreach ($data as $key => $value)
        $stmt->bindParam(':' . $key, $data[$key]);

      if ($stmt->execute())
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    return false;
  }

  public static function getAllDataByColumn(string $table, string $column, $value)
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $sql = 'SELECT * FROM ' . db::get('DB_NAME') . '.' . $table . ' WHERE ' . $column . '=:value';
      $stmt = self::$conn->prepare($sql);

      $stmt->bindParam(':value', $value);

      if ($stmt->execute())
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    return false;
  }

  public static function getOneById(string $table, int $id)
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $sql = 'SELECT * FROM ' . db::get('DB_NAME') . '.' . $table . ' WHERE id=:id';

      $stmt = self::$conn->prepare($sql);
      $stmt->bindParam(':id', $id);

      if ($stmt->execute())
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    return false;
  }

  public static function getOneByColumn(string $table, string $column, $value)
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $tableExists = self::tableExists($table);

      if ($tableExists) {
        $sql = 'SELECT * FROM ' . db::get('DB_NAME') . '.' . $table . ' WHERE ' . $column . '=:value LIMIT 1';
        $stmt = self::$conn->prepare($sql);

        $stmt->bindParam(':value', $value);

        if ($stmt->execute()) {
          return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
      }
    }

    return false;
  }

  public static function tableExists(string $table): bool
  {
    $sql = "SHOW TABLES LIKE '$table'";
    $stmt = self::$conn->prepare($sql);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }

  public static function checkExistById(string $table, int $id): bool
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $sql = 'SELECT * FROM ' . db::get('DB_NAME') . '.' . $table . ' WHERE id=:id';
      $stmt = self::$conn->prepare($sql);

      $stmt->bindParam(':id', $id);
      $stmt->execute();

      if ($stmt->fetch(\PDO::FETCH_ASSOC))
        return true;
    }

    return false;
  }

  public static function checkExistByColumn(string $table, string $column, $value)
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $sql = 'SELECT * FROM ' . db::get('DB_NAME') . '.' . $table . ' WHERE ' . $column . '=:value';
      $stmt = self::$conn->prepare($sql);

      $stmt->bindParam(':value', $value);

      $stmt->execute();

      if ($stmt->fetch(\PDO::FETCH_ASSOC))
        return true;
    }

    return false;
  }

  public static function storeData(string $table, array $data): int
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $columns = implode(',', array_keys($data));
      $params = implode(', :', array_keys($data));

      $stmt = self::$conn->prepare('INSERT INTO ' . db::get('DB_NAME') . '.' . $table . '(' . $columns . ') VALUES (:' . $params . ')');
      foreach ($data as $key => $value)
        $stmt->bindParam(':' . $key, $data[$key]);
      if ($stmt->execute())
        return self::$conn->lastInsertId();
    }

    return false;
  }

  public static function updateData(string $table, array $data, int $idKey): bool
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $set = '';
      foreach ($data as $key => $value)
        $set .= $key . ' =:' . $key . ',';
      $set = substr($set, 0, -1);

      $stmt = self::$conn->prepare('UPDATE ' . db::get('DB_NAME') . '.' . $table . ' SET ' . $set . ' WHERE id=:idKey');
      foreach ($data as $key => $value)
        $stmt->bindParam(':' . $key, $data[$key]);
      $stmt->bindParam(':idKey', $idKey);
      if ($stmt->execute())
        return true;
    }

    return false;
  }

  public static function hardDeleteById(string $table, int $id): bool
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $sql = 'DELETE FROM ' . db::get('DB_NAME') . '.' . $table . ' WHERE id = ?';
      $stmt = self::$conn->prepare($sql);
      $stmt->bindParam(1, $id, \PDO::PARAM_INT);
      if ($stmt->execute())
        return true;
    }

    return false;
  }

  public static function hardDeleteAll(string $table, string $column, $value): bool
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $sql = 'DELETE FROM ' . db::get('DB_NAME') . '.' . $table . ' WHERE ' . $column . '=:value';
      $stmt = self::$conn->prepare($sql);
      $stmt->bindParam(':value', $value);

      if ($stmt->execute())
        return true;
    }

    return false;
  }

  public static function hardDeleteByColumnMinor(string $table, string $column, $value): bool
  {
    self::$conn = db::connect();

    if (is_object(self::$conn)) {

      $sql = 'DELETE FROM ' . db::get('DB_NAME') . '.' . $table . ' WHERE ' . $column . '<:value';
      $stmt = self::$conn->prepare($sql);
      $stmt->bindParam(':value', $value);

      if ($stmt->execute())
        return true;
    }

    return false;
  }
}