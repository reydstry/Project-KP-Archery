@extends('dashboards.member._layout')

@section('title', 'My Bookings')
@section('subtitle', 'Kelola jadwal booking sesi latihan')

@section('content')
<div x-data="bookingsData()" x-init="fetchBookings()">
    
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Booking Saya</h2>
            <p class="text-slate-600 mt-1">Kelola jadwal sesi latihan Anda</p>
        </div>
        <a href="{{ route('member.bookings.create') }}" 
           class="card-animate px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm hover:shadow-md inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Booking Baru
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
        <div class="flex flex-wrap gap-2">
            <button @click="filterStatus = 'all'; fetchBookings()"
                    :class="filterStatus === 'all' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-5 py-2.5 rounded-xl font-semibold transition-all">
                Semua
            </button>
            <button @click="filterStatus = 'pending'; fetchBookings()"
                    :class="filterStatus === 'pending' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-5 py-2.5 rounded-xl font-semibold transition-all">
                Pending
            </button>
            <button @click="filterStatus = 'confirmed'; fetchBookings()"
                    :class="filterStatus === 'confirmed' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-5 py-2.5 rounded-xl font-semibold transition-all">
                Confirmed
            </button>
            <button @click="filterStatus = 'cancelled'; fetchBookings()"
                    :class="filterStatus === 'cancelled' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-5 py-2.5 rounded-xl font-semibold transition-all">
                Cancelled
            </button>
            <button @click="filterStatus = 'completed'; fetchBookings()"
                    :class="filterStatus === 'completed' ? 'bg-gradient-to-r from-slate-500 to-slate-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-5 py-2.5 rounded-xl font-semibold transition-all">
                Completed
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Bookings Grid -->
    <div x-show="!loading" x-cloak class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <template x-for="(booking, index) in bookings" :key="booking.id">
            <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all overflow-hidden"
                 :style="`animation-delay: ${index * 0.05}s`">
                
                <!-- Status Header -->
                <div class="px-6 py-3 border-b border-slate-200"
                     :class="{
                         'bg-amber-50': booking.status === 'pending',
                         'bg-green-50': booking.status === 'confirmed',
                         'bg-red-50': booking.status === 'cancelled',
                         'bg-slate-50': booking.status === 'completed'
                     }">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold uppercase tracking-wide"
                              :class="{
                                  'text-amber-700': booking.status === 'pending',
                                  'text-green-700': booking.status === 'confirmed',
                                  'text-red-700': booking.status === 'cancelled',
                                  'text-slate-700': booking.status === 'completed'
                              }"
                              x-text="booking.status">
                        </span>
                        <span class="text-xs text-slate-500" x-text="'#' + booking.id"></span>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="p-6">
                    <!-- Session Info -->
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-800 text-lg mb-1" x-text="formatDate(booking.session_date)"></h4>
                            <p class="text-slate-600 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="booking.session_time"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Member & Coach Info -->
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm"
                                 x-text="booking.member_name.charAt(0).toUpperCase()">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-500 mb-0.5">Member</p>
                                <p class="font-semibold text-slate-800 truncate" x-text="booking.member_name"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center text-white font-bold text-sm"
                                 x-text="booking.coach_name.charAt(0).toUpperCase()">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-500 mb-0.5">Coach</p>
                                <p class="font-semibold text-slate-800 truncate" x-text="booking.coach_name"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Badge -->
                    <div x-show="booking.attendance" class="mb-4">
                        <div class="p-3 bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl border border-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">Kehadiran:</span>
                                <span 
                                    class="px-3 py-1 rounded-full text-xs font-bold"
                                    :class="booking.attendance?.status === 'present' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200'"
                                    x-text="booking.attendance?.status === 'present' ? 'Hadir' : 'Tidak Hadir'">
                                </span>
                            </div>
                            <p x-show="booking.attendance?.notes" class="text-xs text-slate-500 mt-2" x-text="booking.attendance?.notes"></p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 pt-4 border-t border-slate-200">
                        <button x-show="booking.status === 'confirmed'"
                                @click="cancelBooking(booking.id)"
                                class="flex-1 px-4 py-2.5 bg-red-50 border border-red-200 text-red-700 rounded-xl font-semibold hover:bg-red-100 transition-all inline-flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Cancel Booking
                        </button>
                        
                        <button x-show="booking.status === 'completed'"
                                class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-500 rounded-xl font-semibold cursor-not-allowed">
                            Selesai
                        </button>
                        
                        <button x-show="booking.status === 'cancelled'"
                                class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-500 rounded-xl font-semibold cursor-not-allowed">
                            Dibatalkan
                        </button>
                        
                        <button x-show="booking.status === 'pending'"
                                class="flex-1 px-4 py-2.5 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl font-semibold cursor-default">
                            Menunggu Konfirmasi
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="!loading && bookings.length === 0" x-cloak class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
        <svg class="w-24 h-24 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
        <h4 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Booking</h4>
        <p class="text-slate-500 mb-6">Anda belum memiliki booking sesi latihan</p>
        <a href="{{ route('member.bookings.create') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Buat Booking Sekarang
        </a>
    </div>
</div>

@push('scripts')
<script>
function bookingsData() {
    return {
        loading: true,
        bookings: [],
        filterStatus: 'all',
        
        async fetchBookings() {
            this.loading = true;
            try {
                const url = this.filterStatus === 'all' 
                    ? '/member/bookings' 
                    : `/member/bookings?status=${this.filterStatus}`;
                const response = await API.get(url);
                this.bookings = response.data || [];
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal memuat data booking', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        async cancelBooking(bookingId) {
            if (!confirm('Yakin ingin membatalkan booking ini?')) return;
            
            try {
                await API.post(`/member/bookings/${bookingId}/cancel`);
                showToast('Booking berhasil dibatalkan', 'success');
                this.fetchBookings();
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Gagal membatalkan booking', 'error');
            }
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { 
                weekday: 'long',
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }
    }
}
</script>
@endpush
@endsection
