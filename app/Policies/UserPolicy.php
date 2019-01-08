<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function managerActions(User $user)
    {
       return $user->isManager();
    }

    public function authUserActions(User $user, User $model)
    {
        return $user->id == $model->id;
    }

    public function update(User $user, User $model)
    {
        return $user->isManager() || $user->id == $model->id;
    }

    public function alterAccess(User $user, User $model)
    {
        return $user->isManager() && $user->id != $model->id;
    }

    public function getFreeTables(User $user)
    {
        return $user->isManager() || $user->isWaiter();
    }

    public function allUsers(User $user)
    {
        return $user !== null;
    }

    public function waiterActions(User $user)
    {
       return $user->isWaiter();
    }

    public function cookActions(User $user)
    {
       return $user->isCook();
    }

    public function changeOrderState(User $user)
    {
       return $user->isWaiter() || $user->isCook();
    }

    public function downloadPDF(User $user)
    {
        return $user->isManager() || $user->isCashier();
    }
}
