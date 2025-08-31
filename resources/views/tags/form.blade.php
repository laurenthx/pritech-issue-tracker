{{-- This partial will be included in both create.blade.php and edit.blade.php for tags --}}
<div class="mb-4">
    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Tag Name:</label>
    <input type="text" name="name" id="name" value="{{ old('name', $tag->name ?? '') }}"
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" required>
    @error('name')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

<div class="mb-6">
    <label for="color" class="block text-gray-700 text-sm font-bold mb-2">Color (Hex, e.g., #FF0000):</label>
    {{-- HTML5 color input for a visual color picker --}}
    <input type="color" name="color" id="color" value="{{ old('color', $tag->color ?? '#CCCCCC') }}"
           class="shadow appearance-none border rounded w-16 h-8 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('color') border-red-500 @enderror">
    {{-- Text input for manual hex code entry, synchronized with the color picker --}}
    <input type="text" name="color_text" id="color_text" value="{{ old('color', $tag->color ?? '#CCCCCC') }}"
           class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ml-2 w-32"
           oninput="document.getElementById('color').value = this.value;"
           onchange="if(!this.value.startsWith('#') && this.value.length > 0) this.value = '#' + this.value; document.getElementById('color').value = this.value;">
    @error('color')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
    <p class="text-gray-600 text-xs mt-1">Leave blank for default display color, or use hex code (e.g., #FFC107).</p>
</div>

{{-- JavaScript to synchronize the color input and text input --}}
<script>
    document.getElementById('color').addEventListener('input', function() {
        document.getElementById('color_text').value = this.value;
    });
    document.getElementById('color_text').addEventListener('input', function() {
         // Basic regex for hex color to ensure the color picker updates visually
        const hexColorPattern = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/i;
        if (hexColorPattern.test(this.value)) {
             document.getElementById('color').value = this.value;
        }
    });
    // Initial sync on page load
    document.addEventListener('DOMContentLoaded', () => {
         document.getElementById('color_text').value = document.getElementById('color').value;
    });
</script>