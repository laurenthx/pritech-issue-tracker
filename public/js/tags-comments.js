// public/js/tags-comments.js

document.addEventListener('DOMContentLoaded', () => {
    // --- Essential Globals ---
    const CSRF_TOKEN = window.CSRF_TOKEN;
    const ISSUE_ID = window.ISSUE_ID;
    const PROJECT_ID = window.PROJECT_ID;

    // Diagnostic check: This is the error message you're seeing
    if (!CSRF_TOKEN || !ISSUE_ID || !PROJECT_ID) {
        console.error('tags-comments.js: Global variables were not properly set on the window object. AJAX functions will not work.');
        return; // Fail safe if they are somehow still missing
    } else {
        // console.log('DEBUG: Global variables successfully set:');
        // console.log('CSRF_TOKEN:', CSRF_TOKEN);
        // console.log('ISSUE_ID:', ISSUE_ID);
        // console.log('PROJECT_ID:', PROJECT_ID);
    }

    // --- DOM Elements for Tags ---
    const tagManagerBtn = document.getElementById('manageTagsBtn');
    const tagManager = document.getElementById('tagManager');
    const tagOptionsDiv = document.getElementById('tagOptions');
    const issueTagsListDiv = document.getElementById('issue-tags-list');
    const closeTagManagerBtn = document.getElementById('closeTagManager');

    // --- DOM Elements for Members ---
    const memberManagerBtn = document.getElementById('manageMembersBtn');
    const memberManager = document.getElementById('memberManager');
    const memberOptionsDiv = document.getElementById('memberOptions');
    const issueMembersListDiv = document.getElementById('issue-members-list');
    const closeMemberManagerBtn = document.getElementById('closeMemberManager');

    // --- DOM Elements for Comments ---
    const commentsList = document.getElementById('comments-list');
    const loadMoreCommentsBtn = document.getElementById('loadMoreCommentsBtn');
    const addCommentForm = document.getElementById('addCommentForm');
    let commentsPage = 1;
    let commentsLastPage = 1;


    // --- Helper Functions for AJAX and UI ---

    async function fetchData(url, method = 'GET', data = null) {
        try {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            };
            if (data) {
                options.body = JSON.stringify(data);
            }
            const response = await fetch(url, options);

            if (response.status === 422) {
                const errorData = await response.json();
                throw { status: 422, errors: errorData.errors, message: 'Validation failed.' };
            }

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ message: 'Unknown error' }));
                throw new Error(errorData.message || response.statusText || 'Something went wrong.');
            }
            return await response.json();
        } catch (error) {
            console.error('AJAX Error:', error);
            if (error.status !== 422) {
                // alert('Operation failed: ' + (error.message || 'Please check the console for details.'));
            }
            throw error;
        }
    }

    function renderClickableTag(tag, isAttached, issueId) {
        const span = document.createElement('span');
        span.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium cursor-pointer transition duration-150 ease-in-out border
                         ${isAttached ? 'text-gray-800 border-gray-400 font-bold' : 'text-gray-600 border-gray-300'}`;
        span.style.backgroundColor = tag.color || '#E5E7EB';
        span.textContent = tag.name;
        span.dataset.tagId = tag.id;
        span.addEventListener('click', () => toggleTag(issueId, tag.id));
        return span;
    }

    function renderAttachedTagsDisplay(tags) {
        issueTagsListDiv.innerHTML = '';
        if (tags.length === 0) {
            issueTagsListDiv.innerHTML = '<span class="text-gray-500">No tags attached.</span>';
            return;
        }
        tags.forEach(tag => {
            const span = document.createElement('span');
            span.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-gray-800`;
            span.style.backgroundColor = tag.color || '#E5E7EB';
            span.textContent = tag.name;
            issueTagsListDiv.appendChild(span);
        });
    }

    function renderClickableMember(member, isAssigned, issueId) {
        const span = document.createElement('span');
        span.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium cursor-pointer transition duration-150 ease-in-out border
                         ${isAssigned ? 'bg-purple-200 text-purple-800 border-purple-400 font-bold' : 'bg-gray-100 text-gray-600 border-gray-300'}`;
        span.textContent = member.name;
        span.dataset.userId = member.id;
        span.addEventListener('click', () => toggleMember(issueId, member.id));
        return span;
    }

    function renderAssignedMembersDisplay(members) {
        issueMembersListDiv.innerHTML = '';
        if (members.length === 0) {
            issueMembersListDiv.innerHTML = '<span class="text-gray-500">No members assigned.</span>';
            return;
        }
        members.forEach(member => {
            const span = document.createElement('span');
            span.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-200 text-purple-800`;
            span.textContent = member.name;
            issueMembersListDiv.appendChild(span);
        });
    }


    function renderComment(comment) {
        const commentDiv = document.createElement('div');
        commentDiv.className = 'bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4';
        commentDiv.innerHTML = `
            <p class="text-sm text-gray-500 mb-1"><strong>${comment.author_name}</strong> on ${new Date(comment.created_at).toLocaleString()}</p>
            <p class="text-gray-800">${comment.body}</p>
        `;
        return commentDiv;
    }

    function displayValidationError(field, message, formElement) {
        const errorElement = formElement.querySelector(`#${field}-error`);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
            const inputElement = formElement.querySelector(`[name="${field}"]`);
            if (inputElement) {
                inputElement.classList.add('border-red-500');
            }
        }
    }

    function clearValidationErrors(formElement) {
        formElement.querySelectorAll('.text-red-500.text-xs.italic').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        formElement.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
        });
    }


    // --- Tag Management Logic ---

    async function loadTagsForIssue() {
        try {
            const data = await fetchData(`/api/issues/${ISSUE_ID}/tags`);
            tagOptionsDiv.innerHTML = '';
            data.allTags.forEach(tag => {
                const tagElement = renderClickableTag(tag, tag.is_attached, ISSUE_ID);
                tagOptionsDiv.appendChild(tagElement);
            });
            renderAttachedTagsDisplay(data.attachedTags);
        } catch (error) {
            console.error('Failed to load tags:', error);
        }
    }

    async function toggleTag(issueId, tagId) {
        try {
            await fetchData(`/api/issues/${issueId}/tags/${tagId}/toggle`, 'POST');
            await loadTagsForIssue();
        } catch (error) {
            console.error('Failed to toggle tag:', error);
        }
    }

    // --- Event Listeners for Tag Manager UI ---

    if (tagManagerBtn) {
        tagManagerBtn.addEventListener('click', () => {
            tagManager.classList.toggle('hidden');
            if (!tagManager.classList.contains('hidden')) {
                loadTagsForIssue();
            }
        });
    }
    if (closeTagManagerBtn) {
        closeTagManagerBtn.addEventListener('click', () => {
            tagManager.classList.add('hidden');
        });
    }

    // --- Member Management Logic (NEW) ---

    async function loadMembersForIssue() {
        try {
            const data = await fetchData(`/api/issues/${ISSUE_ID}/members`);
            memberOptionsDiv.innerHTML = '';
            data.allUsers.forEach(user => {
                const memberElement = renderClickableMember(user, user.is_assigned, ISSUE_ID);
                memberOptionsDiv.appendChild(memberElement);
            });
            renderAssignedMembersDisplay(data.assignedMembers);
        } catch (error) {
            console.error('Failed to load members:', error);
        }
    }

    async function toggleMember(issueId, userId) {
        try {
            await fetchData(`/api/issues/${issueId}/members/${userId}/toggle`, 'POST');
            await loadMembersForIssue(); // Reload members to update UI
        } catch (error) {
            console.error('Failed to toggle member:', error);
        }
    }

    // --- Event Listeners for Member Manager UI (NEW) ---
    if (memberManagerBtn) {
        memberManagerBtn.addEventListener('click', () => {
            memberManager.classList.toggle('hidden');
            if (!memberManager.classList.contains('hidden')) {
                loadMembersForIssue(); // Load members when manager opens
            }
        });
    }
    if (closeMemberManagerBtn) {
        closeMemberManagerBtn.addEventListener('click', () => {
            memberManager.classList.add('hidden'); // Hide the manager
        });
    }


    // --- Comment Management Logic ---

    async function loadComments() {
        const loadingText = document.getElementById('loading-comments');
        if (loadingText) {
            if (commentsPage === 1) loadingText.textContent = 'Loading comments...';
            else loadingText.textContent = 'Loading more comments...';
        }
        if (loadMoreCommentsBtn) loadMoreCommentsBtn.classList.add('hidden'); // Hide until we know if there's more

        try {
            const data = await fetchData(`/api/issues/${ISSUE_ID}/comments?page=${commentsPage}`);

            commentsLastPage = data.last_page;

            if (loadingText && loadingText.parentNode) loadingText.remove(); // Remove loading text once data is here

            if (data.data.length === 0 && commentsPage === 1) {
                if (!document.getElementById('no-comments-yet')) { // Only add if not already there
                    commentsList.innerHTML = '<p class="text-gray-500 text-center py-4" id="no-comments-yet">No comments yet. Be the first to add one!</p>';
                }
            } else {
                data.data.forEach(comment => {
                    commentsList.appendChild(renderComment(comment));
                });
            }

            if (commentsPage < commentsLastPage) {
                if (loadMoreCommentsBtn) loadMoreCommentsBtn.classList.remove('hidden');
            } else {
                if (loadMoreCommentsBtn) loadMoreCommentsBtn.classList.add('hidden');
            }
            commentsPage++;
        } catch (error) {
            console.error('Error loading comments:', error);
            if (loadingText) loadingText.textContent = 'Failed to load comments.';
            if (loadMoreCommentsBtn) loadMoreCommentsBtn.classList.add('hidden'); // Hide button on error
        }
    }

    if (addCommentForm) {
        addCommentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearValidationErrors(addCommentForm);

            const formData = new FormData(addCommentForm);
            const data = Object.fromEntries(formData.entries());

            try {
                // Ensure issue_id is explicitly sent if your StoreCommentRequest validates it
                // and it's present as a hidden input in the form.
                data.issue_id = ISSUE_ID; // Override to be safe, ensures correct ID for API call

                const newComment = await fetchData(`/api/issues/${ISSUE_ID}/comments`, 'POST', data);

                const newCommentElement = renderComment(newComment);
                commentsList.prepend(newCommentElement); // Prepend to add at the top

                const noCommentsMessage = document.getElementById('no-comments-yet');
                if (noCommentsMessage) noCommentsMessage.remove(); // Remove 'No comments yet' message

                addCommentForm.reset(); // Clear form fields

                // If this was the first comment, adjust pagination state if necessary
                if (commentsPage === 1 && commentsLastPage === 1) { // Meaning this is the first page, and we just added the only item.
                    if (loadMoreCommentsBtn) loadMoreCommentsBtn.classList.add('hidden'); // Still no more to load
                }

            } catch (error) {
                if (error.status === 422 && error.errors) {
                    for (const field in error.errors) {
                        displayValidationError(field, error.errors[field][0], addCommentForm);
                    }
                } else {
                    console.error('Error adding comment:', error);
                }
            }
        });
    }


    // --- Initial Calls (when the DOM is loaded) ---
    // These run once the script is loaded on the issue detail page.
    // It relies on the isOnIssueDetailPage check, which needs globals to be present.
    const isOnIssueDetailPage = (document.getElementById('manageTagsBtn') !== null);

    if (isOnIssueDetailPage) {
        if (commentsList) {
            loadComments();
        }

        if (loadMoreCommentsBtn) {
            loadMoreCommentsBtn.addEventListener('click', loadComments);
        }
    }
});