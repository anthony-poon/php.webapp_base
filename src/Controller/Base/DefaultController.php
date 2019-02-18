<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 16/2/2019
 * Time: 6:10 PM
 */

namespace App\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {
    /**
     * @Route("/", name="default_index")
     */
    public function index() {
        return $this->render("render/base/index.html.twig");
    }

}