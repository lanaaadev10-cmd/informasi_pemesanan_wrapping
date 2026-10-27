<?php

namespace App\Filament\Pages;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\BookingProgressPhoto;
use App\Models\EWarranty;
use App\Services\BookingService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class WorkshopKanban extends Page
{
    use WithFileUploads;

    protected static ?string $navigationLabel = 'Papan Kanban Workshop';
    protected static ?string $title = 'Papan Visual Pengerjaan Mobil (Kanban)';
    protected static \UnitEnum|string|null $navigationGroup = 'Transaksi';
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-view-columns';
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'workshop-kanban';

    protected string $view = 'filament.pages.workshop-kanban';

    // State untuk Modal Upload Foto Progres
    public ?int $selectedBookingId = null;
    public string $photoStage = 'Pembersihan & Detailing';
    public $progressPhotoFile = null;
    public string $photoCaption = '';
    public bool $isPhotoModalOpen = false;

    public function mount(): void
    {
        abort_unless(Auth::user()?->hasRole('admin'), 403);
    }

    /**
     * Memajukan status booking ke Disetujui (Approved / Jadwal Terkunci)
     */
    public function advanceToApproved(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->payment && $booking->payment->status !== 'verified') {
            app(BookingService::class)->verifyPayment($booking->payment);
        } else {
            $booking->update(['status' => BookingStatus::APPROVED->value]);
        }

        Notification::make()
            ->title('Status Diperbarui: Disetujui')
            ->body("Booking {$booking->booking_code} siap masuk workshop.")
            ->success()
            ->send();
    }

    /**
     * Memajukan status booking ke Sedang Dikerjakan (In Progress)
     */
    public function advanceToInProgress(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        app(BookingService::class)->startProcessing($booking);

        Notification::make()
            ->title('Pengerjaan Dimulai!')
            ->body("Mobil {$booking->vehicle_name} sedang dikerjakan teknisi.")
            ->info()
            ->send();
    }

    /**
     * Memajukan status booking ke Selesai (Completed & Terbitkan E-Warranty)
     */
    public function advanceToCompleted(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        app(BookingService::class)->completeBooking($booking);

        Notification::make()
            ->title('Pengerjaan Selesai!')
            ->body("Booking {$booking->booking_code} selesai. E-Warranty otomatis terbit!")
            ->success()
            ->send();
    }

    /**
     * Buka modal upload foto progres
     */
    public function openPhotoModal(int $bookingId): void
    {
        $this->selectedBookingId = $bookingId;
        $this->photoStage = 'Pemasangan Vinyl / Wrapping';
        $this->photoCaption = '';
        $this->progressPhotoFile = null;
        $this->isPhotoModalOpen = true;
    }

    /**
     * Tutup modal upload foto progres
     */
    public function closePhotoModal(): void
    {
        $this->isPhotoModalOpen = false;
        $this->selectedBookingId = null;
        $this->progressPhotoFile = null;
    }

    /**
     * Simpan foto progres pengerjaan mobil
     */
    public function saveProgressPhoto(): void
    {
        $this->validate([
            'selectedBookingId'  => 'required|exists:bookings,id',
            'photoStage'         => 'required|string|max:100',
            'progressPhotoFile'  => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'photoCaption'       => 'nullable|string|max:255',
        ]);

        $booking = Booking::findOrFail($this->selectedBookingId);
        $path = $this->progressPhotoFile->store('progress_photos', 'public');

        BookingProgressPhoto::create([
            'booking_id'     => $booking->id,
            'stage'          => $this->photoStage,
            'photo_path'     => $path,
            'caption'        => $this->photoCaption ?: "Progres pengerjaan tahap {$this->photoStage}.",
            'uploaded_by_id' => Auth::id(),
        ]);

        $this->closePhotoModal();

        Notification::make()
            ->title('Foto Progres Berhasil Diunggah!')
            ->body("Foto progres mobil {$booking->vehicle_name} kini dapat dilihat pelanggan secara real-time.")
            ->success()
            ->send();
    }

    /**
     * Helper URL WhatsApp dengan pesan dinamis sesuai status
     */
    public function getWhatsAppUrl(Booking $booking, string $type): string
    {
        $phone = preg_replace('/[^0-9]/', '', $booking->customer_phone ?: ($booking->user?->phone ?? ''));
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $custName = $booking->customer_name ?: ($booking->user?->name ?? 'Kak');
        $vehicle = $booking->vehicle_name ?: 'Kendaraan';
        $service = $booking->service_name_snapshot ?: ($booking->layanan?->nama_layanan ?? 'Wrapping');

        $message = match ($type) {
            'awaiting' => "Halo {$custName}, kami dari Dantie Auto Wrapping menginformasikan jadwal booking {$booking->booking_code} untuk {$vehicle} ({$service}). Silakan menyelesaikan transfer DP untuk mengunci slot pengerjaan. Terima kasih!",
            'reminder' => "Halo {$custName}, pengingat dari Dantie Auto Wrapping: Jadwal pengerjaan {$vehicle} dijadwalkan pada {$booking->booking_date?->format('d M Y')}. Ditunggu kedatangannya di workshop kami ya!",
            'progress' => "Halo {$custName}, update pengerjaan {$vehicle} Anda: Saat ini tim teknisi kami sedang melakukan pemasangan stiker/wrapping di workshop. Foto progres dapat dicek langsung di akun Anda: " . route('booking.show', $booking->id),
            'ready'    => "Halo {$custName}, pengerjaan {$vehicle} ({$service}) telah SELESAI 100%! Unit sudah bersih, melewati tahap QC, dan siap dijemput di workshop Dantie. Sertifikat Garansi Digital (E-Warranty) Anda telah aktif di link: " . route('booking.show', $booking->id),
            default    => "Halo {$custName}, info dari Dantie Auto Wrapping terkait booking {$booking->booking_code}.",
        };

        return "https://wa.me/{$phone}?text=" . rawurlencode($message);
    }

    protected function getViewData(): array
    {
        // 1. Antrean Masuk / Menunggu Konfirmasi & Pembayaran
        $pendingBookings = Booking::with(['user', 'layanan', 'payment'])
            ->whereIn('status', [
                BookingStatus::PENDING->value,
                BookingStatus::CONFIRMED->value,
                BookingStatus::AWAITING_PAYMENT->value,
                BookingStatus::PAYMENT_UPLOADED->value,
            ])
            ->orderBy('booking_date', 'asc')
            ->get();

        // 2. Disetujui / Siap Masuk Workshop
        $approvedBookings = Booking::with(['user', 'layanan', 'payment'])
            ->where('status', BookingStatus::APPROVED->value)
            ->orderBy('booking_date', 'asc')
            ->get();

        // 3. Sedang Dikerjakan (In Progress / WIP)
        $inProgressBookings = Booking::with(['user', 'layanan', 'payment', 'progressPhotos'])
            ->where('status', BookingStatus::IN_PROGRESS->value)
            ->orderBy('booking_date', 'asc')
            ->get();

        // 4. Selesai & Siap Diambil
        $completedBookings = Booking::with(['user', 'layanan', 'warranty'])
            ->where('status', BookingStatus::COMPLETED->value)
            ->latest('updated_at')
            ->take(12)
            ->get();

        return [
            'pendingBookings'    => $pendingBookings,
            'approvedBookings'   => $approvedBookings,
            'inProgressBookings' => $inProgressBookings,
            'completedBookings'  => $completedBookings,
        ];
    }
}
