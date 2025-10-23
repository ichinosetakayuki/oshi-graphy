<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Comment;

class CommentLiked extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public User $actor,
        public Comment $comment,
        public string $excerpt
    )
    {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'like',
            'target' => 'comment',
            'comment_id' => $this->comment->id,
            'diary_id' => $this->comment->diary_id ?? null,
            'actor_user_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
            'message' => "{$this->actor->name}さんがあなたのコメント「{$this->excerpt}」にいいねしました。",
            'url' => route('public.diaries.show', $this->comment->diary_id) . '#comment-' . $this->comment->id,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
