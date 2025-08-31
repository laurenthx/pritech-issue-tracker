<x-app-layout>
    {{-- Header Slot --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Issue Details: ' . $issue->title) }}
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
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold text-gray-800">Issue: {{ $issue->title }}</h1>
                        <div class="space-x-2">
                            <a href="{{ route('projects.issues.edit', [$project, $issue]) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Edit Issue
                            </a>
                            <form action="{{ route('projects.issues.destroy', [$project, $issue]) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this issue? This will also delete all associated comments and tag relationships!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Delete Issue
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Issue Details</h2>
                        <p class="mb-2"><strong>Project:</strong> <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:underline">{{ $project->name }}</a></p>
                        <p class="mb-2"><strong>Description:</strong> {{ $issue->description }}</p>
                        <p class="mb-2"><strong>Status:</strong> <span class="relative inline-block px-3 py-1 font-semibold leading-tight"><span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full @if($issue->status == 'open') bg-blue-200 @elseif($issue->status == 'in_progress') bg-yellow-200 @else bg-green-200 @endif"></span><span class="relative @if($issue->status == 'open') text-blue-900 @elseif($issue->status == 'in_progress') text-yellow-900 @else text-green-900 @endif">{{ $issue->status }}</span></span></p>
                        <p class="mb-2"><strong>Priority:</strong> <span class="relative inline-block px-3 py-1 font-semibold leading-tight"><span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full @if($issue->priority == 'low') bg-gray-200 @elseif($issue->priority == 'medium') bg-orange-200 @else bg-red-200 @endif"></span><span class="relative @if($issue->priority == 'low') text-gray-900 @elseif($issue->priority == 'medium') text-orange-900 @else text-red-900 @endif">{{ $issue->priority }}</span></span></p>
                        <p class="mb-2"><strong>Due Date:</strong> {{ $issue->due_date ? $issue->due_date->format('Y-m-d') : 'N/A' }}</p>
                        <p class="mb-2"><strong>Created At:</strong> {{ $issue->created_at->format('Y-m-d H:i') }}</p>
                        <p class="mb-2"><strong>Updated At:</strong> {{ $issue->updated_at->format('Y-m-d H:i') }}</p>

                        {{-- Tags Section --}}
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Tags:</h3>
                            <div id="issue-tags-list" class="flex flex-wrap gap-2 mb-4">
                                @forelse($issue->tags as $tag)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-gray-800" style="background-color: {{ $tag->color ?? '#E5E7EB' }};">
                                        {{ $tag->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-500">No tags attached.</span>
                                @endforelse
                            </div>
                            <button id="manageTagsBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-3 rounded text-sm">
                                Manage Tags
                            </button>
                            <div id="tagManager" class="hidden bg-gray-50 p-4 rounded-lg mt-4 border border-gray-200">
                                <h4 class="font-semibold mb-3">Attach/Detach Tags</h4>
                                <div id="tagOptions" class="flex flex-wrap gap-2 mb-4">
                                    {{-- Tags will be loaded here by AJAX --}}
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Click tags to toggle attachment.</p>
                                <button id="closeTagManager" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-sm">Close</button>
                            </div>
                        </div>

                        {{-- Assigned Members Section --}}
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Assigned Members:</h3>
                            <div id="issue-members-list" class="flex flex-wrap gap-2 mb-4">
                                @forelse($issue->members as $member)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-200 text-purple-800">
                                        {{ $member->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-500">No members assigned.</span>
                                @endforelse
                            </div>
                            <button id="manageMembersBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-3 rounded text-sm">
                                Manage Members
                            </button>

                            <div id="memberManager" class="hidden bg-gray-50 p-4 rounded-lg mt-4 border border-gray-200">
                                <h4 class="font-semibold mb-3">Assign/Unassign Members</h4>
                                <div id="memberOptions" class="flex flex-wrap gap-2 mb-4">
                                    {{-- Member options will be loaded here by AJAX --}}
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Click member names to toggle assignment.</p>
                                <button id="closeMemberManager" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-sm">Close</button>
                            </div>
                        </div>
                    </div> {{-- END of <div class="bg-white shadow-md rounded-lg p-6 mb-8"> where Issue Details are --}}

                    {{-- Comments Section (outside the main details block, still within p-6 text-gray-900) --}}
                    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Comments</h2>
                        <div id="comments-list">
                            {{-- Comments will be loaded here via AJAX --}}
                            <p class="text-gray-500 text-center py-4" id="loading-comments">Loading comments...</p>
                        </div>
                        <div class="flex justify-center mt-4">
                            <button id="loadMoreCommentsBtn" class="hidden bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Load More Comments</button>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-700 mb-4 mt-8">Add a New Comment</h3>
                        <form id="addCommentForm" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            @csrf
                            <input type="hidden" name="issue_id" value="{{ $issue->id }}">
                            <div class="mb-4">
                                <label for="author_name" class="block text-gray-700 text-sm font-bold mb-2">Your Name:</label>
                                <input type="text" name="author_name" id="author_name"
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <p id="author_name-error" class="text-red-500 text-xs italic hidden"></p>
                            </div>
                            <div class="mb-6">
                                <label for="body" class="block text-gray-700 text-sm font-bold mb-2">Comment:</label>
                                <textarea name="body" id="body" rows="4"
                                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                                <p id="body-error" class="text-red-500 text-xs italic hidden"></p>
                            </div>
                            <div class="flex items-center justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Add Comment
                                </button>
                            </div>
                        </form>
                    </div>
                    {{-- End of main content from the @section('content') block --}}

                </div> {{-- End of <div class="p-6 text-gray-900 dark:text-gray-100"> --}}
            </div> {{-- End of <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"> --}}
        </div> {{-- End of <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> --}}
    </div> {{-- End of <div class="py-12"> --}}

    @push('scripts')
    <script>
        window.ISSUE_ID = @json($issue->id);
        window.PROJECT_ID = @json($project->id);

        // This is the LINE THAT NEEDS TO CHANGE: Escape the @ symbol
        console.log('DEBUG show.blade.php @@push(scripts): Global vars set:');

        console.log('  ISSUE_ID:', window.ISSUE_ID);
        console.log('  PROJECT_ID:', window.PROJECT_ID);
    </script>
    @endpush {{-- Correct: Simply @endpush, no arguments --}}

</x-app-layout>