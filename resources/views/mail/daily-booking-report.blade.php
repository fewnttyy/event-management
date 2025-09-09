<x-mail::message>
# Daily Booking Report

---

Berikut adalah ringkasan booking yang masuk pada hari ini, {{ now()->translatedFormat('l, d F Y') }}.

@if ($bookings->isEmpty())
<x-mail::panel>
Tidak ada booking baru pada hari ini.
</x-mail::panel>
@else
<x-mail::table>
| No. | Customer | Event | Tanggal | Jumlah | Waktu Booking |
|:---:|:---|:---|:---:|:---|:---|
@foreach($bookings as $index => $booking)
| {{ $index + 1 }} | {{ $booking->user->name }} | {{ $booking->event->title }} | {{ $booking->event->date }} | {{ $booking->quantity }} | {{ $booking->created_at->format('Y-m-d H:i:s') }} |
@endforeach
</x-mail::table>
@endif

Total Booking : {{ $bookings->count() }}

Terima kasih,
**{{ config('app.name') }} Team**

---

<x-mail::subcopy>
Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
</x-mail::subcopy>
</x-mail::message>