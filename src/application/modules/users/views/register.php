<div class="register">

    <?php if (validation_errors()) :?>
        <?php echo validation_errors(); ?>
    <?php endif; ?>

    <form action="" method="post">

        <label for="email">Email</label>
        <input type="email" name="email" value="<?php echo set_value('email') ?>" />

        <label for="password">Password</label>
        <input type="password" name="password" value="" />

        <label for="pass_confirm">Password (again)</label>
        <input type="password" name="pass_confirm" value="" />

        <input type="submit" name="submit" value="Register" />

    </form>

</div>