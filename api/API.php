<?php

class API 
{
	private $api_class = "";
	private $api_class_method = "";
	private $api_functionality_path = __DIR__ . DIRECTORY_SEPARATOR . "functionality";
	
	private function get_api_class()
	{
		return $this->api_class;
	}
	
	private function set_api_class($class)
	{
		$this->api_class = $class;
	}
	
	private function get_api_class_method()
	{
		return $this->api_class_method;
	}
	
	private function set_api_class_method($method)
	{
		$this->api_class_method = $method;
	}
	
	private function get_api_functionality_path()
	{
		return $this->api_functionality_path;
	}
	
	private function set_api_functionality_path($path)
	{
		$this->api_functionality_path = $path;
	}
	
	private function format_return($return)
	{
		return json_encode($return);
	}
	
	public function request()
	{
		if (!isset($_REQUEST))
		{
			return $this->format_return("Request improperly formed. Please retry.");
		}

		$this->set_api_class(isset($_REQUEST['api_class']) ? $_REQUEST['api_class'] : null);
		$this->set_api_class_method(isset($_REQUEST['api_class_method']) ? $_REQUEST['api_class_method'] : null);

		$api_class = $this->get_api_class();
		$api_class_method = $this->get_api_class_method();
		
		if (!isset($api_class))
		{
			return $this->format_return("No API Class given. Please retry.");
		}

		if (!isset($api_class_method))
		{
			return $this->format_return("No API Class Method given. Please retry.");
		}
		
		$functionality_base_class = $this->get_api_functionality_path() . "/API_functionality_base.php";
		if (!is_file($functionality_base_class) || !file_exists($functionality_base_class))
		{
			return $this->format_return("Could not find API Functionality Base Class. Please contact administrator or developer for assistance.");
		}
		
		include($functionality_base_class);

		$api_class_filename = $this->get_api_functionality_path() . "/$api_class.php";
		if (!is_file($api_class_filename) || !file_exists($api_class_filename))
		{
			return $this->format_return("No API Class file found by name given. Please retry.");
		}
		
		include ($api_class_filename);
		if (!class_exists($api_class))
		{
			return $this->format_return("No API Class found by name given. Please retry.");
		}
		
		if (!method_exists($api_class,$api_class_method))
		{
			return $this->format_return("No API Class Method found by name given. Please retry.");
		}
		
		$class_instance = new $api_class();
		if (!method_exists($api_class,"api_enabled") || $class_instance->api_enabled() !== true)
		{
			return $this->format_return("API is not enabled for this class.");
		}
		
		// If all error checks are good, return class->method()
		return $this->format_return($class_instance->$api_class_method());
	}
}
