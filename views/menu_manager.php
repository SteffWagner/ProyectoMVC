<?php
session_start();


if (isset($_SESSION['usuario']) && isset($_SESSION['rol'])) {

    $rolUsuario = $_SESSION['rol'];


    switch ($rolUsuario) {
        case 'Administrador':
            include 'Dark_MenuDashboard.php';
            break;
        case 'Colaborador':
            include 'MenuDashboard.php';
            break;
        default:
            include 'MenuDashboard.php';
            break;
    }
}
?>
