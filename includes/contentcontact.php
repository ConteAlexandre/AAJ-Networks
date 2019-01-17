

<?php


//Installation de l'objet pour faire fonctionner phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require ('src/PHPMailer.php');
require ('src/Exception.php');
require ('src/SMTP.php');


//Soumission du formulaire
$error = array();
if (isset($_POST{'submitted'})){

    $name = trim(strip_tags($_POST['name']));
    $email = $_POST['email'];
    $content = trim(strip_tags($_POST['content']));
    $subject = trim(strip_tags($_POST['subject']));

    //Attribut des valeurs pour les différentes erreurs

    if (!empty($name)){
        if ( strlen($name) < 5){
            $error['name'] = '5 characteres minimum';
        }elseif (strlen($name) > 100){
            $error['name'] = '100 characteres maximum';
        }
    }else{
        $error['name'] = 'Please, fill the field';
    }
    if (!empty($email)){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error['email'] = 'Please, write a valid email';
        }
    }else{
        $error['email'] = 'Please fill the field';
    }
    if (!empty($subject)){
        if (strlen($subject) < 5){
            $error['subject'] = '5 characteres minimum';
        }elseif (strlen($subject) > 100){
            $error['subject'] = 'Your subject is too long';
        }
    }else{
        $error['subject'] = 'Please fill the field';
    }
    if (!empty($content)){
        if (strlen($content) < 50){
            $error['content'] = '50 characteres minimum';
        }elseif (strlen($content) > 1000){
            $error['content'] = 'Your message is too long';
        }
    }else{
        $error['content'] = 'Please fill the field';
    }

    if (count($error) == 0) {
        $mail = new PHPMailer(true);
        try {
            //Serveur d'envoi
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'localhost';
            $mail->SMTPAuth = false;
            $mail->Username = $email;
            $mail->Password = null;
            $mail->SMTPSecure = false;
            $mail->Port = 1030;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            //Receveur
            $mail->setFrom($email);
            $mail->addAddress($email);

            //Contenu
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = utf8_decode($content. '<br><br>Message sent by' . $name);
            $mail->AltBody = $mail->Body;

            $mail->send();
            $sent = '<span class="whitespan" style="color: red;">Message sent</span>';
        } catch (Exception $e) {
            $unset = '<span style="color: red;">Your message has not been sent</span>';
        }
    }

}


?>


<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="">
            <div class="text-center">
                <h3 class="section-title wow zoomIn titrepadd ">AAJ Networks</h3>
                <p class="section-para  messagecontact text-center italic blanc">For any questions or further details, please fill the form.</p>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">

                    <!--formulaire contact-->


                    <section id="contact1" class="contact1">
                        <div class="container">
                            <div class="row">
                                <div class="contact-overlay">
                                    <div class="col-md-6">

                                        <div class="second-divider"><span class="left-divider"></span></div>

                                        <ul class="list-unstyled">
                                            <li class="wow fadeInUp"><i class="fa fa-phone blanc"></i> <span class="blanc">02.50.80.40.30 </span></li>
                                            <li class="wow fadeInUp"><i class="fa fa-home blanc"></i> <span class="blanc">24 place Saint-Marc, Rouen, France</span></li>
                                            <li class="wow fadeInUp"><i class="fa fa-envelope-o blanc"></i> <span class="blanc">contact@aajnetworks.com</span></li>
                                        </ul>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <form action="" method="post">
                                                <div class="col-sm-11 padd">
                                                    <div class="form-group wow fadeInUp" data-wow-delay="0.2s">
                                                        <label class="sr-only">Your Name</label>
                                                        <input type="text" name="name" class="form-control tspt" placeholder="Your Name" value="<?php if(isset($_POST['name'])) { echo $_POST['name'] ;} ?>">
                                                        <span class="error" style="color: red;"><?php if (isset($error['name'])){ echo $error['name']; } ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-11">
                                                    <div class="form-group wow fadeInUp" data-wow-delay="0.4s">
                                                        <label class="sr-only">Your Email</label>
                                                        <input type="email" name="email" class="form-control tspt" placeholder="Your Email" value="<?php if(isset($_POST['email'])) { echo $_POST['email'] ;} ?>">
                                                        <span class="error" style="color: red;"><?php if (isset($error['email'])){ echo $error['email']; } ?></span>

                                                    </div>
                                                </div>
                                                <div class="col-sm-11">
                                                    <div class="form-group wow fadeInUp" data-wow-delay="0.6s">
                                                        <label class="sr-only">Subject</label>
                                                        <input type="text" name="subject" class="form-control tspt" placeholder="Subject" value="<?php if(isset($_POST['subject'])) { echo $_POST['subject'] ;} ?>">
                                                        <span class="error" style="color: red;"><?php if (isset($error['subject'])){ echo $error['subject']; } ?></span>

                                                    </div>
                                                </div>
                                                <div class="col-sm-11 ">
                                                    <div class="form-group wow fadeInUp" data-wow-delay="0.8s">
                                                        <label class="sr-only">Message</label>
                                                        <textarea class="form-control textarea tspt" name="content" rows="5" placeholder="Message" value="<?php if(isset($_POST['content'])) { echo $_POST['content'] ;} ?>"></textarea>
                                                        <span class="error" style="color: red;"><?php if (isset($error['content'])){ echo $error['content']; } ?></span>

                                                    </div>
                                                </div>
                                                <div class="col-sm-11">
                                                    <div class="form-group wow fadeInUp" data-wow-delay="1s">
                                                        <input type="submit" name="submitted" class="submitted btn btn-success center-block" value="Send Message">
                                                    </div>
                                                </div>
                                            </form>
                                            <?php
                                            if (isset($sent)){
                                                echo $sent;
                                            }elseif (isset($unset)){
                                                echo $unset;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>







                </div>
            </div>
        </div>
    </div>
</div>