<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class User extends Entity
{
	protected $_accessible = ['*'=> true,'user_id' => false];

	protected function _setPassword($password)
	{
		return (new DefaultPasswordHasher)->hash($password);
	}
}

?>