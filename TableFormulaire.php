<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table</title>
</head>
<body>
    <?php
        // Connexion à la base de données
        $host = "localhost";
        $database = "base_formulaire";
        $password = "root";
        $conn = new mysqli($host, $password  , "", $database);

        // Vérifier la connexion
        if ($conn->connect_error) {
        die("Erreur de connexion à la base de données: " . $conn->connect_error);
        }

        // Requête SQL pour récupérer les données de la table
        $sql = "SELECT * FROM client";
        $result = $conn->query($sql);

        // Afficher les données dans un tableau HTML
        echo '<table border=3>';
        echo "<tr><th>Email</th><th>Password</th><th>RepeatPassword</th></tr>";

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["Email"]. "</td>";
                echo "<td>" . $row["Password"]. "</td>";
                echo "<td>" . $row["RepeatPassword"]. "</td>";
                echo "</tr>";
            }
        }else {
                echo "<tr><td colspan='3'>Aucune donnée disponible</td></tr>";
        }

        echo "</table>";
        echo "<a href='Formulaire.php'><button>Enregistrer</button></a>";
            
        // Fermer la connexion à la base de données
        $conn->close();

            
    ?>
</body>
</html>