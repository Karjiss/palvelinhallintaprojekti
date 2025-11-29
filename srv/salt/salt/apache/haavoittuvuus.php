<?php
// Asetetaan HTTP-otsake, joka ei salli X-Frame-Options: NÄIN mahdollistetaan Clickjacking
header('X-Frame-Options: ALLOWALL'); // TAHALLAAN EPÄTURVALLINEN

// Tässä on kaksi globaalia muuttujaa, jotka ovat haavoittuvia
$user_input = isset($_GET['data']) ? $_GET['data'] : 'Ei syötettä';
$file_to_include = isset($_GET['page']) ? $_GET['page'] : 'default.html';

// **********************************
// 1. Tietokanta-asetukset (VAIN EDELLYTTÄEN, ETTÄ KÄYTÖSSÄ ON TIETOKANTA)
// Voi johtaa Information Disclosure -haavoittuvuuteen
$db_host = 'localhost';
$db_user = 'root'; 
$db_pass = 'salainen_salasana_123'; // TAHALLAAN Kovakoodattu salasana
// **********************************

// Luodaan tiedosto, jotta LFI testaus toimii oletuksena
file_put_contents('default.html', '<h3>Oletussisältö sisällytetty. Yritä nyt syöttää ?page=../../../../etc/passwd</h3>');
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Mega Haavoittuva Testisivu</title>
</head>
<body>

    <h1>Kattava Haavoittuvuuspohja</h1>

    <h2>2. Reflected XSS-haavoittuvuus</h2>
    <p>Syötteesi: <?php echo $user_input; ?></p>
    <hr>
    
    <h2>3. Command Injection -haavoittuvuus</h2>
    <form method="POST">
        <label for="cmd">Pingaa IP (vain numeroita):</label>
        <input type="text" id="cmd" name="target" value="127.0.0.1"><br><br>
        <input type="submit" value="Aja komento">
    </form>
    
    <?php
    if (isset($_POST['target'])) {
        $target = $_POST['target'];
        
        // TAHALLAAN EPÄTURVALLINEN: Syötettä ei puhdisteta.
        // Esimerkki hyökkäys: 127.0.0.1; ls -l /
        $command = 'ping -c 4 ' . $target; 
        
        echo "<pre>Tulostus:\n" . shell_exec($command) . "</pre>"; 
    }
    ?>

    <hr>
    
    <h2>4. LFI (Local File Inclusion) -haavoittuvuus</h2>
    <p>Sisällytetty tiedosto: <strong><?php echo htmlspecialchars($file_to_include); ?></strong></p>
    <div style="border: 2px dashed red; padding: 15px;">
        <?php
        // TAHALLAAN EPÄTURVALLINEN: Ei puhdistusta tai rajoituksia.
        include($file_to_include);
        ?>
    </div>
    
    <hr>
    
    <h2>5. Information Disclosure</h2>
    <p>Tarkista tämän sivun lähdekoodi (Inspect/View Page Source) löytääksesi:
        <ul>
            <li>**Kovakoodattu salaisuus** (Koodin alussa: `$db_pass`)</li>
            <li>**X-Frame-Options** -otsakkeen puuttuminen/heikkous</li>
        </ul>
    </p>

</body>
</html>
