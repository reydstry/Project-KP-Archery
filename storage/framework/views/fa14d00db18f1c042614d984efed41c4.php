<div class="relative py-24 sm:py-32 bg-gradient-to-b from-[#0f172a] to-[#1b2659] overflow-hidden">

    <div class="absolute top-10 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                <?php echo e(__('contact.testimonials_title')); ?>

            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                <?php echo e(__('contact.testimonials_subtitle')); ?>

            </p>
        </div>

        <?php
            // PETUNJUK: Buka setiap link Google Review di bawah, lalu copy:
            // 1. Nama reviewer
            // 2. Teks review
            // 3. Jumlah bintang (1-5)
            // Format: ['name' => 'Nama dari Google', 'role' => 'Ulasan Google', 'stars' => 5, 'text' => 'Copy review dari Google', 'photo' => 'URL foto (optional)', 'link' => 'link review']
            
            $testimonials = [
                // Review 1 - https://share.google/EkwBqtTqDNigh9Sby
                [
                    'name' => 'Coach Agus Supriyanto',
                    'role' => 'Ulasan Google', 
                    'stars' => 5,  // Sesuaikan dengan rating di Google (1-5)
                    'text' => 'Tempat latihan panahan yg sangat representative, lengkap banget peralatannya. Dari pemula sampai kelas atlet bisa di sini. Buat orang yg cuman pengen liat orang manah juga bisa di sini.
                                Lapaaang dan luaaaass banget.
                                Ada kantin, toilet yg bersih banget...  Ahh lengkap deh.
                                Eh juga ada kudanya loh,  kedepannya akan jadi arena berkuda.
                                From zero to hero bisa dah di sini.
                                Coach nya juga sabar dan sangat menyesuaikan tingkatan anda..  Yg penting betul dulu caranya, target mah gampang.. Keep Focus',
            'photo' => null,  // Optional: paste URL foto profil dari Google, atau biarkan null untuk avatar otomatis
                    'link' => 'https://share.google/EkwBqtTqDNigh9Sby'
                ],
                
                // Review 2 - https://share.google/eHSthxKY5URynkvbg
                [
                    'name' => 'Salira Bandung Batagor Kuah Balikpapan',
                    'role' => 'Ulasan Google',
                    'stars' => 5,
                    'text' => 'Tempat latihan panahan profesional nih. Dari kelas pemula sampai dengan kelas "legolas" hahaha.
                                Buat anak-anak sampai dengan dewasa bisa latihan di sini.
                                Dari pagi sampai malam juga boleh. Saya sendiri ikut kelas malam yg dari jam 20.00 sampai dengan jam 22.00.
                                Tenang,  tetap terang kok. Pencahayaannya terang banget. Oh iya,  cukup bawa badan aja. Semua sudah lengkap di sini. Bawa teman juga ya,  biar seru dan beradu score.',
                    'photo' => null,
                    'link' => 'https://share.google/eHSthxKY5URynkvbg'
                ],
                

                // Review 3 - https://maps.app.goo.gl/8gAEfCdf891RiZ9d7
                [
                    'name' => 'Vicky Network',
                    'role' => 'Ulasan Google', 
                    'stars' => 5,  // Sesuaikan dengan rating di Google (1-5)
                    'text' => 'Memanah juga merupakan cabang olahraga yang cukup popluer di era sekarang. Sehingga banyak ditemukan sekolah panahan dan tersebar hampir di seluruh kota-kota besar di Indonesia salah satunya balikpapan.',
                    'photo' => null,  // Optional: paste URL foto profil dari Google, atau biarkan null untuk avatar otomatis
                    'link' => 'https://maps.app.goo.gl/8gAEfCdf891RiZ9d7'
                ],
                
                // Review 4 - https://share.google/pkURLlvNX7mUZfAeW
                [
                    'name' => 'Yani Banjar',
                    'role' => 'Ulasan Google',
                    'stars' => 5,
                    'text' => 'Tempat latihan memanah dan berkuda yg asek dan luas
                                Para pelatih dan staf yg ramah ramah',
                    'photo' => null,
                    'link' => 'https://share.google/pkURLlvNX7mUZfAeW'
                ],
                
                // Review 5 - https://share.google/FiXHLH9qTEdSHTjty
                [
                    'name' => 'Tegar Septiyanto',
                    'role' => 'Ulasan Google',
                    'stars' => 5,
                    'text' => 'Buat yg mau latihan panahan alias olahraga kekinian tempat ini cocok banget.',
                    'photo' => null,
                    'link' => 'https://share.google/FiXHLH9qTEdSHTjty'
                ],
                 // Review 6 - https://share.google/ZcySbC2yPBn03hVj2
                [
                    'name' => 'Yuti Iban',
                    'role' => 'Ulasan Google',
                    'stars' => 5,
                    'text' => 'Tempat latihan panahan yang bagus. Ada toilet dan mushola yg bersih, juga tempat menunggu yg nyaman.
                    ',
                    'photo' => null,
                    'link' => 'https://share.google/ZcySbC2yPBn03hVj2'
                ],
                
            ];
        ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $hasLink = isset($t['link']) && !empty($t['link']);
                $isGoogleReview = isset($t['role']) && $t['role'] === 'Ulasan Google';
            ?>
            
            <?php if($hasLink): ?>
            <a href="<?php echo e($t['link']); ?>" target="_blank" class="relative group block">
            <?php else: ?>
            <div class="relative group">
            <?php endif; ?>
                <div class="absolute inset-0 bg-yellow-500/10 rounded-3xl blur-2xl scale-105 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="relative h-full bg-white/5 backdrop-blur-md border border-white/20 rounded-2xl p-6
                            shadow-xl shadow-black/30 hover:shadow-2xl hover:shadow-black/60
                            transition-all duration-300 hover:-translate-y-2 overflow-hidden">

                    <!-- Shine -->
                    <span class="absolute inset-0 w-full h-full 
                                bg-gradient-to-r from-transparent via-white/10 to-transparent
                                -translate-x-full group-hover:translate-x-full 
                                transition-transform duration-700 ease-in-out skew-x-12 pointer-events-none z-10">
                    </span>

                    <?php if($isGoogleReview): ?>
                    <!-- Google Badge -->
                    <div class="flex items-center gap-1.5 mb-3">
                        <svg class="w-4 h-4" viewBox="0 0 48 48" fill="none">
                            <path d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" fill="#FFC107"/>
                            <path d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z" fill="#FF3D00"/>
                            <path d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0124 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z" fill="#4CAF50"/>
                            <path d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 01-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z" fill="#1976D2"/>
                        </svg>
                        <span class="text-white/50 text-xs font-semibold">GOOGLE REVIEW</span>
                    </div>
                    <?php endif; ?>

                    <!-- Quote icon -->
                    <div class="text-yellow-400/30 text-6xl font-serif leading-none mb-2">"</div>

                    <!-- Text -->
                    <p class="text-white/70 text-sm leading-relaxed mb-5 italic"><?php echo e($t['text']); ?></p>

                    <!-- Divider -->
                    <div class="w-full h-px bg-white/10 mb-4"></div>

                    <!-- Profile + Stars -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="absolute inset-0 bg-yellow-500/30 rounded-full blur-md scale-110 pointer-events-none"></div>
                                <?php if(isset($t['photo']) && !empty($t['photo'])): ?>
                                    <img src="<?php echo e($t['photo']); ?>"
                                         alt="<?php echo e($t['name']); ?>"
                                         class="relative w-10 h-10 rounded-full border-2 border-white/20 object-cover"
                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo e(urlencode($t['name'])); ?>&size=80&background=1a307b&color=fff'">
                                <?php else: ?>
                                    <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($t['name'])); ?>&size=80&background=1a307b&color=fff"
                                         alt="<?php echo e($t['name']); ?>"
                                         class="relative w-10 h-10 rounded-full border-2 border-white/20">
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="text-white font-semibold text-sm"><?php echo e($t['name']); ?></p>
                                <p class="text-white/40 text-xs"><?php echo e($t['role']); ?></p>
                            </div>
                        </div>
                        <!-- Stars -->
                        <div class="flex gap-0.5">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                            <svg class="w-4 h-4 <?php echo e($i <= $t['stars'] ? 'text-yellow-400' : 'text-white/20'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <?php if($hasLink): ?>
                    <!-- Click to view full review -->
                    <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="flex items-center gap-1 text-xs text-blue-400 font-semibold bg-blue-500/20 px-2 py-1 rounded-lg backdrop-blur-sm">
                            <span>Lihat lengkap</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php if($hasLink): ?>
            </a>
            <?php else: ?>
            </div>
            <?php endif; ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/kontak/testimonials.blade.php ENDPATH**/ ?>