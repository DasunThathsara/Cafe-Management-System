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

$managerId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM foods WHERE manager_id = :manager_id AND status = 'available'");
$stmt->execute(['manager_id' => $managerId]);
$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['delete_food'])) {
    $stmt = $pdo->prepare("UPDATE foods SET status = 'DELETED' WHERE id = :id");
    $stmt->execute(['id' => $_POST['food_id']]);
    header("Location: view_foods.php");
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
        <h2 class="title">Foods</h2>
        <div class="table-container">
            <div style="display: flex; justify-content: end; margin-right: 45px;">
                <a class="btn-4" style="margin-top: 20px" href="./add_food.php">Add Food</a>
            </div>
            <table border="1">
                <tr>
                    <th>Image</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($foods as $food): ?>
                    <tr>
                        <td><img style="width: 40px; height: 40px; border-radius: 100%" src="<?= $food['image'] ?>" alt=""></td>
                        <td><?= $food['id'] ?></td>
                        <td><?= $food['name'] ?></td>
                        <td><?= $food['description'] ?></td>
                        <td><?= $food['price'] ?></td>
                        <td style="display: grid; align-content: center; justify-content: center">
                            <a class="btn-4" href="./edit_food.php?id=<?php echo $food['id']?>">Edit</a>
                            <form method="POST" class="delete-form" data-id="<?= $food['id'] ?>">
                                <button type="button" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

<script>
    // Add SweetAlert to handle delete confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('.delete-form');
            const foodId = form.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if confirmed
                    form.submit();
                }
            });
        });
    });
</script>

<?php require_once '../../includes/footer.php'; ?>
