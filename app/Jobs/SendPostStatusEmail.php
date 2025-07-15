<?php

namespace App\Jobs;

use App\Mail\PostStatusChanged;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendPostStatusEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Post $post) {}

    public function handle(): void
    {
        if ($this->post->user && $this->post->user->email) {
            Mail::to($this->post->user->email)
                ->send(new PostStatusChanged($this->post));
        }
    }
}
