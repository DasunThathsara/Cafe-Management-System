<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

if ($_SESSION['role'] !== 'manager') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manage_id'])) {
    $complaintId = intval($_POST['manage_id']);
    $stmt = $pdo->prepare("UPDATE complaints SET status = 'RESOLVED' WHERE id = :id");
    $stmt->execute(['id' => $complaintId]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$stmt = $pdo->query("SELECT c.*, ca.name AS cafe_name FROM complaints c LEFT JOIN cafes ca ON c.cafe_id = ca.id ORDER BY id DESC");
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        margin: 50px auto;
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
    }

    footer{
        position: absolute;
        bottom: 0;
        width: 100%;
    }

    .complaint-section{
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
    <div class="complaint-section">
        <h2 class="title">Complaints</h2>
        <div class="table-container">
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Café</th>
                    <th>Complaint</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($complaints as $complaint): ?>
                    <tr>
                        <td><?php echo $complaint['id']; ?></td>
                        <td><?php echo htmlspecialchars($complaint['customer_id']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['cafe_name']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['complaint_text']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['status']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['created_at']); ?></td>
                        <td>
                            <?php if ($complaint['status'] !== 'RESOLVED'): ?>
                                <button
                                        class="resolve-btn"
                                        data-id="<?php echo $complaint['id']; ?>"
                                >Mark as Resolved</button>
                            <?php else: ?>
                                <button disabled style="background-color: #ccc; cursor: not-allowed;">Resolved</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

<script>
    // Handle SweetAlert for "Mark as Resolved"
    document.querySelectorAll('.resolve-btn').forEach(button => {
        button.addEventListener('click', function () {
            const complaintId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to mark this complaint as resolved?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, resolve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a hidden form and submit it
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?php echo $_SERVER['PHP_SELF']; ?>';

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'manage_id';
                    input.value = complaintId;

                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>

<?php include '../../includes/footer.php'; ?>
