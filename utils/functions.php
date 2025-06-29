<?php
function authenticate_user($username, $password) {
    // Dummy user check
    return $username === 'john' && $password === '1234';
}
?>