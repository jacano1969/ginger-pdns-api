<?php
/**
 * libraries/Request.php
 * 
 * @author Big Ginger Nerd
 * @package ginger-pdns-api
 */
 
class Request {
	
	private static $_hostname 	= false;
	private static $_uri 		= false;
	private static $_path 		= false;
	private static $_resource	= false;
	private static $_pathParts	= false;
	private static $_params 	= false;
	private static $_method		= false;
	
	
	public function __construct()
	{
		self::setHostname($_SERVER['SERVER_NAME']);
		self::setUri($_SERVER['REQUEST_URI']);
		self::setMethod($_SERVER['REQUEST_METHOD']);
		self::setPath($_SERVER['PATH_INFO']);
		self::loadParams();
	}

	public static function loadParams()
	{
		$path = substr(self::getPath(), strlen(self::$_resource));

		$path = (substr($path, 0, 1) == "/") ? substr($path, 1): $path;
		$path = (substr($path, -1) == "/") ? substr($path, 0, -1): $path;
	
		$parts = self::$_pathParts;
		
		foreach($parts as $key => $part)
		{
			if(($key % 2) == 1)
			{
				self::$_params[$parts[$key-1]] = urldecode($part);
			} else {
				self::$_params[$part] = "";
			}
		}
		
		// Possible query params
		$queryString = $_SERVER['QUERY_STRING'];
		parse_str($queryString, $queryParams);
		
		self::$_params = array_merge(self::$_params, $queryParams);
	}

	public static function setMethod($method)
	{
		self::$_method = strtolower($method);
	}
	
	public static function getMethod()
	{
		return self::$_method;
	}
	
	public static function setHostname($hostname)
	{
		self::$_hostname = $hostname;
	}
		
	public static function getHostname()
	{
		return self::$_hostname;
	}
	
	public static function setPath($path)
	{
		if(substr($path, 0, 1) == "/")
		{
			$path = substr($path, 1);
		}
		self::$_path = $path;
		$parts = explode("/", $path);
		if(count($parts) > 0)
		{
			self::$_resource = $parts[0];
			unset($parts[0]);
			$parts = array_values($parts);
			self::$_pathParts = $parts;
		}
	}
	
	public static function getPath()
	{
		return self::$_path;
	}
	
	public static function setUri($uri)
	{
		self::$_uri = $uri;
	}
	
	public static function getUri()
	{
		return self::$_uri;
	}
	
	public static function getParams()
	{
		return self::$_params;
	}
	
	public static function getParam($key)
	{
		if(isset(self::$_params[$key]))
		{
			return self::$_params[$key];
		} else {
			return false;
		}
	}
	
}
