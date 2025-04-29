<!DOCTYPE html>
<html>
<head>
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for all buttons */
button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

button:hover {
  opacity:1;
}

/* Extra styles for the cancel button */
.cancelbtn {
  padding: 14px 20px;
  background-color: #f44336;
}

/* Float cancel and signup buttons and add an equal width */
.cancelbtn, .signupbtn {
  float: left;
  width: 50%;
}

/* Add padding to container elements */
.container {
  padding: 16px;
  position: relative;
}

/* Clear floats */
.clearfix::after {
  content: "";
  clear: both;
  display: table;
}

/* Change styles for cancel button and signup button on extra small screens */
@media screen and (max-width: 300px) {
  .cancelbtn, .signupbtn {
     width: 100%;
  }
}

/* Style for error messages */
.error-message {
  color: red;
  font-size: 12px;
  position: absolute;
  margin-top: -20px;
  margin-left: 10px;
  visibility: hidden; /* Ensure initial state is hidden */
}
</style>
</head>
<body>

<form action="/action_page.php" style="border:1px solid #ccc" id="signupForm">
  <div class="container">
    <h1>Sign Up</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>
    <label for="FirstName"><b>First Name</b></label>
    <input type="text" placeholder="Enter First name" name="FirstName" id="FirstName">
    <div class="error-message" id="FirstName-error"></div>

    <label for="LastName"><b>Last Name</b></label>
    <input type="text" placeholder="Enter Last Name" name="LastName" id="LastName">
    <div class="error-message" id="LastName-error"></div>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" id="email">
    <div class="error-message" id="email-error"></div>

    <label for="phone"><b>Phone number</b></label>
    <input type="text" placeholder="Enter phone number" name="phone" id="phone">
    <div class="error-message" id="phone-error"></div>

    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" id="password">
    <div class="error-message" id="password-error"></div>

    <label for="c_password"><b>Repeat Password</b></label>
    <input type="password" placeholder="Repeat Password" name="c_password" id="c_password">
    <div class="error-message" id="c_password-error"></div>

    <div class="clearfix" style="margin-top: 30px;">
      <button type="button" class="cancelbtn">Cancel</button>
      <button type="submit" class="signupbtn">Sign Up</button>
    </div>
  </div>
</form>

<!-- Loading spinner -->
<div id="loading" style="display:none;">Loading...</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function () {
  function displayError(input, message) {
    var errorElement = $('#' + input.attr('id') + '-error');
    errorElement.text(message);
    errorElement.css('visibility', 'visible');
  }

  function removeError(input) {
    var errorElement = $('#' + input.attr('id') + '-error');
    errorElement.text(''); // Clear the error message
    errorElement.css('visibility', 'hidden');
  }

  $('#signupForm').on('submit', function (event) {
    event.preventDefault();
    var hasErrors = false;

    validateInput($('#FirstName'));
    validateInput($('#LastName'));
    validateEmail($('#email'));
    validatePhoneNumber($('#phone'));
    validatePassword($('#password'));
    validateConfirmPassword($('#c_password'), $('#password').val());

    if ($('.error-message').filter(function() { return $(this).text() !== ''; }).length > 0) {
      console.log("gg");
      hasErrors = true;
      swal("Oops...", "Please ensure that all information is entered accurately.", "error");
    }

    if (!hasErrors) {
      insertData();
    }
  });

  $('#FirstName').on('input', function () {
    validateInput($(this));
  });

  $('#LastName').on('input', function () {
    validateInput($(this));
  });

  $('#email').on('input', function () {
    validateEmail($(this));
  });

  $('#phone').on('input', function () {
    validatePhoneNumber($(this));
  });

  $('#password').on('input', function () {
    validatePassword($(this));
  });

  $('#c_password').on('input', function () {
    validateConfirmPassword($(this), $('#password').val());
  });

  function validateInput(input) {
    var value = input.val();
    if (value === "") {
      displayError(input, 'Required field');
    } else if (!/^[a-zA-Z]+$/.test(value.replace(/\s/g, ''))) {
      displayError(input, 'Only alphabetic characters are allowed.');
    } else {
      removeError(input);
    }
  }

  function validateEmail(input) {
    var email = input.val();
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {
      displayError(input, 'Email is required.');
    } else if (!emailRegex.test(email)) {
      displayError(input, 'Invalid email format.');
    } else {
      removeError(input);
    }
  }

  function validatePassword(input) {
    var value = input.val();
    removeError(input);

    if (value === "") {
      displayError(input, 'Required field');
    } else if (value.length < 8 || value.length > 16) {
      displayError(input, 'Password must be between 8 and 16 characters long');
    } else if (!/^(?=.*\d)(?=.*[a-z]).*$/.test(value)) {
      displayError(input, 'Password must contain at least one number and one letter');
    } else if (!/^(?=.*[A-Z]).*$/.test(value)) {
      displayError(input, 'Password must contain at least one uppercase letter');
    } else if (!/^(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).*$/.test(value)) {
      displayError(input, 'Password must contain at least one special character');
    } else {
      removeError(input);
    }
  }

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

  function validatePhoneNumber(input) {
    var phoneNumber = input.val();

    if (phoneNumber === "") {
      displayError(input, 'Required field');
    } else if (!/^\d+$/.test(phoneNumber)) {
      displayError(input, 'Phone number cannot contain letters.');
    } else if (phoneNumber.length > 10 || phoneNumber.length < 9) {
      displayError(input, 'Phone number does not match the length');
    } else {
      removeError(input);
    }
  }

  function insertData() {
    var data = {
      FirstName: $("#FirstName").val(),
      LastName: $("#LastName").val(),
      email: $("#email").val(),
      password: $("#password").val(),
      phone: $("#phone").val(),
    };
    console.log(data);
    $.ajax({
      type: "POST",
      url: "insert_user.php",
      data: data,
      beforeSend: function () {
        showLoading();
      },
      success: function (response) {
        hideLoading();
        console.log(response);
        if(response === "fail") {
          swal("Oops...", "Register Failed, Invalid Email ", "error");
        } else {
          swal("Success", "Register Successfully", "success");
        }
      },
      error: function (error) {
        console.error("Error:", error);
      }
    });
  }

  function showLoading() {
    $('#loading').show();
  }

  function hideLoading() {
    $('#loading').hide();
  }

  $('.cancelbtn').on('click', function () {
    window.location.href = 'login.php'; 
  });

});
</script>

</body>
</html>
