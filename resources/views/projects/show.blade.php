<x-app-layout>
    {{-- Header Slot --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Project Details: ' . $project->name) }}
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

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold text-gray-800">Project: {{ $project->name }}</h1>
                        <div class="space-x-2">
                            {{-- <--- ADDED: Conditional Edit Button --}}
                            @can('update', $project)
                                <a href="{{ route('projects.edit', $project) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Edit Project
                                </a>
                            @endcan

                            {{-- <--- ADDED: Conditional Delete Button --}}
                            @can('delete', $project)
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this project? This will also delete all associated issues, tags, and comments!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Delete Project
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Project Details</h2>
                        <p class="mb-2"><strong>Description:</strong> {{ $project->description }}</p>
                        <p class="mb-2"><strong>Owner:</strong> {{ $project->user->name ?? 'N/A' }}</p> {{-- <--- ADDED: Display Project Owner --}}
                        <p class="mb-2"><strong>Start Date:</strong> {{ $project->start_date ? $project->start_date->format('Y-m-d') : 'N/A' }}</p>
                        <p class="mb-2"><strong>Deadline:</strong> {{ $project->deadline ? $project->deadline->format('Y-m-d') : 'N/A' }}</p>
                        <p class="mb-2"><strong>Created At:</strong> {{ $project->created_at->format('Y-m-d H:i') }}</p>
                        <p class="mb-2"><strong>Updated At:</strong> {{ $project->updated_at->format('Y-m-d H:i') }}</p>
                    </div>

                    {{-- Section for listing issues --}}
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Issues for {{ $project->name }}</h2>
                        <a href="{{ route('projects.issues.create', $project) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Add New Issue
                        </a>
                    </div>

                    @if ($project->issues->isEmpty())
                        <div class="bg-white shadow-md rounded-lg p-6">
                            <p class="text-gray-600">No issues found for this project. Start by adding one!</p>
                        </div>
                    @else
                        <div class="bg-white shadow-md rounded-lg overflow-hidden">
                            <table class="min-w-full leading-normal">
                                <thead>
                                    <tr>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Title
                                        </th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Priority
                                        </th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Due Date
                                        </th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($project->issues as $issue)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ $issue->title }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                                                    <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full
                                                        @if($issue->status == 'open') bg-blue-200 text-blue-900
                                                        @elseif($issue->status == 'in_progress') bg-yellow-200 text-yellow-900
                                                        @else bg-green-200 text-green-900 @endif"></span>
                                                    <span class="relative">{{ $issue->status }}</span>
                                                </span>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                 <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                                                    <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full
                                                        @if($issue->priority == 'low') bg-gray-200 text-gray-900
                                                        @elseif($issue->priority == 'medium') bg-orange-200 text-orange-900
                                                        @else bg-red-200 text-red-900 @endif"></span>
                                                    <span class="relative">{{ $issue->priority }}</span>
                                                </span>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ $issue->due_date ? $issue->due_date->format('Y-m-d') : 'N/A' }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                                <a href="{{ route('projects.issues.show', [$project, $issue]) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                <a href="{{ route('projects.issues.edit', [$project, $issue]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                <form action="{{ route('projects.issues.destroy', [$project, $issue]) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this issue? This will also delete all associated comments and tag relationships!');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
</x-app-layout>