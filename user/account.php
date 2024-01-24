<?php
require '../includes/database.php';
require '../includes/redirect.php';
session_start();
$data = new database();
$connection = $data->connect();
$sql = "SELECT * FROM users where id=" . $_SESSION['id'];
$result = $connection->query($sql);
$row = $result->fetch_assoc();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $country = $_POST["country"];
    $postal_code = $_POST["postal_code"];
    $sql = "UPDATE users SET `username`=?, `firstname`=?, `lastname`=?, `address`=?, `city`=?, `country`=?, `postal_code`=? WHERE `id`=?;";
    $statement = $connection->prepare($sql);
    $statement->bind_param("ssssssii", $username, $firstname, $lastname, $address, $city, $country, $postal_code, $_SESSION['id']);
    $statement->execute();
    header("Location: account.php?message=success");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
</head>

<body>
    <nav class="navbar p-3">
        <div class="d-flex col-12 col-md-3 col-lg-2 mb-2 mb-lg-0 flex-wrap flex-md-nowrap justify-content-between">
            <a class="navbar-brand" href="#">
                <img src="../logo.png" alt="Logo" width="175" height="50">
            </a>
            <button class="navbar-toggler d-md-none collapsed mb-3" type="button" data-toggle="collapse" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="d-flex align-items-center">
            <a href="../logout.php" class="btn btn-outline-danger">Log Out</a>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="user.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                <span class="ml-2">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <span class="ml-2">Cart</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ordersummary.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                    <polyline points="13 2 13 9 20 9"></polyline>
                                </svg>
                                <span class="ml-2">Order Summary</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="account.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span class="ml-2">Account</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
                <div class="container">
                <?php if (isset($_GET['message'])) : ?>
                    <?php if ($_GET['message'] === "success") : ?>
                        <div class="alert alert-success" role="alert">
                            <h6>Saved</h6>
                        </div>
                    <?php endif ?>
                <?php endif ?>
                    <form method="post">
                        <div class="jumbotron jumbotron-fluid" style="background-image: linear-gradient(to right, #4facfe, #00f2fe);">
                            <div class="text-center">
                                <img src="avathar.jpg" class="rounded-circle" style="width: 150px;">
                                <h1 class="display-4 text-white"><?php echo $row['username'] ?></h1>
                                <p class="lead text-white">Welcome to your profile.</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-subtitle mb-2 text-muted">User information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="input-username" class="col-sm-3 col-form-label">Username</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="input-username" name="username" class="form-control" placeholder="Username" value="<?php echo $row['username'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="input-email" class="col-sm-3 col-form-label">Email address</label>
                                            <div class="col-sm-9">
                                                <input type="email" id="input-email" class="form-control" placeholder="<?php echo $row['email'] ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="input-first-name" class="col-sm-3 col-form-label">First name</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="input-first-name" name="firstname" class="form-control" placeholder="First name" value="<?php echo $row['firstname'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="input-last-name" class="col-sm-3 col-form-label">Last name</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="input-last-name" name="lastname" class="form-control" placeholder="Last name" value="<?php echo $row['lastname'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-subtitle mb-2 text-muted">Contact information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="input-address" class="col-sm-3 col-form-label">Address</label>
                                            <div class="col-sm-9">
                                                <input id="input-address" class="form-control" name="address" placeholder="Home Address" value="<?php echo $row['address'] ?>" type="text">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="input-city" class="col-sm-3 col-form-label">City</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="input-city" name="city" class="form-control" placeholder="City" value="<?php echo $row['city'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="input-country" class="col-sm-3 col-form-label">Country</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="input-country" name="country" class="form-control" placeholder="Country" value="<?php echo $row['country'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="input-postal-code" class="col-sm-3 col-form-label">Postal code</label>
                                            <div class="col-sm-9">
                                                <input type="number" id="input-postal-code" name="postal_code" class="form-control" placeholder="Postal code" value="<?php echo $row['postal_code'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center m-4">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
        new Chartist.Line('#traffic-chart', {
            labels: ['January', 'Februrary', 'March', 'April', 'May', 'June'],
            series: [
                [23000, 25000, 19000, 34000, 56000, 64000]
            ]
        }, {
            low: 0,
            showArea: true
        });
    </script>
</body>

</html>