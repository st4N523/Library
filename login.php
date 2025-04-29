<?php
	// $sessionTimeout = 30 * 60;
	// session_set_cookie_params($sessionTimeout);
	session_start();
	
	?>
<!DOCTYPE html>
<html>
<head>
	<title>`Login Form</title>
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet"> <!--font-->
	<script src="https://kit.fontawesome.com/a81368914c.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!--popup boxes-->
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> <!--font-->
	<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- adjusts its layout based on the device's width-->
    <style>
        *{
	padding: 0;
	margin: 0;
	box-sizing: border-box;
}

body{
    font-family: 'Poppins', sans-serif;
    overflow: hidden;
}

.container{
    width: 100vw;
    height: 100vh;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap :7rem;
    padding: 0 2rem;
}

.img{
	display: flex;
	justify-content: flex-end;
	align-items: center;
}

.login-content{
	display: flex;
	justify-content: flex-start;
	align-items: center;
	text-align: center;
}

.img img{
	width: 500px;
}

form{
	width: 360px;
}

.login-content img{
    height: 100px;
}

.login-content h2{
	margin: 15px 0;
	color: #333;
	text-transform: uppercase;
	font-size: 2.9rem;
}

.login-content .input-div{
	position: relative;
    display: grid;
    grid-template-columns: 7% 93%;
    margin: 25px 0;
    padding: 5px 0;
    border-bottom: 2px solid #d9d9d9;
}

.login-content .input-div.one{
	margin-top: 0;
}

.i{
	color: #d9d9d9;
	display: flex;
	justify-content: center;
	align-items: center;
}

.i i{
	transition: .3s;
}

.input-div > div{
    position: relative;
	height: 45px;
}

.input-div > div > h5{
	position: absolute;
	left: 10px;
	top: 50%;
	transform: translateY(-50%);
	color: #999;
	font-size: 18px;
	transition: .3s;
}

.input-div:before, .input-div:after{
	content: '';
	position: absolute;
	bottom: -2px;
	width: 0%;
	height: 2px;
	background-color: #38d39f;
	transition: .4s;
}

.input-div:before{
	right: 50%;
}

.input-div:after{
	left: 50%;
}

.input-div.focus:before, .input-div.focus:after{
	width: 50%;
}

.input-div.focus > div > h5{
	top: -5px;
	font-size: 15px;
}

.input-div.focus > .i > i{
	color: #38d39f;
}

.input-div > div > input{
	position: absolute;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	border: none;
	outline: none;
	background: none;
	padding: 0.5rem 0.7rem;
	font-size: 1.2rem;
	color: #555;
	font-family: 'poppins', sans-serif;
}

.input-div.pass{
	margin-bottom: 4px;
}

a{
	display: block;
	text-align: right;
	text-decoration: none;
	color: #999;
	font-size: 0.9rem;
	transition: .3s;
}

a:hover{
	color: #38d39f;
}

.btn{
	display: block;
	width: 100%;
	height: 50px;
	border-radius: 25px;
	outline: none;
	border: none;
	background-color: skyblue;
	background-size: 200%;
	font-size: 1.2rem;
	color: black;
	font-family: 'Poppins', sans-serif;
	text-transform: uppercase;
	margin: 1rem 0;
	cursor: pointer;
	transition: .5s;
}
.btn:hover{
	background-position: right;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="img">
            <img src="img/WHAT-IS-THE-PURPOSE-OF-A-LIBRARY-MANAGEMENT-SYSTEM-min.png" alt="Library Management System">
        </div>
        <div class="login-content">
            <form method="post">
                <img src="img/profile.jpg" alt="Profile Image">
                <h2 class="title">Welcome</h2>
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="div">
                        <h5>Email</h5>
                        <input type="text" class="input" name="email" id="email">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Password</h5>
                        <input type="password" class="input" name="password" id="password">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
						<a href="register.php">Register</a>
                    </div>
                </div>
                <input type="submit" class="btn" value="Login" name="login">
            </form>
        </div>
    </div>
    <script>
        const inputs = document.querySelectorAll(".input");

        function addcl() {
            let parent = this.parentNode.parentNode;
            parent.classList.add("focus");
        }

        function remcl() {
            let parent = this.parentNode.parentNode;
            if (this.value == "") {
                parent.classList.remove("focus");
            }
        }

        inputs.forEach(input => {
            input.addEventListener("focus", addcl);
            input.addEventListener("blur", remcl);
        });
    </script>
</body>
</html>

<?php 
    if(isset($_POST["login"])){
        include("dataconnection.php");
        $email = mysqli_real_escape_string($connect, trim($_POST['email']));
        $password = trim($_POST['password']);
        if(empty($email) || empty($password))
        {
        ?>
            <script>
                swal({
                title: "Failed!",
                text: "Please fill your information.",
                icon: "error",
                button: "OK",
            });
            </script>
            <?php
        }	else{
            $sql = mysqli_query($connect, "SELECT * FROM admin where email = '$email'");
            $count = mysqli_num_rows($sql);
            if($count > 0)
            {
                $fetch = mysqli_fetch_assoc($sql);
                $status = $fetch["AdminStatus"];
                $adminpassword = $fetch["password"];
                if($status=="Active" && $password == $adminpassword){
                    $_SESSION['Admin_ID'] = $fetch['admin_id'];
                    $_SESSION['First_Name'] = $fetch['first_name'];
                    $_SESSION['Last_Name'] = $fetch['last_name'];
                    // $_SESSION['profile'] = $fetch['AdminPicture'];
                    $_SESSION['AdminType'] = $fetch['admin_type'];
                    ?>
                    <script>
                        console.log("login");
                        swal({
                        title: "Admin Login Successful",
                        text: "Welcome to  Library Management System",
                        icon: "success",
                        button: "OK!",
                        }).then((value) => {
                            window.location.href = "index.php";
                    });
                    </script>
                    <?php
                }
                else if($status=="Blocked" || $status=="Closed"){
                    ?>
                    <script>
                        console.log("blocked");
                        swal({
                        title: "Failed",
                        text: "Your account has already been blocked because of unlawful activity.",
                        icon: "error",
                        button: "OK!",
                        });
                    </script>
                    <?php
                }
                else if($password != $adminpassword){
                    ?>
                    <script>
                        console.log("fails");
                        swal({
                        title: "Failed!",
                        text: "Incorrect password",
                        icon: "error",
                        button: "OK",
                    });
                    </script>
                    <?php
                }
            }
            else{
                ?>
                <script>
                    console.log("email not exist");
                    swal({
                    title: "Failed!",
                    text: "Email no exsist",
                    icon: "error",
                    button: "OK",
                });
                </script>
                <?php
            }
        }
    }
?>