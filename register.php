<?php

require('SQLconnect.php');

echo "<!DOCTYPE = HTML>\n";
echo "<html>\n";
echo "  <head>\n";
echo "      <title>REGISTER</title>\n";
echo "      <link rel = 'stylesheet' type = 'text/css' href = 'style.css'>\n";
echo "  </head>\n";
echo "  <body>\n";

include('nav.php');

echo "      <div class = 'register_box'>\n";
echo "          <form method = 'POST'>\n";
echo "              <h5>First Name: <input type = 'text' placeholder = 'Tony' name = 'name'></h5>\n";
echo "              <h5>Last Name: <input type = 'text' placeholder = 'Cervantes' name = 'lname'></h5>\n";
echo "              <h5>UserName: <input type = 'text' placeholder = 'XLR8' name = 'username'></h5>\n";
echo "              <h5>Email: <input type = 'email' placeholder = 'email@email.com' name = 'email'></h5>\n";
echo "              <h5>Password: <input type = 'password' placeholder = '*****' name = 'password'></h5>\n";
echo "              <h5>Confirm Password: <input type = 'password' placeholder = '*****' name = 'confirm_password'></h5>\n";

echo "              <h5>Make: <input type = 'text' placeholder = 'Toyota' name = 'make'></h5>\n";
echo "              <h5>Model: <input type = 'text' placeholder = 'GT86' name = 'model'></h5>\n";
echo "              <h5>2017: <input type = 'number' placeholder = '2017' name = 'year'></h5>\n";
echo "              <h5>Last 4 of LicensePlate: <input type = 'text' placeholder = 'C777' name = 'licensePlate'></h5>\n";
echo "              <h5>Color: <input type = 'text' placeholder = 'Grey' name = 'color'></h5>\n";
echo "              <h5>Photo: <input type = 'text' placeholder = 'asdf.jpg' name = 'photo'></h5>\n";
echo "              <button class = 'nav_btn' type = 'submit'>Create</button>\n";
echo "          </form>\n";
echo "      </div>\n";

echo "  </body>\n";
echo "</html>\n";


if (!empty($_POST['name']) && !empty($_POST['lname']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])){

    //this converts the post parameters from HTML into PHP parameters as well as we sanatize it to avoid sql queries being interjected
    $name = htmlspecialchars(trim($_POST['name']));/////////////////ADD SANATIZATION
    $lname = htmlspecialchars(trim($_POST['lname']));
    $username = htmlspecialchars(trim($_POST['username']));/////////////////ADD SANATIZATION
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));/////////////////ADD SANATIZATION
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    $make = htmlspecialchars(trim($_POST['make']));/////////////////ADD SANATIZATION
    $model = htmlspecialchars(trim($_POST['model']));
    $year = htmlspecialchars(trim($_POST['year']));/////////////////ADD SANATIZATION
    $licensePlate = htmlspecialchars(trim($_POST['licensePlate']));
    $color = htmlspecialchars(trim($_POST['color']));

    //$name = $_POST['name'];
    //$lname = $_POST['lname'];
    //$username = $_POST['username'];
    //$email = $_POST['email'];
    //$password = $_POST['password'];
    //$confirm_password = $_POST['confirm_password'];

    //echo "name: $name , lastname: $lname, username: $username , email: $email , password: $password , confirm: $confirm_password, Make: $make, Model = $model, Year: $year, licenseplate: $licensePlate";

    if ($password == $confirm_password){

        $sql = "select * from Users where userName = '$username' or email = '$email' or licensePlate = '$licensePlate'";
        if ($res = $db->query($sql)){

            $row = $res->FETCH_ASSOC();
            
            $exists = '';
            
            if ($row['userName'] == $username){

                $exists .= 'UserName taken<br>';
            }
            if ($row['email'] == $email){

                $exists .= 'Email already exists<br>';
            }
            if ($row['licensePlate'] == $licensePlate){

                $exists .= 'License Plate already exists<br>';
            }
            if ($row['userName'] == $username or $row['email'] == $email or $row['licensePlate'] == $licensePlate){

                echo $exists;
            }
            else{

                //echo "email or username is not taken<br>\n";
                $sql = "insert into Users (firstName, lastName, userName, password, email, make, model, year, color, licensePlate) values('$name','$lname','$username','$password','$email', '$make', '$model', '$year', '$color', '$licensePlate');";
                $db->query($sql);

                $_SESSION['active'] = true;
                $_SESSION['firstName'] = $row['firstName'];
                $_SESSION['lname'] = $row['lastName'];
                $_SESSION['access'] = $row['access'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['username'] = $row['userName'];

                $_SESSION['make'] = $row['make'];
                $_SESSION['model'] = $row['model'];
                $_SESSION['year'] = $row['year'];
                $_SESSION['color'] = $row['color'];
                $_SESSION['licensePlate'] = $row['licensePlate'];
                $_SESSION['photo'] = $row['photo'];

                header('Location: login.php');
            }
        }
        //echo "passwords matched\n";
    }
    else{

        echo "Please try again, your passwords did not match\n";
    }

}
else{

    $error = 'You forgot to enter the following:<br><br>';
    if (empty($_POST['name'])){

        $error .= 'FirstNAME<br>';
    }
    if (empty($_POST['lname'])){

        $error .= 'LastNAME<br>';
    }
    if (empty($_POST['username'])){

        $error .= 'USERNAME<br>';
    }
    if (empty($_POST['email'])){

        $error .= 'EMAIL<br>';
    }
    if (empty($_POST['password'])){

        $error .= 'PASSWORD<br>';
    }
    if ( empty($_POST['confirm_password'])){

        $error .= 'CONFIRM PASSWORD<br>';
    }
    if (empty($_POST['make'])){

        $error .= 'Make<br>';
    }
    if (empty($_POST['model'])){

        $error .= 'model<br>';
    }
    if (empty($_POST['year'])){

        $error .= 'year<br>';
    }
    if (empty($_POST['color'])){

        $error .= 'color<br>';
    }
    if ( empty($_POST['licensePlate'])){

        $error .= 'LicensePlate<br>';
    }


    //echo "TEST that is nothing is posted\n";
    echo "$error\n";
}
?>
