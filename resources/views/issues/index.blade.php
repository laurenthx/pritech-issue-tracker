<x-app-layout>
    {{-- Header Slot --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All Issues') }}
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

                    <h1 class="text-3xl font-bold text-gray-800 mb-6">All Issues</h1>

                    {{-- Filters & Search Section --}}
                    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">Filter & Search Issues</h2>

                        {{-- Search Input Field --}}
                        <div class="mb-6">
                            <label for="issueSearchInput" class="block text-gray-700 text-sm font-bold mb-2">Search by Title/Description:</label>
                            {{-- Retain search term if present in URL --}}
                            <input type="text" id="issueSearchInput" value="{{ request('search') }}" placeholder="Type to search issues..."
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        {{-- Filters Form (now integrated with AJAX search via JS) --}}
                        <form id="issueFilterForm" action="{{ route('issues.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Status Filter --}}
                            <div>
                                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                                <select name="status" id="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">All</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            {{-- Priority Filter --}}
                            <div>
                                <label for="priority" class="block text-gray-700 text-sm font-bold mb-2">Priority:</label>
                                <select name="priority" id="priority" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">All</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>
                            {{-- Tag Filter --}}
                            <div>
                                <label for="tag_id" class="block text-gray-700 text-sm font-bold mb-2">Tag:</label>
                                <select name="tag_id" id="tag_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">All</option>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ (string)request('tag_id') === (string)$tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-3 flex justify-end mt-4">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Apply Filters
                                </button>
                                <a href="{{ route('issues.index') }}" class="ml-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Clear Filters
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Issue List Container for AJAX Updates --}}
                    <div id="issue-list-container">
                        @if ($issues->isEmpty())
                            <div class="bg-white shadow-md rounded-lg p-6" id="no-issues-message">
                                <p class="text-gray-600">No issues found matching your criteria.</p>
                            </div>
                        @else
                            {{-- Includes the table from the partial --}}
                            @include('issues.partials.issues_table', ['issues' => $issues])
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</x-app-layout>