// Get the main modal for workout list
var modal = document.getElementById("myModal");
var span = document.getElementsByClassName("close")[0];

// Get the exercise modal for details
var exerciseModal = document.getElementById("exerciseModal");
var exerciseModalSpan = document.getElementsByClassName("close")[1];

// When the user clicks on <span> (x), close the modals
span.onclick = function() {
    modal.style.display = "none";
}
exerciseModalSpan.onclick = function() {
    exerciseModal.style.display = "none";
}

// When the user clicks on a workout name, fetch and display the exercises
$(".workout-link").click(function() {
    var workoutId = $(this).data("workout-id");
    var workoutName = $(this).text();
    $("#workoutTitle").text(workoutName);
    fetchExercises(workoutId);
});

function fetchExercises(workoutId) {
    $.ajax({
        url: `fetch-exercises.php?workout_id=${workoutId}`,
        type: 'GET',
        success: function(data) {
        	fetchedExercises = data.exercises;
          console.log("Fetched exercises:", fetchedExercises);
            var exerciseList = $("#exerciseList");
            exerciseList.empty();
            if (data.exercises.length > 0) {
                data.exercises.forEach(function(exercise) {
                    var exerciseItem = $("<li>").text(exercise.exercise_name);
                    exerciseItem.click(function() {
                        showExerciseDetails(exercise);
                    });
                    exerciseList.append(exerciseItem);
                });
            } else {
                var noExercises = $("<li>").text("Rest days are vital for fitness. They aid in muscle recovery, lower injury chances, and prevent exhaustion. Consider it a battery recharge for your body, giving muscles time to repair and grow. On rest days, light activities like walking or stretching are beneficial. Stay hydrated, eat nutritiously, and prioritize sleep. These days enhance performance and well-being.");
                noExercises.css("font-size", "25px");
                exerciseList.append(noExercises);
            }
            modal.style.display = "block";
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
$(".exercise-link").click(function() {
    var exercise = $(this).text();
    showExerciseDetails(exercise) 
});


function showExerciseDetails(exercise) {
    $("#exerciseDetails").empty();

var exerciseName = $("<h3>").html(exercise.exercise_name + "<hr>");

    var exerciseImage = $("<img>").attr("src", "../src/" + exercise.image_url).attr("alt", exercise.exercise_name);
    exerciseImage.addClass("exercise-image");

    var leftColumn = $("<div>").addClass("left-column").append(exerciseImage);
    
    var exerciseDescription = $("<p>").html("<h4>Steps -</h4><br>"+exercise.description);
    var targetMuscle = $("<p>").html("<h4>Target Muscle: </h4>" + exercise.target_muscle);

    
    var rightColumn = $("<div>").addClass("right-column").append( targetMuscle , exerciseDescription);

    var detailsContainer = $("<div>").addClass("details-container");
    detailsContainer.append(exerciseName ,leftColumn,rightColumn);

    $("#exerciseDetails").append(detailsContainer);

    // Display the exercise modal for details
    exerciseModal.style.display = "block";
}



