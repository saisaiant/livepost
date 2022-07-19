<?php 

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository {

    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = User::query()->create([
                'name' => data_get($attributes, 'name'),
                'email' => data_get($attributes, 'email'),
            ]);
            return $created;
        });
    }

    public function update(User $user, array $attributes)
    {
        return DB::transaction(function() use ($user, $attributes) {
            $updated = $user->update([
                'name' => data_get($attributes , 'name', $user->name),
                'email' => data_get($attributes, 'email', $user->email),
            ]);

            if(!$updated) {
                throw new \Exception('Failed to update user');
            }

            return $user;
        });
    }

    public function forceDelete(User $user)
    {
        return DB::transaction(function () use ($user) {
            $deleted = $user->forceDelete();

            if(!$deleted) {
                throw new \Exception('Cannot delete the user.');
            }
            return $deleted;
        });
    }

}


