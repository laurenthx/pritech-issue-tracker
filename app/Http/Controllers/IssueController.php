<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Issue;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\User; // Added for bonus members feature

use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Http\Requests\StoreCommentRequest; // Added for comment validation

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // Added for error logging

class IssueController extends Controller
{
    /**
     * Display a listing of the resource (global issues list with filters and search).
     */
    public function index(Request $request)
    {
        $query = Issue::with(['project', 'tags']); // Eager load project and tags for each issue

        // --- START ADDED: Text Search Logic ---
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }
        // --- END ADDED ---

        // Existing Filter Logic (no changes, already handles empty values correctly)
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        if ($request->has('priority') && $request->priority !== '') {
            $query->where('priority', $request->priority);
        }
        if ($request->has('tag_id') && $request->tag_id !== '') {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }

        $issues = $query->orderBy('created_at', 'desc')->paginate(10);
        $tags = Tag::orderBy('name')->get();

        // --- START ADDED: Conditional view rendering for AJAX ---
        if ($request->ajax()) {
            return view('issues.partials.issues_table', compact('issues'))->render();
        }
        // --- END ADDED ---

        return view('issues.index', compact('issues', 'tags'));
    }


    /**
     * Show the form for creating a new issue for a specific project.
     */
    public function create(Project $project)
    {
        return view('issues.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage for a specific project.
     */
    public function store(StoreIssueRequest $request, Project $project)
    {
        $data = $request->validated();
        $data['project_id'] = $project->id;
        $issue = Issue::create($data);

        return redirect()->route('projects.issues.show', [$project, $issue])->with('success', 'Issue created successfully!');
    }

    /**
     * Display the specified resource (a single issue's details).
     */
    public function show(Project $project, Issue $issue)
    {
        if ($issue->project_id !== $project->id) {
            abort(404, 'Issue not found in this project.');
        }

        $issue->load(['project', 'tags', 'members', 'comments' => function ($query) {
            $query->latest();
        }]);

        return view('issues.show', compact('project', 'issue'));
    }

    /**
     * Show the form for editing the specified resource (issue).
     */
    public function edit(Project $project, Issue $issue)
    {
        if ($issue->project_id !== $project->id) {
            abort(404, 'Issue not found in this project.');
        }
        return view('issues.edit', compact('project', 'issue'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIssueRequest $request, Project $project, Issue $issue)
    {
        if ($issue->project_id !== $project->id) {
            abort(404, 'Issue not found in this project.');
        }
        $issue->update($request->validated());
        return redirect()->route('projects.issues.show', [$project, $issue])->with('success', 'Issue updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * This method is used by the `projects.issues.destroy` route (e.g., from an Issue Detail page or Project's issues list).
     * It redirects back to the project's issues list after deletion.
     */
    public function destroy(Project $project, Issue $issue)
    {
        if ($issue->project_id !== $project->id) {
            abort(404, 'Issue not found in this project.');
        }
        $issue->delete();

        // Redirects to the project's detail page, which lists its issues.
        return redirect()->route('projects.show', $project)->with('success', 'Issue deleted successfully!');
    }

    /**
     * Remove the specified resource from storage globally.
     * This method is used by the new `issues.global_destroy` route (e.g., from the All Issues list).
     * It redirects back to the previous page (the global issues list) after deletion.
     */
    public function globalDestroy(Issue $issue) // Note: This only receives an Issue, not a Project
    {
        $issue->delete();
        // Redirects back to the page from which the delete request originated (e.g., /issues page).
        return redirect()->back()->with('success', 'Issue deleted successfully!');
    }


    // --- START: FULL AJAX API METHODS ---

    /**
     * API: Get all tags and the tags attached to the issue.
     */
    public function getTags(Issue $issue)
    {
        $allTags = Tag::all();
        $attachedTagIds = $issue->tags->pluck('id')->toArray();

        $allTags->each(function ($tag) use ($attachedTagIds) {
            $tag->is_attached = in_array($tag->id, $attachedTagIds);
        });

        return response()->json([
            'allTags' => $allTags,
            'attachedTags' => $issue->tags,
        ]);
    }

    /**
     * API: Toggle (attach/detach) a tag for an issue.
     */
    public function toggleTag(Issue $issue, Tag $tag)
    {
        $issue->tags()->toggle($tag->id);
        $issue->load('tags'); // Reload to get the updated list
        return response()->json(['attachedTags' => $issue->tags, 'message' => 'Tag toggled successfully.']);
    }

    /**
     * API: Get paginated comments for an issue.
     */
    public function getComments(Issue $issue, Request $request)
    {
        $comments = $issue->comments()->orderBy('created_at', 'desc')->paginate(5, ['*'], 'page', $request->input('page', 1));
        return response()->json($comments);
    }

    /**
     * API: Add a new comment to an issue.
     * Uses StoreCommentRequest for validation.
     */
    public function addComment(StoreCommentRequest $request, Issue $issue)
    {
        try {
            $comment = new Comment([
                'issue_id' => $issue->id,
                'author_name' => $request->validated('author_name'),
                'body' => $request->validated('body'),
            ]);
            $issue->comments()->save($comment);
            return response()->json($comment->fresh(), 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error adding comment: ' . $e->getMessage(), ['issue_id' => $issue->id, 'request_data' => $request->all()]);
            return response()->json(['message' => 'Failed to add comment due to an internal error.'], 500);
        }
    }

    /**
     * API (Bonus): Get all users and users assigned to the issue.
     */
    public function getMembers(Issue $issue)
    {
        $allUsers = User::all(['id', 'name']);
        $assignedUserIds = $issue->members->pluck('id')->toArray();

        $allUsers->each(function ($user) use ($assignedUserIds) {
            $user->is_assigned = in_array($user->id, $assignedUserIds);
        });

        return response()->json([
            'allUsers' => $allUsers,
            'assignedMembers' => $issue->members,
        ]);
    }

    /**
     * API (Bonus): Toggle (assign/unassign) a user for an issue.
     */
    public function toggleMember(Issue $issue, User $user)
    {
        $issue->members()->toggle($user->id);
        $issue->load('members');
        return response()->json(['assignedMembers' => $issue->members, 'message' => 'Member toggled successfully.']);
    }

    // --- END: FULL AJAX API METHODS ---
}