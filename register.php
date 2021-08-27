<?php
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'employee_db';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['fname'], $_POST['lname'], $_POST['EmpID'], $_POST['PNumber'], $_POST['email'])) {
	// Could not get the data that should have been sent.
	exit('Please complete the registration form!');
}

// Make sure the submitted registration values are not empty.
if (empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['EmpID']) || empty($_POST['PNumber'])  || empty($_POST['email'])) {
	// One or more values are empty.
	exit('Please complete the registration form');
}
// We need to check if the account with that username exists.
if ($stmt = $con->prepare('SELECT id, FirstName FROM employees WHERE EmployeeID = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['EmpID']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Username already exists
		echo 'User exists, please choose another!';
	} else {
        // Username doesnt exists, add new user
    if ($stmt = $con->prepare('INSERT INTO employees (FirstName, LastName, EmployeeID, PhoneNumber, email) VALUES (?, ?, ?, ?, ?)')) {
	// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
	$stmt->bind_param('sssss', $_POST['fname'], $_POST['lname'], $_POST['EmpID'], $_POST['PNumber'], $_POST['email']);
	$stmt->execute();
    $_SESSION['success'] = "User Registration successful";
    
      
        header('Location: employee.html');
    } else {
	// Something is wrong with the sql statement.
	echo 'Could not prepare statement!';
}
	}
	$stmt->close();
} else {
	// Something is wrong with the sql statement, check to make sure accusers table exists with all  fields.
	echo 'Could not prepare statement!';
}
$con->close();
?>
 