<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 30/7/2018
 * Time: 11:45 AM
 */

namespace App\Entity\Task;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class TaskAbstract
 * @package App\Entity\Task
 * @ORM\Entity()
 * @ORM\Table(name="task_abstract")
 * @ORM\HasLifecycleCallbacks()
 */
abstract class TaskAbstract {
	/**
	 * @var int
	 * @ORM\Column(type="integer", length=11)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $isDone = false;

	/**
	 * @ORM\PrePersist()
	 */
	abstract function execute();
}