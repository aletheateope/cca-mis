<?php
session_start();
if (isset($_SESSION['month'])) {
    unset($_SESSION['month']);
}
