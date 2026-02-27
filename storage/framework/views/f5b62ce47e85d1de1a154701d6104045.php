

<?php $__env->startSection('title', 'Login - FocusOneX Archery'); ?>

<?php $__env->startSection('content'); ?>
<section class="relative min-h-screen flex items-center justify-center px-4 overflow-hidden">

    <!-- Background -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo e(asset('asset/img/latarbelakanglogin.jpeg')); ?>"
             alt="Background"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/60"></div>
    </div>

    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-md">
        <div class="backdrop-blur-sm border border-white/20 rounded-2xl shadow-2xl shadow-black/50 px-10 py-10">
            <!-- Title -->
            <h1 class="text-3xl font-bold text-white text-center mb-10">
                Login
            </h1>

            <!-- Error Messages -->
            <?php if($errors->any()): ?>
            <div class="mb-6 rounded-xl border border-red-400/30 bg-red-500/10 p-3 text-sm text-red-300">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p><?php echo e($error); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <!-- Success Message -->
            <?php if(session('status')): ?>
            <div class="mb-6 rounded-xl border border-green-400/30 bg-green-500/10 p-3 text-sm text-green-300">
                <?php echo e(session('status')); ?>

            </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="<?php echo e(route('login.post')); ?>" class="space-y-8">
                <?php echo csrf_field(); ?>

                <!-- Email -->
                <div class="relative">
                    <input type="email"
                           name="email"
                           id="email"
                           value="<?php echo e(old('email')); ?>"
                           required
                           placeholder="Enter your email"
                           class="w-full bg-transparent border-0 border-b border-white
                                  text-white placeholder-white text-sm
                                  pb-2 pt-1 focus:outline-none focus:border-white
                                  transition-colors duration-200">
                </div>

                <!-- Password -->
                <div class="relative">
                    <input type="password"
                           id="password"
                           name="password"
                           autocomplete="current-password"
                           required
                           placeholder="Enter your password"
                           class="w-full bg-transparent border-0 border-b border-white
                                  text-white placeholder-white text-sm
                                  pb-2 pt-1 pr-10 focus:outline-none focus:border-white
                                  transition-colors duration-200
                                  [&::-ms-reveal]:!hidden [&::-webkit-credentials-auto-fill-button]:!hidden">
                    <button type="button"
                            onclick="togglePassword()"
                            tabindex="-1"
                            class="absolute right-0 top-1/2 -translate-y-1/2 text-white/80 hover:text-white transition-colors focus:outline-none">
                        <svg id="eye-open" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eye-closed" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-white/70 cursor-pointer">
                        <input type="checkbox"
                               name="remember"
                               class="h-4 w-4 rounded border-white/30 bg-white/10 text-blue-500 focus:ring-blue-500/50">
                        Remember me
                    </label>
                    <a href="<?php echo e(route('password.request')); ?>"
                       class="text-sm text-white/70 hover:text-white transition-colors">
                        Forgot password?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit"
                        class="relative flex items-center justify-center gap-3 w-full
                        bg-white border border-white/20 hover:bg-white/80
                        rounded-xl py-3 px-4 text-sm font-medium text-black
                        transition-all duration-300 cursor-pointer">
                    Log In
                </button>

            </form>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-grow border-t border-white/15"></div>
                <span class="mx-4 text-white/50 text-sm">atau</span>
                <div class="flex-grow border-t border-white/15"></div>
            </div>



            <!-- Google Login -->
            <a href="<?php echo e(route('auth.google.redirect')); ?>"
               class="relative flex items-center justify-center gap-3 w-full
                      bg-white hover:bg-white/80 border border-white/20
                      rounded-xl py-3 px-4 text-sm font-medium text-black
                      transition-all duration-300 cursor-pointer">
                <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Login dengan Google
            </a>

            <!-- Register & Back -->
            <p class="mt-6 text-center text-sm text-white/50">
                Don't have an account?
                <a href="<?php echo e(route('register')); ?>" class="font-semibold text-white hover:text-white/80 transition-colors">
                    Register
                </a>
            </p>

            <div class="mt-4 text-center">
                <a href="<?php echo e(route('beranda')); ?>"
                   class="inline-flex items-center gap-2 text-sm text-white/60 hover:text-white transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Menu Utama
                </a>
            </div>

        </div>
    </div>
</section>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        passwordInput.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Project-KP-Archery\resources\views/auth/login.blade.php ENDPATH**/ ?>