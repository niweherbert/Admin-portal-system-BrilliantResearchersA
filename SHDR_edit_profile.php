<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: SHDR.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color:rgb(0, 63, 6);
            position: fixed;
            display: flex;
            flex-direction: column;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 15px 20px;
            display: block;
            transition: background 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color:rgb(2, 54, 12);
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h1 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="file"],
        .form-group input[type="tel"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group .edit-icon {
            float: right;
            cursor: pointer;
            margin-top: -28px;
            margin-right: 10px;
        }
        .form-actions {
            text-align: center;
        }
        .form-actions button {
            background-color:rgb(0, 63, 4);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-actions button:hover {
            background-color:rgb(10, 84, 0);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="SHDR_admindashb.php">Home</a>
        <a href="SHDR_search.php">Search</a>
        <a href="SHDR_stock.php">Store & Requisition</a>
        <a href="SHDR_edit_profile.php" class="active">Edit Profile</a>
    </div>
    <div class="content">
        <div class="form-container">
            <h1>Edit Profile</h1>
            <form>
                <div class="form-group">
                    <label for="update-picture">Update Picture:</label>
                    <input type="file" id="update-picture" name="picture">
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="WILSON Jonathan">
                    <img src="edit-icon.png" alt="Edit" class="edit-icon">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="jameswilson@gmail.com">
                    <img src="edit-icon.png" alt="Edit" class="edit-icon">
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" value="+250791762646">
                    <img src="edit-icon.png" alt="Edit" class="edit-icon">
                </div>
                <div class="form-actions">
                    <button type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
