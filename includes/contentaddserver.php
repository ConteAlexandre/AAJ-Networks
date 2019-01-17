


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
            $error['name'] = '8 characteres minimum';
        }elseif (strlen($name) > 75 ){
            $error['name'] = '75 characteres maximum';
        }
    }else {
        $error['name'] = 'Please fill the field';
    }

    if (!empty($ip)){
        if (filter_var($ip, FILTER_VALIDATE_IP)){

        }else{
            $error['ip'] = 'Please write a valid ip address';
        }
    }else{
        $error['ip'] = 'Please fill the field';
    }

    if (!empty($mac)){
        if (filter_var($mac, FILTER_VALIDATE_MAC)){

        }else{
            $error['mac'] = 'Please write a valid mac address';
        }
    }else{
        $error['mac'] = 'Please fill the field';
    }

    if (!empty($mask)){
        if (filter_var($mask, FILTER_VALIDATE_IP)){

        }else{
            $error['mask'] = 'Please write a valid mask address';
        }
    }else{
        $error['mask'] = 'Please fill the field';
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12 blanc" for="name">Server's name<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="name" class="form-control col-md-7 col-xs-12 tspt" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="Office" required="required" type="text">
                            <span class="error"><?php if (!empty($error['name'])){ echo $error['name']; } ?></span>

                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12 blanc" for="ip">IP source <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="name" class="form-control col-md-7 col-xs-12 tspt" data-validate-length-range="6" data-validate-words="2" name="ip" placeholder="198.168.1.1" required="required" type="text">
                            <span class="error"><?php if (isset($error['ip'])){ echo $error['ip']; } ?></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12 blanc" for="mac">MAC adress <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12 tspt">
                            <input id="mac" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="mac" placeholder="22:22:22:22:22" required="required" type="text">
                            <span class="error"><?php if (isset($error['mac'])){ echo $error['mac']; } ?></span>
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12 blanc" for="mask">Mask adress <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12 tspt">
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
<<<<<<< HEAD
            <div class="col-md-12 col-sm-12 col-xs-12">
                <table class="tableauip table table-bordered table-striped text-center">
=======
            <div class="col-md-12 col-sm-12 col-xs-12 ">
                <table class="tableauip table table-bordered table-hover ">
>>>>>>> af16e4c4d448336da36f6d9721043782035cedaa
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">IP</th>
                        <th scope="col">MAC Adress</th>
                        <th scope="col">MAsk Adress</th>
                        <th scope="col">Supprimer</th>
                        <th >Analyser</th>
                    </tr>
                    <?php
                    foreach ($recupip as $donneeip){
                        echo '<tr>
                                <td>'.$donneeip['name'].'</td>
                                <td>'.$donneeip['INET_NTOA(ip)'].'</td>
                                <td>'.$donneeip['macaddr'].'</td>
                                <td>'.$donneeip['INET_NTOA(mask)'].'</td>
                                <td><a href="deleteip.php?id='.$donneeip['id'].'" onclick="return confirm(\'Etes-vous sûr de vouloir supprimer définitevement cette adresse ip?\')"><img src="https://img.icons8.com/metro/24/000000/delete-database.png"></a></td>
                                <td><a href="analyse.php?id='. $donneeip['id'] . '"  onclick="window.open(this.href, \'exemple\', \'height=400, width=800, top=100, left=100, toolbar=no, menubar=yes, location=no, resizable=yes, scrollbars=no, status=no\') ; return false;"><img src="https://img.icons8.com/metro/24/000000/view-file.png" alt=""></a></td>
                               </tr>';
                    }
                    ?>

                </table>
            </div>
        </div>
    </div>
</div>
</div>
