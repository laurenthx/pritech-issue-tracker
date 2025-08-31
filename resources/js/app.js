import './bootstrap';
import Alpine from 'alpinejs';

// import 'tailwindcss/tailwind.css'; // Uncomment if you are importing TailwindCSS directly in JS

window.Alpine = Alpine;
Alpine.start();

import '../../public/js/tags-comments.js'; // Your original import
import './issue_search.js';               // <--- ADDED: New JS for global issue search/filter