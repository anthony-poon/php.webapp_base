<?php
namespace App\Service;

use App\Entity\SiteUser;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BaseTemplateHelper {
    private $sideMenu = [];
    private $title = "Web Application";
    private $jsParam = [];
	private $user = null;
    public function __construct(ParameterBagInterface $paramsBag, RouterInterface $router, TokenStorageInterface $tokenStorage) {
		$token = $tokenStorage->getToken();
		if ($token) {
			$this->user = $token->getUser();
		}
    	$this->sideMenu = [
        	[
        		"text" => "Home",
				"icon" => "home",
				"url" => $router->generate("home")
			], [
				"text" => "Friend List",
				"url" => $router->generate("social_list_friend"),
				"isVisible" => $this->user instanceof SiteUser
			]
		];
    }

    /**
     * @return array
     */
    public function getSideMenu(): array {
        return $this->sideMenu;
    }

    public function addSideMenuItem(array $item) {
        $this->sideMenu[] = $item;
        return $this;
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
     * @return array
     */
    public function getJsParam(): array {
        return $this->jsParam;
    }

    /**
     * @param array $jsParam
     * @return BaseTemplateHelper
     */
    public function setJsParam(array $jsParam): BaseTemplateHelper {
        $this->jsParam = $jsParam;
        return $this;
    }

}