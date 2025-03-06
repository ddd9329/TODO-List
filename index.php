<?php
  require_once "config.php";

  if (!isset($_SESSION["user_id"])) {
      header("Location: login.php");
      exit();
  }

  $user_id = $_SESSION["user_id"];

  if (isset($_POST["task"])) {
      $action = $_POST["submit"];
      $task = mysqli_real_escape_string($connection, $_POST["task"]);
      
      switch ($action) {
          case "ADD":
              $insertQuery = "INSERT INTO tasks (user_id, task, status, date) VALUES ('$user_id', '$task', 'PENDING', CURRENT_TIMESTAMP)";
              mysqli_query($connection, $insertQuery);
              break;
          case "EDIT":
              if (isset($_POST["edit"])) {
                  $edit = mysqli_real_escape_string($connection, $_POST["edit"]);
                  $enum = mysqli_real_escape_string($connection, $_POST["enum"]);
                  $updateQuery = "UPDATE tasks SET task = '$edit', status = '$enum' WHERE task = '$task' AND user_id = '$user_id'";
                  mysqli_query($connection, $updateQuery);
              }
              break;
          case "DELETE":
              $getTaskQuery = "SELECT id FROM tasks WHERE task = '$task' AND user_id = '$user_id' LIMIT 1";
              $taskResult = mysqli_query($connection, $getTaskQuery);
              if (mysqli_num_rows($taskResult) > 0) {
                  $taskRow = mysqli_fetch_assoc($taskResult);
                  $task_id = $taskRow['id'];
                  $deleteCommentsQuery = "DELETE FROM comments WHERE task_id = '$task_id'";
                  mysqli_query($connection, $deleteCommentsQuery);
              }
              $deleteQuery = "DELETE FROM tasks WHERE task = '$task' AND user_id = '$user_id'";
              mysqli_query($connection, $deleteQuery);
              break;
          case "COMMENT":
              if (isset($_POST["edit"])) {
                  $comment = mysqli_real_escape_string($connection, $_POST["edit"]);
                  $getTaskQuery = "SELECT id FROM tasks WHERE task = '$task' AND user_id = '$user_id' LIMIT 1";
                  $taskResult = mysqli_query($connection, $getTaskQuery);
                  if (mysqli_num_rows($taskResult) > 0) {
                      $taskRow = mysqli_fetch_assoc($taskResult);
                      $task_id = $taskRow['id'];
                      $commentQuery = "INSERT INTO comments (task_id, comment, date) VALUES ('$task_id', '$comment', CURRENT_TIMESTAMP)";
                      mysqli_query($connection, $commentQuery);
                  }
              }
              break;
      }
  }

  $query = "SELECT * FROM tasks WHERE user_id = '$user_id' ORDER BY status ASC, id ASC";
  $result = mysqli_query($connection, $query);
  $tasks = [];
  while ($row = mysqli_fetch_assoc($result)) {
      $tasks[] = $row;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TODO List - Todo App</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'menu.php'; ?>
  
  <form method="POST" action="">
    <input type="text" id="task" name="task" placeholder="Enter task" required>
    <input type="submit" id="submit" name="submit" value="ADD">
  </form>

  <form method="POST" action="">
    <input type="text" id="edit" name="edit" placeholder="Edit task">
    <select id="taskSelect" name="task">
      <?php foreach($tasks as $task): ?>
        <option value="<?php echo $task['task']; ?>"><?php echo $task['task']; ?></option>
      <?php endforeach; ?>
    </select>
    <select id="enum" name="enum">
      <option value="PENDING">Pending</option>
      <option value="IN_PROGRESS">In Progress</option>
      <option value="DONE">Done</option>
    </select>
    <input type="submit" id="submit" name="submit" value="EDIT">
    <input type="submit" id="submit" name="submit" value="DELETE">
  </form>

  <form method="POST" action="">
    <input type="text" id="edit" name="edit" placeholder="Enter comment">
    <select id="taskSelect_Comment" name="task">
      <?php foreach($tasks as $task): ?>
        <option value="<?php echo $task['task']; ?>"><?php echo $task['task']; ?></option>
      <?php endforeach; ?>
    </select>
    <input type="submit" id="submit" name="submit" value="COMMENT">
  </form>

  <table>
    <thead>
      <tr>
        <th>Task</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($tasks as $task): ?>
        <tr>
          <td><?php echo $task['task']; ?></td>
          <td><?php echo $task['status']; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <script>
    const tasks = <?php echo json_encode($tasks); ?>;
    const taskSelect = document.getElementById("taskSelect");
    const editInput = document.getElementById("edit");
    const enumInput = document.getElementById("enum");
    
    if (tasks.length > 0) {
      const task = tasks.find(t => t.task === taskSelect.value);
      editInput.value = taskSelect.value;
      enumInput.value = task.status;
    }
    
    taskSelect.addEventListener("change", (event) => {
      const selectedTask = tasks.find(t => t.task === event.target.value);
      editInput.value = event.target.value;
      enumInput.value = selectedTask.status;
    });
  </script>

  <p><a href="profile.php">Edit Profile</a> | <a href="logout.php">Logout</a></p>
</body>
</html>