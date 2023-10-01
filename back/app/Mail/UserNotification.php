<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected User $user, 
        protected string $content
    ){}

    public function build()
    {
        return $this->subject('Notificação sobre usuário')
                    ->view('mails.user-notification')
                    ->with([
                        'user' => $this->user,
                        'content' => $this->content,
                    ]);
    }
}
