<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

// Check if user is logged in and is a café manager
if ($_SESSION['role'] !== 'manager') {
    header("Location: ../login.php");
    exit;
}

// Fetch tables managed by this manager
$managerId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tables WHERE manager_id = :manager_id");
$stmt->execute(['manager_id' => $managerId]);
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle add, update, and delete table actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_table'])) {
        $stmt = $pdo->prepare("UPDATE tables SET table_number = :table_number, seats = :seats WHERE id = :id AND manager_id = :manager_id");
        $stmt->execute([
            'table_number' => $_POST['table_number'],
            'seats' => $_POST['capacity'],
            'id' => $_POST['table_id'],
            'manager_id' => $managerId
        ]);
    } elseif (isset($_POST['delete_table'])) {
        $stmt = $pdo->prepare("DELETE FROM tables WHERE id = :id AND manager_id = :manager_id");
        $stmt->execute(['id' => $_POST['table_id'], 'manager_id' => $managerId]);
    }
    header("Location: view_tables.php");
    exit;
}
?>

<style>
    .title {
        text-align: center;
        color: #333;
        padding: 25px 0;
        font-size: 2.2rem;
        background: linear-gradient(to right, #ff8c00, #ff5900);
        color: white;
        margin: 0;
        border-radius: 20px 20px 0 0;
    }

    table {
        width: 90%;
        margin: 10px auto;
        border-collapse: collapse;
        border-spacing: 0;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border: none;;
    }

    th, td {
        padding: 12px 15px;
        text-align: center;
        border: none;
    }

    th {
        background-color: #ff9900;
        color: white;
        font-weight: bold;
    }

    td {
        background-color: rgba(255, 235, 204, 0.6);
        color: #555;
    }

    tr:nth-child(even) td {
        background: rgba(255, 238, 204, 0.21);
    }

    .btn-4, button {
        width: 80px;
        background-color: #ff8c00;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        margin: 5px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .btn-4:hover, button:hover {
        background-color: #e07b00;
    }

    @media (max-width: 768px) {
        table {
            font-size: 0.9rem;
        }

        h2 {
            font-size: 1.5rem;
        }
    }

    .container{
        display: flex;
        justify-content: center;
        width: 100%;
    }

    footer{
        position: absolute;
        bottom: 0;
        width: 100%;
    }

    .cafe-section{
        margin-top: 100px;
        height: 100%;
        width: 1000px;
        border-radius: 20px;
        box-shadow: 0 0 10px 0.1px rgba(0, 0, 0, 0.16);
    }

    .table-container{
        width: 100%;
        height: 100%;
        overflow: scroll;
    }
</style>

<div class="container">
    <div class="cafe-section">
        <h2 class="title">Tables</h2>
        <div class="table-container">
            <div style="display: flex; justify-content: end; margin-right: 45px;">
                <a class="btn-4" style="margin-top: 20px" href="./add_table.php">Add Table</a>
            </div>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Table Number</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($tables as $table): ?>
                    <tr>
                        <td><?= $table['id'] ?></td>
                        <td><?= $table['table_number'] ?></td>
                        <td><?= $table['seats'] ?></td>
                        <td><?= $table['status'] ?></td>
                        <td>
                            <!-- Delete Form -->
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="table_id" value="<?= $table['id'] ?>">
                                <button type="submit" name="delete_table">Delete</button>
                            </form>

                            <!-- Update Button -->
                            <button type="button" onclick="openUpdateForm(<?= htmlspecialchars(json_encode($table)) ?>)">Update</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Update Table -->
<div id="updateModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px; border:1px solid #ccc; box-shadow:0 0 10px rgba(0,0,0,0.5);">
    <h3>Update Table</h3>
    <form method="post" id="updateForm">
        <input type="hidden" name="table_id" id="table_id">
        <label for="table_number">Table Number:</label>
        <input type="text" name="table_number" id="table_number" required>
        <br>
        <label for="capacity">Capacity:</label>
        <input type="number" name="capacity" id="capacity" required>
        <br>
        <button type="submit" name="update_table">Update</button>
        <button type="button" onclick="closeUpdateForm()">Cancel</button>
    </form>
</div>

<script>
    function openUpdateForm(table) {
        // Populate the form with table details
        document.getElementById('table_id').value = table.id;
        document.getElementById('table_number').value = table.table_number;
        document.getElementById('capacity').value = table.seats;

        // Show the modal
        document.getElementById('updateModal').style.display = 'block';
    }

    function closeUpdateForm() {
        // Hide the modal
        document.getElementById('updateModal').style.display = 'none';
    }
</script>

<?php require_once '../../includes/footer.php'; ?>
