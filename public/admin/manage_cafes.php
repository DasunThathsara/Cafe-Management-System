<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

// Check if user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch all café registration requests
$stmt = $pdo->query("SELECT * FROM cafes");
$pendingCafes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Approve or reject café requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cafeId = $_POST['cafe_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE cafes SET status = 'APPROVED' WHERE id = :id");
    } elseif ($action === 'reject') {
        $stmt = $pdo->prepare("UPDATE cafes SET status = 'REJECTED' WHERE id = :id");
    } elseif ($action === 'ban') {
        $stmt = $pdo->prepare("UPDATE cafes SET status = 'BANNED' WHERE id = :id");
    } elseif ($action === 'unban') {
        $stmt = $pdo->prepare("UPDATE cafes SET status = 'APPROVED' WHERE id = :id");
    }

    $stmt->execute(['id' => $cafeId]);
    header("Location: manage_cafes.php");
    exit;
}
?>
<style>
    h2 {
        text-align: center;
        color: #ff8c00;
        margin: 20px 0;
        font-size: 2rem;
    }

    table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 12px 15px;
        text-align: center;
        border: 1px solid #ffc966;
    }

    th {
        background-color: #ff9900;
        color: white;
        font-weight: bold;
    }

    td {
        background-color: #ffebcc;
        color: #555;
    }

    tr:hover {
        background-color: #ffe0b3;
    }

    button {
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

    button:hover {
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
        <h2>Manage Cafés</h2>
        <div class="table-container">
            <?php
            require "../components/adminResturantcard.php";

            foreach ($pendingCafes as $row) {
                render_meal_card($row);
            }
            ?>
            </table>
        </div>
    </div>
</div>
<?php require_once '../../includes/footer.php'; ?>
