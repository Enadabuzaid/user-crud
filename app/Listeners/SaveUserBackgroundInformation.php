<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserSaved;
use App\Services\UserService;


class SaveUserBackgroundInformation implements ShouldQueue
{
    /**
     * The UserService instance.
     *
     * @var \App\Services\UserService
     */
    protected $userService;

    /**
     * Create the event listener.
     *
     * @param  \App\Services\UserService  $userService
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    /**
     * Handle the event.
     */
    public function handle(UserSaved $event): void
    {
        $this->userService->saveUserDetails($event->user);
    }
}
