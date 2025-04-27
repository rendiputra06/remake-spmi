<?php

namespace App\Notifications;

use App\Models\Audit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuditReportNotification extends Notification
{
    use Queueable;

    protected $audit;
    protected $additionalMessage;

    /**
     * Create a new notification instance.
     */
    public function __construct(Audit $audit, string $additionalMessage = null)
    {
        $this->audit = $audit;
        $this->additionalMessage = $additionalMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('filament.admin.resources.audits.view', ['record' => $this->audit->id]);

        return (new MailMessage)
            ->subject('Laporan Audit: ' . $this->audit->title)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Laporan audit berikut telah diselesaikan dan tersedia untuk Anda')
            ->line('Judul: ' . $this->audit->title)
            ->line('Tanggal audit: ' . $this->audit->audit_date_start . ' s/d ' . $this->audit->audit_date_end)
            ->when($this->additionalMessage, function ($message) {
                return $message->line($this->additionalMessage);
            })
            ->action('Lihat Laporan Audit', $url)
            ->line('Terima kasih telah menggunakan aplikasi SPMI!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'audit_id' => $this->audit->id,
            'audit_title' => $this->audit->title,
            'message' => 'Laporan audit telah tersedia',
            'additional_message' => $this->additionalMessage,
        ];
    }
}
