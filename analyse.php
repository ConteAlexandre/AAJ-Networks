<?php
require "includes/pdo.php";
require "includes/fonction.php";


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT INET_NTOA(ip), INET_NTOA(mask) FROM server WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':id', $id);
    $query->execute();
    $listServer = $query->fetchall();


    $json = file_get_contents('data/file.json');
    $content = json_decode($json, true);

    $Network = array(
        "authorized" => 0,
        "forbidden" => 0
    );
    $listIp = array();
    $totalCo = 0;
    $test = 0;


    foreach ($content as $contents) {

        if (isset($contents['_source']['layers']['ip']['ip.src']) && isset($contents['_source']['layers']['ip']['ip.dst'])) {

            /*       echo '<li> Source : ' .$contents['_source']['layers']['ip']['ip.src']. '</li><li> Destination :'.

                  $contents['_source']['layers']['ip']['ip.dst'].'</li>';*/

            if ($listServer[0]['INET_NTOA(ip)'] == $contents['_source']['layers']['ip']['ip.dst']) {


                $ipServ = $listServer[0]['INET_NTOA(ip)'];
                $maskServ =  $listServer[0]['INET_NTOA(mask)'];
                $ipSrc = explode('.', $contents['_source']['layers']['ip']['ip.src']);


                $index = getIndex($maskServ);
                $indexValue = intval($maskServ[$index]);

                $magicVal = 256 - $indexValue;;

                $firstIp = getFirstIp($magicVal, $ipServ, $index);
                $lastIp = getLastIp($magicVal, $ipServ, $index);
                $ipSrc = implode('.', $ipSrc);


                if (array_key_exists($ipSrc, $listIp)) {
                    $totalCo++;
                    $listIp[$ipSrc]++;
                    $Network['forbidden']++;
                } else {
                    $ipSrc = explode('.', $contents['_source']['layers']['ip']['ip.src']);
                    if (!compareIP($ipSrc, $firstIp, $lastIp)) {
                        $verifip = array();
                        $verifip = "L'adresse " . $contents['_source']['layers']['ip']['ip.src'] . ' ne fait pas parti du même reseau<br>';
                        $ipSrc = implode('.', $ipSrc);
                        $listIp[$ipSrc] = $test;

                    }
                }


            }
        } else {
            /*echo "L'adresse".$contents['_source']['layers']['ip']['ip.src'].' ne se connecte pas au reseau<br>';*/
            $Network['authorized']++;
        }
    }

    //echo $totalCo;




}

include 'includes/header.php';

?>

<h2 class="text-center titrebrun">The report</h2>
<p class="text-center blanc">The analyse has been done with this IP source <strong><?php echo $listServer[0]['INET_NTOA(ip)'] ?></strong> and with this mask <strong><?php echo $listServer[0]['INET_NTOA(mask)'] ?></strong></p>
<div class="text-center col-md-2"></div>
    <div class="text-center col-md-9">
        <canvas id="bar-chart" width="200" height="100"></canvas>
    </div>
<div class="text-center col-md-2"> </div>

    <div class="clearfix"></div>

<div class="row">
    <div class="col-md-12"></div>
        <p class="text-center blanc"><u>List of forbidden IP address</u></p>
    </div>

    <div class="clearfix"></div>

<div class="row text-center"
    <div class="col-md-12 ">
        <?php foreach ($listIp as $key => $value){
        echo '<p class="blanc">The address'. $key .' tried to connect '. $value .' times '.' </p> '; }?>
    </div>

</div>

<script>// Bar chart
    new Chart(document.getElementById("bar-chart"), {
        type: 'bar',
        data: {
            labels:[<?php foreach ($Network as $key => $value){ echo '"' . $key . '",'; }?>],

            datasets: [{
                label: "How many authorized and forbidden IP",
                backgroundColor: ["#3e95cd", "#8e5ea2", "#8e5ea2", "#8e5ea2", "#8e5ea2", "#8e5ea2", "#8e5ea2"],
                data: [<?php foreach ($Network as $key => $value){ echo '"' . $value . '",'; }?>]
            }]
        },
        options: {
            title: {
                display: true,

            }
        }
    });
    </script>


<?php
include 'includes/footer.php';
?>