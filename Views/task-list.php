<div class="row align-items-center border-secondary border-bottom">
    <div class="col-md-6">
        <h1>Tasks</h1>
    </div>

    <div class="col-md-6">
        <a class="btn btn-primary float-end" href="task/create" role="button">Create task</a>

        <?php if ($auth) : ?>
            <a class="btn btn-primary float-end me-2" href="logout" role="button">Logout</a>
        <?php else : ?>
            <a class="btn btn-primary float-end me-2" href="login" role="button">Login as administrator</a>
        <?php endif ?>

    </div>

</div>

<div class="row mt-4">
    <div class="col-md-6">
        <form action="" method="POST">
            <div class="row align-items-center">
                <div class="col-auto">
                    <label for="orderBy" class="col-form-label">Order by:</label>
                </div>
                <div class="col-auto">
                    <select id="orderBy" name="orderBy" class="form-select form-select-sm" aria-label="Default select example">
                        <option value="id" <?php echo $orderBy == 'id' ? 'selected' : '' ?>>default</option>
                        <option value="name" <?php echo $orderBy == 'name' ? 'selected' : '' ?>>Name</option>
                        <option value="email" <?php echo $orderBy == 'email' ? 'selected' : '' ?>>Email</option>
                        <option value="done" <?php echo $orderBy == 'done' ? 'selected' : '' ?>>Done</option>
                    </select>
                </div>

                <div class="col-auto">
                    <label for="orderDirection" class="col-form-label">Direction:</label>
                </div>
                <div class="col-auto">
                    <select id="orderDirection" name="orderDirection" class="form-select form-select-sm" aria-label="Default select example">
                        <option value="asc" <?php echo $orderDirection == 'asc' ? 'selected' : '' ?>>Asc</option>
                        <option value="desc" <?php echo $orderDirection == 'desc' ? 'selected' : '' ?>>Desc</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-6">
        <?php if ($pageCount > 1) : ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination float-end">

                    <?php if ($currentPage - 1 >= 1) : ?>
                        <li class="page-item"><a class="page-link" href="<?php echo $currentPage == 2 ? 'task' : 'task/page/' . ($currentPage - 1) ?>">Previous</a></li>
                    <?php else : ?>
                        <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a></li>
                    <?php endif ?>

                    <li class="page-item <?php echo $currentPage == 1 ? 'active' : '' ?>"><a class="page-link" href="task">1</a></li>
                    <?php for ($i = 2; $i <= $pageCount; $i++) : ?>
                        <li class="page-item <?php echo $currentPage == $i ? 'active' : '' ?>"><a class="page-link" href="task/page/<?php echo $i ?>"><?php echo $i ?></a></li>
                    <?php endfor ?>

                    <?php if ($currentPage + 1 <= $pageCount) : ?>
                        <li class="page-item"><a class="page-link" href="task/page/<?php echo $currentPage + 1 ?>">Next</a></li>
                    <?php else : ?>
                        <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a></li>
                    <?php endif ?>
                </ul>
            </nav>
        <?php endif ?>
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
                <th>Marks</th>
                <?php if ($auth) echo '<th></th>' ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($tasks) : $ctr = ($currentPage - 1) * $tasksPerPage + 1;
                foreach ($tasks as $task) : ?>
                    <tr>
                        <td><?php echo $ctr ?></td>
                        <td><?php echo $task['name'] ?></td>
                        <td><?php echo $task['email'] ?></td>
                        <td><?php echo $task['task'] ?></td>
                        <td>
                            <?php if ($task['done'] == 1) : ?>
                                <span class="badge bg-success">Done</span>
                            <?php endif ?>
                            <?php if ($task['updated'] == 1) : ?>
                                <span class="badge bg-info text-dark">Updated by admin</span>
                            <?php endif ?>
                        </td>
                        <?php if ($auth) : ?>
                            <td>
                                <a class="btn btn-primary btn-sm" href="task/edit/<?php echo $task['id'] ?>" role="button">Edit</a>
                                <a class="btn btn-danger btn-sm" href="task/delete/<?php echo $task['id'] ?>" role="button">Delete</a>
                                <?php if ($task['done'] == 1) : ?>
                                    <a class="btn btn-success btn-sm disabled" href="task/done/<?php echo $task['id'] ?>" role="button" aria-disabled="true">Mark as done</a>
                                <?php else : ?>
                                    <a class="btn btn-success btn-sm" href="task/done/<?php echo $task['id'] ?>" role="button">Mark as done</a>
                                <?php endif ?>
                            </td>
                        <?php endif ?>
                    </tr>
            <?php $ctr++;
                endforeach;
            endif; ?>
        </tbody>
    </table>
    <?php if (empty($tasks)) : ?>
        <hr class="mt-2">
        <div class="mt-5 col-md-6 offset-md-3">
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading">No tasks yet</h4>
                <p>You can create task by clicking button!</p>
                <hr>
                <div class="row">
                    <div class="col">
                        <a class="btn btn-primary" href="task/create" role="button">Create first task</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
