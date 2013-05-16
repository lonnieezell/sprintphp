<?php if (validation_errors()) :?>
    <?php echo validation_errors(); ?>
<?php endif; ?>

<?php echo form_open(); ?>

    <input type="hidden" name="redirect" value="/" />

    <label for="email">Email</label>
    <input type="email" name="email" value="<?php echo set_value('email'); ?>" />

    <label for="password">Password</label>
    <input type="password" name="password" value="<?php echo set_value('password'); ?>" />

    <label for="remember">
        <input type="checkbox" name="remember" value="1" /> Remember me for future visits?
    </label>

    <input type="submit" name="submit" value="Login" />

<?php echo form_close(); ?>