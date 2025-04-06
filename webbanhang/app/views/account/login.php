<?php include 'app/views/shares/header.php'; ?>

<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">

                        <form action="/webbanhang/account/checklogin" method="post">
                            <div class="mb-md-5 mt-md-4 pb-5">
                                <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                <p class="text-white-50 mb-5">Please enter your login and password!</p>

                                <!-- Username Input -->
                                <div class="form-outline form-white mb-4">
                                    <input type="text" name="username" class="form-control form-control-lg" id="username" required />
                                    <label class="form-label" for="username">UserName</label>
                                </div>

                                <!-- Password Input -->
                                <div class="form-outline form-white mb-4">
                                    <input type="password" name="password" class="form-control form-control-lg" id="password" required />
                                    <label class="form-label" for="password">Password</label>
                                </div>

                                <!-- Forgot Password -->
                                <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#!">Forgot password?</a></p>

                                <!-- Login Button -->
                                <button class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>

                                <!-- Social Login Icons -->
                                <div class="d-flex justify-content-center text-center mt-4 pt-1">
                                    <a href="#!" class="text-white me-4"><i class="fab fa-facebook-f fa-lg"></i></a>
                                    <a href="#!" class="text-white mx-4"><i class="fab fa-twitter fa-lg"></i></a>
                                    <a href="#!" class="text-white ms-4"><i class="fab fa-google fa-lg"></i></a>
                                </div>
                            </div>

                            <!-- Sign Up Link -->
                            <div>
                                <p class="mb-0">Don't have an account? <a href="/webbanhang/account/register" class="text-white-50 fw-bold">Sign Up</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>

<!-- Add custom CSS to improve UI -->
<style>
    /* Gradient Background */
    .gradient-custom {
        background: linear-gradient(45deg, #6a11cb, #2575fc);
    }

    /* Styling for form inputs */
    .form-control-lg {
        border-radius: 1.5rem;
        padding: 1.5rem;
    }

    .form-outline label {
        font-size: 1rem;
        font-weight: bold;
    }

    .btn-outline-light {
        border-radius: 1.5rem;
        padding: 0.75rem 2.5rem;
        text-transform: uppercase;
        transition: background-color 0.3s ease;
    }

    .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .card-body {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }

    /* Social Icons */
    .fab {
        transition: transform 0.3s ease;
    }

    .fab:hover {
        transform: scale(1.2);
    }
</style>

<!-- Add Font Awesome (for social media icons) -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
