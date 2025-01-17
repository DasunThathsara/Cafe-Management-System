<?php
function render_meal_card($res)
{
    ?>
    <li class="meal-card">
        <div class="meal-card_inner" onclick="orderMeal(<?php echo htmlspecialchars($res['id']); ?>)">
            <img src="../<?= $res['image'] ?>" class="meal-card__image">
            <div class="meal-card__content">
                <h3 class="meal-card__name"><?php echo htmlspecialchars($res['name']); ?></h3>
                <p class="meal-card__description"><?php echo htmlspecialchars($res['address']); ?></p>
                <p class="meal-card__price"><?php echo htmlspecialchars($res['phone']); ?></p>
            </div>
        </div>
        <?php if($res['status'] == "PENDING"){?>
            <div class="meal-card_buttons">
                <form method="post" style="display: inline;">
                    <input type="hidden" name="cafe_id" value="<?= $res['id'] ?>">
                    <button type="submit" name="action" class="btn-3" value="approve">Approve</button>
                </form>
                <form method="post" style="display: inline;">
                    <input type="hidden" name="cafe_id" value="<?= $res['id'] ?>">
                    <button type="submit" name="action" class="btn-3" value="reject">Reject</button>
                </form>
            </div>
        <?php } elseif($res['status'] == "APPROVED"){?>
            <div class="meal-card_buttons">
                <form method="post" style="display: inline;">
                    <input type="hidden" name="cafe_id" value="<?= $res['id'] ?>">
                    <button type="submit" name="action" class="btn-3" value="ban">BANNED</button>
                </form>
            </div>
        <?php } elseif($res['status'] == "BANNED"){?>
        <div class="meal-card_buttons">
            <form method="post" style="display: inline;">
                <input type="hidden" name="cafe_id" value="<?= $res['id'] ?>">
                <button type="submit" name="action" class="btn-3" value="unban">UNBANNED</button>
            </form>
        </div>
        <?php }?>

    </li>
    <script>
        function Restable(resId) {
            // Redirect to a specific restaurant page using resId
            window.location.href = 'reservation.php?res_id=' + resId;
        }

        function orderMeal(resId) {
            // Redirect to meal.php with res_id as a parameter
            window.location.href = 'meal.php?res_id=' + resId;
        }


    </script>
    <style>
        .meal-card__buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }
        .btn-3 {

            flex-direction: column;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            background-color: #e67e22;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-3:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }
        .btn-3:active {
            transform: scale(1);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }
    </style>


    <?php
}
?>

