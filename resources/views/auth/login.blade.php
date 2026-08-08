<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Stockify</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">

    <div class="flex min-h-screen items-center justify-center px-6">

        <div class="w-full max-w-md">

            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900">
                    Stockify
                </h1>

                <p class="mt-2 text-gray-600">
                    Login ke sistem manajemen stok
                </p>
            </div>

            <div class="rounded-lg bg-white p-6 shadow">

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}">

                    @csrf

                    <div class="mb-5">
                        <label
                            for="email"
                            class="mb-2 block text-sm font-medium text-gray-900"
                        >
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900"
                            placeholder="nama@email.com"
                        >
                    </div>

                    <div class="mb-5">
                        <label
                            for="password"
                            class="mb-2 block text-sm font-medium text-gray-900"
                        >
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900"
                            placeholder="••••••••"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700"
                    >
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>
</html>