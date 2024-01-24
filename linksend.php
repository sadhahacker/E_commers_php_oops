<?php
require 'vendor/autoload.php'; // Composer autoloader
require 'includes/database.php';
require 'includes/mailer.php';
$data = new database();
$connection = $data->connect();
$email = $_GET['email'];
$sql = "select token_hash,token_expire from users where email= ?";
$statement = $connection->prepare($sql);
$statement->bind_param('s',$email);
$statement->execute();
$statement->bind_result($token_hash,$token_expire);
$statement->fetch();

$resetLink = "http://localhost/oopslogin/updatepass.php?token_hash=" . urlencode($token_hash);

$mailer = new mailer;
$mailer->linkSend($resetLink,$token_expire,$email);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<section class="bg-light py-3 py-md-5" style="height: 100vh;">

        <div class="container">
          <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
              <div class="card border border-light-subtle rounded-3 shadow-sm">
                <div class="card-body p-3 p-md-4 p-xl-5">
                  <div class="text-center mb-3">
                    <a href="#!">
                      <img src="logo.png" alt="BootstrapBrain Logo" width="175" height="57">
                    </a>
                  </div>
                  <h2 class="fs-6 fw-normal text-center text-secondary mb-4">An email has been sent to <?php echo $_GET['email'] ?> with instructions on how to reset your password. Please check your email.</h2>
                    <div class="row gy-2 overflow-hidden">
                        <a href="login.php"><div class="col-12">
                        <div class="d-grid my-3">
                          <button class="btn btn-primary btn-lg" type="submit">Back to login</button>
                        </div>
                      </div></a>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
</body>
</html>