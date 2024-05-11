<?php
session_start();
require_once 'config/connect.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"
    />
    <link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet"/>
    <title>Login</title>
</head>
<body>
<section class="vh-100">
    <div class="container py-5 w-100 h-100 position-absolute top-50 start-50 translate-middle">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-sm-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card" style="border-radius: 1rem;">
                            <div class="col-sm-10 col-md-12 col-lg-12 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">
                                    <form id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
                                        <h3 class="fw-normal mb-3 pb-3 text-center" style="letter-spacing: 1px;">Log in to your account</h3>
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="text" id="username" name="username" class="form-control form-control-lg" maxlength="50" required/>
                                            <label class="form-label" for="username">Username</label>
                                        </div>
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="password" id="password" name="password" class="form-control form-control-lg" required/>
                                            <label class="form-label" for="password">Password</label>
                                        </div>
                                        <div class="pt-1 mb-4">
                                            <button type="submit" class="btn btn-dark btn-lg btn-block">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // 1. Data validation
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === NULL || $username === '' || $password === NULL || $password === '')
    {
        echo '<script>alert("Invalid data")</script>';
        exit();
    }
    // 2. Check username if exists.

    $stmt = CONN->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s",$username);
    if (!$stmt->execute())
    {
        echo '<script>alert("Something went wrong.")</script>';
        exit();
    }

    $result = $stmt->get_result();
    if (!mysqli_num_rows($result))
    {
        echo '<script>alert("Invalid credentials.")</script>';
        exit();
    }
    $row = mysqli_fetch_assoc($result);
    if (!password_verify($password, $row['password']))
    {
        echo '<script>alert("Invalid credentials.")</script>';
        exit();
    }
    $_SESSION['logged'] = true;
    $_SESSION['username'] = $username;
    header('Location: home.php');
}
?>
<script type="text/javascript" src="js/script.js"></script>
<!-- MDB -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js"
></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>