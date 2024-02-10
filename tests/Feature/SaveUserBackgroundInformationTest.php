<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event; // Ensure this facade is imported
use Illuminate\Support\Facades\Queue;
use App\Models\User;
use App\Events\UserSaved;
use App\Services\UserService;
use App\Listeners\SaveUserBackgroundInformation;

class SaveUserBackgroundInformationTest extends TestCase
{
    use RefreshDatabase;

    public function testSaveUserBackgroundInformationListener()
    {
        Event::fake();

        $user = User::factory()->create();

        $userServiceMock = $this->createMock(UserService::class);
        $userServiceMock->expects($this->once())
            ->method('saveUserDetails')
            ->with($this->equalTo($user));

        $listener = new SaveUserBackgroundInformation($userServiceMock);
        $listener->handle(new UserSaved($user));
    }
}
