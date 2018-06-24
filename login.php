<?php
$title = "Log-in";
include 'header.php';
session_start();
if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true) header('location: /admin/');

if (isset($_POST['adminPassword'])) {
    if ($_POST['adminPassword'] === $pAdmin_password) {
        $_SESSION['isAdmin'] = true;
        header('location: /admin/');
    }
}
?>

    <div>
        <div class="row">
            <div class="col-12 col-md-6 col-sm-8 mx-auto ">
                <div class="card border-light mt-5 shadow">
                    <div class="card-header">Administrator</div>
                    <div class="card-body pt-2">
                        <form action="#" method="POST">
                            <input type="password" name="adminPassword" class="form-control" placeholder="Password">
                            <button class="btn btn-primary btn-sm mt-2 float-right">Login</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php


include 'footer.php';
?>