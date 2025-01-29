<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Brilliant Researchers Africa</title>
  <style>


* {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: linear-gradient(to bottom right, #87CEEB, #1E90FF);
    }

    .container {
      width: 350px;
      padding: 20px;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    .container h1 {
      font-size: 20px;
      margin-bottom: 15px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      font-size: 14px;
      margin-bottom: 5px;
      text-align: left;
    }

    .form-group input, .form-group select {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .form-group button {
      width: 100%;
      padding: 10px;
      background: #1E90FF;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .form-group button:hover {
      background: #104E8B;
    }

    .links {
      margin-top: 10px;
    }

    .links a {
      color: #1E90FF;
      text-decoration: none;
      font-size: 12px;
    }

    .links a:hover {
      text-decoration: underline;
    }


 /* Responsive styles */
 @media screen and (max-width: 768px) {
      .container {
        width: 90%;
        max-width: 350px;
      }

      .form-group input, .form-group select {
        font-size: 16px; /* Prevents zoom on mobile devices */
      }
    }

    @media screen and (max-width: 480px) {
      .container {
        padding: 15px;
      }

      .container h1 {
        font-size: 18px;
      }

      .form-group {
        margin-bottom: 10px;
      }

      .form-group label {
        font-size: 12px;
      }

      .form-group input, .form-group select {
        padding: 8px;
      }

      .form-group button {
        font-size: 14px;
      }

      .links a {
        font-size: 11px;
      }
    }

    .logo-container {
    text-align: center;
    margin-bottom: 20px;
  }

  .logo {
    max-width: 100%;
    height: auto;
    max-height: 100px; /* Adjust this value as needed */
  }




  </style>
</head>
<body>
  <div class="container">
    <div class="logo-container">
      <img src="BRA.png" alt="SHDR Logo" class="logo">
    </div>

    <!-- Login Form -->
    <div id="loginForm">
      <h2>LOGIN</h2>
      <form action="login.php" method="POST">
        <div class="form-group">
          <label for="loginEmail">Email or Phone</label>
          <input type="text" id="loginEmail" name="email_or_phone" placeholder="Enter email or phone" required>
        </div>
        <div class="form-group">
          <label for="loginPassword">Enter Password</label>
          <input type="password" id="loginPassword" name="password" placeholder="Enter password" required>
        </div>
        <div class="form-group">
          <label for="loginRole">User Role</label>
          <select id="loginRole" name="user_role" required>
            <option value="">Select Role</option>
            <option value="Admin">Admin</option>
            <option value="Employee">Employee</option>
            <option value="Intern">Intern</option>
            <option value="Trainer">Trainer</option>
          </select>
        </div>
        <div class="links">
          <a href="forgot_password.php">Forgot password?</a>
        </div>
        <div class="form-group">
          <button type="submit">Login</button>
        </div>
        <div class="links">
          <a href="#" onclick="showSignUpForm()">Create account?</a>
        </div>
      </form>
    </div>

    <!-- Signup Form -->
    <div id="signupForm" style="display: none;">
      <h2>CREATE ACCOUNT</h2>
      <form action="register.php" method="POST">
        <div class="form-group">
          <label for="signupName">Name</label>
          <input type="text" id="signupName" name="name" placeholder="Enter your name" required>
        </div>
        <div class="form-group">
          <label for="signupEmail">Email or Phone</label>
          <input type="text" id="signupEmail" name="email_or_phone" placeholder="Enter email or phone" required>
        </div>
        <div class="form-group">
  <label for="signupPassword">Enter Password</label>
  <div style="position: relative;">
    <input type="password" id="signupPassword" name="password" placeholder="Enter password" 
           required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}" style="padding-right: 30px;">
    <i id="togglePassword" class="fa fa-eye" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
  </div>
  <small style="color: #1E90FF; display: block; margin-top: 5px;">Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one digit, and one special character.</small>
</div>

<div class="form-group">
  <label for="confirmPassword">Confirm Password</label>
  <input type="password" id="confirmPassword" placeholder="Confirm password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}">
</div>

        <div class="form-group">
          <label for="signupRole">User Role</label>
          <select id="signupRole" name="user_role" onchange="showSubRoles()" required>
            <option value="">Select Role</option>
            <option value="Admin">Admin</option>
            <option value="Employee">Employee</option>
            <option value="Intern">Intern</option>
            <option value="Trainer">Trainer</option>
          </select>
        </div>
        <div class="form-group" id="subRoleGroup" style="display: none;">
          <label for="subRole">Specialization</label>
          <select id="subRole" name="specialization" onchange="showFinalRoles()">
            <option value="">Select Specialization</option>
            <option value="Physician">Physicist</option>
            <!-- Add other specializations as needed -->
            <option value="Chemist">Chemist</option>
            <option value="IT">IT</option>
            <option value="Mechanical">Mechanical</option>
            <option value="Electrical">Electrical</option>
            <option value="Architecture">Architecture</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="form-group" id="finalRoleGroup" style="display: none;">
          <label for="finalRole">Role</label>
          <select id="finalRole" name="role">
            <option value="">Select Final Role</option>
            <option value="CEO">CEO</option>
            <option value="Manager">Manager</option>
            <option value="Researcher">Researcher</option>
            <option value="Trainer">Trainer</option>
            <option value="Engineer">Engineer</option>
            <option value="Technician">Technician</option>
            <option value="Designer">Designer</option>
            <option value="Developer">Developer</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="form-group" id="finalRoleGroup" style="display: none;">
          <label for="finalRole">Final Role</label>
          <input type="text" id="finalRole" name="final_role" placeholder="Enter your final role">
        </div>
        <div class="form-group">
          <button type="submit">Sign Up</button>
        </div>
        <div class="links">
          <a href="#" onclick="showLoginForm()">Back to Login</a>
        </div>
      </form>
  </div>

  <script>

function showSignUpForm() {
      document.getElementById("loginForm").style.display = "none";
      document.getElementById("signupForm").style.display = "block";
    }

    function showLoginForm() {
      document.getElementById("signupForm").style.display = "none";
      document.getElementById("loginForm").style.display = "block";
    }

    function showSubRoles() {
      const role = document.getElementById("signupRole").value;
      const subRoleGroup = document.getElementById("subRoleGroup");
      subRoleGroup.style.display = role ? "block" : "none";
    }

    function showFinalRoles() {
      const subRole = document.getElementById("subRole").value;
      const finalRoleGroup = document.getElementById("finalRoleGroup");
      finalRoleGroup.style.display = subRole ? "block" : "none";
    }

    
    function showResetPasswordForm() {
      document.getElementById("loginForm").style.display = "none";
      document.getElementById("signupForm").style.display = "none";
      document.getElementById("resetPasswordForm").style.display = "block";
    }
  </script>
</body>
</html>
