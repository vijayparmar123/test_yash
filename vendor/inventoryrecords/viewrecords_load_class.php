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


class SSP_records {
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
					// debug($uid);die;
					$mat_code = self::get_materialitemcode($uid);
					$row[ $column['dt'] ] = $mat_code;
				}
				else if($j== 3)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					if($uid != 0)
					{
						$mt = $data[$i][ $columns[15]['field'] ];
					}
					else
					{
						$mt = $data[$i][ $columns[16]['field'] ];
					}
					$row[ $column['dt'] ] = $mt;
				}
				else if($j== 4)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$consume_name = self::get_consume_type($uid);
					$row[ $column['dt'] ] = $consume_name;
				}
				else if($j== 5)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					if($uid != "" && $uid != NULL)
					{
						$cost_group = $uid;
					}else{
						$cost_group = "c";
					}
					$row[ $column['dt'] ] = $cost_group;
				}
				else if($j== 6)
				{
					$material_id = $data[$i][ $columns[2]['field'] ];
					if($material_id != 0)
					{
						$m_id = $material_id;
					}
					else
					{
						$m_id = $data[$i][ $columns[16]['field'] ];
					}
					$project_id=$data[$i][ $columns[11]['field'] ];
					$max_purchase_level = self::get_maximum_stock_level($project_id,$m_id);
					$row[ $column['dt'] ] = $max_purchase_level;
				}
				else if($j== 7)
				{
					$project_id=$data[$i][ $columns[10]['field'] ];
					$material_id = $data[$i][ $columns[2]['field'] ];
					if($material_id != 0)
					{
						$m_id = $material_id;
					}
					else
					{
						$m_id = $data[$i][ $columns[16]['field'] ];
					}
					$total_stock_in = bcdiv(self::get_total_stockin($project_id,$m_id),1,3);
					// $uid=$data[$i][ $columns[$j]['field'] ];
					// $stock_in = bcdiv($uid,1,3);
					$row[ $column['dt'] ] = $total_stock_in;
				}
				else if($j== 8)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$max_que = ($uid != 0) ? bcdiv($data[$i][ $columns[7]['field'] ]/$uid,1,3) : "NA";
					$row[ $column['dt'] ] = $max_que ;
				}
				else if($j== 9)
				{
					$project_id=$data[$i][ $columns[10]['field'] ];
					$material_id = $data[$i][ $columns[2]['field'] ];
					if($material_id != 0)
					{
						$m_id = $material_id;
					}
					else
					{
						$m_id = $data[$i][ $columns[16]['field'] ];
					}
					$total_stock_out = bcdiv(self::get_total_stockout($project_id,$m_id),1,3);
					// $uid=$data[$i][ $columns[$j]['field'] ];
					// $stock_out = bcdiv($uid,1,3);
					$row[ $column['dt'] ] = $total_stock_out ;
				}
				else if($j== 10)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$consume_value = $data[$i][ $columns[4]['field'] ];
					
					$material_id = $data[$i][ $columns[2]['field'] ];
					if($material_id != 0)
					{
						$m_id = $material_id;
					}
					else
					{
						$m_id = $data[$i][ $columns[16]['field'] ];
					}
					if($consume_value == 1)
					{
						$current_stock = bcdiv(self::get_current_stock($uid,$m_id),1,3);
					}else{
						$current_stock = bcdiv(self::get_symbolic_stock($uid,$m_id),1,3);
					}
					
					$row[ $column['dt'] ] = $current_stock ;
				}
				else if($j== 11)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$material_id = $data[$i][ $columns[2]['field'] ];
					if($material_id != 0)
					{
						$m_id = $material_id;
					}
					else
					{
						$m_id = $data[$i][ $columns[16]['field'] ];
					}
					$current_stock = bcdiv(self::get_current_stock($uid,$m_id),1,3);
					$row[ $column['dt'] ] = $current_stock ;
				}
				else if($j== 12)
				{
					$material_id = $data[$i][ $columns[2]['field'] ];
					if($material_id != 0)
					{
						$m_id = $material_id;
					}
					else
					{
						$m_id = $data[$i][ $columns[16]['field'] ];
					}
					$project_id=$data[$i][ $columns[11]['field'] ];
					
					$row[ $column['dt'] ] = self::get_min_stock_level($project_id,$m_id);
				}
				else if($j== 13)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$item_unit = self::get_items_units($uid);
					$row[ $column['dt'] ] = $item_unit;
				}
				else if($j== 14)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$material_name=$data[$i][ $columns[16]['field'] ];
					$project_id=$data[$i][ $columns[11]['field'] ];
					$role = self::get_user_role($user);
					$html = '';
					
					if($uid != 0)
					{
						$m_id = $uid;
					}
					else
					{
						$m_id = $data[$i][ $columns[16]['field'] ];
					}
					
						if($uid)
						{
							$html .= "<a href='stockledger/{$project_id}/{$uid}' target='_blank' class='btn btn-primary btn-clean'><i class='icon-eye-open'></i> View</a>";
						}else{
							$html .= "<a href='stockledger/{$project_id}/{$material_name}' target='_blank' class='btn btn-primary btn-clean'><i class='icon-eye-open'></i> View</a>";
						}
					
					
					if(self::retrive_accessrights($role,'managestock')==1)
					{
						$html .= "<button type='button' data-toggle='modal' p_id='{$project_id}' m_id='{$m_id}'
								data-target='#load_modal' class='btn btn-primary btn-clean viewmodal'><i class='icon-eye-open'></i>Manage Stock </button>";
					}
					
					$row[ $column['dt'] ] =$html;
				}
				else {
					$row[ $column['dt'] ] = $data[$i][ $columns[$j]['field'] ];
				}
			}
			$flag = 1;
			$material_id=$data[$i][ $columns[2]['field'] ];
			if($material_id)
			{
				$project_specific = self::is_material_projectspecific($material_id);
				
				if($project_specific)
				{
					$material_code = self::get_materialitemcode($uid);
					$m_c = explode("/",$material_code);
					$m_c = ($m_c[3])?$m_c[3]:'';
					
					$project_id=$data[$i][ $columns[11]['field'] ];
					$project_code = self::get_projectcode($project_id);
					$p_c = explode("/",$project_code);
					$p_c = ($p_c[2])?$p_c[2]:'';
					if($m_c != $p_c)
					{
						$flag = 0;
					}
				}
			}
			if($flag){
				$out[] = $row;
			}
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
		$projects_ids = self::users_project($user);
		$role = self::get_user_role($user);
		$post = $request;	
		$or = array();				
		// $orwhere_name = array();
		// $orwhere_id = array();
		// $material_name = array();
		// $material_id = array();
		
		$p = explode(",",$post["pro_id"]);
		$m = explode(",",$post["material_id"]);
		$consume = explode(",",$post["consume"]);
		$cost_group = explode(",",$post["cost_group"]);
		$material_sub_group = explode(",",$post["material_sub_group"]);
		
		$or["stock_history.project_id IN"] = (!empty($p) && $p[0] != "All" )?$post["pro_id"]:NULL;
		$or["stock_history.material_id IN"] = (!empty($m) && $m[0] != "All")?$post["material_id"]:NULL;
		$or["stock_history.min_quantity ="] = (!empty($post["minimum_stock"]))?$post["minimum_stock"]:NULL;
		$or["stock_history.max_quantity ="] = (!empty($post["maximum_purchase"]))?$post["maximum_purchase"]:NULL;
		$or["material.consume IN"] = (!empty($consume) && $consume[0] != "All" )?$post["consume"]:NULL;
		$or["material.cost_group IN"] = (!empty($cost_group) && $cost_group[0] != "All" )?$post["cost_group"]:NULL;
		$or["material.material_sub_group IN"] = (!empty($material_sub_group) && $material_sub_group[0] != "All" )?$post["material_sub_group"]:NULL;
		
		if($or["stock_history.project_id IN"] == NULL)
		{
			if(self::project_alloted($role)==1)
			{
				$or["stock_history.project_id IN"] = implode(",",$projects_ids);
			}
		}
		if($or["stock_history.material_id IN"] == NULL)
		{
			if($role == "deputymanagerelectric")
			{
				$material_ids = self::get_deputymanagerelectric_material();
				$material_ids = json_decode($material_ids);
				$or["stock_history.material_id IN"] = implode(",",$material_ids);
			}
			
			if(self::project_alloted($role)==1)
			{
				$material_ids = self::get_user_material_id($user);
				$or["stock_history.material_id IN"] = implode(",",$material_ids);
			}
		}
		// $mat = explode(",",$post["material_id"]);
		// if(!empty($post["material_id"]) && $post["material_id"][0] != "All")
		// {
			// foreach($mat as $retrive)
			// {
				// if(is_numeric($retrive))
				// {
					// $material_id[] = $retrive;
				// }
				// else
				// {
					// $material_name[] = $retrive;	
				// }
			// }
			// $orwhere_id["material_id IN"] = (!empty($material_id)) ? $material_id : NULL ;
			// $orwhere_name["material_name IN"] = (!empty($material_name)) ? $material_name : NULL;
		// }
		//$or["asset_id LIKE"] = (!empty($post["asset_id"]))?"%{$post["asset_id"]}%":NULL;
		
		$keys = array_keys($or,"");				
		foreach ($keys as $k)
		{unset($or[$k]);}
		
		// $keys1 = array_keys($orwhere_id,"");				
		// foreach ($keys1 as $k1)
		// {unset($orwhere_id[$k1]);}
		
		// $keys2 = array_keys($orwhere_name,"");				
		// foreach ($keys2 as $k2)
		// {unset($orwhere_name[$k2]);}
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
				if($key == "stock_history.project_id IN")
				{
					$extraquery .= ' '. $key .' '. '('.$value.')';
				}else if($key == "stock_history.material_id IN")
				{
					$extraquery .= ' '. $key .' '. '('.$value.')';
				}else if($key == "material.consume IN")
				{
					$extraquery .= ' '. $key .' '. '('.$value.')';
				}else if($key == "material.material_sub_group IN")
				{
					$extraquery .= ' '. $key .' '. '('.$value.')';
				}else if($key == "material.cost_group IN")
				{
					$extraquery .= ' '. $key .' '. '("'.$value.'")';
				}else
				{
					$extraquery .= ' '. $key .' '. "'$value'";
				}
			}
			else
			{
				if($key == "stock_history.project_id IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '('.$value.')';
				}else if($key == "stock_history.material_id IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '('.$value.')';
				}else if($key == "material.consume IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '('.$value.')';
				}else if($key == "material.material_sub_group IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '('.$value.')';
				}else if($key == "material.cost_group IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '("'.$value.'")';
				}else
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
		
		// if(!empty($orwhere_id) || !empty($orwhere_name))
		// {
			// if(!empty($orwhere_id))
			// {
				// $extrawhere .= 'andWhere'.implode(",",$orwhere_id).' orWhere'.implode(",",$orwhere_name);
			// }
			// elseif(!empty($orwhere_name))
			// {
				// $extrawhere .= 'andWhere'.implode(",",$orwhere_name).' orWhere'.implode(",",$orwhere_id);
			// }
			// else
			// {
				// $extrawhere .= 'andWhere'.implode(",",$orwhere_id).' orWhere'.implode(",",$orwhere_name);
			// }
			
		// }
		// echo $extrawhere;die;
		// echo "SELECT SQL_CALC_FOUND_ROWS ".implode(", ", self::pluck($columns, 'db'))."
			// FROM $joinQuery
			// $where
			// $extrawhere
			// GROUP BY project_id, material_id, material_name
			// $order
			// $limit";die;
		$data = self::sql_exec( $db, $bindings,
			"SELECT SQL_CALC_FOUND_ROWS ".implode(", ", self::pluck($columns, 'db'))."
			FROM $joinQuery
			$where
			$extrawhere
			GROUP BY project_id, material_id, material_name
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
	static function get_items_units($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id));
		$cnt = $results->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$results = $tmp_tbl->find()->where(['material_id'=>$material_id]);
		}
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
	
	static function get_materialitemcode($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id));
		$cnt = $results->count();
		if($cnt == 0)
		{
			$tmp_tbl = TableRegistry::get("erp_material_temp");
			$results = $tmp_tbl->find()->where(['material_id'=>$material_id]);
		}
		$material_code = "";
		foreach($results as $retrive_data)
		{
			$material_code = $retrive_data['material_item_code'];					
		}
		return $material_code;
	}
	
	static function get_current_stock($project_id,$material_id)
	{		
		$history_tbl = TableRegistry::get("erp_stock_history");
		$opening_stock = 0;
		if(is_numeric($material_id))
		{
			$opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		
		if(!empty($opening_stock_data))
		{
			$opening_stock = $opening_stock_data[0]["quantity"];			
		}
		
		if(is_numeric($material_id))
		{
		$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type NOT IN"=>array("os","sst_to")])->hydrate(false)->toArray();
		}
		else
		{
			$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_name"=>$material_id,"type NOT IN"=>array("os","sst_to")])->hydrate(false)->toArray();
		}
		if(isset($stockledger))
		{
			foreach($stockledger as $retrive_data)
			{
				$opening_stock = self::get_stock_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
			}
		}
		return $opening_stock;
	}
	
	static function get_symbolic_stock($project_id,$material_id)
	{		
		$history_tbl = TableRegistry::get("erp_stock_history");
		$opening_stock = 0;
		if(is_numeric($material_id))
		{
			$opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		
		if(!empty($opening_stock_data))
		{
			$opening_stock = $opening_stock_data[0]["quantity"];			
		}
		
		if(is_numeric($material_id))
		{
		$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type !="=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_name"=>$material_id,"type !="=>"os"])->hydrate(false)->toArray();
		}
		if(isset($stockledger))
		{
			foreach($stockledger as $retrive_data)
			{
				$opening_stock = self::get_symbolic_stock_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
			}
		}
		return $opening_stock;
	}
	
	static function get_stock_balance($type,$old_stock,$new_stock)
	{		
		switch($type)
		{
			CASE "is":
				return $old_stock - $new_stock;
			break;
			
			CASE "rmc":
				return $old_stock - $new_stock;
			break;
			
			CASE "mrn":
				return $old_stock - $new_stock;
			break;
			
			CASE "rbn":
				return $old_stock + $new_stock;
			break;
			
			CASE "grn":
				return $old_stock + $new_stock;
			break;
			
			CASE "sst_from":
				return $old_stock - $new_stock;
			break;
			CASE "sst_to":
				return $old_stock + $new_stock;
			break;
			
			default :
				return $old_stock + $new_stock;
		}
	}
	
	static function get_symbolic_stock_balance($type,$old_symbolic_stock,$new_stock)
	{		
		switch($type)
		{
			// CASE "is":
				// return $old_symbolic_stock - $new_stock;
			// break;
			
			CASE "mrn":
				return $old_symbolic_stock - $new_stock;
			break;
			
			// CASE "rbn":
				// return $old_symbolic_stock + $new_stock;
			// break;
			
			CASE "grn":
				return $old_symbolic_stock + $new_stock;
			break;
			
			CASE "sst_from":
				return $old_symbolic_stock - $new_stock;
			break;
			// CASE "sst_to":
				// return $old_symbolic_stock + $new_stock;
			// break;
			
			default :
				return $old_symbolic_stock;
		}
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
	
	static function get_min_stock_level($project_id,$material_id)
	{
		$erp_stock_history = TableRegistry::get("erp_stock_history");
		if(is_numeric($material_id))
		{
			$result = $erp_stock_history->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$result = $erp_stock_history->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		
		if(!empty($result))
		{
			return $result[0]['min_quantity'];
		}else{
			return '';
		}
	}
	
	static function get_maximum_stock_level($project_id,$material_id)
	{
		$erp_stock_history = TableRegistry::get("erp_stock_history");
		if(is_numeric($material_id))
		{
			$result = $erp_stock_history->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$result = $erp_stock_history->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		
		if(!empty($result))
		{
			return $result[0]['max_quantity'];
		}else{
			return '';
		}
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
	static function get_deputymanagerelectric_material()
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(['material_code IN'=>['6','7','10','15']]);
		$material_ids = array();
		foreach($results as $material)
		{
			$material_ids[] = $material->material_id;
		}
		return json_encode($material_ids);
	}
	static function get_consume_type($consume_value)
	{
		$consume_type = "";
		if($consume_value == 1)
		{
			$consume_type = "Consumable";
		}elseif($consume_value == 0){
			$consume_type = "Retunable / Non-consumable";
		}elseif($consume_value == 3){
			$consume_type = "Asset";
		}else{
			$consume_type = "";
		}
		return $consume_type;
	}
	
	static function old_project($user_id)
	{
		$projects = array();
		$erp_projects_assign = TableRegistry::get('erp_projects_assign');
		$results = $erp_projects_assign->find()->where(array('user_id'=>$user_id));
		foreach($results as $retrive_data)
		{
			$projects[] = $retrive_data['project_id'];
		}
		return $projects;
	}
	
	static function get_user_assign_projects_id($user_id)
	{
		$assign_projects = self::old_project($user_id);
		$projects = array();
		if(!empty($assign_projects))
		{
			foreach($assign_projects as $project_id)
			{
				$projects[] = $project_id;				
			}
		}
		return $projects;
	}
	
	static function get_user_material_id($user_id)
	{
		$assign_projects = self::get_user_assign_projects_id($user_id);
		$assign_projects = array_merge($assign_projects,[0]);
		$material_ids = array();
		if(!empty($assign_projects))
		{
			$erp_material = TableRegistry::get('erp_material');
			$results = $erp_material->find()->where(['project_id IN'=>$assign_projects])->select(['material_id']);
			
			foreach($results as $material)
			{
				$material_ids[] = $material->material_id;
			}
		}
		return $material_ids;
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
	
	static function is_material_projectspecific($material_id)
	{
		$erp_material = TableRegistry::get('erp_material');
		$results = $erp_material->find()->where(array('material_id'=>$material_id))->first();
		if(!empty($results))
		{
			return $results->project_id;
		}else{
			return 0;
		}
	}
	
	static function get_projectcode($project_id)
	{	
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);	
		$result_arr = array();
		$result_arr['project_code'] = '-';
		if(!empty($project_data)){
		
			foreach($project_data as $retrive_data){
			$result_arr['project_code'] = $retrive_data['project_code'];			
		}
		}
		return $result_arr['project_code'];
	}
	
	static function get_total_stockin($project_id,$material_id)
	{		
		$history_tbl = TableRegistry::get("erp_stock_history");
		$opening_stock = 0;
		if(is_numeric($material_id))
		{
			$opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		else
		{
			$opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		}
		
		if(!empty($opening_stock_data))
		{
			$opening_stock = $opening_stock_data[0]["quantity"];			
		}
		
		if(is_numeric($material_id))
		{
		$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type IN"=>array("grn","mrn","sst_from")])->hydrate(false)->toArray();
		}
		else
		{
			$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_name"=>$material_id,"type IN"=>array("grn","mrn","sst_from")])->hydrate(false)->toArray();
		}
		// debug($stockledger);die;
		if(isset($stockledger))
		{
			foreach($stockledger as $retrive_data)
			{
				$opening_stock = self::get_stockin_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
			}
		}
		return $opening_stock;
	}
	
	static function get_stockin_balance($type,$old_stock,$new_stock)
	{		
		switch($type)
		{
			// CASE "is":
				// return $old_stock - $new_stock;
			// break;
			
			CASE "mrn":
				return $old_stock + ( - $new_stock);
			break;
			
			// CASE "rbn":
				// return $old_stock + $new_stock;
			// break;
			
			CASE "grn":
				return $old_stock + $new_stock;
			break;
			
			CASE "sst_from":
				return $old_stock + ( - $new_stock);
			break;
			// CASE "sst_to":
				// return $old_stock + $new_stock;
			// break;
			
			default :
				return $old_stock + $new_stock;
		}
	}
	
	static function get_total_stockout($project_id,$material_id)
	{		
		$history_tbl = TableRegistry::get("erp_stock_history");
		// $opening_stock = 0;
		// if(is_numeric($material_id))
		// {
			// $opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_id"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		// }
		// else
		// {
			// $opening_stock_data = $history_tbl->find()->where(["project_id"=>$project_id,"material_name"=>$material_id,"type"=>"os"])->hydrate(false)->toArray();
		// }
		
		// if(!empty($opening_stock_data))
		// {
			// $opening_stock = $opening_stock_data[0]["quantity"];			
		// }
		$opening_stock = 0;
		if(is_numeric($material_id))
		{
		$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_id"=>$material_id,"type IN"=>array("is","rbn","rmc")])->hydrate(false)->toArray();
		}
		else
		{
			$stockledger = $history_tbl->find("all")->where(["project_id"=>$project_id,"material_name"=>$material_id,"type IN"=>array("is","rbn","rmc")])->hydrate(false)->toArray();
		}
		// debug($stockledger);die;
		if(isset($stockledger))
		{
			foreach($stockledger as $retrive_data)
			{
				$opening_stock = self::get_stockout_balance($retrive_data["type"],$opening_stock,$retrive_data["quantity"]);
			}
		}
		return $opening_stock;
	}
	
	static function get_stockout_balance($type,$old_stock,$new_stock)
	{		
		switch($type)
		{
			CASE "is":
				return $old_stock + $new_stock;
			break;
			
			CASE "rmc":
				return $old_stock + $new_stock;
			break;
			
			// CASE "mrn":
				// return $old_stock + $new_stock;
			// break;
			
			CASE "rbn":
				return $old_stock + ( - $new_stock);
			break;
			
			// CASE "grn":
				// return $old_stock + $new_stock;
			// break;
			
			// CASE "sst_from":
				// return $old_stock + $new_stock;
			// break;
			// CASE "sst_to":
				// return $old_stock + $new_stock;
			// break;
			
			default :
				return $old_stock + $new_stock;
		}
	}
}

