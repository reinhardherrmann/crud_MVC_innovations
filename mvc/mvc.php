<?php
class Model{
    protected function save(){}
    protected function edit(){}
    protected function update(){}
    protected function delete(){}
    protected function search(){}

}

class View extends Model {

    function render(){
        include "template.php";
    }

}

class Control extends View {
    function __construct(){

        //parent::__construct();
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
        $this->render();
    }

}