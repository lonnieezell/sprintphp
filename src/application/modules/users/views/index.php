<?php if (isset($users) && is_array($users) && count($users)) : ?>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?php echo $user->id ?></td>
                <td>
                    <a href="/users/edit/<?php echo $user->id ?>">
                        <?php echo $user->username ?>
                    </a>
                </td>
                <td><?php echo $user->email ?></td>
                <td><?php echo $user->last_login ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>No users found.</p>

<?php endif; ?>