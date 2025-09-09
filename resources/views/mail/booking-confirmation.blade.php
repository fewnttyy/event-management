<x-mail::message>
# Event Booking Confirmation

Dear **{{ $user->name }}**,

Booking Anda telah berhasil dikonfirmasi. Berikut adalah detail booking:

<x-mail::panel>
**Event:** {{ $event->title }}  
**Tanggal:** {{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}  
**Jumlah Tiket:** {{ $booking->quantity }} tiket  
**Waktu Booking:** {{ \Carbon\Carbon::parse($booking->date)->format('d F Y') }}     
**Status:** {{ ucfirst($booking->status) }}
</x-mail::panel>

**Instruksi Penting:**
- Simpan email ini sebagai bukti booking
- Tunjukkan email ini saat check-in

Terima kasih,  
**{{ config('app.name') }} Team**

<x-mail::subcopy>
Email otomatis. Jangan balas email ini
</x-mail::subcopy>
</x-mail::message>