<div class="col-md-6 offset-md-3">
    <h1>Login</h1>
    <form method="POST" action="" class="needs-validation" novalidate>
        <?php if ($authFail) : ?>
            <div class="alert alert-danger" role="alert">
                <p>Incorrect login or password</p>
            </div>
        <?php endif ?>

        <div class="mb-3">
            <label for="login" class="form-label">Login</label>
            <input id="login" class="form-control <?php if ($errors['login']) echo 'is-invalid';
                                                    elseif ($errors['login'] === false) echo 'is-valid' ?>" aria-describedby="loginHelp" type="text" name="login" placeholder="Ludochka" pattern="[A-Za-z]+" required value="<?php echo $oldInput['login'] ?>">
            <?php if ($errors['login']) : ?>
                <div class="invalid-feedback">
                    <?php echo $errors['login'] ?>
                </div>
            <?php elseif ($errors['login'] === false) : ?>
                <div class="valid-feedback">
                    Ok!
                </div>
            <?php endif; ?>
            <div id="loginHelp" class="form-text">Login may contain only letters</div>
        </div>


        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-control <?php if ($errors['password']) echo 'is-invalid';
                                                        elseif ($errors['password'] === false) echo 'is-valid' ?>" type="password" name="password" required>
            <?php if ($errors['password']) : ?>
                <div class="invalid-feedback">
                    <?php echo $errors['password'] ?>
                </div>
            <?php elseif ($errors['password'] === false) : ?>
                <div class="valid-feedback">
                    Ok!
                </div>
            <?php endif; ?>
        </div>

        <input type="hidden" name="csrf" value="">
        <button type="submit" class="btn btn-primary">Login</button>
        <a class="btn btn-primary" href="<?php echo url('/') ?>" role="button">Cancel</a>
    </form>
</div>