<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Listeners\SaveUserBackgroundInformation;
use App\Events\UserSaved;
use App\Models\User;
use App\Services\UserService;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;


class SaveUserBackgroundInformationTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_processes_user_saved_event_correctly()
    {
        $mockUserService = Mockery::mock(UserService::class);
        $mockUserService->shouldReceive('saveUserDetails')
            ->once()
            ->with(Mockery::on(function ($user) {
                return $user instanceof User;
            }));

        $listener = new SaveUserBackgroundInformation($mockUserService);
        $user = new User(['id' => 1]);
        $event = new UserSaved($user);

        $listener->handle($event);
    }

}
