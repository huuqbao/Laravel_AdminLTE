<?php

namespace App\Jobs;

use App\Mail\DemoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $email;
    protected string $name;

    public function __construct(string $email, string $name)
    {
        $this->email = $email;
        $this->name = $name;
    }

    public function handle(): void
    {
        Mail::to($this->email)->send(new DemoMail($this->name));
    }
}
