<?php
session_start();  // Pokreće PHP sesiju kako bi se mogli koristiti $_SESSION podaci

if(!isset($_SESSION["id_korisnik"])){                //proverava da li je korisnik ulogovan, ako ne vraca ga na prijavu
    header("Location: prijava.php");
    exit();
}
//brisanje sesije
if(isset($_POST["logout"])){
    session_destroy();
    header("Location: prijava.php");
    exit();
}
//uzimanje podatke iz sesije
$ime = $_SESSION["ime"];
$slovo = strtoupper($ime[0]);
$id_korisnik = $_SESSION["id_korisnik"];
//konekcije sa bazom
$host="localhost";
$user="root";
$password="";
$database="cavoskill";
//provera konekcije
$db = mysqli_connect($host,$user,$password,$database);
if(!$db){
    echo "Greska sa bazom";
}

$poruka = "";

   //Zavrsetak kursa

if(isset($_POST["zavrsi"])){       //kada korisnik stisne dugme zavrsi

    $sifra_kursa = $_POST["sifra_kursa"];    //uzima sifru tog kursa koji je zavrsen

    // Upis u tabelu zavrsen_kurs
    $insert1 = "
        INSERT INTO zavrsen_kurs (id_korisnik, sifra_kursa, datum_zavrsetka)
        VALUES ('$id_korisnik','$sifra_kursa',NOW())
    ";
    mysqli_query($db,$insert1);       //izvrsava upit

    // Pronalaženje sertifikata za dati kurs
    $q = "
        SELECT sifra_sertifikata
        FROM sertifikat
        WHERE sifra_kursa='$sifra_kursa'
    ";
    $rezultat = mysqli_query($db, $q);           
    $r = mysqli_fetch_assoc($rezultat);
    $sifra_sertifikata = $r["sifra_sertifikata"];

    // Upis sertifikata korisniku
    $insert2 = "
        INSERT INTO sertifikat_korisnik
        (sifra_sertifikata, id_korisnik, datum_izdavanja)
        VALUES ('$sifra_sertifikata','$id_korisnik',NOW())
    ";
    mysqli_query($db,$insert2);

    $poruka = "Kurs je završen i sertifikat je dodat!";
}

//Napustanje(brisanje) kursa

if(isset($_POST["obrisi"])){              //ukoliko se stisne dugme obrisi

    $sifra_kursa = $_POST["sifra_kursa"];   //da zna koji kurs brise i pronalazi sifru kupovine

    $sql = "                        
        SELECT ku.sifra_kupovine
        FROM kupovina ku
        JOIN kupovina_kursa kk 
        ON ku.sifra_kupovine = kk.sifra_kupovine
        WHERE ku.id_korisnik = '$id_korisnik'
        AND kk.sifra_kursa = '$sifra_kursa'
    ";
    $rez = mysqli_query($db,$sql);
    $red = mysqli_fetch_assoc($rez);
    $sifra_kupovine = $red["sifra_kupovine"];
    //brisanje kursa iz kupovine
    mysqli_query($db,"
        DELETE FROM kupovina_kursa
        WHERE sifra_kupovine='$sifra_kupovine'
        AND sifra_kursa='$sifra_kursa'
    ");
    //brisanje kupovine
    mysqli_query($db,"
        DELETE FROM kupovina
        WHERE sifra_kupovine='$sifra_kupovine'
    ");

    $poruka = "Kurs je uspešno ispisan!";
}

//Prikaz kupljenih kurseva
$kurs = "
SELECT DISTINCT 
    k.sifra_kursa,
    k.naziv_kursa,
    k.kategorija,
    k.nivo,
    k.slika_kursa,
    d.naziv_distributera
FROM kurs k
JOIN kupovina_kursa kk ON k.sifra_kursa = kk.sifra_kursa
JOIN kupovina ku ON kk.sifra_kupovine = ku.sifra_kupovine
JOIN kurs_distributer kd ON k.sifra_kursa = kd.sifra_kursa
JOIN distributer d ON kd.id_distributer = d.id_distributer
WHERE ku.id_korisnik = '$id_korisnik'
";

$rez = mysqli_query($db,$kurs);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Moji kursevi | CavoSkill</title>
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
.course-card{background:#fff;border-radius:12px;padding:15px;box-shadow:0 10px 20px rgba(0,0,0,.08);display:flex;flex-direction:column;}
.course-image{width:100%;height:130px;object-fit:cover;border-radius:8px;margin-bottom:10px;}
.publisher{font-size:13px;color:#555;margin-bottom:6px;}
.buy-btn{margin-top:auto;padding:10px;border:none;border-radius:8px;background:#dc2626;color:white;cursor:pointer;}
.finish-btn{margin-top:6px;padding:10px;border:none;border-radius:8px;background:#16a34a;color:white;cursor:pointer;}
.finished{color:#16a34a;font-weight:600;margin-top:10px;}
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
<li class="active"><a href="moji_kursevi.php">Moji kursevi</a></li>
<li><a href="dostupni_kursevi.php">Dostupni kursevi</a></li>
<li><a href="moji_sertifikati.php">Moji sertifikati</a></li>
<li><a href="moj_profil.php">Moj profil</a></li>
</ul>
</aside>

<main class="content">

<h2>Moji kupljeni kursevi</h2>

<?php if($poruka!="") echo "<script>alert('$poruka');</script>"; ?>

<div class="course-grid">

<?php
while($k = mysqli_fetch_assoc($rez)){

    $sk = $k['sifra_kursa'];

    $zk = mysqli_query($db,"
        SELECT * FROM zavrsen_kurs
        WHERE id_korisnik='$id_korisnik'
        AND sifra_kursa='$sk'
    ");
    $zavrsen = mysqli_num_rows($zk) > 0;

    echo "
    <div class='course-card' ".($zavrsen ? "onclick=\"window.location='sert/$sk.pdf'\" style='cursor:pointer'" : "").">
        <img src='images/{$k['slika_kursa']}' class='course-image'>
        <h3>{$k['naziv_kursa']}</h3>
        <p class='publisher'>Izdavac: {$k['naziv_distributera']}</p>
        <p><b>Kategorija:</b> {$k['kategorija']}</p>
        <p><b>Nivo:</b> {$k['nivo']}</p>

        <form method='post'>
            <input type='hidden' name='sifra_kursa' value='$sk'>
            ".(
                $zavrsen
                ? "<div class='finished'>Završen kurs</div>"
                : "
                <button name='obrisi' class='buy-btn'>Ispisi me</button>
                <button name='zavrsi' class='finish-btn'>Završi kurs</button>
                "
            )."
        </form>
    </div>
    ";
}
?>

</div>

</main>
</div>

</body>
</html>
