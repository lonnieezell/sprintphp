<br/>

<div class="row">
    <div class="large-4 large-offset-4">
        <h1>Login</h1>

        <?php if (validation_errors()) :?>
            <?php echo validation_errors(); ?>
        <?php endif; ?>

        <?php echo form_open(); ?>

            <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />

            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo set_value('email'); ?>" />

            <label for="password">Password</label>
            <input type="password" name="password" value="<?php echo set_value('password'); ?>" />

            <input type="submit" name="submit" value="Login" />

        <?php echo form_close(); ?>

    </div>
</div>

<br/>