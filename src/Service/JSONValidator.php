<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 1/11/2018
 * Time: 10:48 AM
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class JSONValidator {
    private $validator;
    public function __construct(ValidatorInterface $validator){
        $this->validator = $validator;
    }

    public function validate(array $json, array $constraint): JsonResponse {
        $errors = $this->validator->validate($json, new Assert\Collection($constraint));
        if (0 == count($errors)) {
            return new JsonResponse([
                "status" => "success"
            ]);
        } else {
            $rtn = [
                "status" => "failure"
            ];
            foreach ($errors as $error) {
                /* @var $error \Symfony\Component\Validator\ConstraintViolation */
                $rtn["errors"][] = [
                    "propertyPath" => $error->getPropertyPath(),
                    "message" => $error->getMessage()
                ];
            }
            return new JsonResponse($rtn, 400);
        }
    }

    public function validateEntity($entity): JsonResponse {
        $errors = $this->validator->validate($entity);
        if (0 == count($errors)) {
            return new JsonResponse([
                "status" => "success"
            ]);
        } else {
            $rtn = [
                "status" => "failure"
            ];
            foreach ($errors as $error) {
                /* @var $error \Symfony\Component\Validator\ConstraintViolation */
                $rtn["errors"][] = [
                    "propertyPath" => $error->getPropertyPath(),
                    "message" => $error->getMessage()
                ];
            }
            return new JsonResponse($rtn, 500);
        }
    }
}