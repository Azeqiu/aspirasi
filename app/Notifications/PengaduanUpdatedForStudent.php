<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengaduanUpdatedForStudent extends Notification
{
    use Queueable;

    public function __construct(public Pengaduan $pengaduan, public bool $statusChanged, public bool $tanggapanChanged)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $judul = $this->pengaduan->judul;

        $mail = (new MailMessage)
            ->subject('Update Pengaduan: ' . $judul)
            ->greeting('Hai ' . ($this->pengaduan->user?->name ?? 'Siswa') . ',')
            ->line('Ada update pada pengaduan kamu.')
            ->line('Judul: ' . $judul)
            ->line('Kategori: ' . $this->pengaduan->kategori);

        if ($this->statusChanged) {
            $mail->line('Status terbaru: ' . $this->pengaduan->status);
        }

        if ($this->tanggapanChanged && !empty($this->pengaduan->tanggapan)) {
            $mail->line('Tanggapan admin:')
                 ->line($this->pengaduan->tanggapan);
        }

        return $mail
            ->action('Lihat Detail Pengaduan', route('siswa.pengaduan.show', $this->pengaduan->id))
            ->line('Terima kasih.');
    }
}
