<?php
session_start();    // Pokreće PHP sesiju kako bi se mogli koristiti $_SESSION podaci
if(!isset($_SESSION["id_korisnik"])){    // provera da li je korisnik ulogovan
    header("Location: prijava.php");
    exit();
}

if(isset($_POST["logout"])){
    session_destroy();
    header("Location: prijava.php");
    exit();
}
//uzima podatke iz sesije
$ime = $_SESSION["ime"];
$slovo = strtoupper($ime[0]);
$id = $_SESSION["id_korisnik"];
//konekcija sa bazom
$host="localhost";
$user="root";
$password="";
$database="cavoskill";
$db = mysqli_connect($host,$user,$password,$database);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Moji sertifikati | CavoSkill</title>
<link rel="stylesheet" href="css/stil.css">

<style>
.user-box{display:flex;align-items:center;gap:12px;}
.avatar{width:36px;height:36px;background:#6c4cff;color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:bold;}
.user-name{font-weight:600;}
.logout-btn{background:none;border:1px solid #6c4cff;color:#6c4cff;padding:6px 14px;border-radius:20px;cursor:pointer;}
.logout-btn:hover{background:#6c4cff;color:white;}
.sidebar ul li{margin-bottom:12px;}
.sidebar ul li a{font-family:"Segoe UI","Inter",Arial,sans-serif;font-size:15px;padding:8px 16px;display:block;border-radius:10px;}
.sidebar ul li a:hover{background:rgba(255,255,255,0.12);}
.sidebar ul li.active a{background:rgba(255,255,255,0.18);}

.course-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:20px;}
.course-card{background:#fff;border-radius:12px;padding:15px;box-shadow:0 10px 20px rgba(0,0,0,.08);display:flex;flex-direction:column;text-align:center;}
.course-image{width:100%;height:130px;object-fit:cover;border-radius:8px;margin-bottom:10px;}
.publisher{font-size:13px;color:#555;margin-bottom:6px;}
.download{color:#6c4cff;font-weight:600;text-decoration:none;margin-top:8px;}
.app-footer{
    background: linear-gradient(90deg,#1e3a8a,#2563eb);
    color: white;
    padding: 18px 40px;
}
.footer-content{
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
}
.footer-content p{
    margin: 0;
    font-size: 14px;
    opacity: 0.9;
}
.footer-content a{
    color: white;
    text-decoration: none;
    font-weight: 500px;
}
.footer-content a:hover{
    text-decoration: underline;
}
</style>
</head>

<body>

<header class="top-bar">
<div class="logo"><img src="images/logo.png"></div>
<div class="top-actions">
<div class="user-box">
<span class="avatar"><?php echo $slovo; ?></span>
<span class="user-name"><?php echo $ime; ?></span>
<form method="post"><button name="logout" class="logout-btn">Odjavi se</button></form>
</div>
</div>
</header>

<div class="layout">

<aside class="sidebar">
<ul>
<li><a href="pocetna.php">Početna</a></li>
<li><a href="moji_kursevi.php">Moji kursevi</a></li>
<li><a href="dostupni_kursevi.php">Dostupni kursevi</a></li>
<li class="active"><a href="moji_sertifikati.php">Moji sertifikati</a></li>
<li><a href="moj_profil.php">Moj profil</a></li>

</ul>
</aside>

<main class="content">

<h2>Moji sertifikati</h2>

<div class="course-grid">

<?php
//Prikaz zavrsenog kursa u mojim sertifikatima, koji korisnik ima koji sertifikat
$select = "SELECT 
    k.naziv_kursa,
    k.slika_kursa,
    d.naziv_distributera,
    s.dokument
FROM sertifikat_korisnik sk
JOIN sertifikat s ON sk.sifra_sertifikata = s.sifra_sertifikata
JOIN kurs k ON s.sifra_kursa = k.sifra_kursa
JOIN kurs_distributer kd ON k.sifra_kursa = kd.sifra_kursa
JOIN distributer d ON kd.id_distributer = d.id_distributer
WHERE sk.id_korisnik = '$id'"; // samo za trenutno ulogovanog

$rez = mysqli_query($db,$select);

if(mysqli_num_rows($rez)==0){                  //provera da li korisnik ima sertifikate
    echo "<p>Još nemate izdatih sertifikata.</p>";
}

while($r = mysqli_fetch_assoc($rez)){                 ///samo ukoliko se kurs zavrsio
    echo " 
    <div class='course-card'>
        <img src='images/{$r['slika_kursa']}' class='course-image'>
        <h3>{$r['naziv_kursa']}</h3>
        <p class='publisher'>Issued by {$r['naziv_distributera']}</p>
        <a class='download' href='sert/{$r['dokument']}' download>Preuzmi sertifikat</a>
    </div>
    ";//Ukoliko nema zavrsenih kurseva ne prikazuje nista, ukoliko se zavrsi kurs radi while i prikazuje taj kurs
}
?>

</div>

</main>
</div>
<footer class="app-footer">
    <div class="footer-content">
        <div class="footer-left">
        <p>Kontakt:<a href="mailto:kontakt@gmail.com">kontakt@gmail.com</a></p>
        </div>
        <div class="footer-right"><p>© 2026 CavoSkill</p> </div>
    </div>
</footer>
</body>
</html>
