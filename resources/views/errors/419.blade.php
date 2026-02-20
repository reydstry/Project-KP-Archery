<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired - FocusOneX Archery</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-2xl p-8 text-center">
            <!-- Icon -->
            <div class="w-24 h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-slate-800 mb-3">Session Expired</h1>
            
            <!-- Message -->
            <p class="text-slate-600 mb-2">Sesi Anda telah berakhir atau sudah kedaluwarsa.</p>
            <p class="text-sm text-slate-500 mb-8">Hal ini terjadi karena Anda terlalu lama tidak aktif atau menggunakan tombol back setelah logout.</p>

            <!-- Actions -->
            <div class="space-y-3">
                <button onclick="window.location.reload()" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 transition-all shadow-lg hover:shadow-xl">
                    Refresh Halaman
                </button>
                
                <a href="{{ route('login') }}" 
                   class="block w-full px-6 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-all">
                    Login Kembali
                </a>
                
                <a href="{{ url('/') }}" 
                   class="block w-full px-6 py-3 text-blue-600 hover:text-blue-700 font-semibold transition-all">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <p class="text-sm text-slate-600">
                <strong>Tips:</strong> Untuk menghindari masalah ini, jangan gunakan tombol back browser setelah logout atau submit form.
            </p>
        </div>
    </div>

    <script>
        // Auto-redirect after 10 seconds if user doesn't click anything
        let countdown = 10;
        const timer = setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = '{{ route('login') }}';
            }
        }, 1000);

        // Stop auto-redirect if user interacts
        document.addEventListener('click', () => {
            clearInterval(timer);
        });
    </script>
</body>
</html>
