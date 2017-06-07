<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Notifications\Messages\MailMessage;

class UserInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $invitedUser;
    public function __construct(User $invited)
    {
        //
        $this->invitedUser=$invited;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       $user = Auth::user();
       
       return   $this
                    ->subject('Invite to create account.')
                    ->from( ['address' => $user->email, 'name' => $user->name])
                    ->markdown('vendor.notifications.email')->with([
                        "level" => "default",
                        "greeting" => "Hi, ".$this->invitedUser->name."!",
                        "introLines" => [
                            "You have been invited to json delivery platform. Please follow the link below to continue registration."
                        ],
                        "actionText" => "Redeem Invitation",
                        "actionUrl" => url('/accept/'.$this->invitedUser->invite_token),
                        "outroLines" => [
                            "Attention!: This Invitation is valid for 24 hours."
                        ]
                    ]);
       
    }
}
