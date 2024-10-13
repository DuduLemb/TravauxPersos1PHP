<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
    <style>
        * {box-sizing: border-box}

        /* Add padding to containers */
        .container {
            padding: 16px;
        }

        /* Full-width input fields */
        input[type=text], input[type=password] {
            width: 60%;
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

        /* Overwrite default styles of hr */
        hr {
            border: 1px solid #f1f1f1;
            margin-bottom: 25px;
        }

        /* Set a style for the submit/register button */
        .registerbtn {
            background-color: #04AA6D;
            color: white;
            padding: 16px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
            opacity: 0.9;
        }

        .registerbtn:hover {
            opacity:1;
        }

        /* Add a blue text color to links */
        a {
            color: dodgerblue;
        }

        /* Set a grey background color and center the text of the "sign in" section */
        .signin {
            background-color: #f1f1f1;
            text-align: center;
        }
        .error {color: #FF0000;
        }
    </style>
</head>
<body>
    <?php
        //Connexion a la base de données
        $host = "localhost";
        $database = "base_formulaire";
        $password = "root";
        $conn = new mysqli($host,$password,"",$database);
        //Vérifier la connexion
        if($conn->connect_error){
            die("Erreur de connexion à la base de données:".$conn->connect_error);
        }
        //vérifier si le formulaire a été soumis
        $email = $psw = $pswrepeat = $pswrepeato = "";
        $emailErr = $pswErr = $pswrepeatErr = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $email = test_input($_POST["email"]);
            // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
            $psw = test_input($_POST["psw"]);
            if(is_not_valid_password($psw)) {
                $pswErr  = "Invalid password format";
            }
            $pswrepeat = test_input($_POST["psw-repeat"]);
            if(is_not_valid_password($pswrepeat)) {
                $pswrepeatErr  = "Invalid password format";
            }
            if($pswrepeat != $psw) {
                $pswrepeato = "Incorrect password";
            }
            //préparer la requete SQL
            $sql = "INSERT INTO client(Email,Password,RepeatPassword) VALUES(?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss",$email,$psw,$pswrepeat);
             // Exécuter la requête
            if ($stmt->execute()) {
            echo "Données enregistrées avec succès !";
            } else {
            echo "Erreur lors de l'enregistrement des données: " . $stmt->error;
            }

            // Fermer la requête préparée
            $stmt->close();

        }
        // Fermer la connexion à la base de données
        $conn->close();


        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        function is_not_valid_password($password) {
            // Vérifie si le mot de passe ne respecte pas certains critères de sécurité
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);
        
            if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                return true;
            } else {
                return false;
            }
        }        
        
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="container">
            <h1>Register</h1>
            <p>Please fill in this form to create an account.</p>
            <hr>

            <label for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="email" id="email" required value="<?php echo $email;?>">
                <span class="error">* <?php echo $emailErr;?></span><br>

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" id="psw" required value="<?php echo $psw;?>">
                <span class="error">* <?php echo $pswErr;?></span><br>

            <label for="psw-repeat"><b>Repeat Password</b></label>
            <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required value="<?php echo $pswrepeat;?>">
                <span class="error">* <?php echo $pswrepeatErr; echo "<br>"; echo $pswrepeato;?></span>
            <hr>

            <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>
            <button type="submit" class="registerbtn">Register</button>
        </div>

        <div class="container signin">
            <p>Already have an account? <a href="#">Sign in</a>.</p>
        </div>
    </form>
</body>
</html>