<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'MikroTik UserMan' ?></title>
    <?php $appLogo = $data['settings']['app_logo'] ?? 'assets/img/mangoteklogo.png'; ?>
    <link rel="icon" type="image/png" href="<?= BASEURL . '/' . $appLogo; ?>">
    <link rel="shortcut icon" type="image/png" href="<?= BASEURL . '/' . $appLogo; ?>">
    <meta name="description" content="MikroTik User Manager - RADIUS User Management for RouterOS 7">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/all.min.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/style.css">
</head>
<body>
