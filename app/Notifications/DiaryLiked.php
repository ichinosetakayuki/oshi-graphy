<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DiaryLiked extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(

        public int $diaryId,
        public int $actorUserId,
        public string $actorName,
        public string $excerpt
    )
    {
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'like',
            'diary_id' => $this->diaryId,
            'actor_user_id' => $this->actorUserId,
            'actor_name' => $this->actorName,
            'message' => "{$this->actorName}さんがあなたの日記「{$this->excerpt}」にいいねしました。",
            'url' => route('diaries.show', $this->diaryId),
        ];
    }

}
