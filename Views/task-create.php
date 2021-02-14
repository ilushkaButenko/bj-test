<form method="POST" action="/task/store">
<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input id="name" class="form-control" aria-describedby="nameHelp" type="text" name="name" placeholder="Ilya Denisovich" pattern="[A-Z][a-z]+(\s[A-Z][a-z]+)?" required value="<?php echo $oldInput['name'] ?>">
    <div id="nameHelp" class="form-text">Please enter only letters and start with capital letter</div>
</div>


<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input id="email" class="form-control" type="email" name="email" placeholder="ilabutenko256@gmail.com" required value="<?php echo $oldInput['email'] ?>">
</div>

<div class="mb-3">
    <label for="task" class="form-label">Task</label>
    <textarea id="task" class="form-control" name="task" placeholder="Get a job at BeeJee" required value="<?php echo $oldInput['task'] ?>"></textarea>
</div>

<input type="hidden" name="csrf" value="">
<button type="submit" class="btn btn-primary">Create task</button>
<a class="btn btn-primary" href="/task/list" role="button">Cancel</a>
</form>