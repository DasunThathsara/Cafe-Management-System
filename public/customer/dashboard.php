<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth.php';
require_once '../../includes/header.php';

// Check if user is logged in and is a customer
if ($_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}
?>

<?php require '../components/foodCard.php'; ?>
<main>
    <section class="hero">
        <img src="/gallery_cafe/assets/images/resturant.jpg" alt="Restaurant Interior">
        <div class="hero-text">
            <h2>Welcome to The Gallery Café</h2>
            <p>Experience the finest dining with a blend of local and international cuisines.</p>
            <a href="menu.php" class="btn">View Menu</a>
            <a href="reservation.php" class="btn">Make a Reservation</a>
        </div>
    </section>

    <section class="featured">
        <h2>Menus
            <hr>
        </h2>
        <div class="meals-wrapper">
            <div class="meals-container">
                <?php
                $stmt = $pdo->query("SELECT f.*, c.name AS cafe_name FROM foods f LEFT JOIN cafes c ON f.manager_id = c.manager_id WHERE category = 'food'");

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        DishCard($row['image'], $row['name'], $row['description'], $row['price'], $row['category'], $row['cafe_name']);
                    }
                } else {
                    echo "<p>No menus available.</p>";
                }

                ?>
            </div>
        </div>
    </section>

    <!-- Foods and Beverages Section -->
    <section class="featured">
        <h2>Foods and Beverages
            <hr>
        </h2>
        <div class="meals-wrapper">
            <div class="meals-container">
                <?php
                $stmt = $pdo->query("SELECT f.*, c.name AS cafe_name FROM foods f LEFT JOIN cafes c ON f.manager_id = c.manager_id WHERE category = 'beverage'");

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        DishCard($row['image'], $row['name'], $row['description'], $row['price'], $row['category'], $row['cafe_name']);
                    }
                } else {
                    echo "<p>No menus available.</p>";
                }

                ?>
            </div>
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.order-now-button').forEach(button => {
            button.addEventListener('click', function() {
                const mealName = this.closest('.card').querySelector('h3').textContent;
                fetch('order_food.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        mealName: mealName
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Order placed successfully!');
                        } else {
                            alert('Error placing order. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error placing order. Please try again.');
                    });
            });
        });
    });
</script>

<?php require_once '../../includes/footer.php'; ?>
