<?php
/**
 * Created by PhpStorm.
 * User: SEAI
 * Date: 5/18/2019
 * Time: 10:47 PM
 */
include_once "config.php";

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if(!$connection){
    throw new Exception("Couldn't Connect with Database");
}
$query = "SELECT * FROM tasks WHERE complete = 0 ORDER BY date";
$result = mysqli_query($connection, $query);
$completedQuery = "SELECT * FROM tasks WHERE complete = 1 ORDER BY date desc ";
$comResult = mysqli_query($connection, $completedQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TODO-TASK</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/milligram/examples/index.html">
    <link rel="stylesheet" href="style/milligram/dist/milligram.min.css">
    <link rel="stylesheet" href="style/milligram/license">
    <style>
        body {
            margin-top: 30px;
        }
        #main {
            padding: 0px 150px 0px 150px;
        }
        #action {
            width: 150px;
        }
    </style>
</head>
<body>

<div class="container" id="main">
    <h1>Tasks Manager</h1>
    <p>This is a sample project for managing our daily tasks. We're going to use HTML, CSS, PHP, JavaScript and MySQL
        for this project</p>
    <h4>Completed Tasks</h4>
    <?php
        if(mysqli_num_rows($comResult) > 0){
            ?>
            <table>
                <thead>
                <tr>
                    <th></th>
                    <th>Id</th>
                    <th>Task</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while($cdata = mysqli_fetch_assoc($comResult)){
                    $timestamp = strtotime($cdata['date']);
                    $date = date("jS M, Y", $timestamp);
                    ?>
                    <tr>
                        <td><input class="label-inline" type="checkbox" value="<?php echo $cdata['id']; ?>" checked disabled></td>
                        <td><?php echo $cdata['id']; ?></td>
                        <td><strike><?php echo $cdata['task'] ?></strike></td>
                        <td><strike><?php echo $date ?></strike></td>
                        <td><a class="delete" data-deleteid="<?php echo $cdata['id']; ?>" href='#'>Delete</a> |
                            <a class="incomplete" data-itaskid="<?php echo $cdata['id']; ?>" href='#'>Return</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
    <?php
        }else{
            echo "Not Yet Complete";
        }
    ?>

    <hr>

    <h4>Upcoming Tasks</h4>
    <?php
        if(mysqli_num_rows($result) == 0){
            ?>
        <h3>NO DATA</h3>
    <?php
        }else{
    ?>
            <form action="task.php" method="post">
    <table>

        <thead>
        <tr>
            <th></th>
            <th>Id</th>
            <th>Task</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
            while($data = mysqli_fetch_assoc($result)){
                $timestamp = strtotime($data['date']);
                $date = date("jS M, Y", $timestamp);
                ?>
                <tr>
                    <td><input name="taskids[]" class="label-inline" type="checkbox" value="<?php echo $data['id']; ?>"></td>
                    <td><?php echo $data['id']; ?></td>
                    <td><?php echo $data['task'] ?></td>
                    <td><?php echo $date ?></td>
                    <td><a class="delete" data-deleteid="<?php echo $data['id']; ?>" href='#'>Delete</a> |
                        <a class="edit" href='#'>Edit</a> |
                        <a class="complete" data-taskid="<?php echo $data['id']; ?>" href='#'>Complete</a>
                    </td>
                </tr>
        <?php
            }
            mysqli_close($connection);
        ?>
        </tbody>
    </table>

    <select id="action" name="action" >
        <option value="0">With Selected</option>
        <option value="bulkdelete">Delete</option>
        <option value="bulkcomplete">Mark As Complete</option>
    </select>
    <input class="button-primary" id="bulksubmit" type="submit" value="Submit">
            </form>
    <hr>
    <?php
        }
    ?>
    <h4>Add Tasks</h4>
    <form method="post" action="task.php">
        <fieldset>
            <?php
                $action = $_GET['added'];
                if($action){
                    echo '<p style="color: green">Task added Successfully</p>';
                }
            ?>
            <label for="task">Task</label>
            <input type="text" placeholder="Task Details" id="task" name="task">
            <label for="date">Date</label>
            <input type="text" placeholder="Task Date" id="date" name="date">

            <input class="button-primary" type="submit" value="Add Task">
            <input type="hidden" name="action" value="add">
        </fieldset>
    </form>
</div>
<form method="post" action="task.php" id="completeId">
    <input type="hidden" id="comAction" name="action" value="complete">
    <input type="hidden" id="taskid" name="taskid">
</form>
<form method="post" action="task.php" id="IncompleteId">
    <input type="hidden" id="incomAction" name="action" value="incomplete">
    <input type="hidden" id="itaskid" name="itaskid">
</form>
<form method="post" action="task.php" id="deleteID">
    <input type="hidden" id="deleteAction" name="action" value="delete">
    <input type="hidden" id="deleteid" name="deleteid">
</form>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script>
    ;(function($){
        $(document).ready(function(){
            $(".complete").on('click',function () {
                var id = $(this).data("taskid");
                $('#taskid').val(id);
                $('#completeId').submit();
            });
        });
        $(document).ready(function(){
            $(".incomplete").on('click',function () {
                var id = $(this).data("itaskid");
                $('#itaskid').val(id);
                $('#IncompleteId').submit();
            });
        });
        $(document).ready(function(){
            $(".delete").on('click',function () {
                if(confirm("do you want to delete")){
                    var id = $(this).data("deleteid");
                    $('#deleteid').val(id);
                    $('#deleteID').submit();}
            });
        });
        $(document).ready(function(){
            $("#bulksubmit").on('click',function () {
                if($('#action').val() == 'bulkdelete'){
                    if(!confirm("do you want to delete")) {
                        return false;
                    }
                }
            });
        });
    })(jQuery);
</script>
</html>
