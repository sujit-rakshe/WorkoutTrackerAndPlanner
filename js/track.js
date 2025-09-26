$(document).ready(function() {
    var routine_id; // Define routine_id in the scope

    // Function to fetch today's schedule and display
    function fetchTodaysSchedule() {
        $.ajax({
            url: './php/fetch-todays-schedule.php',
            type: 'GET',
            success: function(data) {
                var scheduleTable = $("#todaysScheduleTable");
                scheduleTable.empty();

                if (data.exercises.length > 0) {
                    // Create table header
                    var tableHeader = $("<tr>");
                    tableHeader.append($("<th>").text("Exercise"));
                    /*tableHeader.append($("<th>").text("Set 1"));
                    tableHeader.append($("<th>").text("Set 2"));
                    tableHeader.append($("<th>").text("Set 3"));*/
                    scheduleTable.append(tableHeader);

                    // Loop through each exercise
                    data.exercises.forEach(function(exercise) {
                        var exerciseRow = $("<tr>");

                        // Exercise name column
                        var exerciseNameCol = $("<td>").text(exercise.exercise_name);
                        exerciseRow.append(exerciseNameCol);
/*
                        // Set input columns
                        for (var i = 1; i <= 3; i++) {
                            var setInput = $("<input>").attr({
                                type: "number", // Change to number type
                                class: "form-control",
                                placeholder: exercise.unit,
                                required: true // Make input required
                            });
                            var setCol = $("<td>").append(setInput);
                            exerciseRow.append(setCol);
                        }
*/
                        // Append the row to the table
                        scheduleTable.append(exerciseRow);
                    });
                } else {
                    var noWorkoutsRow = $("<tr>").append($("<td>").attr("colspan", 4).text("No workouts scheduled for today."));
                    scheduleTable.append(noWorkoutsRow);
                }

                // Store routine_id after fetching schedule
                routine_id = data.routine_id;
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    // Call the function on page load
    fetchTodaysSchedule();
/*
    // Function to handle form submission
    $("#submitWorkout").on("click", function() {
        var formData = [];
        $("#todaysScheduleTable tbody tr").each(function() {
            var exerciseData = {
                exercise_name: $(this).find("td:first-child").text(),
                sets: []
            };
            $(this).find("input").each(function() {
                var setInput = $(this).val();
                exerciseData.sets.push(setInput);
            });
            formData.push(exerciseData);
        });

        // AJAX to send form data to PHP script
        $.ajax({
            url: './php/save-workout.php',
            type: 'POST',
            data: {
                routine_id: routine_id, // Pass routine_id
                workout_data: JSON.stringify(formData) // Pass form data
            },
            success: function(response) {
                // Handle success response
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    });*/
});

