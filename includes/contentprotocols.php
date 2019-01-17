<!-- page content -->
<?php
include 'includes/functions.php';
$error = array();

$json_source = file_get_contents("data/file.json");
?>

<div class="right_col role="main"">
    <div class="">
        <div class="">
            <div class="title_left text-center">
                <h3 class="titrebrun">Distribution of protocols</h3>
            </div>
                <div class="clearfix"></div>
                <div class="row mb-5">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <p class="big-para text-center blanc italic">You can see the list and a pie of your protocols</p>
                            <!-- <form class="protocolsForm" action="" method="post" enctype="multipart/form-data">
                                  <label for="type_trame">Choose a type:</label>
                                  <select id="type_trame" name="type_trame">
                                      <option value="not">--Please choose an option--</option>
                                      <option value="frame">Frame</option>
                                      <option value="eth">ETH</option>
                                      <option value="vlan">VLAN</option>
                                      <option value="ip">IP</option>
                                      <option value="tcp">TCP</option>
                                  </select>
                                  <br>
                                 <input type="submit" name="submitted" class="btn btn-info button center-block btnbleu" value="SEE">
                             </form>-->
                        </div>
                </div>

            <div class="clearfix"></div>

            <div class="row margintop">
                <div class="col-sm-12">

                    <?php
                    if (!empty($json_source)) {
                    // Décode le JSON
                    $json_data = json_decode($json_source, true);
                    /* $datas = $_POST['type_trame'];*/
                    ?>
                    <p class="blanc"><span class="grand"><?php echo count($json_data) ?> </span> available frames</p>


                </div>
            </div>

                <div class="row">
                        <div class="col-sm-9">

                                 <?php
                                    /*if ($datas != 'not') {
                                    foreach ($json_data as $v) {

                                        */?><!--<p>Type : <?php /*echo $datas */?> </p>--><?php
                                /*
                                                            echo '<pre>';
                                                            print_r($v['_source']['layers'][$datas]) . '<br>';
                                                            echo '</pre>';
                                                        }
                                                    }*/
                                foreach ($json_data as $v) {
                                    $protocols = countprotocol($v, $protocols);
                                }

                                ?>
                                <canvas id="pie-chart" width="800" height="450"></canvas>

                         </div>
                    <div class="col-sm-3 text-center ">
                            <br><br><br>
                        <?php

                        foreach ($protocols as $key => $value) {
                            $orderprotocols[$key] = $value ;
                        }
                        array_multisort($orderprotocols, SORT_DESC, $protocols);

                        ?>                  <ol><?php
                            foreach ($protocols as $key => $value) {
                                echo '<li>' . $key . ' : ' . $value . '</li>';
                            }


                            }

                            ?>
                    </div>
                </div>
        </div>
    </div>
    </div>


<script class="col-sm-3">
    new Chart(document.getElementById("pie-chart"), {
        type: 'pie',
        data: {
            labels:[<?php foreach ($protocols as $key => $key){ echo '"' . $key . '",'; }?>],

            datasets: [{
                label: "En nombre de protocoles",
                backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#b45850","#c42a50","#c14750","#d40120","#ae550","#f41850","#685az0","#bf81b","#587512","#a784be","#c8751e","#875fa7","#e875ac","#784abb","#abcdef","#123456","#789123","#fedcba","#4875ac"],
                data: [<?php foreach ($protocols as $key => $value){ echo '"' . $value . '",'; }?>]
            }]
        },
        options: {
            title: {
                display: true,
                text: ''
            }
        }
    });
</script>
