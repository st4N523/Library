<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
  $(document).ready(function () { 
    var hasErrors = false;
    console.log("add_user");
    // Input event listener for first_name input
    $('#FirstName').on('input', function () {
      validateInput($(this));
    });
    // Input event listener for last_name input
    $('#LastName').on('input', function () {
      validateInput($(this));
    });
    $('#email').on('input', function () {
        validateEmail($(this));
    });
    $('#password').on('input', function () {
        validatePassword($(this));
    });
    $('#c_password').on('input', function () {
        validateConfirmPassword($(this), $('#password').val());
    });
    $('#phone').on('input', function () {
      validatePhoneNumber($(this));
    });
    $('#agree-term').on('click', function () {
      validateCheckbox();
});
    $('#submitbtn').on('click', function (event) {
    var hasErrors = false;
    validateInput($('#FirstName'));
    validateInput($('#LastName'));
    validateEmail($('#email'));
    validatePhoneNumber($('#phone'));
    validatePassword($('#password'));
    validateConfirmPassword($('#c_password'), $('#password').val());
    validateCheckbox(); 

    if ($('.error-message').length > 0) {
        hasErrors = true;
        event.preventDefault();
        console.log('got error');
        swal("Oops...", "Please ensure that all information is entered accurately.", "error");
      }
      if (!hasErrors) {
        event.preventDefault();
        console.log('Insert action');
        insertData();
      }
    });
    function validatePostCode(input) {
      var value = input.val();
      if(value ==="")
      {
        displayError(input, 'Required field');
      }
      else if (/^[a-zA-Z]+$/.test(value.replace(/\s/g, '')))  {
        displayError(input, 'Only number are allowed.');
      } else {
        removeError(input);
      }
    }
    function validateCheckbox() {
    var checkbox = $('#agree-term');
    if (!checkbox.is(':checked')) {
        displayError(checkbox, 'You must agree to the Terms of Service.');
    } else {
        removeError(checkbox);
    }
}

    // Function to validate general input (first_name, last_name)
    function validateEmail(input) {
        var email = input.val(); // Get the entered email value

    // Use a regular expression for email validation
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {
        // If the email is empty, show an error
        displayError(input, 'Email is required.');
    } else if (!emailRegex.test(email)) {
        // If the email does not match the regex pattern, show an error
        displayError(input, 'Invalid email format.');
    } else {
        validateEmailExistence(email).then(function (response) {
            if (response === 'exists') {
                displayError(input, 'Email already exists.');
            } else {
                removeError(input);
            }
        }).catch(function (error) {
            console.error('Error checking email existence:', error);
        });
    }
    }
    function validatePassword(input) {
            var value = input.val();
            removeError(input);

            if (value === "") {
                displayError(input, 'Required field');
            }
            else if (value.length < 8 || value.length > 16) {
                displayError(input, 'Password must be between 8 and 16 characters long');
            }
            else if (!/^(?=.*\d)(?=.*[a-z]).*$/.test(value)) {
            displayError(input, 'Password must contain at least one number and one letter');
            }
            else if (!/^(?=.*[A-Z]).*$/.test(value)) {
            displayError(input, 'Password must contain at least one uppercase letter');
            }
            else if (!/^(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).*$/.test(value)) {
            displayError(input, 'Password must contain at least one special character');
            } 
            else {
                removeError(input);
            }
        }
        // Function to validate confirm password input
      function validateConfirmPassword(input, password) {
            var value = input.val();
            removeError(input);

            if (value === "") {
                displayError(input, 'Required field');
            } else if (value !== password) {
                displayError(input, 'Passwords do not match');
            } else {
                removeError(input);
            }
      }
      function validateEmailExistence(email) {
          return new Promise(function (resolve, reject) {
              $.ajax({
                  type: "POST",
                  url: "check_emailUser.php",
                  data: {
                      action: "check_email",
                      email: email
                  },
                  success: function (response) {
                      resolve(response);
                  },
                  error: function (error) {
                      reject(error);
                  }
              });
          });
      }

    function validateInput(input) {
      var value = input.val();
      if(value ==="")
      {
        displayError(input, 'Required field');
      }
      else if (!/^[a-zA-Z]+$/.test(value.replace(/\s/g, '')))  {
        displayError(input, 'Only alphabetic characters are allowed.');
      } else {
        removeError(input);
      }
    }

    // Function to validate phone number input
    function validatePhoneNumber(input) {
      var phoneNumber = input.val();

      if (phoneNumber === "") {
        displayError(input, 'Required field');
      }
      else if (!/^\d+$/.test(phoneNumber)){
        displayError(input, 'Phone number cannot contain letters.');
      }
      else if(phoneNumber.length >10 || phoneNumber.length < 9)
      {
        displayError(input, 'Phone number does not match the length');
      } 
      else {
        removeError(input);
      }
    }
    function insertData() {
        var data = {
            action: "insert_user",
            FirstName: $("#FirstName").val(),
            LastName: $("#LastName").val(),
            email: $("#email").val(),
            password: $("#password").val(),
            phone: $("#phone").val(),
            location: $("#location").val(),
        };
        console.log(data);
        $.ajax({
            type: "POST",
            url: "insert_user.php", // Change this to the actual script handling the insertion
            data: data,
            beforeSend: function () {
            showLoading();
            },
            success: function (response) {
                hideLoading();
                if(response === "fail")
                {
                    swal("Oops...", "Register Failed, Invalid Email ", "error");
                }
                else{
                    swal("Success", "Register Successfully, OTP sent to your email", "success").then(function () {
                        location.replace("verificationUser.php");
                    });
                }
            },
            error: function (error) {
                console.error("Error:", error);
            }
        });
    }
    // Function to display error message
    function displayError(input, message) {
      // Remove existing error message
      removeError(input);
      console.log("error");
      // Add new error message
      var errorMessageDiv = $('<div class="error-message" style="color: red;position:relative;font-size: 12px;"></div>').text(message);
      input.closest('.label').append(errorMessageDiv);
    }

    // Function to remove error message
    function removeError(input) {
      input.closest('.label').find('.error-message').remove();
    }
    function showLoading() {
    $('#loading').show();
}

function hideLoading() {
    $('#loading').hide();
}
  });
</script>