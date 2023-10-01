<?php

namespace App\Observers;

use App\Mail\UserNotification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    public function created(User $user): void
    {
        Mail::to(env('MAIL_TO_ADDRESS'))->send(new UserNotification($user, "O usuário {$user->name} foi criado!"));
    }

    public function updated(User $user): void
    {
        Mail::to(env('MAIL_TO_ADDRESS'))->send(new UserNotification($user, "O usuário {$user->name} foi atualizado!"));
    }

    public function deleted(User $user): void
    {
        Mail::to(env('MAIL_TO_ADDRESS'))->send(new UserNotification($user, "O usuário {$user->name} foi deletado!"));
    }
}
