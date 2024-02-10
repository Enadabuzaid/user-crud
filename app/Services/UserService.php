<?php

namespace App\Services;

use App\Models\Detail;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{

    /**
     * Get a paginated list of users.
     *
     * @param int $perPage Items per page
     * @return LengthAwarePaginator
     */
    public function getPaginatedUsers(int $perPage = 15): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    /**
     * Create a new user with the provided data.
     *
     * @param  array  $userData
     * @return \App\Models\User
     */
    public function createUser(array $userData): User
    {
        $userData['password'] = Hash::make($userData['password']);

        if (isset($userData['photo']) && $userData['photo']->isValid()) {
            $path = $userData['photo']->store('photos', 'public');
            $userData['photo'] = $path;
        } else {
            unset($userData['photo']);
        }

        $user = User::create($userData);

        return $user;

    }

    /**
     * Find and return a user by ID.
     *
     * @param  int  $id
     * @return \App\Models\User|null
     */
    public function findUser(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Update an existing user with the provided data.
     *
     * @param  int    $id
     * @param  array  $userData
     * @return \App\Models\User
     */
    public function updateUser(int $id, array $userData): User
    {
        $user = User::findOrFail($id);

        if (isset($userData['photo']) && $userData['photo']->isValid()) {
            $path = $userData['photo']->store('photos', 'public');
            $userData['photo'] = $path;
        } else {
            unset($userData['photo']);
        }


        $user->update($userData);
        return $user;
    }

    /**
     * Soft delete a user by ID.
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        $user = User::find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }

    /**
     * Get a paginated list of trashed (soft-deleted) users.
     *
     * @param int $perPage Items per page
     * @return LengthAwarePaginator
     */
    public function getTrashedUsersPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return User::onlyTrashed()->paginate($perPage);
    }

    /**
     * Restore a soft-deleted user by ID.
     *
     * @param  int  $id
     * @return bool
     */
    public function restoreUser(int $id): bool
    {
        $user = User::onlyTrashed()->find($id);
        if ($user) {
            return $user->restore();
        }
        return false;
    }

    /**
     * Permanently delete a soft-deleted user by ID.
     *
     * @param  int  $id
     * @return bool
     */
    public function forceDeleteUser(int $id): bool
    {
        $user = User::onlyTrashed()->find($id);
        if ($user) {
            return $user->forceDelete();
        }
        return false;
    }

    /**
     * Upload a photo for a user and update the user record.
     *
     * @param int $userId
     * @param \Illuminate\Http\UploadedFile $photoFile
     * @return \App\Models\User
     */
    public function uploadUserPhoto(int $userId, $photoFile): User
    {
        $user = User::findOrFail($userId);

        if ($photoFile->isValid()) {
            $path = $photoFile->store('photos', 'public');
            $user->update(['photo' => $path]);
        }

        return $user;
    }

    /**
     * Save user details.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function saveUserDetails(User $user)
    {
        $fullName = $user->prefixname . ' ' . $user->firstname . ' ' . $user->middlename . ' ' . $user->lastname . ' ' . $user->suffixname;
        $middleInitial = $user->middlename ? strtoupper(substr($user->middlename, 0, 1)) . '.' : null;
        $avatar = $user->photo;
        $gender = $user->prefixname == 'Mr.' ? 'male' : ($user->prefixname == 'Ms.' ? 'female' : null);

        Detail::create([
            'user_id' => $user->id,
            'key' => 'full_name',
            'value' => $fullName,
        ]);

        Detail::create([
            'user_id' => $user->id,
            'key' => 'middle_initial',
            'value' => $middleInitial,
        ]);

        Detail::create([
            'user_id' => $user->id,
            'key' => 'avatar',
            'value' => $avatar,
        ]);

        Detail::create([
            'user_id' => $user->id,
            'key' => 'gender',
            'value' => $gender,
        ]);
    }
}
