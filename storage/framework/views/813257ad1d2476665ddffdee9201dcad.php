<?php $__env->startSection('title', 'Daftar - FocusOneX Archery'); ?>

<?php $__env->startSection('content'); ?>
<section class="relative min-h-screen flex items-center justify-center px-4 overflow-hidden">

    <!-- Background -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo e(asset('asset/img/latarbelakanglogin.jpeg')); ?>"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/60"></div>
    </div>

    <!-- Register Card -->
    <div class="relative z-10 w-full max-w-md">
        <div class="backdrop-blur-sm border border-white/20 rounded-2xl shadow-2xl shadow-black/50 px-10 py-10">

            <h1 class="text-3xl font-bold text-white text-center mb-10">
                Register
            </h1>

            <?php if($errors->any()): ?>
                <div class="mb-6 rounded-xl border border-red-400/30 bg-red-500/10 p-3 text-sm text-red-300">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('register.post')); ?>" method="POST" class="space-y-8">
                <?php echo csrf_field(); ?>

                <!-- Name -->
                <div>
                    <input type="text"
                           name="name"
                           value="<?php echo e(old('name')); ?>"
                           required
                           placeholder="Full name"
                           class="w-full bg-transparent border-0 border-b border-white
                                  text-white placeholder-white text-sm
                                  pb-2 pt-1 focus:outline-none focus:border-white">
                </div>

                <!-- Email -->
                <div>
                    <input type="email"
                           name="email"
                           value="<?php echo e(old('email')); ?>"
                           required
                           placeholder="Email address"
                           class="w-full bg-transparent border-0 border-b border-white
                                  text-white placeholder-white text-sm
                                  pb-2 pt-1 focus:outline-none focus:border-white">
                </div>

                <!-- Phone -->
                <div>
                    <input type="tel"
                           name="phone"
                           value="<?php echo e(old('phone')); ?>"
                           required
                           placeholder="Phone number"
                           class="w-full bg-transparent border-0 border-b border-white
                                  text-white placeholder-white text-sm
                                  pb-2 pt-1 focus:outline-none focus:border-white">
                </div>

                <!-- Password -->
                <div>
                    <input type="password"
                           name="password"
                           required
                           placeholder="Password"
                           class="w-full bg-transparent border-0 border-b border-white
                                  text-white placeholder-white text-sm
                                  pb-2 pt-1 focus:outline-none focus:border-white">
                </div>

                <!-- Confirm Password -->
                <div>
                    <input type="password"
                           name="password_confirmation"
                           required
                           placeholder="Confirm password"
                           class="w-full bg-transparent border-0 border-b border-white
                                  text-white placeholder-white text-sm
                                  pb-2 pt-1 focus:outline-none focus:border-white">
                </div>

                <button type="submit"
                        class="w-full bg-white border border-white/20 hover:bg-white/80
                               rounded-xl py-3 text-sm font-medium text-black transition-all">
                    Create Account
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-grow border-t border-white/15"></div>
                <span class="mx-4 text-white/50 text-sm">atau</span>
                <div class="flex-grow border-t border-white/15"></div>
            </div>

            <a href="<?php echo e(route('auth.google.redirect')); ?>"
               class="flex items-center justify-center gap-3 w-full
                      bg-white hover:bg-white/80 border border-white/20
                      rounded-xl py-3 text-sm font-medium text-black transition-all">
                <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Login dengan Google
            </a>

            <p class="mt-6 text-center text-sm text-white/50">
                Already have an account?
                <a href="<?php echo e(route('login')); ?>" class="text-white hover:text-white/80 font-semibold">
                    Login
                </a>
            </p>

        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/auth/register.blade.php ENDPATH**/ ?>