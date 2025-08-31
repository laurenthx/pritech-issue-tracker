# PRITECH Mini Issue Tracker (Laravel Technical Task)

## Project Overview

This is a Mini Issue Tracker application built with Laravel, designed to allow a small team to manage projects, issues, tags, and comments. The application fulfills all the core requirements and successfully implements all optional bonus tasks specified in the PRITECH Laravel Technical Task.

## Features

The application provides a comprehensive set of features for issue tracking:

### Core Functionality

-   **Projects Management:**
    -   List, Create, Edit, Delete projects.
    -   View detailed information for each project, including associated issues.
    -   Project entities now include `start_date` and `deadline` fields.
-   **Issues Management:**
    -   List all issues with advanced filtering capabilities (by status, priority, and tag).
    -   Create, Edit, Delete issues.
    -   View detailed information for individual issues, including assigned tags and comments.
-   **Tags Management:**
    -   Create and list custom tags (with unique names and nullable colors).
    -   **AJAX-driven Tag Assignment:** Attach and detach tags to an issue directly from the issue detail page without a full page reload, using a modal/inline form.
-   **Comments Management:**
    -   **AJAX-driven Comment Loading:** Dynamically load comments on the issue detail page using AJAX, with pagination.
    -   **AJAX-driven Comment Submission:** Add new comments without a page reload, prepending them to the list and clearing the form.
    -   Includes client-side validation for `author_name` and `body` with on-page error display (no alerts).

### Technical Implementations

-   Laravel framework (PHP 8.x+).
-   Utilizes Blade templates (`layouts`/`partials`) for UI rendering.
-   Extensive use of JavaScript (vanilla JS for AJAX) for dynamic interactions.
-   Proper use of Laravel Resource Controllers for CRUD operations.
-   Form Request classes for robust server-side validation.
-   Database schema managed via Migrations (including new columns and pivot tables).
-   Factories and Seeders for generating realistic demo data.
-   Eloquent ORM with proper relationships (`hasMany`, `belongsTo`, `belongsToMany`) and eager loading to prevent N+1 query issues.

---

## Bonus (Optional) Features Implemented:

### ✅ 1. Many-to-many with Users (Assignment)

-   Allows assigning multiple "members" (users) to an issue.
-   Implemented via a `issue_user` pivot table.
-   **AJAX-driven Member Assignment:** Display and manage assigned members directly from the issue detail page (attach/detach via AJAX).

### ✅ 2. Authorization Policies

-   Implemented simple Laravel Policies (`ProjectPolicy`) to restrict access.
-   Only the `user` who `owns` a `project` can `edit` or `delete` that specific project.
-   Unauthorized attempts to edit/delete are blocked with a `403 | Unauthorized` response.
-   `user_id` is automatically assigned to projects upon creation.

### ✅ 3. Text Search with Debounce (AJAX)

-   Added a text search input on the global "All Issues" page (`/issues`).
-   Users can search by issue `title` or `description`.
-   The search functionality is **AJAX-driven**, dynamically updating the issues list without a page reload.
-   Incorporates a **debounce mechanism** (500ms delay) to optimize performance by reducing excessive server requests during rapid typing.

---

## Getting Started

Follow these steps to set up and run the project locally.

### Prerequisites

-   PHP (8.0 or higher)
-   Composer
-   Node.js & npm (or Yarn)
-   MySQL or other supported database (SQLite is fine for local development)

### Installation Steps

1.  **Clone the repository:**

    ```bash
    git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY_NAME.git
    cd YOUR_REPOSITORY_NAME
    ```

2.  **Install PHP dependencies:**

    ```bash
    composer install
    ```

3.  **Set up environment variables:**

    -   Copy the example environment file:
        ```bash
        cp .env.example .env
        ```
    -   Edit `.env` to configure your database connection (`DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
    -   Generate an application key:
        ```bash
        php artisan key:generate
        ```

4.  **Run database migrations and seeders:**

    ```bash
    php artisan migrate --seed
    ```

    (This will create database tables and populate them with demo data, including users and projects, which are essential for testing authorization.)

5.  **Install Node.js dependencies:**

    ```bash
    npm install
    ```

6.  **Compile front-end assets:**

    ```bash
    npm run dev
    # Or, for persistent compilation during development:
    # npm run watch
    ```

7.  **Start the Laravel development server:**
    ```bash
    php artisan serve
    ```

### Usage

1.  Open your browser and navigate to `http://127.0.0.1:8000`.
2.  The application uses Laravel Breeze for authentication. You can log in with a seeded user (e.g., `admin@example.com` / `password`) or register a new user.
3.  Explore the `Projects`, `All Issues`, and `Tags` sections to see the functionalities in action.
    -   Test creating projects as different users to verify authorization policies.
    -   Use the search and filter features on the `All Issues` page.
    -   Navigate to an Issue Detail page to test AJAX tag/member assignment and comment submission.

---

## Deliverables Checklist (from task PDF)

-   [x] Git history with logical commits.
-   [x] Make sure the repo is public.

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
