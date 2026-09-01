<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In — HCLSC Samadhan</title>
    <meta name="robots" content="noindex, nofollow">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center px-6">

    <div class="w-full max-w-sm">
        <h1 class="text-xl font-bold text-slate-800 text-center">Administrator Access</h1>
        <p class="text-slate-500 text-sm text-center mt-1">Restricted area. Authorized personnel only.</p>

        <form method="POST" action="{{ route('admin.login') }}"
            class="bg-white border border-slate-200 rounded-2xl shadow-sm px-8 py-9 mt-6">
            @csrf

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="mb-4">
                <label for="username"
                    class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Username or
                    Email</label>
                <input id="username" name="username" type="text" required autofocus autocomplete="username"
                    placeholder="Enter your username or email" value="{{ old('username') }}"
                    class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-slate-800/20">
            </div>

            <div class="mb-6">
                <label for="password"
                    class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                    class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-slate-800/20">
            </div>

            <button type="submit"
                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm py-3 rounded-lg">
                Sign in
            </button>
        </form>
    </div>

</body>

</html>
