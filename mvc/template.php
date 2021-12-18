
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
                    <tr class="t-row">
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