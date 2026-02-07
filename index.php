<?php
include "config.php";

$login_error = '';
$signup_success = '';

// Handle Login
if(isset($_POST['but_submit'])){
    $uname = mysqli_real_escape_string($con,$_POST['txt_uname']);
    $password = mysqli_real_escape_string($con,$_POST['txt_pwd']);

    if ($uname != "" && $password != ""){
        $sql_query = "select count(*) as cntUser from users where username='".$uname."' and password='".$password."'";
        $result = mysqli_query($con,$sql_query);
        $row = mysqli_fetch_array($result);

        $count = $row['cntUser'];

        if($count > 0){
            $_SESSION['uname'] = $uname;
            header('Location: index.html');
            exit();
        }else{
            $login_error = "Wrong Username or Password";
        }
    } else {
        $login_error = "Please fill all fields";
    }
}

// Handle Signup
if(isset($_POST['signup_submit'])){
    $username = mysqli_real_escape_string($con,$_POST['signup_username']);
    $name = mysqli_real_escape_string($con,$_POST['signup_name']);
    $password = mysqli_real_escape_string($con,$_POST['signup_password']);
    $confirm_password = mysqli_real_escape_string($con,$_POST['signup_confirm_password']);

    if($username != "" && $name != "" && $password != ""){
        if($password === $confirm_password){
            // Check if username already exists
            $check_query = "select count(*) as cnt from users where username='".$username."'";
            $check_result = mysqli_query($con,$check_query);
            $check_row = mysqli_fetch_array($check_result);

            if($check_row['cnt'] == 0){
                $insert_query = "insert into users(username,name,password) values('".$username."','".$name."','".$password."')";
                if(mysqli_query($con,$insert_query)){
                    $signup_success = "Account created successfully! Please login.";
                } else {
                    $login_error = "Error creating account";
                }
            } else {
                $login_error = "Username already exists";
            }
        } else {
            $login_error = "Passwords do not match";
        }
    } else {
        $login_error = "Please fill all fields";
    }
}
?>
<html>
    <head>
        <title>HopeGivers Blood Bank - Login / Signup</title>
        <link href="style2.css" rel="stylesheet" type="text/css">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
            }
            
            .auth-container {
                background: white;
                border-radius: 10px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                overflow: hidden;
                width: 100%;
                max-width: 900px;
            }
            
            .auth-wrapper {
                display: flex;
                min-height: 500px;
            }
            
            .auth-section {
                flex: 1;
                padding: 40px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            
            .auth-section.left {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }
            
            .auth-section.left h2 {
                font-size: 28px;
                margin-bottom: 20px;
            }
            
            .auth-section.left p {
                font-size: 14px;
                line-height: 1.6;
                opacity: 0.9;
            }
            
            .auth-section.right {
                background: white;
            }
            
            .tab-buttons {
                display: flex;
                gap: 10px;
                margin-bottom: 30px;
            }
            
            .tab-btn {
                flex: 1;
                padding: 12px;
                border: none;
                background: #f0f0f0;
                cursor: pointer;
                font-size: 16px;
                border-radius: 5px;
                transition: all 0.3s;
                font-weight: 600;
            }
            
            .tab-btn.active {
                background: #667eea;
                color: white;
            }
            
            .form-group {
                display: none;
            }
            
            .form-group.active {
                display: block;
            }
            
            .form-group h3 {
                font-size: 24px;
                margin-bottom: 20px;
                color: #333;
            }
            
            .form-group label {
                display: block;
                margin-top: 15px;
                margin-bottom: 5px;
                color: #555;
                font-weight: 600;
            }
            
            .form-group input {
                width: 100%;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 14px;
                box-sizing: border-box;
                transition: border 0.3s;
            }
            
            .form-group input:focus {
                outline: none;
                border-color: #667eea;
                box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
            }
            
            .form-group button {
                width: 100%;
                padding: 12px;
                margin-top: 20px;
                background: #667eea;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.3s;
            }
            
            .form-group button:hover {
                background: #764ba2;
            }
            
            .error-message {
                color: #dc3545;
                padding: 10px;
                background: #f8d7da;
                border-radius: 5px;
                margin-bottom: 15px;
                border: 1px solid #f5c6cb;
            }
            
            .success-message {
                color: #155724;
                padding: 10px;
                background: #d4edda;
                border-radius: 5px;
                margin-bottom: 15px;
                border: 1px solid #c3e6cb;
            }
            
            @media screen and (max-width: 768px) {
                .auth-wrapper {
                    flex-direction: column;
                    min-height: auto;
                }
                
                .auth-section.left {
                    padding: 30px 40px;
                }
                
                .auth-section.right {
                    padding: 30px 40px;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-wrapper">
                <div class="auth-section left">
                    <h2>HopeGivers Blood Bank</h2>
                    <p>Join our blood donation community and help save lives. Whether you're a regular donor or new to blood donation, we're here to make the process easy and rewarding.</p>
                    <p style="margin-top: 20px;"><strong>Key Benefits:</strong></p>
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        <li>Save lives by donating blood</li>
                        <li>Find blood donors in your network</li>
                        <li>Track your donation history</li>
                        <li>Get health tips and facts</li>
                    </ul>
                </div>
                
                <div class="auth-section right">
                    <div class="tab-buttons">
                        <button class="tab-btn active" onclick="switchTab('login')">Login</button>
                        <button class="tab-btn" onclick="switchTab('signup')">Sign Up</button>
                    </div>
                    
                    <?php if($login_error): ?>
                        <div class="error-message"><?php echo $login_error; ?></div>
                    <?php endif; ?>
                    
                    <?php if($signup_success): ?>
                        <div class="success-message"><?php echo $signup_success; ?></div>
                    <?php endif; ?>
                    
                    <!-- Login Form -->
                    <form method="post" action="">
                        <div class="form-group active" id="login">
                            <h3>Login</h3>
                            <label for="txt_uname">Username</label>
                            <input type="text" id="txt_uname" name="txt_uname" placeholder="Enter your username" required />
                            
                            <label for="txt_pwd">Password</label>
                            <input type="password" id="txt_pwd" name="txt_pwd" placeholder="Enter your password" required />
                            
                            <button type="submit" name="but_submit">Login</button>
                        </div>
                    </form>
                    
                    <!-- Signup Form -->
                    <form method="post" action="">
                        <div class="form-group" id="signup">
                            <h3>Create Account</h3>
                            <label for="signup_username">Username</label>
                            <input type="text" id="signup_username" name="signup_username" placeholder="Choose a username" required />
                            
                            <label for="signup_name">Full Name</label>
                            <input type="text" id="signup_name" name="signup_name" placeholder="Enter your full name" required />
                            
                            <label for="signup_password">Password</label>
                            <input type="password" id="signup_password" name="signup_password" placeholder="Create a password" required />
                            
                            <label for="signup_confirm_password">Confirm Password</label>
                            <input type="password" id="signup_confirm_password" name="signup_confirm_password" placeholder="Confirm your password" required />
                            
                            <button type="submit" name="signup_submit">Create Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <script>
            function switchTab(tab) {
                // Hide all forms
                document.getElementById('login').classList.remove('active');
                document.getElementById('signup').classList.remove('active');
                
                // Remove active class from all tabs
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                
                // Show selected form
                document.getElementById(tab).classList.add('active');
                
                // Add active class to clicked tab
                event.target.classList.add('active');
            }
        </script>
    </body>
</html>


