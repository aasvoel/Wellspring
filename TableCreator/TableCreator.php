<?php

class TableCreator
{
	private $GENERATED_TABLE_ID;
	
	public function create_table($table,$add_datatables_activation = true,$headers_are_first_row = true,$headers = null,$order_by = null)
	{
		$this->set_generated_table_id("table_id_" . time() . mt_rand());
		
		if (isset($headers_are_first_row) && $headers_are_first_row)
		{
			$row_headers = $table[0];
			
			unset($table[0]);
		}
		else if (isset($headers) && is_array($headers) && !empty($headers))
		{
			$row_headers = $headers;
		}
		
		$header_html = $this->create_thead($row_headers);
		$rows_html = $this->create_tbody($table);
		
		$full_table = $this->create_final_table($header_html,$rows_html);

		if (isset($add_datatables_activation) && $add_datatables_activation)
		{
			$full_table .= $this->create_datatables_script($order_by);
		}
		
		return $full_table;
	}
	
	private function create_final_table($header_html = "<th>NULL</th>",$rows_html = "<tr><td>NULL</td></tr>")
	{
		$GENERATED_TABLE_ID = $this->get_generated_table_id();
		
$return = <<<STRING_ALPHAOMEGA
<table id="$GENERATED_TABLE_ID" class="display">
    <thead>
        <tr>
			$header_html
        </tr>
    </thead>
    <tbody>
		$rows_html
    </tbody>
</table>
STRING_ALPHAOMEGA;

return $return;
	}
	
	private function create_datatables_script($order_by)
	{
		$GENERATED_TABLE_ID = $this->get_generated_table_id();
		
		$order = "";
		if (isset($order_by))
		{
			$order = ',"order": [[' . $order_by . ',"asc"]]';
		}
		
		$return = <<<STRING_ALPHAOMEGA
<script>
	$(document).ready( function () 
	{
		$('#$GENERATED_TABLE_ID').DataTable({
			 "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
			 $order
		});
	} );
</script>
STRING_ALPHAOMEGA;
		
		return $return;
	}
	
	private function create_thead($row_headers = [])
	{
		if (!is_array($row_headers))
		{
			$row_headers = [];
		}
		
		$return = "";
		foreach ($row_headers as $header)
		{
			$return .= $this->create_th($header);
		}
		
		return $return;
	}
	
	private function create_th($header = "")
	{
		if (!is_string($header))
		{
			$header = "";
		}
		
		return "<th>$header</th>\r\n";
	}
	
	private function create_tbody($rows = [])
	{
		if (!is_array($rows))
		{
			$rows = [];
		}
		
		$return = "";
		foreach ($rows as $row)
		{
			$return .= $this->create_tr($row);
		}
		
		return $return;
	}
	
	private function create_tr($row = [])
	{
		if (!is_array($row))
		{
			$row = [];
		}
		
		$return = "";
		foreach ($row as $cell)
		{
			$return .= $this->create_td($cell);
		}
		
		return "<tr>\r\n$return\r\n</tr>\r\n";
	}
	
	private function create_td($cell = "")
	{
		if (!is_string($cell))
		{
			$cell = "";
		}
		
		return "<td>$cell</td>\r\n";
	}
	
	private function get_generated_table_id()
	{
		return $this->GENERATED_TABLE_ID;
	}
	
	private function set_generated_table_id($id)
	{
		$this->GENERATED_TABLE_ID = $id;
	}
}