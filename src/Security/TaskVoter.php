<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    const DELETE = 'delete';

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Task $task */
        $task = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($task, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canDelete(Task $task, User $user): bool
    {
        return $user === $task->getAuthor();
    }
}