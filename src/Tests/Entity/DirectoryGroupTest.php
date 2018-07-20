<?php
namespace App\Tests\Entity;

use App\Entity\DirectoryGroup;
use App\Entity\DirectoryObject;
use App\Entity\DirectoryRelation;
use PHPUnit\Framework\TestCase;

class DirectoryGroupTest extends TestCase {

	private function generateDirGroup(int $width, int $depth, array &$flatten = null) {
		$rtn = [];
		for ($i = 0; $i < $width && $depth > 0; $i++) {
			if (rand(0, $depth) <= 1) {
				$obj = new DirectoryObject();
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
				/* @var \App\Entity\DirectoryGroup $DirObj */
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
				$this->printDirGrp($dirObj->getMembers()->toArray(), $depth + 1);
			}
		}
	}

    function testGetEffectiveRelation() {
    	$src = [
    		"relation_1" => [],
			"relation_2" => [],
		];
		$owner = new DirectoryObject();
		$flatten = [];
    	foreach ($src as $relationName => &$arr) {
    		$width = 5;
    		$depth = 5;
			$flatten[$relationName] = [];
    		$arr = $this->generateDirGroup($width, $depth, $flatten[$relationName]);
    		foreach ($arr as $a) {
    			/* @var \App\Entity\DirectoryObject $a */
    			$r = new DirectoryRelation();
    			$r->setOwner($owner);
    			$r->setType($relationName);
    			$r->setTarget($a);
    			$owner->getRelations()->add($r);
			}
		}
    	foreach ($flatten as $relationName => $arr) {
			$haystack = $owner->getEffectiveRelation($relationName);
			foreach ($arr as $obj) {
				$this->assertContains($obj, $haystack);
			}
		}
    	/**
    	foreach ($src as $relateName => $arr) {
    		echo "Relations $relateName\n";
    		$this->printDirGrp($arr);
		}
		**/
    }

	function testGetEffectiveInverseRelation() {
		$src = [
			"relation_1" => [],
			"relation_2" => [],
		];
		$target = new DirectoryObject();
		$flatten = [];
		foreach ($src as $relationName => &$arr) {
			$width = 5;
			$depth = 5;
			$flatten[$relationName] = [];
			$arr = $this->generateDirGroup($width, $depth, $flatten[$relationName]);
			foreach ($arr as $a) {
				/* @var \App\Entity\DirectoryObject $a */
				$r = new DirectoryRelation();
				$r->setOwner($a);
				$r->setType($relationName);
				$r->setTarget($target);
				$target->getInverseRelations()->add($r);
			}
		}
		foreach ($flatten as $relationName => $arr) {
			$haystack = $target->getEffectiveRelation($relationName, true);
			foreach ($arr as $obj) {
				$this->assertContains($obj, $haystack);
			}
		}
	}

    function testArrayAccess() {
		$u1 = new DirectoryObject();
		$subG1 = new DirectoryGroup();
		$subU1 = new DirectoryObject();
		$subG1[0] = $subU1;
		$grp = new DirectoryGroup();
		$grp[0] = $u1;
		$grp[1] = $subG1;
		$this->assertSame($u1, $grp[0]);
		$this->assertSame($subU1, $grp[1][0]);
	}

	function testTraversable() {
		$g = new DirectoryGroup();
		$r = rand(5,10);
		for ($i = 0; $i < $r; $i++) {
			$g[] = new DirectoryObject();
		}
		$count = 0;
		foreach ($g as $obj) {
			$count++;
		}
		$this->assertEquals($count, count($g));
	}

}