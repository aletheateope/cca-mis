<?php
session_start();
if (isset($_SESSION['nextMonth'])) {
    unset($_SESSION['nextMonth']);
}
