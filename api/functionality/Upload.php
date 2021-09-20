<?php

class Upload extends API_functionality_base
{
	private $type_filters;
	private $type;
	private $files;
	
	private function upload($type_filters = null)
	{
		$this->get_files_from_upload();
		
		$this->set_type_filters($type_filters);
		
		$this->filter_files();
		
		$this->remove_unapproved_files_by_type();
		
		$files = $this->get_files();
		if (!isset($files) || empty($files))
		{
			return "No file(s) uploaded and/or file(s) removed because of unapproved type.";
		}
		
		$files_saved = $this->save_files_to_database();
		
		if (!isset($files_saved) || !$files_saved)
		{
			return "Some or all file(s) could not be saved. Please see list of uploaded CSV files to confirm your uploads.";
		}
		
		return $files_saved;
	}
	
	public function upload_csv()
	{
		return $this->upload
		(
			[
				"text/csv",
				"text/tsv",
				"text/plain",
				"application/vnd.ms-excel",
			]
		);
	}
	
	private function get_type_filters()
	{
		return $this->type_filters;
	}
	
	private function set_type_filters($type_filters)
	{
		$this->type_filters = $type_filters;
	}
	
	private function get_type()
	{
		return $this->type;
	}
	
	private function set_type($type)
	{
		$this->type = $type;
	}
	
	private function get_files_from_upload()
	{
		$files = isset($_FILES) ? $_FILES : [];
		
		$this->set_files($files);
	}
	
	private function get_files()
	{
		return $this->files;
	}
	
	private function set_files($files)
	{
		$this->files = $files;
	}
	
	private function remove_unapproved_files_by_type()
	{
		$files = $this->get_files();
		
		$approved_filetypes = $this->get_approved_filetypes();
		
		$array_filter = function($value,$key) use ($approved_filetypes)
		{
			$type = isset($value['type']) ? $value['type'] : "NO_TYPE_FOUND";
			
			if (!in_array($type,$approved_filetypes))
			{
				return false;
			}
			
			return true;
		};
		
		$files = array_filter($files,$array_filter,ARRAY_FILTER_USE_BOTH);
		
		$this->set_files($files);
	}
	
	private function filter_files()
	{
		$files = $this->get_files();
		$type_filters = $this->get_type_filters();
		
		if (!isset($type_filters) || !is_array($type_filters) || empty($type_filters))
		{
			return;
		}
		
		$array_filter = function($value,$key) use ($type_filters)
		{
			$type = isset($value['type']) ? $value['type'] : "NO_TYPE_FOUND";
			
			if (!in_array($type,$type_filters))
			{
				return false;
			}
			
			return true;
		};
		
		$files = array_filter($files,$array_filter,ARRAY_FILTER_USE_BOTH);
		
		$this->set_files($files);
	}
	
	private function get_approved_filetypes()
	{
		return 
		[
			"text/csv",
			"text/tsv",
			"text/plain",
			"application/vnd.ms-excel",
		];
	}
	
	private function save_files_to_database()
	{
		$files = $this->get_files();
		
		global $CRUD_instance;
		
		$return = "";
		foreach ($files as $key => $file)
		{
			$table = fopen($files[$key]['tmp_name'],"r");
			
			$table_array_mapped = [];
			if ($table !== false)
			{
				while(! feof($table))
				{
					$table_array_mapped[] = fgetcsv($table);
				}
			}
			
			foreach ($table_array_mapped as $row_key => $row)
			{
				foreach ($row as $value_key => $value)
				{
					$table_array_mapped[$row_key][$value_key] = trim($table_array_mapped[$row_key][$value_key]," ");
				}
			}
			
			$return .= $CRUD_instance->create($table_array_mapped) . " ";
		}
		
		return $return;
	}
	
	private function get_filenames()
	{
		$files = $this->get_files();
		$filenames = [];
		
		foreach ($files as $key => $file)
		{
			if (isset($file['name']) && !empty($file['name']) && !in_array($file['name'],$filenames))
			{
				$filenames[] = $file['name'];
			}
		}
	}
}
