<?php include 'app/views/shares/header.php'; ?>
<?php

if (isset($errors)) {
    echo "<div class='alert alert-danger'>";
    echo "<ul>";
    foreach ($errors as $err) {
        echo "<li>$err</li>";
    }
    echo "</ul>";
    echo "</div>";
}

?>

<div class="container">
    <div class="card shadow-lg mx-auto" style="max-width: 500px;">
        <div class="card-body p-4">
            <h3 class="text-center mb-4">Register</h3>
            <form class="user" action="/webbanhang/account/save" method="post">
                <div class="form-group row mb-3">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                        <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Username" required>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                        <input type="text" class="form-control form-control-user" id="fullname" name="fullname" placeholder="Full Name" required>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                        <input type="password" class="form-control form-control-user" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
                    </div>
                </div>

                <div class="form-group text-center">
                    <button class="btn btn-primary btn-block py-2 mt-3">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
