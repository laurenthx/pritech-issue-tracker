{{-- This partial will be included in both create.blade.php and edit.blade.php --}}
<div class="mb-4">
    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Project Name:</label>
    <input type="text" name="name" id="name" value="{{ old('name', $project->name ?? '') }}"
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" required>
    @error('name')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
    <textarea name="description" id="description" rows="5"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description', $project->description ?? '') }}</textarea>
    @error('description')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Start Date:</label>
    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}"
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('start_date') border-red-500 @enderror">
    @error('start_date')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

<div class="mb-6">
    <label for="deadline" class="block text-gray-700 text-sm font-bold mb-2">Deadline:</label>
    <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $project->deadline ? $project->deadline->format('Y-m-d') : '') }}"
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('deadline') border-red-500 @enderror">
    @error('deadline')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>