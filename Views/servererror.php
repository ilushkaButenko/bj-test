<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Http error 500: Internal server error</h4>
    <p>Something has happened with server and this page cannot be shown. Please, try later.</p>
    <?php if ($isLoggedIn === true): ?>
        <p>
            <pre><?php echo $errorMessage ?></pre>
        </p>
    <?php endif ?>
    <hr>
    <div class="row">
        <div class="col">
            <a class="btn btn-primary" href="<?php echo $tasksUrl ?? url('/') ?>" role="button">Main page</a>
        </div>
    </div>
</div>