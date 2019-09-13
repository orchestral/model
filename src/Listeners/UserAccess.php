<?php

namespace Orchestra\Model\Listeners;

use Illuminate\Support\Collection;
use Orchestra\Model\User;

class UserAccess
{
    /**
     * Match current user to roles.
     *
     * @param  \Orchestra\Model\User|null  $user
     *
     * @return \Illuminate\Support\Collection|null
     */
    public function handle(?User $user): ?Collection
    {
        // When user is null, we should expect the roles is not available.
        // Therefore, returning null would propagate any other event
        // listeners (if any) to try resolve the roles.

        return ! \is_null($user) ? $user->getRoles() : null;
    }
}
