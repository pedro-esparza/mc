<?php

namespace src\app\controllers;

use src\app\classes\helpers;
use src\app\controllers\repositoryController;
use src\core\emailService;

class baseController
{
  public static function indexAction(): array
  {
    return helpers::formatResponse(200, 'Let\'s start build something Incredible!', []);
  }
  public static function noActionFound(): array
  {
    return helpers::formatResponse(404, 'No Action Found!', []);
  }

  protected static function getAllBase(object $model): array
  {
    return repositoryController::getAllData($model);
  }

  protected static function getAllPaginatedBase(object $model, array $request): array
  {
    $pageNumber = array_key_exists('pageNumber', $request) ? intval($request['pageNumber']) : 1;
    $pageSize = array_key_exists('pageSize', $request) ? intval($request['pageSize']) : 10;
    $fieldColumn = array_key_exists('fieldColumn', $request) ? strval($request['fieldColumn']) : '';
    $fieldValue = array_key_exists('fieldValue', $request) ? strval($request['fieldValue']) : '';
    $ascending = array_key_exists('ascending', $request) ? strval($request['ascending']) : 'asc';

    if (count($request) > 0) {
      foreach ($request as $key => $value)
        if (!property_exists($model, $key))
          unset($request[$key]);
    }

    return repositoryController::getAllPaginated($model, $pageNumber, $pageSize, $request, $fieldColumn, $fieldValue, $ascending);
  }

  protected static function getAllPaginatedByLikeBase(object $model, array $request): array
  {
    $pageNumber = array_key_exists('pageNumber', $request) ? intval($request['pageNumber']) : 1;
    $pageSize = array_key_exists('pageSize', $request) ? intval($request['pageSize']) : 10;
    $fieldColumn = array_key_exists('fieldColumn', $request) ? strval($request['fieldColumn']) : '';
    $fieldValue = array_key_exists('fieldValue', $request) ? strval($request['fieldValue']) : '';
    $ascending = array_key_exists('ascending', $request) ? strval($request['ascending']) : 'asc';

    if (count($request) > 0) {
      foreach ($request as $key => $value)
        if (!property_exists($model, $key))
          unset($request[$key]);
    }

    return repositoryController::getAllPaginatedByLike($model, $pageNumber, $pageSize, $request, $fieldColumn, $fieldValue, $ascending);
  }

  protected static function getCountFilteredBase(object $model, array $request): array
  {
    if (count($request) > 0)
      foreach ($request as $key => $value)
        if (!property_exists($model, $key))
          unset($request[$key]);

    $return = count($request) > 0
      ? repositoryController::getCountDataFiltered($model->getTableName(), $request)
      : helpers::formatResponse(403, 'Property Not Found', []);

    return $return;
  }

  protected static function getAllFilteredBase(object $model, array $request): array
  {
    $limitqty = array_key_exists('limitqty', $request) ? intval($request['limitqty']) : 100000;
    $ascending = array_key_exists('ascending', $request) ? strval($request['ascending']) : 'asc';

    if (count($request) > 0) {
      foreach ($request as $key => $value)
        if (!property_exists($model, $key))
          unset($request[$key]);
    }

    $return = count($request) > 0
      ? repositoryController::getAllDataFiltered($model->getTableName(), $request, $limitqty, $ascending)
      : helpers::formatResponse(403, 'Property Not Found', []);

    return $return;
  }

  protected static function getAllDataByColumnBase(object $model, array $request): array
  {
    $field = helpers::getFirstKeyName($request);
    $value = $request[$field];

    $result = property_exists($model, $field)
      ? repositoryController::getAllDataByColumn($model->getTableName(), $field, $value)
      : helpers::formatResponse(403, 'Property Not Found', []);

    return $result;
  }

  protected static function getAllDataByLikeBase(object $model, array $request): array
  {
    $field = helpers::getFirstKeyName($request);
    $value = $request[$field];

    $result = property_exists($model, $field)
      ? repositoryController::getAllDataByLike($model->getTableName(), $field, $value)
      : helpers::formatResponse(403, 'Property Not Found', []);

    return $result;
  }

  protected static function getOneByIdBase(object $model, array $request): array
  {
    $return = array();
    if (isset($request['id']) && !empty($request['id'])) {

      $model->set('id', intval($request['id']));
      $return = repositoryController::getOneById($model);
    } else
      $return = helpers::formatResponse(403, 'Id Not Found', []);

    return $return;
  }

  protected static function getOneByColumnBase(object $model, array $request): array
  {
    $column = helpers::getFirstKeyName($request);
    $value = $request[$column];

    $result = property_exists($model, $column)
      ? repositoryController::getOneByColumn($model->getTableName(), $column, $value)
      : helpers::formatResponse(403, 'Property Not Found', []);

    return $result;
  }

  protected static function checkExistByIdBase(object $model, array $request): array
  {
    $return = array();
    if (isset($request['id']) && !empty($request['id'])) {
      $model->set('id', intval($request['id']));
      $return = repositoryController::checkExistById($model);
    } else
      $return = helpers::formatResponse(403, 'Id Not Found', []);

    return $return;
  }

  protected static function checkExistByColumnBase(object $model, array $request): array
  {
    $column = helpers::getFirstKeyName($request);
    $value = $request[$column];

    $result = property_exists($model, $column)
      ? repositoryController::checkExistByColumn($model->getTableName(), $column, $value)
      : helpers::formatResponse(403, 'Property Not Found', []);

    return $result;
  }

  protected static function storeBase(object $model, array $request): array
  {
    $return = array();

    if (isset($request['id']))
      unset($request['id']);

    $model = helpers::populateModel($model, $request);
    $model->set('created_by', $request['_USER']['id']);
    $model->set('modified_by', $request['_USER']['id']);
    $posted = repositoryController::storeData($model);

    if ($posted['status'] === 200) {
      $model->set('id', $posted['data']['id']);
      $return = repositoryController::getOneById($model);

    } else
      $return = $posted;

    return $return;
  }

  protected static function updateBase(object $model, array $request): array
  {
    $return = array();
    $result = array();

    $result = self::getOneByIdBase($model, $request);

    if (isset($result['status']) && $result['status'] === 200) {

      $model = helpers::populateModel($model, $result['data']);
      $model = helpers::populateModel($model, $request);
      $model->set('modified_by', $request['_USER']['id']);

      $return = repositoryController::updateDataById($model);
    } else
      $return = helpers::formatResponse(403, 'Resource Not Exist', []);

    return $return;
  }

  protected static function modifyBase(object $model, array $request): array
  {
    $return = array();

    if (isset($request['id']) && !empty($request['id'])) {

      $result = array();
      $result = self::getOneByIdBase($model, $request);

      if (isset($result['status']) && $result['status'] === 200) {


        unset($request['id']);
        $model->set('modified_by', 1);

        if (count($request) > 0)
          foreach ($request as $key => $value)
            if (!property_exists($model, $key))
              unset($request[$key]);

        if (count($request) > 0) {
          $model = helpers::populateModel($model, $result['data']);
          $keyName = helpers::getFirstKeyName($request);
          $model = helpers::populateModel($model, [$keyName => $request[$keyName]]);
        }


        $return = repositoryController::updateDataById($model);
      } else
        $return = helpers::formatResponse(403, 'Resource Not Exist', []);
    } else
      $return = helpers::formatResponse(403, 'Key Not Found', []);


    return $return;
  }

  protected static function hardDeleteByIdBase(object $model, array $request): array
  {
    $return = array();
    $result = array();

    $result = self::getOneByIdBase($model, $request);

    if (isset($result['status']) && $result['status'] === 200) {
      $model->set('id', intval($result['data']['id']));
      $return = repositoryController::hardDeleteById($model);
    } else
      $return = helpers::formatResponse(403, 'Resource Not Exist', []);

    return $return;
  }

  protected static function hardDeleteAllBase(object $model, array $request): array
  {
    $return = array();

    $column = helpers::getFirstKeyName($request);
    $value = $request[$column];

    $return = property_exists($model, $column)
      ? repositoryController::hardDeleteAll($model->getTableName(), $column, $value)
      : helpers::formatResponse(403, 'Resource Not Exist', []);

    return $return;
  }

  protected static function hardDeleteByColumnMinorBase(object $model, array $request): array
  {
    $return = array();

    $column = helpers::getFirstKeyName($request);
    $value = $request[$column];

    $return = property_exists($model, $column)
      ? repositoryController::hardDeleteByColumnMinor($model->getTableName(), $column, $value)
      : helpers::formatResponse(403, 'Resource Not Exist', []);

    return $return;
  }

  public static function postUploadFile(array $data): array
  {
    $id = intval($data['_REQUEST']['id'] ?? 0);
    $module = strval($data['_REQUEST']['module'] ?? '');

    if (!helpers::validModule($module) || empty($data['_FILES']['file']))
      return helpers::formatResponse(404, 'File Not Found', []);

    $path = 'assets/uploads/' . date("Y-m") . '/' . $module;
    $imageFileType = pathinfo(basename($data['_FILES']['file']["name"]), PATHINFO_EXTENSION);
    $filename = "{$id}_" . time() . ".{$imageFileType}";
    $uploaded_file = "{$path}/{$filename}";

    if (!file_exists($path))
      mkdir($path, 0777, true);

    if (move_uploaded_file($data['_FILES']['file']['tmp_name'], $uploaded_file)) {
      $cname = "\\src\\app\\models\\{$module}Model";
      self::modifyBase(new $cname, ['id' => $id, 'image' => '/' . $uploaded_file]);
      return helpers::formatResponse(200, 'File Uploaded', $uploaded_file);
    }

    return helpers::formatResponse(401, 'File Not Uploaded', []);
  }


  public static function getConvertImageToBase64(array $request)
  {
    $path = ltrim($request['path'], '/');

    if (!file_exists($path))
      return helpers::formatResponse(404, 'File not found', null);

    $imageData = file_get_contents($path);
    $imageType = pathinfo($path, PATHINFO_EXTENSION);
    $base64 = sprintf('data:image/%s;base64,%s', $imageType, base64_encode($imageData));

    return helpers::formatResponse(200, 'Image converted to Base64', $base64);
  }

  public static function postSendEmailWithPDF(array $request): array
  {
    if (empty($_FILES['file']))
      return helpers::formatResponse(403, 'File not uploaded', []);

    if (empty($_FILES['file']['tmp_name']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK)
      return helpers::formatResponse(403, 'File upload error', []);

    if (mime_content_type($_FILES['file']['tmp_name']) !== 'application/pdf')
      return helpers::formatResponse(403, 'Invalid file type', []);

    $email = filter_var($request['_REQUEST']['email'], FILTER_SANITIZE_EMAIL);

    return emailService::sendEmailWithPDF($email, $_FILES['file'])
      ? helpers::formatResponse(200, 'Email Sent', [])
      : helpers::formatResponse(403, 'Email Not Sent', []);
  }
}
