<?php 

namespace App\Repositories;

use App\Events\Models\User\UserCreated;
use App\Events\Models\User\UserDeleted;
use App\Events\Models\User\UserUpdated;
use App\Exceptions\GeneralJsonException;
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

            throw_if(!$created, GeneralJsonException::class, 'Failed to create model.');
            event(new UserCreated($created));
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

            // if(!$updated) {
            //     throw new \Exception('Failed to update user');
            // }
            throw_if(!$updated, GeneralJsonException::class, 'Failed to update user.');
            event(new UserUpdated($user));

            return $user;
        });
    }

    public function forceDelete(User $user)
    {
        return DB::transaction(function () use ($user) {
            $deleted = $user->forceDelete();

            // if(!$deleted) {
            //     throw new \Exception('Cannot delete the user.');
            // }
            throw_if(!$deleted, GeneralJsonException::class, 'Cannot delete user.');
            event(new UserDeleted($user));
        
            return $deleted;
        });
    }

}


