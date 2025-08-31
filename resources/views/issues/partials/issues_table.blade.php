{{-- resources/views/issues/partials/issues_table.blade.php --}}

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full leading-normal" id="issuesTable">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Project
                </th>
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
                    Tags
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Due Date
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($issues as $issue)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <a href="{{ route('projects.show', $issue->project) }}" class="text-blue-600 hover:text-blue-900">{{ $issue->project->name }}</a>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ $issue->title }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                            <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full
                                @if($issue->status == 'open') bg-blue-200
                                @elseif($issue->status == 'in_progress') bg-yellow-200
                                @else bg-green-200 @endif"></span>
                            <span class="relative @if($issue->status == 'open') text-blue-900
                                @elseif($issue->status == 'in_progress') text-yellow-900
                                @else text-green-900 @endif">{{ $issue->status }}</span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                         <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                            <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full
                                @if($issue->priority == 'low') bg-gray-200
                                @elseif($issue->priority == 'medium') bg-orange-200
                                @else bg-red-200 @endif"></span>
                            <span class="relative @if($issue->priority == 'low') text-gray-900
                                @elseif($issue->priority == 'medium') text-orange-900
                                @else text-red-900 @endif">{{ $issue->priority }}</span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        {{-- Display issue's tags --}}
                        @forelse($issue->tags as $tag)
                            <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold text-gray-700 mr-2 mb-2" style="background-color: {{ $tag->color ?? '#E5E7EB' }};">
                                {{ $tag->name }}
                            </span>
                        @empty
                            <span class="text-gray-500">No Tags</span>
                        @endforelse
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ $issue->due_date ? $issue->due_date->format('Y-m-d') : 'N/A' }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                        {{-- Links to show, edit the issue, passing both project and issue --}}
                        <a href="{{ route('projects.issues.show', [$issue->project, $issue]) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                        <a href="{{ route('projects.issues.edit', [$issue->project, $issue]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        
                        {{-- Use the global_destroy route for global issues list --}}
                        <form action="{{ route('issues.global_destroy', $issue) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this issue? This will also delete all associated comments and tag relationships!');">
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

{{-- Pagination Links --}}
<div class="mt-4" id="pagination-links">
    {{ $issues->appends(request()->query())->links() }}
</div>