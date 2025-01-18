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
        <?php if ($res['status'] == "PENDING") { ?>
            <div class="meal-card_buttons">
                <button type="button" class="btn-3" onclick="confirmAction(<?= $res['id'] ?>, 'approve')">Approve</button>
                <button type="button" class="btn-3" onclick="confirmAction(<?= $res['id'] ?>, 'reject')">Reject</button>
            </div>
        <?php } elseif ($res['status'] == "APPROVED") { ?>
            <div class="meal-card_buttons">
                <button type="button" class="btn-3" onclick="confirmAction(<?= $res['id'] ?>, 'ban')">Ban</button>
            </div>
        <?php } elseif ($res['status'] == "BANNED") { ?>
            <div class="meal-card_buttons">
                <button type="button" class="btn-3" onclick="confirmAction(<?= $res['id'] ?>, 'unban')">Unban</button>
            </div>
        <?php } ?>
    </li>
    <script>
        function orderMeal(resId) {
            window.location.href = 'meal.php?res_id=' + resId;
        }

        function confirmAction(cafeId, action) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to ${action} this café?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, do it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form using JavaScript
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = ''; // Current page
                    form.innerHTML = `
                        <input type="hidden" name="cafe_id" value="${cafeId}">
                        <input type="hidden" name="action" value="${action}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
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
            padding: 10px 50px;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(to right, #ff8c00, #ff5900);
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

