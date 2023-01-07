<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UpTask | <?php echo $titulo ?? '' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Open+Sans&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
    <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">

    <link rel="icon" href="/build/img/agenda.png" type="image/png">
</head>
<body>

    <?php echo $contenido; ?>
    <?php echo $script ?? ''; ?>

</body>
</html>