<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 28/10/2018
 * Time: 5:34 PM
 */

namespace App\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SecurityAjaxController extends Controller {

    /**
     * @Route("/ajax/security/login", name="security_ajax_login")
     */
    public function login() {
        $user = $this->getUser();
        return $this->json(array(
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ));
    }

}