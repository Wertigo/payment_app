<?php

namespace App\Repositories;

use App\User;

class UserRepository
{
    /**
     * @param int $id
     * @param bool $withWallet
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|User
     */
    public static function findById($id, $withWallet = false)
    {
        if ($withWallet) {
            return User::with('wallet')->findOrFail($id);
        }

        return User::findOrFail($id);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function findAllForReport()
    {
        return User::pluck('name', 'name');
    }

    /**
     * @param $name
     * @param bool $withWallet
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|User
     */
    public static function findByName($name, $withWallet = false)
    {
        if ($withWallet) {
            User::with('wallet')
                ->where('name', '=', $name)
                ->firstOrFail()
            ;
        }

        return User::where('name', '=', $name)->firstOrFail();
    }
}