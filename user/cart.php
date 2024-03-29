<?php
session_start();
require '../includes/database.php';
$data = new database();
$connection = $data->connect();
$cartsql = "SELECT * FROM cart WHERE customer_id =" . $_SESSION['id'];
$result = $connection->query($cartsql);
$cartrow = $result->fetch_all(MYSQLI_ASSOC);
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
            <?php if (isset($_GET['message'])) : ?>
                    <?php if ($_GET['message'] === "success") : ?>
                        <div class="alert alert-success" role="alert">
                            <h6>Delete Success</h6>
                        </div>
                    <?php endif ?>
                <?php endif ?>
                <?php if (isset($_GET['message'])) : ?>
                    <?php if ($_GET['message'] === "buy") : ?>
                        <div class="alert alert-success" role="alert">
                            <h6>Ordered success</h6>
                        </div>
                    <?php endif ?>
                <?php endif ?>

                <div class="row">
                    <div class="col-12 col-xl-12 mb-4 mb-lg-0">
                        <div class="card">
                            <h5 class="card-header">Your Cart</h5>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Id</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Total Price</th>
                                                <th scope="col">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($cartrow as $key) : ?>
                                                <tr>
                                                    <th scope="row"><?php echo $key['id']; ?></th>
                                                    <td><?php echo $key['product']; ?></td>
                                                    <td><?php echo $key['price']; ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <a href="updatequantity.php?quan=<?php echo $key['quantity'] - 1; ?>&id=<?php echo $key['id']; ?>"><button class="btn btn-sm btn-primary rounded-circle quantity-decrease">-</button></a>
                                                            <input type="text" style="width: 50px; margin:10px" class="form-control quantity" value="<?php echo $key['quantity']; ?>">
                                                            <a href="updatequantity.php?quan=<?php echo $key['quantity'] + 1; ?>&id=<?php echo $key['id']; ?>"><button class="btn btn-sm btn-primary rounded-circle quantity-increase">+</button></a>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $key['total_price']; ?></td>
                                                    <td>
                                                        <a href="deletecart.php?id=<?php echo $key['id']; ?>" class="btn btn-sm btn-primary">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                    <?php include('viewproduct.php'); ?>
                                </div>
                                <a href="addordersummary.php?id=<?php echo $_SESSION['id'] ?>" class="btn btn-block btn-success">Buy</a>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to handle quantity increase
            $('.quantity-increase').click(function() {
                var quantityField = $(this).siblings('.quantity');
                var currentQuantity = parseInt(quantityField.val(), 10);
                quantityField.val(currentQuantity + 1);
            });

            // Function to handle quantity decrease
            $('.quantity-decrease').click(function() {
                var quantityField = $(this).siblings('.quantity');
                var currentQuantity = parseInt(quantityField.val(), 10);
                if (currentQuantity > 0) {
                    quantityField.val(currentQuantity - 1);
                }
            });
        });
    </script>
    <script>
        function viewProduct(productName, productCategory, productPrice, productDescription, imageUrl) {
            document.getElementById('productName').innerText = 'Product Name: ' + productName;
            document.getElementById('productCategory').innerText = 'Category: ' + productCategory;
            document.getElementById('productPrice').innerText = 'Price: ' + productPrice;
            document.getElementById('productDescription').innerText = 'Description: ' + productDescription;
            document.getElementById('productImage').src = '../productimages/' + imageUrl;
        }
    </script>
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