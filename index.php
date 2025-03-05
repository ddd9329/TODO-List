<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TODO List</title>

  <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        $connection = mysqli_connect("localhost", "root", "", "todo list");
        if (!$connection) {
        die(mysqli_connect_error());
        }
        
        if (isset($_POST["task"])) {
            $action = $_POST["submit"];
            $task = $_POST["task"];
            switch ($action) {
                case "ADD":
                    $insertQuery = "INSERT INTO tasks (task, isComplete) VALUES ('$task', 0)";
                    mysqli_query($connection, $insertQuery);
                    break;
                case "EDIT":
                    if (isset($_POST["edit"])) {
                      $complete = isset($_POST['complete']);
                      $edit = $_POST["edit"];
                      
                      $updateQuery = "UPDATE tasks SET isComplete = '$complete', task = '$edit' WHERE task = '$task'";
                      mysqli_query($connection, $updateQuery);
                    }
                    break;
                case "DELETE":
                    $deleteQuery = "DELETE FROM tasks WHERE task = '$task'";
                    mysqli_query($connection, $deleteQuery);
                    break;
            }
        }

        $query = "SELECT * FROM tasks ORDER BY isComplete ASC, id ASC";
        $result = mysqli_query($connection, $query);
        $tasks = [];
        while ($row = mysqli_fetch_assoc($result)) {
        $tasks[] = $row;
        }
    ?>

  <form method="POST" action="">
    <input type="text" id="task" name="task" placeholder="Enter task" required>
    <input type="submit" id="submit" name="submit" value="ADD">
  </form>

  <form method="POST" action="">
    <input type="text" id="edit" name="edit" placeholder="Enter edit">

    <select id="taskSelect" name="task">
      <?php foreach($tasks as $task): ?>
        <option value="<?php echo $task['task']; ?>"><?php echo $task['task']; ?></option>
      <?php endforeach; ?>
    </select>

    <input type="checkbox" id="complete" name="complete">
    <input type="submit" id="submit" name="submit" value="EDIT">
    <input type="submit" id="submit" name="submit" value="DELETE">
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
          <td><?php echo ($task['isComplete'] == 1 ? "Complete" : "Incomplete"); ?></td> 
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <script>
    const tasks = <?php echo json_encode($tasks); ?>;
    const taskSelect = document.getElementById("taskSelect")

    const completeInput = document.getElementById("complete")
    const editInput = document.getElementById("edit")

    const task = tasks.find(t => t.task === taskSelect.value)
    completeInput.checked = (task.isComplete == 1 && true || false)
    editInput.value = taskSelect.value

    taskSelect.addEventListener("change", (event) => {
      const task = tasks.find(t => t.task === taskSelect.value)
      completeInput.checked = (task.isComplete == 1 && true || false)
      editInput.value = event.target.value
    })
  </script>

  <?php mysqli_close($connection); ?>
</body>
</html>