<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\Pesanan;
use App\Enums\NotificationType;

/**
 * Service untuk manage notifications
 * 
 * Handles:
 * - Create notification
 * - Send notifications (email, SMS, in-app)
 * - Mark as read
 * - Delete notification
 */
class NotifikasiService
{
    /**
     * Create notification
     */
    public function createNotification(
        $userId,
        string $judul,
        string $pesan,
        string $tipe = 'in_app',
        ?int $idPesanan = null
    ): Notifikasi {
        return Notifikasi::create([
            'id_user' => $userId,
            'id_pesanan' => $idPesanan,
            'tipe' => $tipe,
            'judul' => $judul,
            'pesan' => $pesan,
        ]);
    }

    /**
     * Send notifications melalui berbagai channel
     * Bisa di-queue untuk async processing
     */
    public function sendNotification(Notifikasi $notifikasi): void
    {
        match ($notifikasi->tipe) {
            NotificationType::EMAIL->value => $this->sendEmail($notifikasi),
            NotificationType::SMS->value => $this->sendSMS($notifikasi),
            NotificationType::IN_APP->value => $this->markAsSent($notifikasi),
            NotificationType::PUSH->value => $this->sendPush($notifikasi),
            default => null,
        };
    }

    /**
     * Send email notification
     */
    protected function sendEmail(Notifikasi $notifikasi): void
    {
        try {
            $user = $notifikasi->user;
            // Implement email sending
            // \Mail::send(...);
            $notifikasi->update(['status' => 'sent']);
        } catch (\Exception $e) {
            $notifikasi->update(['status' => 'failed']);
            \Log::error('Email notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Send SMS notification
     */
    protected function sendSMS(Notifikasi $notifikasi): void
    {
        try {
            $user = $notifikasi->user;
            // Implement SMS sending via provider (Twilio, etc)
            $notifikasi->update(['status' => 'sent']);
        } catch (\Exception $e) {
            $notifikasi->update(['status' => 'failed']);
            \Log::error('SMS notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Send push notification
     */
    protected function sendPush(Notifikasi $notifikasi): void
    {
        try {
            // Implement push notification
            $notifikasi->update(['status' => 'sent']);
        } catch (\Exception $e) {
            $notifikasi->update(['status' => 'failed']);
            \Log::error('Push notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Mark notification as sent (in-app)
     */
    protected function markAsSent(Notifikasi $notifikasi): void
    {
        $notifikasi->update(['status' => 'sent']);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notifikasi $notifikasi): Notifikasi
    {
        $notifikasi->update(['is_read' => true]);
        return $notifikasi;
    }

    /**
     * Get user notifications with pagination
     */
    public function getUserNotifications($userId, $perPage = 20)
    {
        return Notifikasi::where('id_user', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount($userId): int
    {
        return Notifikasi::where('id_user', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Delete old notifications (older than 30 days)
     */
    public function cleanupOldNotifications($daysOld = 30): void
    {
        Notifikasi::where('created_at', '<', now()->subDays($daysOld))->delete();
    }
}
