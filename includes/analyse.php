<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15/01/2019
 * Time: 13:50
 */
require "pdo.php";
require "fonction.php";




if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT INET_NTOA(ip), INET_NTOA(mask) FROM server WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':id', $id);
    $query->execute();
    $listServer = $query->fetchall();

    debug($listServer);


    $json = file_get_contents('../data/file.json');
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
                        echo "L'adresse " . $contents['_source']['layers']['ip']['ip.src'] . ' ne fait pas parti du même reseau<br>';
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
    //debug($Network);
    //echo $totalCo;
    debug($listIp);



}

include 'header.php';
?>


<canvas id="bar-chart" width="400" height="225"></canvas>

<script>// Bar chart
    new Chart(document.getElementById("bar-chart"), {
        type: 'bar',
        data: {
            labels:[2,2],

            datasets: [{
                label: "En nombre de protocoles",
                backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#b45850","#c42a50","#c14750","#d40120","#ae550","#f41850","#685az0","#bf81b","#587512","#a784be","#c8751e","#875fa7","#e875ac","#784abb","#abcdef","#123456","#789123","#fedcba","#4875ac"],
                data: ['bla','na']
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Répartition des différents protocoles'
            }
        }
    });
    </script>


<?php
include 'footer.php';
?>