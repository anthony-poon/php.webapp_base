<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 1/11/2018
 * Time: 10:48 AM
 */

namespace App\Service;

use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class JsonValidator {
    private $validator;
    private $allowExtraFields = false;
    private $allowMissingFields = false;
    private $errors = [];
    public function __construct(ValidatorInterface $validator){
        $this->validator = $validator;
    }

    /**
     * @param array $json
     * @param array $constraint
     * @return bool
     */
    public function validate(array $json, array $constraint): bool {
        $this->errors = [];
        $errors = $this->validator->validate($json, new Assert\Collection([
            "fields" => $constraint,
            "allowExtraFields" => $this->allowExtraFields,
            "allowMissingFields" => $this->allowMissingFields,
        ]));

        foreach ($errors as $error) {
            $this->errors[] = $error;
        }

        if (0 === count($this->errors)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $entity
     * @return bool
     */
    public function validateEntity($entity): bool {
        $this->errors = [];
        $errors = $this->validator->validate($entity);

        foreach ($errors as $error) {
            $this->errors[] = $error;
        }

        if (0 === count($this->errors)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isAllowExtraFields(): bool {
        return $this->allowExtraFields;
    }

    /**
     * @param bool $allowExtraFields
     * @return JsonValidator
     */
    public function setAllowExtraFields(bool $allowExtraFields): JsonValidator {
        $this->allowExtraFields = $allowExtraFields;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowMissingFields(): bool {
        return $this->allowMissingFields;
    }

    /**
     * @param bool $allowMissingFields
     * @return JsonValidator
     */
    public function setAllowMissingFields(bool $allowMissingFields): JsonValidator {
        $this->allowMissingFields = $allowMissingFields;
        return $this;
    }


}