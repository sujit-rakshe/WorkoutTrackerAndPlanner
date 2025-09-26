
        function showForm(formId) {
            var forms = document.querySelectorAll('form');
            forms.forEach(function (form) {
                form.classList.remove('active-form');
            });
            var selectedForm = document.getElementById(formId);
            selectedForm.classList.add('active-form');
            var switchMessage = document.getElementById('switchMessage');
            if (formId === 'loginForm') {
                switchMessage.innerHTML = 'Not a member yet? Join <a href="#" onclick="switchForm(\'signupForm\')">Sign up</a>';
            } else {
                switchMessage.innerHTML = 'Already have an account? <a href="#" onclick="switchForm(\'loginForm\')">Login</a>';
            }
        }

        function switchForm(formId) {
            showForm(formId);
        }

        showForm('loginForm');

        document.getElementById('signupForm').addEventListener('submit', function(event) {
            var email = document.getElementById('signupEmail').value;
            var password = document.getElementById('signupPassword').value;
            var dob = new Date(document.getElementById('signupDOB').value);
            var minAge = 12;
            var maxAge = 60;

            // Flag to track if any validation error occurs
            var hasError = false;

            // Validate email
            if (!emailIsValid(email)) {
                document.getElementById('signupEmailError').innerText = 'Please enter a valid email address.';
                hasError = true;
            } else {
                document.getElementById('signupEmailError').innerText = '';
            }

            // Validate password
            if (password.length < 8) {
                document.getElementById('signupPasswordError').innerText = 'Password must be at least 8 characters long.';
                hasError = true;
            } else {
                document.getElementById('signupPasswordError').innerText = '';
            }

            // Validate date of birth
            var today = new Date();
            var minDate = new Date();
            minDate.setFullYear(minDate.getFullYear() - minAge); // Minimum age
            var maxDate = new Date();
            maxDate.setFullYear(maxDate.getFullYear() - maxAge); // Maximum age

            if (dob >= minDate || dob <= maxDate) {
                document.getElementById('signupDOBError').innerText = 'Please enter a valid date of birth (between 12 and 60 years old).';
                hasError = true;
            } else {
                document.getElementById('signupDOBError').innerText = '';
            }

            // Prevent form submission if there are validation errors
            if (hasError) {
                event.preventDefault();
            }
        });

        // Function to validate email format
        function emailIsValid(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        document.getElementById('loginForm').addEventListener('submit', function(event) {
            var email = document.getElementById('loginEmail').value;
            var password = document.getElementById('loginPassword').value;

            // Flag to track if any validation error occurs
            var hasError = false;

            // Validate email
            if (!emailIsValid(email)) {
                document.getElementById('loginEmailError').innerText = 'Please enter a valid email address.';
                hasError = true;
            } else {
                document.getElementById('loginEmailError').innerText = '';
            }

            // Validate password
            if (password.length < 8) {
                document.getElementById('loginPasswordError').innerText = 'Password must be at least 8 characters long.';
                hasError = true;
            } else {
                document.getElementById('loginPasswordError').innerText = '';
            }

            // Prevent form submission if there are validation errors
            if (hasError) {
                event.preventDefault();
            }
        });

