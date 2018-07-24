<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 20/7/2018
 * Time: 5:41 PM
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class SecurityGroup extends DirectoryGroup {
	/**
	 * @var string
	 * @ORM\Column(type="string", length=256, unique=true)
	 */
	private $siteToken;

	/**
	 * @return string
	 */
	public function getSiteToken(): string {
		return $this->siteToken;
	}

	/**
	 * @param string $siteToken
	 * @return SecurityGroup
	 */
	public function setSiteToken(string $siteToken): SecurityGroup {
		$this->siteToken = $siteToken;
		return $this;
	}

	public function getFriendlyName(): string {
		return $this->getName();
	}

	public function getFriendlyClassName(): string {
		return "Security Group";
	}
}