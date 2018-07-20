<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 20/7/2018
 * Time: 11:41 AM
 */

namespace App\Entity\Enum;
use MabeEnum\Enum;

class UserRoleEnum extends Enum{
	const ADMIN = "ROLE_ADMIN";
	const ROLE_1 = "ROLE_1";
	const ROLE_2 = "ROLE_2";
}