<?php require 'includes/header.php' ?>
<h1 class="d-flex justify-content-center p-5">Welcome to the login page!</h1>
<div id="formContent">
    <form method="post">
        <input name="email" type="text" id="login" class="fadeIn second" placeholder="login">
        <input name="password" type="text" id="password" class="fadeIn third" placeholder="password">
        <input type="submit" name="submit" class="fadeIn fourth" value="Log In">
    </form>

</div>
<?php require 'includes/footer.php'; ?>
