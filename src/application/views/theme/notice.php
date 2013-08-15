<div id="notices" class="row">
    <div class="twelve columns">

        <?php if (isset($notice) && is_array($notice)) : ?>
        <div class="alert-box <?php echo $notice['type']; ?>">
            <a href="#" class="close">&times;</a>
            <?php echo $notice['message']; ?>
        </div>
        <?php endif; ?>

    </div>
</div>