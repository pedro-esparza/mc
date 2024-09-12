<?php

namespace src\app\controllers;

use src\app\classes\helpers;
use src\app\models\repositoryModel;

class repositoryController
{
  public static function getAllData(object $model): array
  {
    $result = repositoryModel::getAllData($model::getTableName());
    $return = is_array($result)
      ? helpers::formatResponse(200, 'success', $result)
      : helpers::formatResponse(403, 'Not Found', []);

    return $return;
  }

  public static function getAllPaginated(object $model, int $pageNumber, int $pageSize, array $request, string $field, string $value, string $ascending): array
  {
    $result = repositoryModel::getAllPaginated($model::getTableName(), $pageNumber, $pageSize, $request, $field, $value, $ascending);
    $return = is_array($result)
      ? helpers::formatResponse(200, 'success', $result)
      : helpers::formatResponse(403, 'Not Found', []);

    return $return;
  }

  public static function getAllPaginatedByLike(object $model, int $pageNumber, int $pageSize, array $request, string $field, string $value, string $ascending): array
  {
    $result = repositoryModel::getAllPaginatedByLike($model::getTableName(), $pageNumber, $pageSize, $request, $field, $value, $ascending);
    $return = is_array($result)
      ? helpers::formatResponse(200, 'success', $result)
      : helpers::formatResponse(403, 'Not Found', []);

    return $return;
  }

  public static function getCountDataFiltered(string $table, array $data): array
  {
    $result = repositoryModel::getCountDataFiltered($table, $data);
    $return = is_array($result)
      ? helpers::formatResponse(200, 'success', $result)
      : helpers::formatResponse(403, 'Not Found', []);

    return $return;
  }

  public static function getAllDataFiltered(string $table, array $data, int $limitqty, string $ascending): array
  {
    $result = repositoryModel::getAllDataFiltered($table, $data, $limitqty, $ascending);
    $return = is_array($result)
      ? helpers::formatResponse(200, 'success', $result)
      : helpers::formatResponse(403, 'Not Found', []);

    return $return;
  }

  public static function getAllDataByColumn(string $table, string $field, $value): array
  {
    $result = repositoryModel::getAllDataByColumn($table, $field, $value);
    $return = is_array($result)
      ? helpers::formatResponse(200, 'Success', $result)
      : helpers::formatResponse(403, 'Not Found', []);

    return $return;
  }

  public static function getAllDataByLike(string $table, string $field, $value): array
  {
    $result = repositoryModel::getAllDataByLike($table, $field, $value);
    $return = is_array($result)
      ? helpers::formatResponse(200, 'Success', $result)
      : helpers::formatResponse(403, 'Not Found', []);

    return $return;
  }

  public static function getOneById(object $model): array
  {
    if (repositoryModel::checkExistById($model->getTableName(), $model->get('id'))) {
      $result = repositoryModel::getOneById($model->getTableName(), $model->get('id'));
      $return = is_array($result)
        ? helpers::formatResponse(200, 'Success', $result)
        : helpers::formatResponse(403, 'Not Found', []);
    } else
      $return = helpers::formatResponse(404, 'Resource Not Exist', []);

    return $return;
  }

  public static function getOneByColumn(string $table, string $column, $value): array
  {
    $result = repositoryModel::getOneByColumn($table, $column, $value);
    $return = is_array($result)
      ? helpers::formatResponse(200, 'Success', $result)
      : helpers::formatResponse(403, 'Not Found', []);

    return $return;
  }

  public static function checkExistById(object $model): array
  {
    $return = repositoryModel::checkExistById($model->getTableName(), $model->get('id'))
      ? helpers::formatResponse(200, 'Success', [])
      : helpers::formatResponse(404, 'Resource Not Exist', []);
    return $return;
  }

  public static function checkExistByColumn(string $table, string $column, $value): array
  {
    $return = repositoryModel::checkExistByColumn($table, $column, $value)
      ? helpers::formatResponse(200, 'Success', [])
      : helpers::formatResponse(404, 'Resource Not Exist', []);
    return $return;
  }

  public static function storeData(object $model): array
  {
    $id = repositoryModel::storeData($model->getTableName(), $model->getFilteredObject());

    return $id
      ? helpers::formatResponse(200, 'Resource Created', repositoryModel::getOneById($model->getTableName(), $id))
      : helpers::formatResponse(401, 'Resource Not Created', []);
  }

  public static function updateDataById(object $model): array
  {
    $return = repositoryModel::updateData($model->getTableName(), $model->getObjectVars(), $model->get('id'))
      ? helpers::formatResponse(200, 'Resource Updated', [])
      : helpers::formatResponse(401, 'Resource Not Updated', []);

    return $return;
  }

  public static function hardDeleteById(object $model): array
  {
    if (repositoryModel::checkExistById($model->getTableName(), intval($model->get('id')))) {
      $return = repositoryModel::hardDeleteById($model->getTableName(), intval($model->get('id')))
        ? helpers::formatResponse(200, 'Resource Deleted', [])
        : helpers::formatResponse(404, 'Resource Not Deleted', []);
    } else
      $return = helpers::formatResponse(404, 'Resource Not Exist', []);

    return $return;
  }

  public static function hardDeleteAll(string $table, string $column, $value): array
  {
    $return = repositoryModel::hardDeleteAll($table, $column, $value)
      ? helpers::formatResponse(200, 'Resource Deleted', [])
      : helpers::formatResponse(404, 'Resource Not Deleted', []);

    return $return;
  }

  public static function hardDeleteByColumnMinor(string $table, string $column, $value): array
  {
    $return = repositoryModel::hardDeleteByColumnMinor($table, $column, $value)
      ? helpers::formatResponse(200, 'Resource Deleted', [])
      : helpers::formatResponse(404, 'Resource Not Deleted', []);

    return $return;
  }

}