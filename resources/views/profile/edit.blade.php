<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Profile
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
                
                <div class="flex items-center gap-5">
                    
                    <div class="w-20 h-20 rounded-full bg-blue-500 flex items-center justify-center text-white text-3xl font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                            {{ Auth::user()->name }}
                        </h1>

                        <p class="text-gray-500 dark:text-gray-300">
                            {{ Auth::user()->email }}
                        </p>
                    </div>

                </div>

                <hr class="my-6 border-gray-300 dark:border-gray-700">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-white mb-2">
                            Informasi Akun
                        </h3>

                        <p class="text-gray-700 dark:text-gray-300">
                            <strong>Nama:</strong>
                            {{ Auth::user()->name }}
                        </p>

                        <p class="text-gray-700 dark:text-gray-300 mt-2">
                            <strong>Email:</strong>
                            {{ Auth::user()->email }}
                        </p>
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-white mb-2">
                            Status Akun
                        </h3>

                        <p class="text-green-600 font-semibold">
                            Login Berhasil
                        </p>

                        <p class="text-gray-700 dark:text-gray-300 mt-2">
                            Selamat datang di halaman profile.
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>