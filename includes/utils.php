<?php

/**
 * Redirect to a specific URL.
 *
 * @param string $url
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Display a success message.
 *
 * @param string $message
 * @return string
 */
function displaySuccess($message) {
    return "<p class='success'>" . htmlspecialchars($message) . "</p>";
}

/**
 * Display an error message.
 *
 * @param string $message
 * @return string
 */
function displayError($message) {
    return "<p class='error'>" . htmlspecialchars($message) . "</p>";
}

/**
 * Check if the user is logged in.
 *
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if the user has a specific role.
 *
 * @param string $role
 * @return bool
 */
function isUserRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}
