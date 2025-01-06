<?php
include '../../config.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Scheduling starts here !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!



if (isset($_POST['createschedule'])) {
    // Get the selected sport
    $sport_id = $_POST['sport'];

    // Check if any matchups for this sport don't have results yet
    $pending_matches_query = "SELECT id FROM schedule WHERE sport = '$sport_id' AND (winner IS NULL OR loser IS NULL)";
    $pending_matches_result = $conn->query($pending_matches_query);

    if ($pending_matches_result->num_rows > 0) {
        // Redirect or show an error message if there are incomplete matches
        $_SESSION['type'] = 'danger';
        $_SESSION['msg'] = 'Cannot create a new schedule. Previous round matchups are not yet completed!';
        header("Location: schedule.php");
        exit();
    }

    // Update the round in the sport table
    $update_round_sql = "UPDATE sport SET round = round + 1 WHERE id = '$sport_id'";
    $conn->query($update_round_sql);

    // Retrieve the updated round value
    $round_query = "SELECT round FROM sport WHERE id = '$sport_id'";
    $round_result = $conn->query($round_query);
    $current_round = $round_result->fetch_assoc()['round'];

    // Retrieve the elimination type
    $elimination_query = "SELECT elimination FROM sport WHERE id = '$sport_id'";
    $elimination_result = $conn->query($elimination_query);
    $elimination = $elimination_result->fetch_assoc()['elimination'];

    // Get the place from the form
    $place = ($_POST['place'] == 0) ? $_POST['otherplace'] : $_POST['place'];

    // Get the schedule date
    $schedule_date = $_POST['scheduleDate'];

    if ($elimination == 1) {
        // Single elimination logic
        $team_ids = array();
        $team_id_sql = "SELECT id FROM team WHERE sport = $sport_id AND status = 'Qualified'";
        $team_id_result = $conn->query($team_id_sql);

        while ($row = $team_id_result->fetch_assoc()) {
            $team_ids[] = $row['id'];
        }

        shuffle($team_ids);

        $bye_team = null;
        if (count($team_ids) % 2 !== 0) {
            $bye_team = array_pop($team_ids);
        }

        $matchups = array_chunk($team_ids, 2);

        foreach ($matchups as $matchup) {
            $team1 = $matchup[0];
            $team2 = isset($matchup[1]) ? $matchup[1] : null;

            if (!$team2) {
                continue;
            }

            $schedule_datetime = getNextAvailableScheduleDateTime($conn, $place, $schedule_date);

            $insert_sql = "INSERT INTO schedule (sport, team1, team2, schedule, place, round) 
                           VALUES ('$sport_id', '$team1', '$team2', '$schedule_datetime', '$place', '$current_round')";
            $conn->query($insert_sql);
        }

    } elseif ($elimination == 2) {
        // Stage-based matchmaking logic
        $stages = [];
        $stage_query = "SELECT DISTINCT stage FROM team WHERE sport = $sport_id AND status = 'Qualified'";
        $stage_result = $conn->query($stage_query);

        while ($row = $stage_result->fetch_assoc()) {
            $stages[] = $row['stage']; // Collect unique stages
        }

        foreach ($stages as $stage) {
            // Get teams for the current stage
            $team_query = "SELECT id FROM team WHERE sport = $sport_id AND status = 'Qualified' AND stage = $stage";
            $team_result = $conn->query($team_query);

            $team_ids = [];
            while ($row = $team_result->fetch_assoc()) {
                $team_ids[] = $row['id'];
            }

            shuffle($team_ids);

            $bye_team = null;
            if (count($team_ids) % 2 !== 0) {
                $bye_team = array_pop($team_ids);
            }

            $matchups = array_chunk($team_ids, 2);

            foreach ($matchups as $matchup) {
                $team1 = $matchup[0];
                $team2 = isset($matchup[1]) ? $matchup[1] : null;

                if (!$team2) {
                    continue;
                }

                $schedule_datetime = getNextAvailableScheduleDateTime($conn, $place, $schedule_date);

                $insert_sql = "INSERT INTO schedule (sport, team1, team2, schedule, place, round) 
                               VALUES ('$sport_id', '$team1', '$team2', '$schedule_datetime', '$place', '$current_round')";
                $conn->query($insert_sql);
            }
        }
    }

    // Redirect or display success message
    $_SESSION['type'] = 'success';
    $_SESSION['msg'] = 'Schedule successfully created!';
    header("Location: schedule.php");
    exit();
}




// Scheduling ends here !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


if (isset($_POST['createroundrobinschedule'])) {
    $sport_id = $_POST['sport'];
    $start_date = $_POST['startDate'];
    $place = $_POST['place'] === 'others' ? $_POST['customPlace'] : $_POST['place'];


    $conn->begin_transaction(); // Start transaction
    try {
        // Fetch sport name
        $sport_query = "SELECT name FROM sport WHERE id = '$sport_id'";
        $sport_result = $conn->query($sport_query);
        if ($sport_result->num_rows === 0) {
            throw new Exception("Invalid sport selected!");
        }
        $sport_name = $sport_result->fetch_assoc()['name'];

        // Fetch teams
        $teams_query = "SELECT id FROM team WHERE sport = '$sport_id'";
        $teams_result = $conn->query($teams_query);
        $teams = array_column($teams_result->fetch_all(MYSQLI_ASSOC), 'id');
        if (empty($teams)) {
            throw new Exception("No teams found for sport: $sport_name!");
        }

        // Handle odd number of teams
        if (count($teams) % 2 != 0) {
            $teams[] = null; // Add a dummy team
        }

        // Generate round-robin matchups
        $matchups = [];
$total_rounds = count($teams) - 1;

for ($round = 0; $round < $total_rounds; $round++) {
    $round_matchups = [];
    for ($i = 0; $i < count($teams) / 2; $i++) {
        $team1 = $teams[$i];
        $team2 = $teams[count($teams) - 1 - $i];

        // Check for duplicate matchups in the current and previous rounds
        $is_duplicate = false;
        foreach ($round_matchups as $match) {
            if (
                ($match['team1'] === $team1 && $match['team2'] === $team2) ||
                ($match['team1'] === $team2 && $match['team2'] === $team1)
            ) {
                $is_duplicate = true;
                break;
            }
        }

        foreach ($matchups as $match) {
            if (
                ($match['team1'] === $team1 && $match['team2'] === $team2) ||
                ($match['team1'] === $team2 && $match['team2'] === $team1)
            ) {
                $is_duplicate = true;
                break;
            }
        }

        // If duplicate, replace one of the teams
        if ($is_duplicate) {
            $replacement_found = false;
            foreach ($teams as $replacement_team) {
                if (
                    $replacement_team !== $team1 &&
                    $replacement_team !== $team2 &&
                    !in_array($replacement_team, array_column($round_matchups, 'team1')) &&
                    !in_array($replacement_team, array_column($round_matchups, 'team2'))
                ) {
                    if (rand(0, 1)) {
                        $team1 = $replacement_team;
                    } else {
                        $team2 = $replacement_team;
                    }
                    $replacement_found = true;
                    break;
                }
            }

            if (!$replacement_found) {
                continue; // Skip this pairing if no valid replacement found
            }
        }

        // Add the valid matchup to the round
        if (($team1 && $team2) && ($team1 !== $team2)) {
            $round_matchups[] = [
                'team1' => $team1,
                'team2' => $team2,
                'round' => $round + 1,
            ];
        }
    }

    // Add this round's matchups to the overall matchups
    $matchups = array_merge($matchups, $round_matchups);

    // Rotate teams (except the first one)
    array_splice($teams, 1, 0, array_pop($teams));
}

        // Schedule matchups
        $datetime = new DateTime($start_date);
        foreach ($matchups as $match) {
            $scheduled_time = $datetime->format('Y-m-d H:i:s');
            $insert_query = "INSERT INTO roundrobin_matchups (sport_id, team1, team2, round, scheduledatetime, place)
                             VALUES ('$sport_id', '{$match['team1']}', '{$match['team2']}', '{$match['round']}', '$scheduled_time', '$place')";
            if (!$conn->query($insert_query)) {
                throw new Exception("Database error: " . $conn->error);
            }
            $datetime->modify('+1 hour');
            if ($datetime->format('H:i:s') === '00:00:00') {
                $datetime->modify('+1 day')->setTime(8, 0); // Reset to 8 AM next day
            }
        }

        $conn->commit(); // Commit transaction
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = "Round robin schedule for $sport_name created successfully!";
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction on error
        $_SESSION['type'] = 'danger';
        $_SESSION['msg'] = $e->getMessage();
    }

    header('Location: roundrobin.php');
}







if (isset($_POST['roundrobinschedule'])) {
    $schedule_id = $_POST['schedule_id'];
    $schedule_datetime = $_POST['schedule'];

    // Validate if the schedule already exists
    $check_query = "SELECT COUNT(*) as count FROM roundrobin_matchups WHERE scheduledatetime = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $schedule_datetime);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // Schedule already exists
        $_SESSION['type'] = "danger";
        $_SESSION['msg'] = "The selected schedule is already assigned to another matchup. Please choose a different date and time.";
        header("Location: roundrobin.php");
        exit();
    }

    // Update the matchup with the new schedule
    $update_query = "UPDATE roundrobin_matchups SET scheduledatetime = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $schedule_datetime, $schedule_id);

    if ($stmt->execute()) {
        // Success
        $_SESSION['type'] = "success";
        $_SESSION['msg'] = "Schedule successfully updated.";
    } else {
        // Failure
        $_SESSION['type'] = "danger";
        $_SESSION['msg'] = "Failed to update the schedule. Please try again.";
    }

    // Redirect to roundrobin.php
    header("Location: roundrobin.php");
    exit();
}



if (isset($_POST['saveScore'])) {
    // Get the matchup ID and scores from the form
    $matchupId = $_POST['matchupId'];
    $team1Score = $_POST['team1_score'];
    $team2Score = $_POST['team2_score'];

    // Query to get team1 and team2 from the roundrobin_matchups table based on matchup ID
    $query = "SELECT team1, team2 FROM roundrobin_matchups WHERE id = '$matchupId'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Fetch the result to get the team IDs
        $matchup = $result->fetch_assoc();
        $team1Id = $matchup['team1'];
        $team2Id = $matchup['team2'];

        // Determine the winner based on the scores
        $winner = null;
        if ($team1Score > $team2Score) {
            $winner = $team1Id; // team1 wins
        } elseif ($team2Score > $team1Score) {
            $winner = $team2Id; // team2 wins
        }

        // Update the database with the scores and winner
        $updateQuery = "
            UPDATE roundrobin_matchups
            SET team1_score = '$team1Score',
                team2_score = '$team2Score',
                winner = '$winner'
            WHERE id = '$matchupId'
        ";

        if ($conn->query($updateQuery) === TRUE) {
            // Set session success message
            $_SESSION['type'] = "success";
            $_SESSION['msg'] = "Scores successfully updated.";
        } else {
            // Set session error message in case of failure
            $_SESSION['type'] = "danger";
            $_SESSION['msg'] = "Error updating scores.";
        }
    } else {
        // If no matchup was found, set an error message
        $_SESSION['type'] = "danger";
        $_SESSION['msg'] = "Matchup not found.";
    }

    // Redirect back to the roundrobin.php page
    header("Location: roundrobin.php");
    exit;
}




// Function to get the next available datetime for the specified place with 1-hour interval
function getNextAvailableScheduleDateTime($conn, $place, $selected_date) {
    // Calculate the start and end datetime for the selected date
    $start_datetime = strtotime($selected_date . ' 08:00:00');
    $end_datetime = strtotime($selected_date . ' 19:00:00');

    // Initialize the available datetime variable
    $available_datetime = null;

    // Iterate through each hour within the time range
    for ($current_datetime = $start_datetime; $current_datetime <= $end_datetime; $current_datetime += 3600) {
        // Check if there is any existing schedule at the specified place and datetime
        $existing_schedule_sql = "SELECT COUNT(*) AS schedule_count FROM schedule WHERE place = '$place' AND schedule = '" . date('Y-m-d H:i:s', $current_datetime) . "'";
        $existing_schedule_result = $conn->query($existing_schedule_sql);
        $existing_schedule_row = $existing_schedule_result->fetch_assoc();
        $schedule_count = $existing_schedule_row['schedule_count'];

        // If no existing schedule found, set the available datetime
        if ($schedule_count == 0) {
            $available_datetime = date('Y-m-d H:i:s', $current_datetime);
            break;
        }
    }

    return $available_datetime;
}




if(isset($_POST['addnews'])) {
    // Folder to save the uploaded images
    $target_dir = "../news/";

    // Generate a unique filename
    $random_filename = uniqid();

    // Get the file extension
    $imageFileType = strtolower(pathinfo($_FILES["newsImage"]["name"], PATHINFO_EXTENSION));

    // Final filename with extension
    $target_file = $target_dir . $random_filename . "." . $imageFileType;

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["newsImage"]["tmp_name"]);
    if($check !== false) {
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
        } else {
            // Check file size
            if ($_FILES["newsImage"]["size"] > 5000000) {
                echo "Sorry, your file is too large.";
            } else {
                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                } else {
                    // Upload the image
                    if (move_uploaded_file($_FILES["newsImage"]["tmp_name"], $target_file)) {
                        // Insert data into the database
                        include '../../config.php';
                        $newsTitle = $_POST['newsTitle'];
                        $newsDate = $_POST['newsDate'];
                        $newsContent = $_POST['newsContent'];
                        $imageFilename = $random_filename . "." . $imageFileType;

                        $sql = "INSERT INTO news (title, dateuploaded, content, image) VALUES ('$newsTitle', '$newsDate', '$newsContent', '$imageFilename')";
                        if ($conn->query($sql) === TRUE) {
                            $_SESSION['type'] = 'success';
                            $_SESSION['msg'] = "New record created successfully";
                            header('Location: news/index.php');
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            }
        }
    } else {
        echo "File is not an image.";
    }
    exit();
}


if (isset($_POST['updatenews'])) {
    include '../../config.php';

    $newsId = $_POST['newsId'];
    $newsTitle = $_POST['newsTitle'];
    $newsDate = $_POST['newsDate'];
    $newsContent = $_POST['newsContent'];
    $newImage = false;

    // Folder to save the uploaded images
    $target_dir = "../news/";
    $imageFilename = null;

    // Check if a new image was uploaded
    if (isset($_FILES["newsImage"]) && $_FILES["newsImage"]["error"] == UPLOAD_ERR_OK) {
        $random_filename = uniqid();
        $imageFileType = strtolower(pathinfo($_FILES["newsImage"]["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . $random_filename . "." . $imageFileType;

        $check = getimagesize($_FILES["newsImage"]["tmp_name"]);
        if ($check !== false) {
            if ($_FILES["newsImage"]["size"] <= 5000000 &&
                in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                // Upload the image
                if (move_uploaded_file($_FILES["newsImage"]["tmp_name"], $target_file)) {
                    $imageFilename = $random_filename . "." . $imageFileType;
                    $newImage = true;
                } else {
                    echo "Error uploading the new image.";
                    exit;
                }
            } else {
                echo "Invalid file format or file size too large.";
                exit;
            }
        } else {
            echo "File is not an image.";
            exit;
        }
    }

    // Build the SQL query to update the news record
    if ($newImage) {
        // Update with the new image
        $sql = "UPDATE news SET title = '$newsTitle', dateuploaded = '$newsDate', content = '$newsContent', image = '$imageFilename' WHERE id = '$newsId'";
    } else {
        // Update without changing the image
        $sql = "UPDATE news SET title = '$newsTitle', dateuploaded = '$newsDate', content = '$newsContent' WHERE id = '$newsId'";
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = "News updated successfully";
        header('Location: news/index.php');
    } else {
        echo "Error updating record: " . $conn->error;
    }
}




/*-- PLace --*/


if(isset($_POST['newplace'])) {
    // Retrieve the place name from the form
    $placeName = $_POST['place'];

    // Perform any necessary validation on the input data

    // Insert the new place into the database
    $sql = "INSERT INTO place (place) VALUES ('$placeName')";
    
    if ($conn->query($sql) === TRUE) {
        // Place added successfully, redirect back to the page with a success message
        session_start();
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'New place added successfully.';
        header("Location: place.php"); // Replace 'your_page.php' with the appropriate page
        exit();
    } else {
        // Error occurred while adding the place, redirect back with an error message
        session_start();
        $_SESSION['type'] = 'error';
        $_SESSION['msg'] = 'Error: ' . $conn->error;
        header("Location: place.php"); // Replace 'your_page.php' with the appropriate page
        exit();
    }
}


if (isset($_POST['newteam'])) {

    // Get form data
    $sport_id = $_POST['course'];
    $team_name = $_POST['name'];

    // Handle file upload
    $target_dir = "../teamLogo/";
    $file_name = generateRandomString(5); // Function to generate 5 random alphanumeric characters
    $target_file = $target_dir . $file_name . ".jpg"; // Append ".jpg" extension assuming JPEG format

    // Retrieve the value of 'elimination' from the 'sport' table
    $result = $conn->query("SELECT elimination FROM sport WHERE id = '$sport_id'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $elimination = $row['elimination']; // Get the value of 'elimination'
    } else {
        echo "Error: Sport not found.";
        exit();
    }

    // Check if file has been uploaded
    if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
        // File upload successful, proceed with database insertion
        $sql = "INSERT INTO team (sport, name, logo, stage) 
                VALUES ('$sport_id', '$team_name', '$file_name', '$elimination')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['type'] = 'success';
            $_SESSION['msg'] = 'New team successfully registered!';
            header("Location: team.php"); // Redirect to team.php or any other desired page
            exit();
        } else {
            // Error in SQL query
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // File upload failed
        echo "Sorry, there was an error uploading your file.";
    }
}



if (isset($_POST['deleteTeam'])) {
    $teamId = $_POST['team_id'];
    $deleteQuery = "DELETE FROM team WHERE id = '$teamId'";
    if ($conn->query($deleteQuery)) {
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Team successfully deleted!';
    } else {
        $_SESSION['type'] = 'danger';
        $_SESSION['msg'] = 'Failed to delete team!';
    }
    header("Location: team.php");
    exit();
}


if (isset($_POST['updateTeam'])) {
    // Retrieve form data
    $teamId = $_POST['team_id'];
    $sport = $_POST['sport'];
    $teamName = $conn->real_escape_string($_POST['name']);

    // Check if a new logo is uploaded
    if (!empty($_FILES['logo']['name'])) {
        $logoFile = $_FILES['logo']['name'];
        $logoTmp = $_FILES['logo']['tmp_name'];
        $logoExt = strtolower(pathinfo($logoFile, PATHINFO_EXTENSION));

        // Validate file type
        if ($logoExt != "jpg" && $logoExt != "jpeg") {
            $_SESSION['type'] = 'danger';
            $_SESSION['msg'] = 'Only JPG and JPEG file types are allowed for the logo!';
            header("Location: team.php");
            exit();
        }

        // Move the uploaded file and update the database
        $logoName = $teamId . ".jpg";
        $logoPath = "../teamLogo/" . $logoName;

        if (move_uploaded_file($logoTmp, $logoPath)) {
            $updateQuery = "UPDATE team SET sport = '$sport', name = '$teamName', logo = '$teamId' WHERE id = '$teamId'";
        } else {
            $_SESSION['type'] = 'danger';
            $_SESSION['msg'] = 'Failed to upload the new logo!';
            header("Location: team.php");
            exit();
        }
    } else {
        // Update without changing the logo
        $updateQuery = "UPDATE team SET sport = '$sport', name = '$teamName' WHERE id = '$teamId'";
    }

    // Execute the query
    if ($conn->query($updateQuery)) {
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Team successfully updated!';
    } else {
        $_SESSION['type'] = 'danger';
        $_SESSION['msg'] = 'Failed to update team: ' . $conn->error;
    }

    // Redirect back to the page
    header("Location: team.php");
    exit();
}



// Function to generate random alphanumeric string
function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}





// Elimination starts here !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!



if (isset($_POST['updateResult'])) {
    // Get form data
    $schedule_id = $_POST['schedule_id'];
    $winnerID = $_POST['winnerID'];
    $loserID = $_POST['loserID'];
    $sport_id = $_POST['sport_id'];

    // Check the elimination type from the 'sport' table
    $elimination_query = "SELECT elimination FROM sport WHERE id = '$sport_id'";
    $elimination_result = $conn->query($elimination_query);

    if ($elimination_result->num_rows > 0) {
        $elimination = $elimination_result->fetch_assoc()['elimination'];

        if ($elimination == 1) {
            // Single elimination logic
            if (!empty($winnerID) && !empty($loserID)) {
                // Update the schedule table to set the winner and loser
                $update_schedule_sql = "UPDATE schedule 
                                        SET winner = '$winnerID', loser = '$loserID' 
                                        WHERE id = '$schedule_id'";

                if ($conn->query($update_schedule_sql) === TRUE) {
                    // Update the team table to mark the loser as eliminated
                    $update_team_sql = "UPDATE team 
                                        SET status = 'Eliminated' 
                                        WHERE id = '$loserID'";

                    if ($conn->query($update_team_sql) === TRUE) {
                        $_SESSION['type'] = 'success';
                        $_SESSION['msg'] = 'Result updated successfully!';
                        header("Location: schedule.php");
                        exit();
                    } else {
                        $_SESSION['type'] = 'danger';
                        $_SESSION['msg'] = 'Error updating team status: ' . $conn->error;
                        header("Location: schedule.php");
                        exit();
                    }
                } else {
                    $_SESSION['type'] = 'danger';
                    $_SESSION['msg'] = 'Error updating schedule: ' . $conn->error;
                    header("Location: schedule.php");
                    exit();
                }
            } else {
                $_SESSION['type'] = 'danger';
                $_SESSION['msg'] = 'Please select both a winner and a loser.';
                header("Location: schedule.php");
                exit();
            }
        } elseif ($elimination == 2) {
            // Stage-based elimination logic
            if (!empty($winnerID) && !empty($loserID)) {
                // Update the schedule table to set the winner and loser
                $update_schedule_sql = "UPDATE schedule 
                                        SET winner = '$winnerID', loser = '$loserID' 
                                        WHERE id = '$schedule_id'";

                if ($conn->query($update_schedule_sql) === TRUE) {
                    // Fetch the current stage of the loser
                    $loser_stage_query = "SELECT stage FROM team WHERE id = '$loserID'";
                    $loser_stage_result = $conn->query($loser_stage_query);

                    if ($loser_stage_result->num_rows > 0) {
                        $loser_stage = $loser_stage_result->fetch_assoc()['stage'];

                        // Decrement the stage
                        $new_stage = $loser_stage - 1;

                        if ($new_stage == 1) {
                            // If new stage is 1, update stage but keep status as is
                            $update_team_sql = "UPDATE team 
                                                SET stage = '$new_stage' 
                                                WHERE id = '$loserID'";
                        } elseif ($new_stage == 0) {
                            // If new stage is 0, update stage and set status to 'Eliminated'
                            $update_team_sql = "UPDATE team 
                                                SET stage = '$new_stage', status = 'Eliminated' 
                                                WHERE id = '$loserID'";
                        } else {
                            // For other stages, just update the stage
                            $update_team_sql = "UPDATE team 
                                                SET stage = '$new_stage' 
                                                WHERE id = '$loserID'";
                        }

                        if ($conn->query($update_team_sql) === TRUE) {
                            $_SESSION['type'] = 'success';
                            $_SESSION['msg'] = 'Result updated successfully!';
                            header("Location: schedule.php");
                            exit();
                        } else {
                            $_SESSION['type'] = 'danger';
                            $_SESSION['msg'] = 'Error updating team stage: ' . $conn->error;
                            header("Location: schedule.php");
                            exit();
                        }
                    } else {
                        $_SESSION['type'] = 'danger';
                        $_SESSION['msg'] = 'Error fetching team stage: ' . $conn->error;
                        header("Location: schedule.php");
                        exit();
                    }
                } else {
                    $_SESSION['type'] = 'danger';
                    $_SESSION['msg'] = 'Error updating schedule: ' . $conn->error;
                    header("Location: schedule.php");
                    exit();
                }
            } else {
                $_SESSION['type'] = 'danger';
                $_SESSION['msg'] = 'Please select both a winner and a loser.';
                header("Location: schedule.php");
                exit();
            }
        } else {
            $_SESSION['type'] = 'danger';
            $_SESSION['msg'] = 'Invalid elimination type.';
            header("Location: schedule.php");
            exit();
        }
    } else {
        $_SESSION['type'] = 'danger';
        $_SESSION['msg'] = 'Error fetching elimination type: ' . $conn->error;
        header("Location: schedule.php");
        exit();
    }
}



// Elimination ends here !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!





if (isset($_POST['verifyAthlete'])) {
    // Get the USER ID from the form
    $userId = $_POST['verifyID'];

    // Update the 'verification' column in the 'users' table to '1' where id matches
    $sql = "UPDATE users SET verification = 1 WHERE id = $userId";
    if ($conn->query($sql) === TRUE) {
        echo "Verification successful";
        header("Location: athletes.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Check if the form is submitted and if the declineAthlete button is clicked
if (isset($_POST['declineAthlete'])) {
    // Get the USER ID from the form
    $userId = $_POST['verifyID'];

    // Update the 'athlete' column in the 'users' table to '0' where id matches
    $sql = "UPDATE users SET athlete = 0 WHERE id = $userId";
    if ($conn->query($sql) === TRUE) {
        echo "Athlete declined";
        header("Location: users.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}


// Add Sport
if(isset($_POST['addSport'])) {
    $sportName = $_POST['sportName'];
    $elimination = $_POST['elimination'];

    $sql = "INSERT INTO sport (name, elimination) VALUES ('$sportName', '$elimination')";
    if ($conn->query($sql) === TRUE) {
        session_start();
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Sport added successfully.';
        header("Location: sport.php"); // Redirect back to the index page
        exit();
    } else {
        echo "Error adding sport: " . $conn->error;
    }
}


// Edit Sport
if(isset($_POST['editSport'])) {
    $editSportID = $_POST['editSportID'];
    $sportName = $_POST['sportName'];

    $sql = "UPDATE sport SET name='$sportName' WHERE id='$editSportID'";
    if ($conn->query($sql) === TRUE) {
        session_start();
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Sport updated successfully.';
        header("Location: sport.php"); // Redirect back to the index page
        exit();
    } else {
        echo "Error updating sport: " . $conn->error;
    }
}

// Delete Sport
if(isset($_POST['deleteSport'])) {
    $deleteSportID = $_POST['deleteSportID'];

    $sql = "DELETE FROM sport WHERE id='$deleteSportID'";
    if ($conn->query($sql) === TRUE) {
        session_start();
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Sport deleted successfully.';
        header("Location: sport.php"); // Redirect back to the index page
        exit();
    } else {
        echo "Error deleting sport: " . $conn->error;
    }
}



// Add Place
if(isset($_POST['addPlace'])) {
    $placeName = $_POST['addPlaceName'];

    $sql = "INSERT INTO place (place) VALUES ('$placeName')";
    if ($conn->query($sql) === TRUE) {
        session_start();
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Place added successfully.';
        header("Location: place.php"); // Redirect back to the index page
        exit();
    } else {
        echo "Error adding place: " . $conn->error;
    }
}



// Edit Place
if(isset($_POST['editPlace'])) {
    $editPlaceID = $_POST['editPlaceID'];
    $placeName = $_POST['editPlaceName'];

    $sql = "UPDATE place SET place='$placeName' WHERE id='$editPlaceID'";
    if ($conn->query($sql) === TRUE) {
        session_start();
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Place updated successfully.';
        header("Location: place.php"); // Redirect back to the index page
        exit();
    } else {
        echo "Error updating place: " . $conn->error;
    }
}

// Delete Place
if(isset($_POST['deletePlace'])) {
    $deletePlaceID = $_POST['deletePlaceID'];

    $sql = "DELETE FROM place WHERE id='$deletePlaceID'";
    if ($conn->query($sql) === TRUE) {
        session_start();
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Place deleted successfully.';
        header("Location: place.php"); // Redirect back to the index page
        exit();
    } else {
        echo "Error deleting place: " . $conn->error;
    }
}

// Function to sanitize input data
    function sanitize($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

// Insert new course
if(isset($_POST['registerCourse'])) {
    // Sanitize input data
    $code = sanitize($_POST['registerCourseCode']);
    $name = sanitize($_POST['registerCourseName']);

    // Insert data into the database
    $sql = "INSERT INTO course (code, name) VALUES ('$code', '$name')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'New course registered successfully.';
    } else {
        $_SESSION['type'] = 'error';
        $_SESSION['msg'] = 'Error registering the new course: ' . $conn->error;
    }
    header('Location: course.php');
    exit();
}


// Edit Course
if(isset($_POST['editCourse'])) {
    $editCourseID = $_POST['editCourseID'];
    $courseCode = $_POST['editCourseCode'];
    $courseName = $_POST['editCourseName'];

    $sql = "UPDATE course SET code='$courseCode', name='$courseName' WHERE id='$editCourseID'";
    if ($conn->query($sql) === TRUE) {
        session_start();
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Course updated successfully.';
        header("Location: course.php"); // Redirect back to the index page
        exit();
    } else {
        echo "Error updating course: " . $conn->error;
    }
}

// Delete Course
if(isset($_POST['deleteCourse'])) {
    $deleteCourseID = $_POST['deleteCourseID'];

    $sql = "DELETE FROM course WHERE id='$deleteCourseID'";
    if ($conn->query($sql) === TRUE) {
        session_start();
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Course deleted successfully.';
        header("Location: course.php"); // Redirect back to the index page
        exit();
    } else {
        echo "Error deleting course: " . $conn->error;
    }
}


if(isset($_POST['editUser'])) {
    // Handle edit user action
    $id = $_POST['id'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $year = $_POST['year'];
    $course = $_POST['course'];
    $athlete = $_POST['athlete'];
    $sport = $_POST['sport'];
    $team = $_POST['team'];
    if ($athlete == 1) {
        $verification = 1;
    }
    else{
        $verification = 0;
    }

    // Perform update query
    $sql = "UPDATE users SET lastname='$lastName', firstname='$firstName', middlename='$middleName', year='$year', course='$course', athlete='$athlete', sport='$sport', team='$team', verification='$verification' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = "Record updated successfully";
        header('Location: users.php');
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

if(isset($_POST['deleteUser'])) {
    // Handle delete user action
    $id = $_POST['id'];

    // Perform delete query
    $sql = "DELETE FROM users WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = "Record deleted successfully";
        header('Location: users.php');
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}


if(isset($_POST['newScheduleDateTime'])) {
    // Retrieve the posted data
    $rescheduleId = $_POST['rescheduleId'];
    $newScheduleDateTime = $_POST['newScheduleDateTime'];

    // Update the schedule with the new date and time
    $sql = "UPDATE schedule SET schedule = '$newScheduleDateTime' WHERE id = '$rescheduleId'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['type'] = 'success';
        $_SESSION['msg'] = 'Schedule successfully rescheduled.';
    } else {
        $_SESSION['type'] = 'danger';
        $_SESSION['msg'] = 'Error rescheduling schedule: ' . $conn->error;
    }

    // Redirect back to the page where the form was submitted
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
}


$conn->close();
?>
