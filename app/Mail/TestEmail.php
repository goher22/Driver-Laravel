<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Test Email')
                    ->markdown('vendor.notifications.email')->with([
                        "level" => "default",
                        "greeting" => "Congratulations!",
                        "introLines" => [
                            "Test email was sent successfully."
                        ],
                        "outroLines" => [
                            "Your e-mail settings have been successfully configured."
                        ]
                    ]);
    }
}
