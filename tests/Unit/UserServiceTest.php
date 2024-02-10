<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;



class UserServiceTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
        Storage::fake('public');

    }

    /** @test */
    public function it_can_return_a_paginated_list_of_users()
    {
        User::factory()->count(20)->create();

        $result = $this->userService->getPaginatedUsers(10);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(10, $result->items());
    }

    /** @test */
    public function it_can_store_a_user_with_a_photo_to_database()
    {
        $file = UploadedFile::fake()->image('user-photo.jpg');

        $userData = [
            'prefixname' => 'Mr',
            'firstname' => 'John',
            'middlename' => 'A',
            'lastname' => 'Doe',
            'suffixname' => 'Jr',
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'secretPassword', // This will be hashed by the service
            'photo' => $file,
            'type' => 'admin',
        ];

        $user = $this->userService->createUser($userData);

        $this->assertNotEquals('secretPassword', $user->password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('secretPassword', $user->password));

        $expectedPhotoPath = 'photos/' . $file->hashName();
        Storage::disk('public')->assertExists($expectedPhotoPath);
        $this->assertEquals($expectedPhotoPath, $user->photo);
    }

    /** @test */
    public function it_can_find_and_return_an_existing_user()
    {
        $createdUser = User::factory()->create();

        $foundUser = $this->userService->findUser($createdUser->id);

        $this->assertNotNull($foundUser, 'User should be found.');
        $this->assertEquals($createdUser->id, $foundUser->id, 'The found user ID should match the created user ID.');
        $this->assertEquals($createdUser->email, $foundUser->email, 'The found user email should match the created user email.');
    }

    /** @test */
    public function it_can_update_an_existing_user_with_all_details()
    {
        $existingUser = User::factory()->create();

        $newPhoto = UploadedFile::fake()->image('new-photo.jpg');

        $updateData = [
            'prefixname' => 'Mr',
            'firstname' => 'Jane',
            'middlename' => 'B',
            'lastname' => 'Smith',
            'suffixname' => 'Sr',
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'newSecretPassword',
            'photo' => $newPhoto,
            'type' => 'editor',
        ];

        $this->userService->updateUser($existingUser->id, $updateData);

        $updatedUser = User::findOrFail($existingUser->id);

        $this->assertEquals($updateData['prefixname'], $updatedUser->prefixname);
        $this->assertEquals($updateData['firstname'], $updatedUser->firstname);
        $this->assertEquals($updateData['middlename'], $updatedUser->middlename);
        $this->assertEquals($updateData['lastname'], $updatedUser->lastname);
        $this->assertEquals($updateData['suffixname'], $updatedUser->suffixname);
        $this->assertEquals($updateData['username'], $updatedUser->username);
        $this->assertEquals($updateData['email'], $updatedUser->email);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newSecretPassword', $updatedUser->password));
        $this->assertEquals($updateData['type'], $updatedUser->type);

        $expectedNewPhotoPath = 'photos/' . $newPhoto->hashName();
        Storage::disk('public')->assertExists($expectedNewPhotoPath);
        $this->assertEquals($expectedNewPhotoPath, $updatedUser->photo);
    }

    /** @test */
    public function it_can_soft_delete_an_existing_user()
    {
        // Arrange: Create a user to soft delete using User factory
        $user = User::factory()->create();

        // Act: Soft delete the user using the service
        $result = $this->userService->deleteUser($user->id);

        // Assert: Verify the user is soft deleted
        $this->assertTrue($result, 'User should be successfully soft deleted.');
        $this->assertSoftDeleted('users', ['id' => $user->id]);

        // Optionally, verify the user is still retrievable from the database as a soft deleted model
        $deletedUser = User::withTrashed()->find($user->id);
        $this->assertNotNull($deletedUser->deleted_at, 'The deleted_at column should not be null.');
    }

    /** @test */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {
        User::factory()->count(10)->create()->each(function ($user) {
            $user->delete();
        });

        $trashedUsers = $this->userService->getTrashedUsersPaginated(5);

        $this->assertInstanceOf(LengthAwarePaginator::class, $trashedUsers, 'Expected a LengthAwarePaginator instance.');
        $this->assertEquals(5, $trashedUsers->count(), 'Expected pagination to limit results to 5 per page.');

        foreach ($trashedUsers as $user) {
            $this->assertNotNull($user->deleted_at, 'Expected user to be soft deleted.');
        }
    }

    /** @test */
    public function it_can_restore_a_soft_deleted_user()
    {
        // Arrange: Create and soft-delete a user
        $user = User::factory()->create();
        $userId = $user->id;
        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $userId]);

        $result = $this->userService->restoreUser($userId);

        $this->assertTrue($result, 'User should be successfully restored.');

        $restoredUser = User::find($userId);

        $this->assertNotNull($restoredUser, 'The user should exist after being restored.');
        $this->assertNull($restoredUser->deleted_at, 'The user should not have a deleted_at timestamp after restoration.');
    }

    /** @test */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        $user = User::factory()->create();
        $userId = $user->id;
        $user->delete();

        $this->assertSoftDeleted('users', ['id' => $userId]);

        $result = $this->userService->forceDeleteUser($userId);

        $this->assertTrue($result, 'User should be successfully permanently deleted.');

        $deletedUser = User::withTrashed()->find($userId);
        $this->assertNull($deletedUser, 'The user should not exist in the database after permanent deletion.');
    }

    /** @test */
    public function it_can_upload_photo()
    {
        $user = User::factory()->create();

        $photo = UploadedFile::fake()->image('profile.jpg');


        $updatedUser = $this->userService->uploadUserPhoto($user->id, $photo);

        $this->assertNotNull($updatedUser->photo, 'User photo path should not be null.');

        $filename = basename($updatedUser->photo);


        Storage::disk('public')->assertExists("photos/{$filename}", 'Photo should exist in storage.');

    }

}
