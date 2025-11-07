<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * @deprecated Use CommentOrReplyNotification instead.
 */

class CommentPostedOnYourDiary extends Notification implements ShouldQueue
{

}
