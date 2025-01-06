// Get references to the dropdowns
    var athleteDropdown = document.getElementById('athleteDropdown');
    var sportTeamDropdowns = document.getElementById('sportTeamDropdowns');

    // Add event listener to the athlete dropdown
    athleteDropdown.addEventListener('change', function() {
        // Check if athlete value is set to 1 (Yes)
        if (this.value === '1') {
            // Display the sport and team dropdowns
            sportTeamDropdowns.style.display = 'block';
        } else {
            // Hide the sport and team dropdowns
            sportTeamDropdowns.style.display = 'none';
        }
    });