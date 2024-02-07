<?php
include "../../../connect_DB.php";
include('../layout/aside.php');
include('../layout/header.php');
if (!isset($_SESSION['id'])) {
    header("location:../login/login.php");
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    http_response_code(404);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/layout.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Shipping Method | Create</title>
    <link rel="icon" href="../../../icon.png">
    <script defer src="../scripts/handle_actions.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php echo $aside ?>
        <div class="container">
            <?php echo $header ?>
            <div class="content">
                <h2>Shipping</h2>
                <div class="card">
                    <div class="card-header">
                        <h4> Create Shipping Method</h4>
                    </div>
                    <div class="card-body">
                        <p class="message">
                        </p>
                        <form onsubmit="createItem(event)" action="handle_actions.php" method="post">
                            <div>
                                <label for="name">Shipping Method Name:</label>
                                <input class="name" type="text" name="name" placeholder="shipping method name" id="name">
                            </div>
                            <div>
                                <label for="type">Type:</label>
                                <select name="type" class="type">
                                    <option value="">Select</option>
                                    <option value="flat_cost">Flast Cost</option>
                                    <option value="min_cost">Min Cost</option>
                                </select>
                            </div>
                            <div class="min-cost">
                                <label for="cost">Min Cost($):</label>
                                <input  type="text" name="min_cost" placeholder="min cost" id="min_cost">
                            </div>
                            <div>
                                <label for="cost">Cost($):</label>
                                <input class="cost" type="text" name="cost" placeholder="cost" id="cost">
                            </div>
                            <div>
                                <label for="status">Status:</label>
                                <select name="status" class="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <button type="submit" class="create">Create</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        var minCost = document.querySelector(".min-cost")
        var minCostInput = minCost.querySelector("input")
        var type = document.querySelector("select.type")
        type.onchange = () => {
            if (type.value == 'min_cost')
            {
                minCost.style.display = "block"

            }
            else
            {
                minCost.style.display = "none"
                minCostInput.value = ''
            }
        }
    </script>
</body>

</html>