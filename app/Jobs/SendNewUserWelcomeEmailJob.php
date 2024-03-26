<?php

namespace App\Jobs;

use App\Mail\UserWelcomeEmail;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewUserWelcomeEmailJob // implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user, public ?string $redirectUrl)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->loadMissing('person');

        Mail::to($this->user->email)
            ->send(new UserWelcomeEmail($this->user, $this->redirectUrl))
        ;
    }
}
