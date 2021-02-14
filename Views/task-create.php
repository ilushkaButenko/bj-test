<form method="POST" action="/task/store">
<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input id="name" class="form-control" aria-describedby="nameHelp" type="text" name="name" placeholder="Ilya Denisovich" required>
    <div id="nameHelp" class="form-text">Please enter only letters and start with capital letter</div>
</div>


<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input id="email" class="form-control" type="email" name="email" placeholder="ilabutenko256@gmail.com" >
</div>

<div class="mb-3">
    <label for="task" class="form-label">Task</label>
    <textarea id="task" class="form-control" name="task" placeholder="Get a job at BeeJee" ></textarea>
</div>

<input type="hidden" name="csrf" value="">
<button type="submit" class="btn btn-primary">Create task</button>
</form>