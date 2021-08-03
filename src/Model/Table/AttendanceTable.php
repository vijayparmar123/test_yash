<?php 
namespace App\Model\Table;
use Cake\ORM\Table;

Class AttendanceTable extends Table{
	
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		$this->table("erp_attendance");
		$this->BelongsTo("erp_users",["foreignKey"=>"user_id"]);
		$this->BelongsTo("AttendanceDetail");
	}
	
}