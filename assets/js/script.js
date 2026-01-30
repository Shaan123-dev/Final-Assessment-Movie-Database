/**
 * MovieDB Application Logic
 * Handles Ajax Live Search and UI Interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. LOCAL LIVE SEARCH (Ajax) ---
    // This targets the search bar on the index.php page
    const localSearch = document.getElementById('localSearch');
    const movieTable = document.getElementById('movieTable');

    if (localSearch && movieTable) {
        localSearch.addEventListener('input', async (e) => {
            const query = e.target.value.trim();

            // Only search if the user has typed at least 2 characters
            if (query.length >= 2) {
                try {
                    const response = await fetch(`ajax-search.php?q=${encodeURIComponent(query)}`);
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const html = await response.text();
                    movieTable.innerHTML = html;
                } catch (error) {
                    console.error('Error during local search:', error);
                }
            } else if (query.length === 0) {
                // If the search is cleared, reload the page to show the full list
                // (Alternatively, you could fetch all movies again via Ajax)
                window.location.reload();
            }
        });
    }

    // --- 2. UI AUTO-CLOSE FOR DROPDOWNS ---
    // Closes the TMDB API dropdown if the user clicks anywhere else on the screen
    document.addEventListener('click', (e) => {
        const dropdown = document.getElementById('apiDropdown');
        const tmdbInput = document.getElementById('tmdbInput');
        const signupDropdown = document.getElementById('tmdbResults'); // For add.php

        if (dropdown && e.target !== tmdbInput) {
            dropdown.innerHTML = '';
        }
        if (signupDropdown && e.target !== document.getElementById('tmdbSearch')) {
            signupDropdown.innerHTML = '';
        }
    });

    // --- 3. DELETE CONFIRMATION ANIMATION ---
    // While we use basic confirm(), we can add a simple console log or 
    // visual feedback for the assessment demonstration.
    const deleteButtons = document.querySelectorAll('.btn-s.delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const confirmed = confirm("Are you sure you want to remove this movie from the database?");
            if (!confirmed) {
                e.preventDefault();
            }
        });
    });

    // --- 4. FORM VALIDATION FEEDBACK ---
    // Simple visual highlight when a field is focused
    const inputs = document.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.style.color = '#6366f1'; // Indigo highlight
        });
        input.addEventListener('blur', () => {
            input.parentElement.style.color = ''; // Reset
        });
    });

});

/**
 * Global Helper for Add/Edit TMDB Auto-fill
 * Note: These are called by 'onclick' in the PHP files
 */
window.autoFill = function(title, year, rating, poster) {
    // Check if elements exist before filling
    const fTitle = document.getElementById('f_title');
    const fYear = document.getElementById('f_year');
    const fRating = document.getElementById('f_rating');
    const fPoster = document.getElementById('f_poster');
    
    if (fTitle) fTitle.value = title;
    if (fYear) fYear.value = year;
    if (fRating) fRating.value = rating;
    if (fPoster) fPoster.value = poster;

    // Clear dropdowns
    const dropdowns = document.querySelectorAll('.api-dropdown, .dropdown');
    dropdowns.forEach(d => d.innerHTML = '');
    
    // Provide a small UI hint that auto-fill worked
    console.log(`Auto-filled: ${title}`);
};