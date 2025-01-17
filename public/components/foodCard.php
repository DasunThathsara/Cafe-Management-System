<?php
function DishCard($imageSrc, $title, $description, $price, $cuisineType, $cafe_name, $additionalClass = '')
{
    echo "
    <div class='card $additionalClass'>
        <img src='$imageSrc' alt='$title'>
        <h3>$title</h3>
        <p>$description</p>
        <p>LKR $price</p>
        <p><b>$cafe_name</b></p>
        <div class='foodcard-inner'><button class='btn-2 order-now-button'>Order Now</button></div>
    </div>
    ";
}