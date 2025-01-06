$(document).ready(function(){
        $('#myTable').dataTable();

        // Show the modal when "Create schedule" button is clicked
        $('#createScheduleBtn').click(function(){
            $('#createScheduleModal').modal('show');
        });

        // Fetch teams using AJAX and populate options for Team 2
        $('#team1Select').change(function(){
            var team1 = $(this).val();
            $('#team2Select').empty();
            $('#team2Select').append($('<option>', { 
                value: '',
                text : 'Please select team' 
            }));

            // Fetch teams using AJAX
            $.ajax({
                url: 'fetch_teams.php',
                type: 'GET',
                data: {
                    team1: team1
                },
                dataType: 'json',
                success: function(response) {
                    $.each(response, function(index, team) {
                        $('#team2Select').append($('<option>', { 
                            value: team.id,
                            text : team.name
                        }));
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Handle error
                }
            });
        });

        // Submit schedule form when "Save" button is clicked
        $('#createScheduleModal').on('click', '#saveScheduleBtn', function(){
            $('#scheduleForm').submit();
        });
    });