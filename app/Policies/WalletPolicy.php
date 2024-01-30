<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wallet;

class WalletPolicy
{
    public function before(User $user)
    {
        return $user->is_admin ? true : null;
    }

    public function own(User $user, Wallet $wallet)
    {
        return $wallet->user_id == $user->id;
    }
}
