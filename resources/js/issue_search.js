// resources/js/issue_search.js

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('issueSearchInput');
    const filterForm = document.getElementById('issueFilterForm');
    const issueListContainer = document.getElementById('issue-list-container');
    // searchTimeout is handled internally by the debounce function

    // Early exit if crucial elements are missing (e.g., if on a different page)
    if (!searchInput || !filterForm || !issueListContainer) {
        console.info('issue_search.js: Not on the issues index page or required DOM elements not found. Skipping search initialization.');
        return;
    }

    // --- Debounce Function ---
    // Prevents a function from being called too frequently (e.g., on every keystroke)
    const debounce = (func, delay) => {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), delay);
        };
    };

    // --- Main function to fetch and render issues via AJAX ---
    async function fetchIssues(pageUrl = null) {
        // Build query parameters based on current search input and filter dropdowns
        const queryParams = new URLSearchParams();

        // Get search term
        const searchTerm = searchInput.value.trim();
        if (searchTerm) {
            queryParams.set('search', searchTerm);
        }

        // Get filter terms from form dropdowns (only if selected, not 'All' or empty)
        const status = document.getElementById('status').value;
        if (status) {
            queryParams.set('status', status);
        }

        const priority = document.getElementById('priority').value;
        if (priority) {
            queryParams.set('priority', priority);
        }

        const tagId = document.getElementById('tag_id').value;
        if (tagId) {
            queryParams.set('tag_id', tagId);
        }

        // If a specific page URL is provided (from pagination click), merge its page parameter
        if (pageUrl) {
            const urlObj = new URL(pageUrl);
            const page = urlObj.searchParams.get('page');
            if (page) {
                queryParams.set('page', page);
            }
        }
        
        const url = `/issues?${queryParams.toString()}`;
        console.log('AJAX Fetching issues from:', url); // Debugging line

        try {
            // Send an AJAX request (Laravel detects 'X-Requested-With: XMLHttpRequest' header)
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Crucial for Laravel to return the partial view
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const html = await response.text();     // Expect HTML partial
            issueListContainer.innerHTML = html;    // Replace the content of the container

            // Update URL in browser history without reloading the page
            window.history.pushState(null, '', url);

        } catch (error) {
            console.error('Error fetching issues:', error);
            issueListContainer.innerHTML = `<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                                <span class="block sm:inline">Failed to load issues. Please try again.</span>
                                            </div>`;
        }
    }

    // --- Event Listeners ---

    // 1. Debounced search input listener
    searchInput.addEventListener('input', debounce(() => {
        fetchIssues(); // Trigger AJAX fetch on search input
    }, 500)); // 500ms debounce delay

    // 2. Filter form submission listener (now also triggers AJAX)
    filterForm.addEventListener('submit', (e) => {
        e.preventDefault(); // Prevent default full page reload
        fetchIssues();      // Trigger AJAX fetch on filter apply
    });

    // 3. Clear Filters button listener (to reset the form and trigger AJAX)
    // Find the Clear Filters anchor tag inside the form
    const clearFiltersBtn = filterForm.querySelector('a[href*="issues.index"]');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default navigation
            searchInput.value = ''; // Clear search input
            document.getElementById('status').value = '';
            document.getElementById('priority').value = '';
            document.getElementById('tag_id').value = '';
            fetchIssues(); // Trigger AJAX fetch with cleared filters
        });
    }

    // 4. Delegation for Pagination Links (since pagination is replaced by AJAX)
    issueListContainer.addEventListener('click', (e) => {
        // Check if the clicked element is an <a> tag and is within a pagination element
        // Pagination links generated by Laravel usually have 'pagination' class on their parent ul/nav
        if (e.target.tagName === 'A' && e.target.closest('.pagination')) {
            e.preventDefault(); // Stop default navigation
            fetchIssues(e.target.href); // Pass the page URL to the fetch function
        }
    });

    // 5. Initialize search input if page loads with a search query from browser history
    // This makes sure the initial search value from URL is set if present
    const initialSearchQuery = new URLSearchParams(window.location.search).get('search');
    if (initialSearchQuery) {
        searchInput.value = initialSearchQuery;
    }
});