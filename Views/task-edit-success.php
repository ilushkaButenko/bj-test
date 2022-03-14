<div class="alert alert-success" role="alert">
    <?php if ($createMode) : ?>
        <h4 class="alert-heading">Task has been created!</h4>
        <p>New task looks like:</p>
    <?php else : ?>
        <h4 class="alert-heading">Task has been edited!</h4>
        <p>Now it looks like:</p>
    <?php endif ?>


    <table class="task-table table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Task</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $task['name'] ?></td>
                <td><?php echo $task['email'] ?></td>
                <td><?php echo $task['task'] ?></td>
            </tr>
        </tbody>
    </table>


    <hr>
    <div class="row">
        <div class="col">
            <a class="btn btn-primary" href="<?php echo url('/') ?>" role="button">Continue</a>
        </div>
    </div>
</div>