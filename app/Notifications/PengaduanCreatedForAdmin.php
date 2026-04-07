<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengaduanCreatedForAdmin extends Notification
{
    use Queueable;

    public function __construct(public Pengaduan $pengaduan)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $judul = $this->pengaduan->judul;
        $siswa = $this->pengaduan->user?->name ?? 'Siswa';

        return (new MailMessage)
            ->subject('Pengaduan Baru: ' . $judul)
            ->greeting('Hai Admin,')
            ->line('Ada pengaduan baru yang masuk dan masih berstatus Pending.')
            ->line('Judul: ' . $judul)
            ->line('Dari: ' . $siswa)
            ->line('Kategori: ' . $this->pengaduan->kategori)
            ->line('Status: ' . $this->pengaduan->status)
            ->action('Lihat Detail Pengaduan', route('admin.pengaduan.show', $this->pengaduan->id))
            ->line('Silakan login terlebih dahulu jika diminta.');
    }
}
