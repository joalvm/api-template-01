<?php

namespace App\Listeners;

use App\Events\DeletingPersonEvent;
use App\Exceptions\Users\CannotDeleteSuperAdminException;
use App\Interfaces\Users\UsersInterface;
use App\Models\User\User;

class PreventSuperAdminDeletionListener
{
    /**
     * Create the event listener.
     */
    public function __construct(public UsersInterface $usersRepository)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(DeletingPersonEvent $event): void
    {
        $userModel = $this->getUserModel($event->personModel->id);

        if (!$userModel) {
            return;
        }

        if ($userModel->super_admin and !$event->isSuperAdmin) {
            throw new CannotDeleteSuperAdminException();
        }
    }

    public function getUserModel(int $personId): ?User
    {
        try {
            return $this->usersRepository->getModelByPerson($personId);
        } catch (\Exception $e) {
            return null;
        }
    }
}
