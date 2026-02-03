<div class="bg-white rounded-2xl shadow-lg p-8">
    <h3 class="text-2xl font-bold text-gray-800 mb-6">Kirim Pesan</h3>
    
    <form action="#" method="POST" class="space-y-5">
        @csrf
        
        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                required
                placeholder="Masukkan nama lengkap"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
            >
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email <span class="text-red-500">*</span>
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                required
                placeholder="email@example.com"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
            >
        </div>

        <!-- Nomor Telepon -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                Nomor Telepon <span class="text-red-500">*</span>
            </label>
            <input 
                type="tel" 
                id="phone" 
                name="phone" 
                required
                placeholder="+62 812-3456-7890"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
            >
        </div>

        <!-- Program yang Diminati -->
        <div>
            <label for="program" class="block text-sm font-medium text-gray-700 mb-2">
                Program yang Diminati <span class="text-red-500">*</span>
            </label>
            <select 
                id="program" 
                name="program" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
            >
                <option value="">Pilih program</option>
                <option value="pemula">Program Pemula</option>
                <option value="menengah">Program Menengah</option>
                <option value="advanced">Program Advanced</option>
                <option value="kompetisi">Program Kompetisi</option>
                <option value="privat">Les Privat</option>
            </select>
        </div>

        <!-- Pesan -->
        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                Pesan
            </label>
            <textarea 
                id="message" 
                name="message" 
                rows="4"
                placeholder="Tuliskan pesan atau pertanyaan Anda..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 resize-none"
            ></textarea>
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-md hover:shadow-lg"
        >
            Kirim Pesan
        </button>
    </form>
</div>
