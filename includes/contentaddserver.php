<!--formulaire -->


<?php


//Soumission du formulaire

$error = array();
if (!empty($_POST['submitted'])){
    $name = trim(strip_tags($_POST['name']));
    $ip = $_POST['ip'];
    $mac = $_POST['mac'];
    $mask = $_POST['mask'];


    if (!empty($name)){
        if (strlen($name) < 8 ){
            $error['name'] = 'Veuillez mettre plus de caratères!';
        }elseif (strlen($name) > 75 ){
            $error['name'] = 'Veuillez mettre moins de caratères';
        }
    }else {
        $error['name'] = 'Veuillez rempir ce champs!';
    }

    if (!empty($ip)){
        if (filter_var($ip, FILTER_VALIDATE_IP)){

        }else{
            $error['ip'] = 'Veuillez rentrer une adresse ip valide!';
        }
    }else{
        $error['ip'] = 'Veuillez remplir ce champs!';
    }

    if (!empty($mac)){
        if (filter_var($mac, FILTER_VALIDATE_MAC)){

        }else{
            $error['mac'] = 'Veuillez rentrer une adresse mac valide!';
        }
    }else{
        $error['mac'] = 'Veuillez remplir ce champs!';
    }

    if (!empty($mask)){
        if (filter_var($mask, FILTER_VALIDATE_IP)){

        }else{
            $error['mask'] = 'Veuillez rentrer une adresse mask valide!';
        }
    }else{
        $error['mask'] = 'Veuillez remplir ce champs!';
    }


    if (count($error) == 0){

        $sql = "INSERT INTO server (ip, macaddr, mask, name) VALUES (INET_ATON(:ip),:macaddr,INET_ATON(:mask), :name)";
        $query = $pdo->prepare($sql);
        $query->bindValue(':ip', $ip, PDO::PARAM_STR);
        $query->bindValue(':macaddr', $mac, PDO::PARAM_STR);
        $query->bindValue(':mask', $mask, PDO::PARAM_STR);
        $query->bindValue(':name', $name, PDO::PARAM_STR);
        $query->execute();

    }

}

//Récupération des données pour afficher dans le tableau

$sql2 = "SELECT INET_NTOA(ip), INET_NTOA(mask), macaddr, name, id FROM `server` ";
$query2 = $pdo->prepare($sql2);
$query2->execute();
$recupip = $query2->fetchAll();


?>

<!-- page content -->
<div class="right_col role="main"">
<div class="">
    <div class="">
        <div class="text-center titrepadd">
            <h3>Add a server</h3>
        </div>
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form class="form-horizontal form-label-left" method="post">
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Server's name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="Office" required="required" type="text">
                            <span class="error"><?php if (!empty($error['name'])){ echo $error['name']; } ?></span>

                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ip">IP source <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="ip" placeholder="198.168.1.1" required="required" type="text">
                            <span class="error"><?php if (isset($error['ip'])){ echo $error['ip']; } ?></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mac">MAC adress <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="mac" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="mac" placeholder="22:22:22:22:22" required="required" type="text">
                            <span class="error"><?php if (isset($error['mac'])){ echo $error['mac']; } ?></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mask">Mask adress <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="mask" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="mask" placeholder="255.255.250.1" required="required" type="text">
                            <span class="error"><?php if (isset($error['mask'])){ echo $error['mask']; } ?></span>
                        </div>
                    </div>


                    <!--                        <div class="ln_solid"></div>-->
                    <div class="form-group text-center paddbtn topmarg">
                        <button type="submit" class="btn btn-danger">Cancel</button>
                        <input type="submit" id="submitted" name="submitted" class="submitted btn btn-success " value="Submit">
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <table class="tableauip table table-bordered table-striped">
                    <tr>
                        <td>Name</td>
                        <td>IP</td>
                        <td>MAC Adress</td>
                        <td>MAsk Adress</td>
                        <td>Supprimer</td>
                        <td>Analyser</td>
                    </tr>
                    <?php
                    foreach ($recupip as $donneeip){
                        echo '<tr>
                                <td>'.$donneeip['name'].'</td>
                                <td>'.$donneeip['INET_NTOA(ip)'].'</td>
                                <td>'.$donneeip['macaddr'].'</td>
                                <td>'.$donneeip['INET_NTOA(mask)'].'</td>
                                <td><a href="deleteip.php?id='.$donneeip['id'].'" onclick="return confirm(\'Etes-vous sûr de vouloir supprimer définitevement cette adresse ip?\')"><img src="https://img.icons8.com/metro/24/000000/delete-database.png"></a></td>
                                <td><a href="tools.php"><img src="https://img.icons8.com/metro/24/000000/view-file.png"></a></td>
                                <td><a href="includes/analyse.php?id='. $donneeip['id'] . '" onclick="window.open(this.href, \'exemple\', \'height=400, width=800, top=100, left=100, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no\'); return false;">bon</a></td>

                               </tr>';
                    }
                    ?>

                </table>
            </div>
        </div>
    </div>
</div>
</div>
