<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 30/7/2018
 * Time: 11:55 AM
 */

namespace App\Entity\Task;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ApprovalTaskAbstract
 * @package App\Entity\Task
 * @ORM\Entity()
 * @ORM\Table(name="approval_task")
 */
abstract class ApprovalTaskAbstract extends TaskAbstract {
	const STATUS_PENDING = "pending";
	const STATUS_APPROVED = "approved";
	const STATUS_REJECTED = "rejected";
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $status = "pending";

	public function approve() {
		$this->status = self::STATUS_APPROVED;
		$this->isDone = true;
	}

	public function reject() {
		$this->status = self::STATUS_REJECTED;
		$this->isDone = true;
	}

	public function execute() {
		switch ($this->status) {
			case self::STATUS_APPROVED:
				$this->onApprove();
				break;
			case self::STATUS_REJECTED:
				$this->onReject();
				break;
		}
	}

	abstract function onApprove();

	abstract function onReject();
}