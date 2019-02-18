<?php
namespace App\Service;

use App\Entity\Base\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class BaseTemplateHelper {
    private $sideMenu = [];
    private $sideMenuStyle;
    private $navMenu = [];
    private $layout;
    private $title = "Web Application";
    private $security;
    public function __construct(TokenStorageInterface $storage, RouterInterface $router, ParameterBagInterface $params, Security $security) {
        $this->sideMenuStyle = $params->get("side_menu_style");
        $this->layout = $params->get("layout");
		$this->security = $security;
		$token = $storage->getToken();
        $isLoggedIn = false;
		if ($token) {
		    $user = $token->getUser();
		    $isLoggedIn = $user instanceof User;
        }

    	$this->navMenu = [
		];
        $this->sideMenu = [
            [
                "text" => "Home",
                "icon" => "home",
                "url" => $router->generate("default_index"),
            ],
            [
                "text" => "User Administration",
                "url" => $router->generate("user_list"),
                "hide" => !$isLoggedIn || !$security->isGranted("ROLE_ADMIN")
            ],
            [
                "text" => "Logout",
                "url" => $router->generate("security_logout"),
                "hide" => !$isLoggedIn
            ]
        ];
    }

    /**
     * @return array
     */
    public function getNavMenu(): array {
        return $this->navMenu;
    }

    public function addNavMenu(array $item) {
        $this->navMenu[] = $item;
        return $this;
    }

    public function setNavMenu(array $menu) {
        $this->navMenu = $menu;
    }

    /**
     * @return array
     */
    public function getSideMenu(): array {
        return $this->sideMenu;
    }

    public function addSideMenu(array $item) {
        $this->sideMenu[] = $item;
        return $this;
    }

    public function setSideMenu(array $menu) {
        $this->sideMenu = $menu;
    }

    /**
     * @return string
     */
    public function getSideMenuStyle(): string
    {
        return $this->sideMenuStyle;
    }

    /**
     * @param mixed $sideMenuStyle
     */
    public function setSideMenuStyle($sideMenuStyle) {
        $this->sideMenuStyle = $sideMenuStyle;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @param string $title
     * @return BaseTemplateHelper
     */
    public function setTitle(string $title): BaseTemplateHelper {
        $this->title = $title;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param mixed $layout
     */
    public function setLayout($layout) {
        $this->layout = $layout;
    }


}