<?php

namespace App\Http\Controllers\Api; // Note the 'Api' namespace

use App\Http\Controllers\Controller;
use App\Models\Comment; // Import Comment model
use App\Models\Issue;   // Import Issue model (comments belong to issues)
use App\Http\Requests\StoreCommentRequest; // Import the custom form request
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a paginated listing of comments for a specific issue.
     * Responds to GET /api/issues/{issue}/comments
     */
    public function index(Request $request, Issue $issue)
    {
        // Paginate comments for the given issue, ordered by latest, 5 per page
        $comments = $issue->comments()->latest()->paginate(5);

        // Laravel automatically converts the Paginator instance to JSON format
        return response()->json($comments);
    }

    /**
     * Store a newly created comment for a specific issue.
     * Responds to POST /api/issues/{issue}/comments
     */
    public function store(StoreCommentRequest $request, Issue $issue) // Validated request
    {
        // Create the comment associated with the provided issue
        $comment = $issue->comments()->create($request->validated());

        // Return the newly created comment as JSON with a 201 Created status
        return response()->json($comment, 201);
    }
}