<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 24/7/2018
 * Time: 10:31 AM
 */

namespace App\FormType\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueCollectionValue
 * @package App\FormType\Constraint
 * @Annotation
 */
class UniqueCollectionValue extends Constraint {
	public $message = "The provided set contained duplicated value(s).";
}