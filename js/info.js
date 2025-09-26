$(document).ready(function() {
    var exerciseModal = document.getElementById("exerciseModal");
    var exerciseModalSpan = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modals
    exerciseModalSpan.onclick = function() {
        exerciseModal.style.display = "none";
    };

    // Function to fetch exercises for a specific workout ID
    var fetchedExercises = [];

    function fetchExercises(workoutId) {
        $.ajax({
            url: `php/fetch-exercises.php?workout_id=${workoutId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Store the fetched exercises in the outer variable
                fetchedExercises = response.exercises;
                console.log("Fetched exercises:", fetchedExercises);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    $(".exercise-link").click(function() {
        var exercise_name = $(this).text();

        // Loop through fetchedExercises to find the matching exercise
        for (var i = 0; i < fetchedExercises.length; i++) {
            var exercise = fetchedExercises[i];
            if (exercise_name === exercise.exercise_name) {
                showExerciseDetails(exercise);
                break; // Exit the loop once a match is found
            }
        }
    });

    // Function to display exercise details
    function showExerciseDetails(exercise) {
        console.log("exercise clicked :", exercise);
        $("#exerciseDetails").empty();

        var exerciseName = $("<h3>").html(exercise.exercise_name + "<hr>");

        var exerciseImage = $("<img>").attr("src", "http://localhost/WorkoutPlannerAndTracker/src/" + exercise.image_url).attr("alt", exercise.exercise_name);
        exerciseImage.addClass("exercise-image");

        var leftColumn = $("<div>").addClass("left-column").append(exerciseImage);

        var exerciseDescription = $("<p>").html("<h4>Steps:</h4><br>" + exercise.description);
        var targetMuscle = $("<p>").html("<h4>Target Muscle:</h4>" + exercise.target_muscle);

        var rightColumn = $("<div>").addClass("right-column").append(targetMuscle, exerciseDescription);

        var detailsContainer = $("<div>").addClass("details-container");
        detailsContainer.append(exerciseName, leftColumn, rightColumn);

        $("#exerciseDetails").append(detailsContainer);

        // Display the exercise modal for details
        $("#exerciseModal").modal("show");
    }

    // Automatically fetch exercises for the workout ID on document load
    var workoutId = $(".workout-link").data("workout-id");
    fetchExercises(workoutId);
});

