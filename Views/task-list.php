<div class="row align-items-center">
    <div class="col-md-6">
        <h1>Tasks</h1>
    </div>

    <div class="col-md-6">
        <a class="btn btn-primary float-end" href="/task/create" role="button">Create task</a>
        <a class="btn btn-primary float-end me-2" href="/task/list" role="button">Login as administrator</a>
    </div>
    
</div>


<div class="row">
    <table class="task-table table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Task</th>
                <th>Done</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($tasks): $ctr = ($currentPage - 1) * $tasksPerPage + 1; foreach ($tasks as $task): ?>
            <tr>
                <td><?php echo $ctr ?></td>
                <td><?php echo $task['name'] ?></td>
                <td><?php echo $task['email'] ?></td>
                <td><?php echo $task['task'] ?></td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" <?php echo $task['task'] == 1 ? 'checked' : '' ?> disabled>
                    </div>
                </td>
            </tr>
            <?php $ctr++; endforeach; endif; ?>
        </tbody>
    </table>
</div>

<div class="row">
<?php if ($pageCount > 1): ?>
    <nav aria-label="Page navigation example">
        <ul class="pagination float-end">

            <?php if ($currentPage - 1 >= 1): ?>
                <li class="page-item"><a class="page-link" href="<?php echo $currentPage == 2 ? '/task' : '/task/page/' . ($currentPage - 1) ?>">Previous</a></li>
            <?php else: ?>
                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a></li>
            <?php endif ?>

            <li class="page-item <?php echo $currentPage == 1 ? 'active' : '' ?>"><a class="page-link" href="/task">1</a></li>
            <?php for ($i = 2; $i <= $pageCount; $i++): ?>
            <li class="page-item <?php echo $currentPage == $i ? 'active' : '' ?>"><a class="page-link" href="/task/page/<?php echo $i ?>"><?php echo $i ?></a></li>
            <?php endfor ?>

            <?php if ($currentPage + 1 <= $pageCount): ?>
                <li class="page-item"><a class="page-link" href="/task/page/<?php echo $currentPage + 1 ?>">Next</a></li>
            <?php else: ?>
                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a></li>
            <?php endif ?>
        </ul>
    </nav>
<?php endif ?>
</div>
