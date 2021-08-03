<?php 
namespace App\Model\Table;
use Cake\ORM\Table;

Class AttendanceDetailTable extends Table{
	
	public function initialize(array $config)
	{
		$this->addBehavior('Timestamp');
		$this->table("erp_attendance_detail");
		$this->BelongsTo("erp_users",["foreignKey"=>"user_id"]);
	}
	
}