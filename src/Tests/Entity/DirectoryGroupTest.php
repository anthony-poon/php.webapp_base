<?php
namespace App\Tests\Entity;

use App\Entity\Base\DirectoryGroup;
use App\Entity\Base\SecurityGroup;
use PHPUnit\Framework\TestCase;
use App\Entity\Base\User;

class DirectoryGroupTest extends TestCase {

	private function generateDirGroup(int $width, int $depth, array &$flatten = null) {
		$rtn = [];
		for ($i = 0; $i < $width && $depth > 0; $i++) {
			if (rand(0, $depth) <= 1) {
				$obj = new User();
				$rtn[] = $obj;
				if ($flatten !== null) {
					$flatten[] = $obj;
				}
			} else {
				$rtn[] = new DirectoryGroup();
			}
		}
		foreach ($rtn as &$dirObj) {
			if ($dirObj instanceof DirectoryGroup) {
				/* @var \App\Entity\Base\DirectoryGroup $DirObj */
				$subMember = $this->generateDirGroup(rand(1, $width), $depth - 1, $flatten);
				foreach ($subMember as $m) {
					$dirObj->getMembers()->add($m);
				}
			}
		}
		return $rtn;
	}

	private function printDirGrp($arr, int $depth = 0) {
		foreach ($arr as $dirObj) {
			for ($i = 0; $i < $depth; $i ++) {
				echo "\t";
			}
			echo get_class($dirObj)."\n";
			if ($dirObj instanceof DirectoryGroup) {
				$this->printDirGrp($dirObj->getChildren()->toArray(), $depth + 1);
			}
		}
	}

	public function testGetParentRecursive() {
		$grp1 = new DirectoryGroup();
		$grp1->setName("grp_1");
		$grp2 = new DirectoryGroup();
		$grp2->setName("grp_2");
		$grp3 = new DirectoryGroup();
		$grp3->setName("grp_3");
		// grp_1 is parent of grp_2
		$grp2->getParents()->add($grp1);
		// grp_2 is parent of grp_2
		$grp3->getParents()->add($grp2);
		// grp_2 recursively point to self, assert no stack overflow
		$grp2->getParents()->add($grp3);
		// grp_1 recursively point to grp_2, assert no stack overflow
		$grp1->getParents()->add($grp2);
		$flattened = $grp3->getParentsRecursive();
		$this->assertContains($grp1, $flattened, "", "", true);
		$this->assertContains($grp2, $flattened, "", "", true);
		// assert the final result do not have reference to self
		$this->assertNotContains($grp3, $flattened, "", "", true);
	}

	public function testGetSiteToken() {
		$grp1 = new SecurityGroup();
		$grp1->setSiteToken("token_1")->setName("grp_1");
		$grp2 = new SecurityGroup();
		$grp2->setSiteToken("token_2")->setName("grp_2");
		$grp3 = new SecurityGroup();
		$grp3->setSiteToken("token_3")->setName("grp_3");
		$grp4 = new SecurityGroup();
		$grp4->setSiteToken("token_4")->setName("grp_4");
		$grp3->getParents()->add($grp1);
		$grp3->getParents()->add($grp2);
		$grp4->getParents()->add($grp3);
		$this->assertContains($grp4->getSiteToken(), [
			"token_1",
			"token_2",
			"token_3",
			"token_4",
		]);
	}
}