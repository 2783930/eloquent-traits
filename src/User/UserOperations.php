<?php

namespace EloquentTraits\User;

use Illuminate\Support\Facades\Hash;

/**
 * @property string|int id
 * @property string password
 * @property string second_password
 */
trait UserOperations
{
    private string $secondPasswordSuffix = 'second';

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function setUserPassword($password = null): static
    {
        if (!empty($password)) {
            $this->password = Hash::make(
                $password . $this->id
            );
        }

        return $this;
    }

    public function checkUserPassword($password): bool
    {
        return Hash::check(
            $password . $this->id,
            $this->password
        );
    }

    public function setUserSecondPassword($password = null): static
    {
        if (!empty($password)) {
            $this->second_password = Hash::make(
                $password . $this->id . str($this->secondPasswordSuffix)->reverse()
            );
        }

        return $this;
    }

    public function checkUserSecondPassword($password): bool
    {
        return Hash::check(
            $password . $this->id . str($this->secondPasswordSuffix)->reverse(),
            $this->second_password
        );
    }

    public function setUserIsAdmin(): static
    {
        $this->privileges_code = Hash::make(
            $this->id . "-is-admin"
        );
        return $this;
    }

    public function clearUserIsAdmin(): static
    {
        $this->privileges_code = null;
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Virtual Attributes
    |--------------------------------------------------------------------------
    */

    public function getIsAdminAttribute(): bool
    {
        return Hash::check(
            $this->id . "-is-admin",
            $this->privileges_code
        );
    }
}