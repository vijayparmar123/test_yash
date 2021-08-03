<?php
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
/*
 * Helper functions for building a DataTables server-side processing SQL query
 *
 * The static functions in this class are just helper functions to help build
 * the SQL used in the DataTables demo server-side processing scripts. These
 * functions obviously do not represent all that can be done with server-side
 * processing, they are intentionally simple to show how it works. More complex
 * server-side processing operations will likely require a custom script.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */


// REMOVE THIS BLOCK - used for DataTables test environment only!
$file = $_SERVER['DOCUMENT_ROOT'].'/datatables/mysql.php';
if ( is_file( $file ) ) {
	include( $file );
}


class SSP_inventorypodelivery {
	/**
	 * Create the data output array for the DataTables rows
	 *
	 *  @param  array $columns Column information array
	 *  @param  array $data    Data from the SQL get
	 *  @return array          Formatted data in a row based format
	 */
	static function data_output ( $columns, $data ,$user)
	{
		$out = array();
		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];

				// Is there a formatter?
				if ( isset( $column['formatter'] ) ) {
					// debug($column['dt']);
					// debug($column['formatter']);
					// debug($data[$i]);die;
					// $row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
				}
				else if($j== 2)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$date = self::get_date($uid);
					 $row[ $column['dt'] ] = $date;
				}
				else if($j== 3)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$project = self::get_projectname($uid);
					$row[ $column['dt'] ] = $project;
				}
				// else if($j== 4)
				// {
					// $uid=$data[$i][ $columns[$j]['field'] ];
					// $vendor = self::get_vendor_name($uid);
					// $row[ $column['dt'] ] = $vendor;
				// }
				else if($j== 5)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					if(is_numeric($uid))
					{
						$material = self::get_material_title($uid);
					}else{
						$material = $uid;
					}
					$row[ $column['dt'] ] = $material;
				}
				else if($j== 6)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					if(is_numeric($uid))
					{
						$brand = self::get_brandname($uid);
					}else{
						$brand = $uid;
					}
					$row[ $column['dt'] ] = $brand;
				}
				else if($j== 8)
				{
					$po_qty = $data[$i][ $columns[7]['field'] ];
					$po_remaining_qty = $data[$i][ $columns[9]['field'] ];
					$received_qty = $po_qty - $po_remaining_qty;
					
					$row[ $column['dt'] ] = round($received_qty,2);
				}
				else if($j== 10)
				{
					$material_id=$data[$i][ $columns[$j]['field'] ];
					if(is_numeric($material_id))
					{
						$unit = self::get_items_units($material_id);
					}else{
						$static_unit=$data[$i][ $columns[13]['field'] ];
						$unit = $static_unit;
					}
					
					$row[ $column['dt'] ] = $unit;
				}
				// else if($j== 11)
				// {
					// $po_type=$data[$i][ $columns[$j]['field'] ];
					// if($po_type == "po")
					// {
						// $type_name = "PO";
					// }elseif($po_type == "manual_po")
					// {
						// $type_name = "Manual PO";
					// }elseif($po_type == "local_po"){
						// $type_name = "Local PO";
					// }
					
					// $row[ $column['dt'] ] = $type_name;
				// }
				else if($j== 12)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$po_detail_id=$data[$i][ $columns[15]['field'] ];
					$po_date=$data[$i][ $columns[2]['field'] ];
					$detail_id='';
					$role = self::get_user_role($user);
					$html = '';
										
					if(self::retrive_accessrights($role,'inventorytrackpodelivery')==1)
					{
						$html .= "<a href='javascript:void(0)' class='btn btn-primary btn-clean delivery_status' data-id='{$po_detail_id}' data-toggle='modal' data-target='#load_modal'><i class='icon-eye-open'></i> Delivery Status</a>";
					}
					
					if(self::retrive_accessrights($role,'inventorypodeliveryrecords')==1)
					{
						if(date('Y-m-d',strtotime($po_date)) > date('Y-m-d',strtotime('01-07-2017')))
						{
							$html .= "<a href='previewpo2/{$uid}' target='_blank' class='btn btn-primary btn-clean'><i class='icon-eye-open'></i> View</a>";
						}else{
							$html .= "<a href='previewpo/{$uid}' target='_blank' class='btn btn-primary btn-clean'><i class='icon-eye-open'></i> View</a>";
						}
					}
					
					$row[ $column['dt'] ] =$html;
				}
				else {
					$row[ $column['dt'] ] = $data[$i][ $columns[$j]['field'] ];
				}
			}
			
			$out[] = $row;
		}
		return $out;
	}


	/**
	 * Database connection
	 *
	 * Obtain an PHP PDO connection from a connection details array
	 *
	 *  @param  array $conn SQL connection details. The array should have
	 *    the following properties
	 *     * host - host name
	 *     * db   - database name
	 *     * user - user name
	 *     * pass - user password
	 *  @return resource PDO connection
	 */
	static function db ( $conn )
	{
		if ( is_array( $conn ) ) {
			return self::sql_connect( $conn );
		}

		return $conn;
	}


	/**
	 * Paging
	 *
	 * Construct the LIMIT clause for server-side processing SQL query
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @return string SQL limit clause
	 */
	static function limit ( $request, $columns )
	{
		$limit = '';

		if ( isset($request['start']) && $request['length'] != -1 ) {
			$limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
		}

		return $limit;
	}


	/**
	 * Ordering
	 *
	 * Construct the ORDER BY clause for server-side processing SQL query
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @return string SQL order by clause
	 */
	static function order ( $request, $columns )
	{
		$order = '';

		if ( isset($request['order']) && count($request['order']) ) {
			$orderBy = array();
			$dtColumns = self::pluck( $columns, 'dt' );

			for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
				// Convert the column index into the column data property
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];

				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				if ( $requestColumn['orderable'] == 'true' ) {
					$dir = $request['order'][$i]['dir'] === 'asc' ?
						'ASC' :
						'DESC';

					$orderBy[] = ' '.$column['db'].' '.$dir;
				}
			}

			$order = 'ORDER BY '.implode(', ', $orderBy);
		}

		return $order;
	}


	/**
	 * Searching / Filtering
	 *
	 * Construct the WHERE clause for server-side processing SQL query.
	 *
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here performance on large
	 * databases would be very poor
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @param  array $bindings Array of values for PDO bindings, used in the
	 *    sql_exec() function
	 *  @return string SQL where clause
	 */
	static function filter ( $request, $columns, &$bindings )
	{
		$globalSearch = array();
		$columnSearch = array();
		$dtColumns = self::pluck( $columns, 'dt' );
		//$request['search'] = array('value'=>30);
			//$request['search']['value'] = 'aa';
		if ( isset($request['search']) && $request['search']['value'] != '' ) {
			
			$str = $request['search']['value'];

			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				if ( $requestColumn['searchable'] == 'true' ) {
					$binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
					$globalSearch[] = "`".$column['field']."` LIKE ".$binding;
				}
			}
		}

		// Individual column filtering
		if ( isset( $request['columns'] ) ) {
			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				$str = $requestColumn['search']['value'];

				if ( $requestColumn['searchable'] == 'true' &&
				 $str != '' ) {
					$binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
					$columnSearch[] = "`".$column['field']."` LIKE ".$binding;
				}
			}
		}

		// Combine the filters into a single string
		$where = '';

		if ( count( $globalSearch ) ) {
			$where = '('.implode(' OR ', $globalSearch).')';
		}

		if ( count( $columnSearch ) ) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where .' AND '. implode(' AND ', $columnSearch);
		}

		if ( $where !== '' ) {
			$where = 'WHERE '.$where;
		}
		
		return $where;
		
	}


	/**
	 * Perform the SQL queries needed for an server-side processing requested,
	 * utilising the helper functions of this class, limit(), order() and
	 * filter() among others. The returned array is ready to be encoded as JSON
	 * in response to an SSP request, or can be modified if needed before
	 * sending back to the client.
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array|PDO $conn PDO connection resource or connection parameters array
	 *  @param  string $table SQL table to query
	 *  @param  string $primaryKey Primary key of the table
	 *  @param  array $columns Column information array
	 *  @return array          Server-side processing response array
	 */
	static function simple ( $request, $conn, $table, $primaryKey, $columns ,$joinQuery,$user)
	{	
		$role = self::get_user_role($user);
		$post = $request;	
		$projects_ids = self::users_project($user);
		$or = array();	
		
			
		$or["po.project_id IN"] = (!empty($post["pro_id"]) && $post["pro_id"] != "All" )?$post["pro_id"]:NULL;
		$or["po_detail.material_id IN"] = (!empty($post["material_id"]) && $post["material_id"] != "All")?$post["material_id"]:NULL;
		$or["po_detail.brand_id IN"] = (!empty($post["brand_id"]) && $post["brand_id"] != "All")?$post["brand_id"]:NULL;
		$or["po.vendor_userid IN"] = (!empty($post["vendor_userid"]) && $post["vendor_userid"] != "All")?$post["vendor_userid"]:NULL;
		$or["po.po_date >="] = ($post["date_from"] != "")?date("Y-m-d",strtotime($post["date_from"])):NULL;
		$or["po.po_date <="] = ($post["date_to"] != "")?date("Y-m-d",strtotime($post["date_to"])):NULL;
		$or["po.po_no ="] = (!empty($post["po_no"]))?$post["po_no"]:NULL;
		$or["po.po_purchase_type ="] = (!empty($post["po_type"]) && $post["po_type"] != "All")?$post["po_type"]:NULL;
		$or["po_detail.po_type ="] = (!empty($post["po_type"]) && $post["po_type"] != "All")?$post["po_type"]:NULL;
		
		if($or["po.project_id IN"] == NULL)
		{
			if(self::project_alloted($role)==1)
			{
				$or["po.project_id IN"] = implode(",",$projects_ids);
			}
		}
						
		$keys = array_keys($or,"");				
		foreach ($keys as $k)
		{unset($or[$k]);}
		
			
		
		$or["po_detail.approved ="] = 2;
		$or["po.po_purchase_type ="] = "po";
		// echo "<pre>";
		// print_r($post);
		// echo "</pre>";
		// echo "<pre>";
		// print_r($or);
		// echo "</pre>";
		// die;
			
		$bindings = array();
		$db = self::db( $conn );
	
		$new_table = $table;
		
		$limit = self::limit( $request, $columns );
		$order = self::order( $request, $columns );
		$where = self::filter( $request, $columns, $bindings );
		// debug($extrawhere);die;
		$extraquery = '';
		$extrawhere = '';
		$i = 0;
		foreach($or as $key=>$value)
		{
			if($i == 0)
			{
				if($key == "po.project_id IN")
				{
					$extraquery .= ' '. $key .' '. '('.$value.')';
				}elseif($key == "po_detail.material_id IN")
				{
					$extraquery .= ' '. $key .' '. '('.$value.')';
				}elseif($key == "po_detail.brand_id IN")
				{
					$extraquery .= ' '. $key .' '. '('.$value.')';
				}elseif($key == "po.vendor_userid IN")
				{
					$extraquery .= ' '. $key .' '. '('.$value.')';
				}else
				{
					$extraquery .= ' '. $key .' '. "'$value'";
				}
			}
			else
			{
				if($key == "po.project_id IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '('.$value.')';
				}elseif($key == "po_detail.material_id IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '('.$value.')';
				}elseif($key == "po_detail.brand_id IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '('.$value.')';
				}elseif($key == "po.vendor_userid IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '('.$value.')';
				}
				else
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. "'$value'";
				}
			}
			$i++;
		}
		// debug($extraquery);die;
		if($extraquery != '' )
		{
			$extrawhere = ($where) ? 'AND'.$extraquery : 'WHERE'. $extraquery;
		}
		
		// echo "SELECT SQL_CALC_FOUND_ROWS ".implode(", ", self::pluck($columns, 'db'))."
			// FROM $joinQuery
			// $where
			// $extrawhere
			// $order
			// $limit";die;
		$data = self::sql_exec( $db, $bindings,
			"SELECT SQL_CALC_FOUND_ROWS ".implode(", ", self::pluck($columns, 'db'))."
			FROM $joinQuery
			$where
			$extrawhere
			$order
			$limit"
		);
		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";die;
		$resFilterLength = self::sql_exec( $db,
			"SELECT FOUND_ROWS()"
		);
		$recordsFiltered = $resFilterLength[0][0];

		// Total data set length
		$resTotalLength = self::sql_exec( $db,
			"SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table` "
		);
		$recordsTotal = $resTotalLength[0][0];


		/*
		 * Output
		 */
		return array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => self::data_output( $columns, $data , $user)
		);
	}

	static function complex ( $request, $conn, $table, $primaryKey, $columns, $whereResult=null, $whereAll=null )
	{
		$bindings = array();
		$db = self::db( $conn );
		$localWhereResult = array();
		$localWhereAll = array();
		$whereAllSql = '';

		// Build the SQL query string from the request
		$limit = self::limit( $request, $columns );
		$order = self::order( $request, $columns );
		$where = self::filter( $request, $columns, $bindings );

		$whereResult = self::_flatten( $whereResult );
		$whereAll = self::_flatten( $whereAll );

		if ( $whereResult ) {
			$where = $where ?
				$where .' AND '.$whereResult :
				'WHERE '.$whereResult;
		}

		if ( $whereAll ) {
			$where = $where ?
				$where .' AND '.$whereAll :
				'WHERE '.$whereAll;

			$whereAllSql = 'WHERE '.$whereAll;
		}

		// Main query to actually get the data
		$data = self::sql_exec( $db, $bindings,
			"SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", self::pluck($columns, 'db'))."`
			 FROM `$table`
			 $where
			 $order
			 $limit"
		);

		// Data set length after filtering
		$resFilterLength = self::sql_exec( $db,
			"SELECT FOUND_ROWS()"
		);
		$recordsFiltered = $resFilterLength[0][0];

		// Total data set length
		$resTotalLength = self::sql_exec( $db, $bindings,
			"SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table` ".
			$whereAllSql
		);
		$recordsTotal = $resTotalLength[0][0];

		/*
		 * Output
		 */
		return array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => self::data_output( $columns, $data )
		);
	}


	/**
	 * Connect to the database
	 *
	 * @param  array $sql_details SQL server connection details array, with the
	 *   properties:
	 *     * host - host name
	 *     * db   - database name
	 *     * user - user name
	 *     * pass - user password
	 * @return resource Database connection handle
	 */
	static function sql_connect ( $sql_details )
	{
		try {
			$db = @new PDO(
				"mysql:host={$sql_details['host']};dbname={$sql_details['db']}",
				$sql_details['user'],
				$sql_details['pass'],
				array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION )
			);
		}
		catch (PDOException $e) {
			self::fatal(
				"An error occurred while connecting to the database. ".
				"The error reported by the server was: ".$e->getMessage()
			);
		}

		return $db;
	}


	/**
	 * Execute an SQL query on the database
	 *
	 * @param  resource $db  Database handler
	 * @param  array    $bindings Array of PDO binding values from bind() to be
	 *   used for safely escaping strings. Note that this can be given as the
	 *   SQL query string if no bindings are required.
	 * @param  string   $sql SQL query to execute.
	 * @return array         Result from the query (all rows)
	 */
	static function sql_exec ( $db, $bindings, $sql=null )
	{
		

		// Argument shifting
		if ( $sql === null ) {
			$sql = $bindings;
		}

		$stmt = $db->prepare( $sql );
		//echo $sql;

		// Bind parameters
		if ( is_array( $bindings ) ) {
			for ( $i=0, $ien=count($bindings) ; $i<$ien ; $i++ ) {
				$binding = $bindings[$i];
				$stmt->bindValue( $binding['key'], $binding['val'], $binding['type'] );
			}
		}

		// Execute
		try {
			$stmt->execute();
		}
		catch (PDOException $e) {
			self::fatal( "An SQL error occurred: ".$e->getMessage() );
		}

		// Return all
		return $stmt->fetchAll();
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Internal methods
	 */

	/**
	 * Throw a fatal error.
	 *
	 * This writes out an error message in a JSON string which DataTables will
	 * see and show to the user in the browser.
	 *
	 * @param  string $msg Message to send to the client
	 */
	static function fatal ( $msg )
	{
		echo json_encode( array( 
			"error" => $msg
		) );

		exit(0);
	}

	/**
	 * Create a PDO binding key which can be used for escaping variables safely
	 * when executing a query with sql_exec()
	 *
	 * @param  array &$a    Array of bindings
	 * @param  *      $val  Value to bind
	 * @param  int    $type PDO field type
	 * @return string       Bound key to be used in the SQL where this parameter
	 *   would be used.
	 */
	static function bind ( &$a, $val, $type )
	{
		$key = ':binding_'.count( $a );

		$a[] = array(
			'key' => $key,
			'val' => $val,
			'type' => $type
		);

		return $key;
	}


	/**
	 * Pull a particular property from each assoc. array in a numeric array, 
	 * returning and array of the property values from each item.
	 *
	 *  @param  array  $a    Array to get data from
	 *  @param  string $prop Property to read
	 *  @return array        Array of property values
	 */
	static function pluck ( $a, $prop )
	{
		$out = array();

		for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
			$out[] = $a[$i][$prop];
		}

		return $out;
	}


	/**
	 * Return a string from an array or a string
	 *
	 * @param  array|string $a Array to join
	 * @param  string $join Glue for the concatenation
	 * @return string Joined string
	 */
	static function _flatten ( $a, $join = ' AND ' )
	{
		if ( ! $a ) {
			return '';
		}
		else if ( $a && is_array($a) ) {
			return implode( $join, $a );
		}
		return $a;
	}
	
	static function get_date($date)
	{
			return date('d-m-Y',strtotime($date));
	}
	static function get_user_role($user_id)
	{
		$erp_users = TableRegistry::get('erp_users'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
		$res_array = array();
		foreach($user_data as $retrive_data)
		{
			$res_array['role'] = $retrive_data['role'];
		}
		if(isset($res_array['role']))
		return $res_array['role'];
	}
	static function get_brandname($brand_id)
	{
		$erp_material = TableRegistry::get('erp_material_brand');
		$results = $erp_material->find()->where(array('brand_id'=>$brand_id));
		$brand_name = ' - ';
		foreach($results as $retrive_data)
		{
			$brand_name = $retrive_data['brand_name'];					
		}
		return $brand_name;
	}
	static function get_items_units($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id));
		$units_title = "-";
		foreach($results as $retrive_data)
		{
			$mat_unitid = $retrive_data['unit_id'];
			if(!empty($mat_unitid)){
				$units_title = self::get_category_title($mat_unitid);
				
			}
		}
		return $units_title;
		 
	}
	
	static function get_projectname($project_id)
	{	
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);	
		$result_arr = array();
		$result_arr['project_name'] = '-';
		if(!empty($project_data)){
			foreach($project_data as $retrive_data)
			{
				$result_arr['project_name'] = $retrive_data['project_name'];			
			}
		}
		return $result_arr['project_name'];
	}
	
	static function users_project($user_id)
	{
		$erp_projects_assign = TableRegistry::get('erp_projects_assign'); 
		$result = $erp_projects_assign->find()->where(['user_id'=>$user_id]);
		$projects_id = array();
		foreach($result as $retrive_data)
		{
			$projects_id[] = $retrive_data['project_id'];
		}

		return $projects_id;
	}
	
	static function get_vendor_name($user_id)
	{
		$erp_users = TableRegistry::get('erp_vendor'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
		$res_array = array();
		foreach($user_data as $retrive_data)
		{
			$res_array['first_name'] = $retrive_data['vendor_name'];
			
		}
		if(isset($res_array['first_name']))
		return $res_array['first_name'];
		else
			return 'User No Exist More';
	}
	
	static function get_material_title($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id));
		$cnt = $results->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$results = $tmp_tbl->find()->where(['material_id'=>$material_id]);
		}
		
		$material_title = "";
		foreach($results as $retrive_data)
		{
			$material_title = $retrive_data['material_title'];					
		}
		return $material_title;
	}
	
	static function get_category_title($cat_id)
	{
		$erp_category_master = TableRegistry::get('erp_category_master'); 
		$category_data = $erp_category_master->find()->where(['cat_id'=>$cat_id]);
		$res_array = array();
		foreach($category_data as $retrive_data)
		{
			$res_array['category_title'] = $retrive_data['category_title'];
			$res_array['cat_id'] = $retrive_data['cat_id'];
		}
		if(isset($res_array['category_title']))
			return $res_array['category_title'];
		else
			return '';
	}
	
	static function retrive_accessrights($role,$pagename)
	{
		$data=0;		
		$findvalue=array();		
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		
		$find=$erp_accessrights_tbl->find()->where(['role'=>$role])->first();
		
		if(!empty($find))
		{
			$findvalue=json_decode($find->accessrights);
		}
		
		$findvalue=(array)$findvalue;	
		foreach($findvalue as $result){
			
			$selected = in_array($pagename,$result);
			if($selected==1){
				$data=1;
				return $data;
				break;
			}
		}
	}
	
	static function project_alloted($role){
		$alloted=1;		
		$findvalue=array();		
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		
		$find=$erp_accessrights_tbl->find()->where(['role'=>$role])->first();
		if(!empty($find))
		{
			$alloted=$find->Alloted;
		}
		return $alloted;
	}
}

