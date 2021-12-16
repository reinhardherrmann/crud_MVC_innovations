<?php
global $first_name, $last_name, $ID, $save_update, $info, $action, $param,$list_sql;

//TODO Initialisierung der Variablen in Funktion reset_vars umsetzen


    // Verbindung mit Datenbank herstellen
    try {
        $user="root";
        $pwd="";
        $conn = new PDO("mysql:host=127.0.0.1; dbname=mydb", $user,$pwd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo $e->getMessage();
        exit("keine Verbindung zur Datenbank");
    }

    // Variablen initialisieren
    // reset_vars();
    $first_name=null;
    $last_name=null;
    $ID=null;
    $list_sql = "SELECT * FROM tbl_user ORDER BY usr_last_name ASC";
    $param=[];
    $info="";
    $save_update="save";
    $action="none";


    // auf Click eines beliebigen Buttons reagieren
    if (isset($_POST['button'])){
        $action = $_POST['button'];
        switch ($action){
            case 'save':
                //$save_update="save";
                $sql = "INSERT INTO tbl_user (usr_first_name, usr_last_name) VALUES(:first_name, :last_name);";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name']]);
                if($stmt->rowCount()>0) $info= $stmt->rowCount() . " Datensatz gespeichert!";
                else $info="Keine Daten gespeichert";
                break;
            case 'edit':
                $save_update="update";
                $sql = "SELECT * FROM tbl_user WHERE ID =:id;";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['id'=>$_POST['ID']]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_name = $row['usr_first_name'];
                $last_name = $row['usr_last_name'];
                $ID = $row['ID'];
                break;
            case 'update':
                $sql = "UPDATE tbl_user SET usr_first_name=:first_name, usr_last_name=:last_name WHERE ID=:id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['first_name'=> $_POST['first_name'],
                    'last_name'=>$_POST['last_name'],'id'=>$_POST['ID']]);
                $save_update="save";
                if($stmt->rowCount()>0) $info= $stmt->rowCount() . " Datensatz geändert!";
                else $info="Kein Datensatz wurde geändert!";
                break;
            case 'delete':
                $sql = "DELETE FROM tbl_user WHERE ID = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['id'=>$_POST['ID']]);
                if($stmt->rowCount()>0) $info= $stmt->rowCount() . " Datensatz gelöscht!";
                else $info="Kein Datensatz wurde gelöscht!";
                break;
            case 'search':
                $list_sql = "SELECT * FROM tbl_user WHERE (usr_last_name like :search_string)
                             OR (usr_first_name like :search_string)";
                $param = ['search_string' => '%' . $_POST['search'] . '%'];
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
                            <input class="form-control" type="text" name="first_name" placeholder="Vorname" value="<?=$first_name?>">
                        </div>
                        <div class="col-5">
                            <input class="form-control" type="text" name="last_name" placeholder="Nachname" value="<?=$last_name?>">
                        </div>
                        <div class="col-2 overflow-hidden">
                            <button class="btn btn-primary" type="submit" value=<?=$save_update?> name="button"><?=$save_update?></button>
                        </div>
                        <input type="hidden" name="ID" value="<?=$ID?>">
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