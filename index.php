<?php
try {
    $user="root";
    $pwd="";
    $conn = new PDO("mysql:host=127.0.0.1; dbname=mydb", $user,$pwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo $e->getMessage();
    exit("keine Verbindung zur Datenbank");
}

$param = [];    // leeres array als SQL-Parameter erzeugen, wird ggf. mit Werten gefüllt
$list_sql = "SELECT * FROM tbl_user ORDER BY usr_last_name ASC";

// Variablen initialisieren
$info ="";
$action="none";

// auf Click eines beliebigen Buttons reagieren
if (isset($_POST['button'])){
    $action = $_POST['button'];
    switch ($action){
        case 'save':
            $info="save";
            break;
        case 'edit':
            $info="edit";
            break;
        case 'update':
            $info="update";
            break;
        case 'delete':
            $info="delete";
            break;
        case 'search':
            $info="search";
            break;
    }
}




$stmt = $conn->prepare($list_sql);
$stmt->execute($param);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Daten testweise ohne Formatierung ausgeben
// echo '<pre>', var_dump($rows), '</pre>';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
</head>
<body class="bg-primary">
    <div class="container-fluid w75">
    <div class="jumbotron">
        <div class="card mt-5">
            <div class="card-header text-light bg-info">
                <h4>PHP-CRUD</h4>
            </div>
            <div class="card-body">
                <!-- Formular zur Eingabe/Aktualisierung der Werte -->
                <form method="post">
                    <div class="row">
                        <div class="col-5">
                            <input class="form-control" type="text" name="first_name" placeholder="Vorname">
                        </div>
                        <div class="col-5">
                            <input class="form-control" type="text" name="last_name" placeholder="Nachname">
                        </div>
                        <div class="col-2 overflow-hidden">
                            <button class="btn btn-primary" type="submit" value="save" name="button">Save</button>
                        </div>
                        <input type="hidden" name="ID" value="999">
                    </div>
                </form>
            </div>
        </div>
        <!-- Info-Feld -->
        <h5 class="text-white"><?=$info?></h5>
        <div class="card mt-1">
            <div class="card-header">
                <!-- Suchformular -->
                <form method="post">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="search">
                        <div class="input-group-btn">
                            <button class="btn btn-info" type="submit" name="button" value="search">
                                <i class="fa fa-search text-white"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead class="bg-secondary text-light">
                    <tr>
                        <th scope="col">first_name</th>
                        <th scope="col">last_name</th>
                        <th scope="col" class="d-flex justify-content-end pe-3">action</th>
                    </tr>
                    </thead>
                    <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?=htmlspecialchars($row['usr_first_name'])?></td>
                        <td><?=htmlspecialchars($row['usr_last_name'])?></td>
                        <td class="d-flex justify-content-end">
                            <form method="post">
                                <input type="hidden" name="ID" value="<?=htmlspecialchars($row['ID']) ?>">
                                <button type="submit" class="btn btn-info text-white" value="edit" name="button">edit</button>
                                <button type="submit" class="btn btn-danger text-white" value="delete" name="button">delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </table>.
            </div>
        </div>
    </div>
</div>


</body>
</html>