<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 24/7/2018
 * Time: 10:33 AM
 */

namespace App\FormType\Constraint;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueCollectionValueValidator extends ConstraintValidator {
	public function validate($value, Constraint $constraint) {
		if (!$value instanceof Collection && !is_array($value)) {
			throw new UnexpectedTypeException($value, Collection::class);
		}
		if ($value instanceof Collection) {
			$arr = $value->toArray();
		} else {
			$arr = $value;
		}
		// $a == $b: Have same key/value pairs
		// $a === $b: Have same key/value pairs in the same order and of the same type
		// if $value is unique, array_unique should have no effect, else it is not unique;
		if (array_unique($arr, SORT_REGULAR) != $arr) {
			$this->context->buildViolation($constraint->message)
				->addViolation();
		}
	}

}