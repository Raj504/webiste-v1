<?php

namespace App\Mail;

use App\Models\GymMember;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MembershipReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public GymMember $member;

    public function __construct(GymMember $member)
    {
        $this->member = $member;
    }

    public function build()
    {
        return $this->subject('Your membership at ' . $this->member->gym->name . ' is due for renewal')
            ->view('emails.membership-reminder');
    }
}
