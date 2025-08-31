# PRITECH Mini Issue Tracker

## Project Overview

This is a Mini Issue Tracker application built with **Laravel 9.52.20** (PHP 8.0.30) that fulfills all core requirements and successfully implements **all three optional bonus tasks** specified in the PRITECH Laravel Technical Task. It allows a small team to manage projects, issues, tags, and comments efficiently.

## Features

The application provides a comprehensive set of features for robust issue tracking:

### Core Functionality
*   **Projects Management:**
    *   List, Create, Edit, Delete projects.
    *   View detailed information for each project, including associated issues.
    *   **New Fields:** Project entities now include `start_date` and `deadline` fields.
*   **Issues Management:**
    *   List all issues on a dedicated `/issues` page.
    *   **Filtering:** Advanced filtering by `status`, `priority`, and `tag`.
    *   Create, Edit, Delete issues.
    *   View detailed information for individual issues.
*   **Tags Management:**
    *   Create and list custom tags (with unique names and nullable colors).
    *   **AJAX-driven Tag Assignment:** Attach and detach tags to an issue directly from the issue detail page using an inline form without a full page reload.
*   **Comments Management:**
    *   **AJAX-driven Comment Loading:** Dynamically load paginated comments on the issue detail page using AJAX.
    *   **AJAX-driven Comment Submission:** Add new comments instantly without a page reload, prepending them to the list and automatically clearing the form.
    *   Includes client-side validation for `author_name` and `body` with clear on-page error messages.

### Technical Implementations
*   **Laravel 9.x:** Built on a modern Laravel stack.
*   **Blade Templates:** Efficient UI rendering using Blade layouts and partials.
*   **JavaScript (Vanilla JS):** Extensive use of plain JavaScript for all dynamic and AJAX interactions.
*   **Resource Controllers:** Proper application of Laravel Resource Controllers for streamlined CRUD operations across entities.
*   **Form Request Classes:** Robust server-side validation using dedicated Form Request classes for all data inputs.
*   **Database Migrations & Seeders:** Database schema management via Migrations (including new fields like `start_date`, `deadline`, and the `issue_user` pivot table) and Seeders for generating realistic demo data (users, projects, issues, tags).
*   **Eloquent ORM:** Leverages Eloquent ORM with defined relationships (`hasMany`, `belongsTo`, `belongsToMany`) and eager loading (`with()`, `load()`) to optimize database queries and prevent N+1 issues.

---

## ✅ Bonus (Optional) Features Implemented:

### 1. Many-to-many with Users (Assignment)
*   **Functionality:** Allows assigning multiple "members" (users) to an issue.
*   **Implementation:** Achieved via a `issue_user` pivot table.
*   **UI:** Display and manage assigned members directly from the issue detail page.
*   **AJAX:** Attach and detach members to an issue dynamically without a full page reload.

### 2. Authorization Policies
*   **Policy Definition:** Implemented `ProjectPolicy` to define specific access rules.
*   **Owner Restriction:** Only the `user` who `owns` a `project` is authorized to `edit` or `delete` that project.
*   **Automation:** `user_id` is automatically assigned to projects upon creation, linking them to the authenticated user.
*   **Security:** Unauthorized attempts to perform actions (e.g., trying to access `/projects/{id}/edit` directly for a project you don't own) are blocked with a `403 | This action is unauthorized` error.

### 3. Text Search with Debounce (AJAX)
*   **Search Input:** A text search input is added on the global "All Issues" page (`/issues`).
*   **Criteria:** Users can search issues by `title` or `description`.
*   **Dynamic Updates:** The search functionality is **AJAX-driven**, meaning the issues list updates in real-time as the user types without refreshing the entire page.
*   **Performance Optimization:** Includes a **debounce mechanism** (500ms delay) to prevent excessive server requests during rapid typing, ensuring a smooth user experience.

---

## Getting Started

Follow these steps to set up and run the project locally.

### Prerequisites

*   **PHP:** `8.0` or higher (Application runs on PHP 8.0.30)
*   **Composer**
*   **Node.js & npm:** Or Yarn
*   **Database:** MySQL (recommended), PostgreSQL, or SQLite.

### Installation Steps

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/laurenthx/pritech-issue-tracker.git
    cd pritech-issue-tracker
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Set up environment variables:**
    *   Copy the example environment file:
        ```bash
        cp .env.example .env
        ```
    *   Edit your `.env` file to configure your database connection (`DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
    *   Generate an application key:
        ```bash
        php artisan key:generate
        ```

4.  **Run database migrations and seeders:**
    ```bash
    php artisan migrate --seed
    ```
    _This command is crucial! It creates all necessary database tables and populates them with essential demo data (users, projects, issues, tags, comments, assigned members), which is vital for testing all features, especially authorization and filters._

5.  **Install Node.js dependencies:**
    ```bash
    npm install
    ```

6.  **Compile front-end assets:**
    ```bash
    npm run dev
    # For persistent compilation during development (updates on file change):
    # npm run watch
    # For production-ready assets:
    # npm run build
    ```

7.  **Start the Laravel development server:**
    ```bash
    php artisan serve
    ```

### Usage

1.  Open your web browser and navigate to `http://127.0.0.1:8000`.
2.  The application uses Laravel Breeze for user authentication. You can:
    *   **Log in:** Use a seeded user from `database/seeders` (e.g., `admin@example.com` with password `password`).
    *   **Register:** Create a new user account.
3.  **Explore the application:**
    *   Visit the `Projects` section to manage projects and observe authorization in action.
    *   Go to `All Issues` to use the AJAX search and filters.
    *   Navigate to an `Issue Detail` page to test AJAX tag/member assignment and comment submission.



