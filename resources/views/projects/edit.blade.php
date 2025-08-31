<x-app-layout>
    {{-- Header Slot --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Project: ' . $project->name) }}
        </h2>
    </x-slot>

    {{-- Main Content Slot --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Session Messages --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Your original content from the @section('content') block starts here --}}
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Project: {{ $project->name }}</h1>

                    <div class="bg-white shadow-md rounded-lg p-6">
                        <form action="{{ route('projects.update', $project) }}" method="POST">
                            @csrf {{-- CSRF token for security --}}
                            @method('PUT') {{-- Spoof PUT method for HTML forms --}}
                            @include('projects.form', ['project' => $project]) {{-- Pass the $project variable to the partial --}}

                            <div class="flex items-center justify-between mt-4">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Update Project
                                </button>
                                <a href="{{ route('projects.show', $project) }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                    {{-- Your original content from the @section('content') block ends here --}}

                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</x-app-layout>