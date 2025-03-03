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
                case "COMPLETE":
                    $updateQuery = "UPDATE tasks SET isComplete = 1 WHERE task = '$task'";
                    mysqli_query($connection, $updateQuery);
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
    
    document.getElementById("task").addEventListener("input", function() {
      const inputVal = this.value;
      let buttonText = "ADD";
      
      if (inputVal !== "") {
        const task = tasks.find(t => t.task.toLowerCase() === inputVal.toLowerCase());
        if (task) {
          if (parseInt(task.isComplete) === 1) {
            buttonText = "DELETE";
          } else {
            buttonText = "COMPLETE";
          }
        }
      }
      
      document.getElementById("submit").value = buttonText;
    });
  </script>

  <?php mysqli_close($connection); ?>
</body>
</html>
