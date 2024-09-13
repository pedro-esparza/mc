<?php

namespace src\wizard;

use Exception;

class db
{
	private static $_instance = null;
	private static $WDB_HOST = 'localhost';
	private static $WDB_USER = 'root';
	private static $WDB_PASS = 'toor';
	private static $WDB_NAME = 'mccsystem';
	private static $WDB_PORT = '3306';

	// Método para obtener cualquier configuración de base de datos
	public static function get($key)
	{
		return self::${$key};
	}

	// Patrón Singleton para la conexión
	public static function connect()
	{
		// Si la instancia no ha sido creada, crearla
		if (self::$_instance === null) {
			try {
				// Crear una nueva conexión mysqli
				self::$_instance = new \mysqli(
					self::get('WDB_HOST'),
					self::get('WDB_USER'),
					self::get('WDB_PASS'),
					self::get('WDB_NAME'),
					self::get('WDB_PORT')
				);

				// Verificar si hubo errores de conexión
				if (self::$_instance->connect_errno) {
					throw new Exception('Connection error: ' . self::$_instance->connect_error);
				}
			} catch (Exception $e) {
				// Manejo de errores en la conexión
				error_log($e->getMessage());
				return null; // Devolver null en caso de error
			}
		}

		// Devolver la instancia de conexión
		return self::$_instance;
	}
}
