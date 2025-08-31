{{-- This partial is used by both create.blade.php and edit.blade.php for issues --}}
<div class="mb-4">
    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Issue Title:</label>
    <input type="text" name="title" id="title" value="{{ old('title', $issue->title ?? '') }}"
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror" required>
    @error('title')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
    <textarea name="description" id="description" rows="5"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description', $issue->description ?? '') }}</textarea>
    @error('description')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div>
        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
        <select name="status" id="status"
                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" required>
            <option value="open" {{ old('status', $issue->status ?? '') == 'open' ? 'selected' : '' }}>Open</option>
            <option value="in_progress" {{ old('status', $issue->status ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="closed" {{ old('status', $issue->status ?? '') == 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
        @error('status')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="priority" class="block text-gray-700 text-sm font-bold mb-2">Priority:</label>
        <select name="priority" id="priority"
                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('priority') border-red-500 @enderror" required>
            <option value="low" {{ old('priority', $issue->priority ?? '') == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ old('priority', $issue->priority ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ old('priority', $issue->priority ?? '') == 'high' ? 'selected' : '' }}>High</option>
        </select>
        @error('priority')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="due_date" class="block text-gray-700 text-sm font-bold mb-2">Due Date:</label>
        <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $issue->due_date ? $issue->due_date->format('Y-m-d') : '') }}"
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('due_date') border-red-500 @enderror">
        @error('due_date')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>
</div>