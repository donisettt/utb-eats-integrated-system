<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - UTB Eats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen">
        <div class="w-64 bg-blue-900 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-blue-800">
                UTB Eats 🍔
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 transition">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.merchants') }}" class="block py-2.5 px-4 rounded hover:bg-blue-700 transition">
                    🏪 Data Mitra
                </a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-blue-700 transition opacity-50">
                    🍲 Data Menu (Coming Soon)
                </a>
            </nav>
            <div class="p-4 border-t border-blue-800">
                <button class="w-full bg-red-600 hover:bg-red-700 py-2 rounded text-sm">Logout</button>
            </div>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">@yield('title')</h2>
                <div class="text-gray-600">Admin Kampus</div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>
