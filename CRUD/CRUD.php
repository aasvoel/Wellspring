<?php

class CRUD
{
	private $database_location = null;
	private $database_data = [];
	private $database_table_keys = [];
	
	public function create($table = null)
	{
		if (!isset($table))
		{
			return "Table not given.";
		}
		
		$this->open_database();
		
		$database_data = $this->get_database_data();
		
		$key = array_search($table, $database_data);
		if ($key !== false)
		{
			return "Identical table is already in the database with ID: $key";
		}
		else
		{
			$count = count($database_data);
			$database_data[$count] = $table;
		}
		
		$this->set_database_data($database_data);
		
		return $this->save_data_to_database();
	}
	
	public function read($table_id,$row_id = null)
	{
		if (!isset($table_id))
		{
			return "Table ID not given.";
		}
		
		$this->open_database();
		
		$database_data = $this->get_database_data();
		
		if (!isset($database_data))
		{
			return "Database could not be loaded.";
		}
		
		if (!isset($database_data[$table_id]))
		{
			return "Table with that ID does not exist.";
		}
		
		$table_data = $database_data[$table_id];
		
		if (isset($row_id))
		{
			if (isset($table_data[$row_id]))
			{
				$table_data = $table_data[$row_id];
			}
			else
			{
				$table_data = "Row with that ID does not exist.";
			}
		}
		
		return $table_data;
	}
	
	public function delete($table_id,$row_id = null)
	{
		$table_data = $this->read($table_id,$row_id);
		
		if (!isset($table_data) || !is_array($table_data))
		{
			return $table_data;
		}
		
		$database_data = $this->get_database_data();
		
		if (isset($row_id))
		{
			unset($database_data[$table_id][$row_id]);
		}
		else
		{
			unset($database_data[$table_id]);
		}
		
		$this->set_database_data($database_data);
		
		$this->save_data_to_database();
	}
	
	public function update($table_id)
	{
		$table_data = $this->read($table_id,$row_id);
		
		if (!isset($table_data) || !is_array($table_data))
		{
			return $table_data;
		}
		
		
		
		$this->save_data_to_database();
	}
	
	private function get_database_location()
	{
		return $this->database_location;
	}
	
	public function set_database_location($location)
	{
		$this->database_location = $location;
	}
	
	private function get_database_data()
	{
		return $this->database_data;
	}
	
	private function set_database_data($data)
	{
		$this->database_data = $data;
	}
	
	public function get_database_table_keys()
	{
		return $this->database_table_keys;
	}
	
	private function set_database_table_keys($keys)
	{
		$this->database_table_keys = $keys;
	}
	
	public function open_database()
	{
		$database_location = $this->get_database_location();
		
		if (isset($_SESSION))
		{
			$database = isset($_SESSION[$database_location]) ? $_SESSION[$database_location] : json_encode([]);
		}
		
		if (isset($database) && is_string($database) && !empty($database))
		{
			$data_contents = json_decode($database);
		}
		else
		{
			$data_contents = [];
		}
		
		if ($data_contents !== false)
		{
			$this->set_database_data($data_contents);
			
			$table_keys = array_keys($data_contents);
			
			if (isset($table_keys) && !empty($table_keys))
			{
				$this->set_database_table_keys($table_keys);
			}
		}
	}
	
	private function save_data_to_database()
	{
		$database_location = $this->get_database_location();
		$data_contents = $this->get_database_data();
		
		if (!isset($_SESSION))
		{
			return "Could not get session.";
		}
		
		$_SESSION[$database_location] = json_encode($data_contents);
		
		$this->open_database();
		$new_data_contents = $this->get_database_data();
		
		$saved = $new_data_contents === $data_contents;
		
		return ($saved === false) ? "Database not updated." : "Database updated.";
	}
}