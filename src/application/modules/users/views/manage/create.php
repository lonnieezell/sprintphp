<?php echo form_open( current_url(), 'class="custom"'); ?>

<?php if (validation_errors()) : ?>
    <div class="alert-box alert">
        <?php echo validation_errors(); ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="large-3 columns">
        <label for="email" class="right inline">Email Address</label>
    </div>
    <div class="large-9 columns">
        <input type="email" name="email" value="<?php echo set_value('email'); ?>">
    </div>
</div>

<div class="row">
    <div class="large-3 columns">
        <label for="username" class="right inline">Username</label>
    </div>
    <div class="large-9 columns">
        <input type="text" name="username" value="<?php echo set_value('username'); ?>">
    </div>
</div>

<br/><br/>

<div class="row">
    <div class="large-3 columns">
        <label for="password" class="right inline">Password</label>
    </div>
    <div class="large-9 columns">
        <input type="password" name="password" value="">
    </div>
</div>

<div class="row">
    <div class="large-3 columns">
        <label for="pass_confirm" class="right inline">Password (Again)</label>
    </div>
    <div class="large-9 columns">
        <input type="password" name="pass_confirm" value="">
    </div>
</div>

<div class="row">
    <div class="large-3 columns"></div>
    <div class="large-9 columns">
        <input type="submit" name="submit" class="button" value="Create User" />
    </div>
</div>

<?php echo form_close(); ?>