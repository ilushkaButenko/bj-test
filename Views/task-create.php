<div class="col-md-6 offset-md-3">
  <?php if ($editMode) : ?>
    <h1>Edit task</h1>
  <?php else : ?>
    <h1>Create task</h1>
  <?php endif; ?>
  <form method="POST" action="task/<?php echo $editMode ? 'edit' : 'create' ?>" class="needs-validation" novalidate>
    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input id="name" class="form-control <?php if ($errors['name']) echo 'is-invalid';
                                            elseif ($errors['name'] === false) echo 'is-valid' ?>" aria-describedby="nameHelp" type="text" name="name" placeholder="Ilya Denisovich" pattern="[A-Z][a-z]+(\s[A-Z][a-z]+)?" required value="<?php echo $oldInput['name'] ?>">
      <?php if ($errors['name']) : ?>
        <div class="invalid-feedback">
          <?php echo $errors['name'] ?>
        </div>
      <?php elseif ($errors['name'] === false) : ?>
        <div class="valid-feedback">
          Ok!
        </div>
      <?php endif; ?>
      <div id="nameHelp" class="form-text">Please enter only letters and <strong>start with capital letter</strong></div>
    </div>


    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input id="email" class="form-control <?php if ($errors['email']) echo 'is-invalid';
                                            elseif ($errors['email'] === false) echo 'is-valid' ?>" type="email" name="email" placeholder="ilabutenko256@gmail.com" required value="<?php echo $oldInput['email'] ?>">
      <?php if ($errors['email']) : ?>
        <div class="invalid-feedback">
          <?php echo $errors['email'] ?>
        </div>
      <?php elseif ($errors['email'] === false) : ?>
        <div class="valid-feedback">
          Ok!
        </div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label for="task" class="form-label">Task</label>
      <textarea id="task" class="form-control <?php if ($errors['task']) echo 'is-invalid';
                                              elseif ($errors['task'] === false) echo 'is-valid' ?>" name="task" placeholder="Get a job at BeeJee" required><?php echo $oldInput['task'] ?></textarea>
      <?php if ($errors['task']) : ?>
        <div class="invalid-feedback">
          <?php echo $errors['task'] ?>
        </div>
      <?php elseif ($errors['task'] === false) : ?>
        <div class="valid-feedback">
          Ok!
        </div>
      <?php endif; ?>
    </div>

    <?php if ($editMode) : ?>
      <input type="hidden" name="editMode" value="true">
      <button type="submit" class="btn btn-primary">Save task</button>
    <?php else : ?>
      <button type="submit" class="btn btn-primary">Create task</button>
    <?php endif ?>

    <a class="btn btn-primary" href="/" role="button">Cancel</a>
  </form>
</div>