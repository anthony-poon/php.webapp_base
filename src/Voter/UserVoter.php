<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 1/11/2018
 * Time: 2:59 PM
 */

namespace App\Voter;


use App\Entity\Base\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter {
    const CREATE = "entity_user_create";
    const READ = "entity_user_read";
    const UPDATE = "entity_user_update";
    const DELETE = "entity_user_delete";
    protected function supports($attribute, $subject){
        switch ($attribute) {
            case self::CREATE:
            case self::READ:
                // CR can have null subject
                return true;
            case self::UPDATE:
            case self::DELETE:
                // UD must specify subject
                return $subject instanceof User;
            default:
                return false;
        }
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token) {
        /* @var $subject \App\Entity\Base\User */
        $user = $token->getUser();
        switch ($attribute) {
            case self::CREATE:
                return true;
            case self::READ:
                if ($subject) {
                    if (in_array("ROLE_ADMIN", $subject->getRoles())) {
                        // Only admin can read admin account
                        return $user instanceof User && in_array("ROLE_ADMIN", $user->getRoles());
                    }
                    // If not admin account, anyone can read
                    return true;
                } else {
                    // If subject is not specified, any logged in user can read other user
                    return $user instanceof User;
                }
                break;
            case self::UPDATE:
                if ($user instanceof User && in_array("ROLE_ADMIN", $user->getRoles())) {
                    // If is admin, can update all user
                    return true;
                } else {
                    // Else can only update self if logged in
                    return $user instanceof User && $subject->getId() == $user->getId();
                }
                break;
            case self::DELETE:
                // Only admin can delete
                return $user instanceof User && in_array("ROLE_ADMIN", $user->getRoles());
        }
        return false;
    }


}