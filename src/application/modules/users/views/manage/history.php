<?php if (isset($logins) && is_array($logins) && count($logins)) : ?>

    <div class="row">
        <div class="large-6 columns">
            <p><?php echo $range_first .'-'. $range_end ?> of <?php echo number_format($total_rows); ?></p>
        </div>
    </div>

    <table style="width: 100%" >
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>IP Address</th>
                <th>Time</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($logins as $login) : ?>

            <tr>
                <td>
                    <a href="<?php echo site_url('manage/users/edit/'. $login->user_id) ?>">
                        <?php echo $login->username; ?>
                    </a>
                </td>
                <td><?php echo $login->email; ?></td>
                <td><?php echo $login->ip_address; ?></td>
                <td><?php echo date('H:i a', strtotime($login->timestamp)); ?></td>
                <td><?php echo date('d-m-Y', strtotime($login->timestamp)); ?></td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>

    <?php echo $this->pagination->create_links(); ?>

<?php else : ?>
    <div class="alert-box">
        Unable to find any history.
    </div>
<?php endif; ?>