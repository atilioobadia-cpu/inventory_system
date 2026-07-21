<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In - Mtokoma Motorcycle Parts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#171717',
                        'accent': '#2563EB',
                        'accent-light': '#EBF2FF',
                        'heading': '#171717',
                        'body': '#383838',
                        'muted': '#525252',
                        'border': '#e2e2e2',
                        'control-bg': '#f3f3f3',
                        'danger': '#e03636',
                        'danger-light': '#fde8e8',
                    },
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-card-bg font-sans antialiased min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        {{-- Logo & Title --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-accent rounded-2xl mb-4">
                <svg class="w-9 h-9 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2-1h2m10 1l2-1V8a1 1 0 00-1-1h-2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 6h2a2 2 0 012 2v4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-heading">Mtokoma</h1>
            <p class="text-muted text-sm mt-1">Inventory Management System</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-xl border border-border p-8">
            <h2 class="text-lg font-semibold text-heading mb-6">Sign in to your account</h2>

            @if($errors->any())
            <div class="bg-danger-light border border-red-200 rounded-lg px-4 py-3 mb-6">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-danger mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                        <p class="text-sm text-danger">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if(session('status'))
            <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-3 mb-6">
                <p class="text-sm text-success">{{ session('status') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-body mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full pl-10 pr-4 py-2.5 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-colors"
                               placeholder="you@example.com">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-body mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required
                               class="w-full pl-10 pr-4 py-2.5 border border-border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-colors"
                               placeholder="Enter your password">
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                               class="w-4 h-4 text-accent border-border rounded focus:ring-accent">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-accent hover:underline">
                        Forgot password?
                    </a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full bg-primary text-white py-2.5 px-4 rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors focus:outline-none focus:ring-2 focus:ring-accent/20 focus:ring-offset-2">
                    Sign In
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-muted mt-6">&copy; 2026 Mtokoma Motorcycle Parts. All rights reserved.</p>
    </div>
</body>
</html>