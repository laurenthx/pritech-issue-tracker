<?php

namespace App\Http\Controllers;

use App\Models\Tag;       // Import Tag model
use App\Models\Issue;     // Import Issue model for AJAX tag management
use App\Http\Requests\StoreTagRequest; // Import custom form requests
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request; // Import Request for AJAX validation/handling

class TagController extends Controller
{
    /**
     * Display a listing of the resource (all tags).
     */
    public function index()
    {
        $tags = Tag::orderBy('name')->get(); // Get all tags, ordered alphabetically by name
        return view('tags.index', compact('tags')); // Pass tags to the view
    }

    /**
     * Show the form for creating a new resource (tag).
     */
    public function create()
    {
        return view('tags.create'); // Display the tag creation form
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request) // Validated request
    {
        Tag::create($request->validated());
        return redirect()->route('tags.index')->with('success', 'Tag created successfully!');
    }

    /**
     * Display the specified resource.
     * For tags, we don't need a dedicated 'show' page; the index lists them.
     * We'll redirect back to index.
     */
    public function show(Tag $tag)
    {
        return redirect()->route('tags.index');
    }

    /**
     * Show the form for editing the specified resource (tag).
     */
    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag')); // Display the tag edit form
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag) // Validated request
    {
        $tag->update($request->validated());
        return redirect()->route('tags.index')->with('success', 'Tag updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        // Due to onDelete('cascade') in the 'issue_tag' migration, relationships will be removed.
        return redirect()->route('tags.index')->with('success', 'Tag deleted successfully! Note: Issue-Tag relationships were also removed.');
    }

    // --- AJAX Endpoints for attaching/detaching tags to issues ---

    /**
     * Toggles (attaches/detaches) a tag for a given issue.
     * Used for AJAX requests.
     */
    public function toggleIssueTag(Request $request, Issue $issue, Tag $tag)
    {
        // Ensure this is an AJAX request to respond with JSON
        if ($request->ajax()) {
            if ($issue->tags()->where('tag_id', $tag->id)->exists()) {
                // Tag is already attached, so detach it
                $issue->tags()->detach($tag);
                return response()->json(['status' => 'detached', 'message' => 'Tag detached successfully.']);
            } else {
                // Tag is not attached, so attach it
                $issue->tags()->attach($tag);
                return response()->json(['status' => 'attached', 'message' => 'Tag attached successfully.']);
            }
        }
        // If not an AJAX request, respond with an error
        return response()->json(['message' => 'Invalid request'], 400);
    }

    /**
     * Gets all available tags and indicates which ones are attached to a specific issue.
     * Used for AJAX requests to populate the tag management interface.
     */
    public function getIssueTags(Request $request, Issue $issue)
    {
        if ($request->ajax()) {
            $issueTags = $issue->tags()->pluck('tags.id')->toArray(); // Get IDs of tags attached to this issue
            $allTags = Tag::select('id', 'name', 'color')->orderBy('name')->get(); // Get all available tags

            // Format data to include 'is_attached' status for client-side rendering
            $formattedTags = $allTags->map(function ($tag) use ($issueTags) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'color' => $tag->color,
                    'is_attached' => in_array($tag->id, $issueTags), // Check if this tag is attached to the issue
                ];
            });

            // Also return just the attached tags for updating the display list on the page
            return response()->json([
                'allTags' => $formattedTags, // All tags with their attached status
                'attachedTags' => $issue->tags()->select('tags.id', 'tags.name', 'tags.color')->get() // Only currently attached tags
            ]);
        }
        return response()->json(['message' => 'Invalid request'], 400);
    }
}