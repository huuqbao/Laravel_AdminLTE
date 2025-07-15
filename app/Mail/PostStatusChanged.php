<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Post $post) {}

    public function build()
    {
        return $this->subject("Trạng thái bài viết đã thay đổi")
                    ->view('emails.post_status') 
                    ->with(['post' => $this->post]);
    }
}
