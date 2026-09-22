<?php
session_start();  // Pokreće PHP sesiju i pamtti podatke dok je korisnik ulogovan
// provera da li je korisnik ulogovan
if(!isset($_SESSION["id_korisnik"])){        ///cuva id trenutno ulogovano korisnika i proverava da li on postoji
    header("Location: prijava.php");                  ///preusmerava korisnika na prijavu
    exit();
}

if(isset($_POST["logout"])){
    session_destroy();                   //brisemo celu sesiju 
    header("Location: prijava.php");              ///preusmerava na prijavu
    exit();
}

$ime = $_SESSION["ime"];             //uzima ime iz sesije
$slovo = strtoupper($ime[0]);               ///slovo koje koristi za avatara                
$id_korisnik = $_SESSION["id_korisnik"];             //cuva id korisnika u promenljivu
//konekcija
$host="localhost";
$user="root";
$password="";
$database="cavoskill";

$db = mysqli_connect($host,$user,$password,$database);
if(!$db){       ///provera konekcije
    echo  "Greska sa bazom";
}

$poruka = "";    //promenljiva da li je nesto uspesno ili nije

if(isset($_POST["kupi"])){     //aktiviranje kada se stisne dugme kupi

    $sifra_kursa = $_POST["sifra_kursa"];    //uzimanje sifre kursa i povezivanje kursa sa kupovinom

    $sql = "SELECT sifra_kupovine FROM kupovina ORDER BY sifra_kupovine DESC LIMIT 1";            //uzima poslednju sifru kupovine, sortira opadajuce i samo jedan
    $rez = mysqli_query($db,$sql);              //salje upit bazi i izvrsava ga

    if(mysqli_num_rows($rez)==0){           //ako nema kupovine, dobija sifru kp1
        $nova_kupovina = "KP1";   //Postavka sifre kupovine
    }
    else{
        $red = mysqli_fetch_assoc($rez);    ///uzima red kao asocijativni niz
        $broj = intval(substr($red["sifra_kupovine"],2));  //skida KP i ostaje kao broj 
        $nova_kupovina = "KP".($broj+1);     //dodaje 1 i to je rucno generisanje ID-ja
    }
    //insert u tabelu kupovina
    $sql = "INSERT INTO kupovina (sifra_kupovine,id_korisnik,datum_kupovine)
            VALUES ('$nova_kupovina','$id_korisnik',NOW())";
    mysqli_query($db,$sql);

    $sql = "INSERT INTO kupovina_kursa (sifra_kupovine,sifra_kursa)
            VALUES ('$nova_kupovina','$sifra_kursa')";
    mysqli_query($db,$sql);                 //Ubacivanje podataka o kupovini u tabele

    $poruka = "Kurs je uspesno kupljen!";        //poruka korisniku 
}

// Sklanjanje kupjenog kursa iz prikaza       //select sluzi da se ne bi prikazali kursevi koji su kupljeni
$nestanak = "
SELECT                                    
    k.sifra_kursa,
    k.naziv_kursa,
    k.kategorija,
    k.nivo,
    k.cena,
    k.slika_kursa,
    d.naziv_distributera
FROM kurs k
JOIN kurs_distributer kd ON k.sifra_kursa = kd.sifra_kursa
JOIN distributer d ON kd.id_distributer = d.id_distributer
WHERE k.sifra_kursa NOT IN (
    SELECT kk.sifra_kursa
    FROM kupovina_kursa kk
    JOIN kupovina ku ON kk.sifra_kupovine = ku.sifra_kupovine
    WHERE ku.id_korisnik = '$id_korisnik'
)
";

$rez = mysqli_query($db,$nestanak);     //naredba bazi da izvrsi upit
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dostupni kursevi | CavoSkill</title>
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
.buy-btn{margin-top:auto;padding:10px;border:none;border-radius:8px;background:#6c4cff;color:white;cursor:pointer;}
.publisher{font-size:13px;color:#555;margin-bottom:6px;}
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

<script>
function potvrdiKupovinu(){
    return confirm("Da li ste sigurni da zelite da kupite ovaj kurs?");
}
</script>
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
<li class="active"><a href="dostupni_kursevi.php">Dostupni kursevi</a></li>
<li><a href="moji_sertifikati.php">Moji sertifikati</a></li>
<li><a href="moj_profil.php">Moj profil</a></li>

</ul>
</aside>

<main class="content">

<h2>Dostupni kursevi</h2>

<?php if($poruka!="") echo "<script>alert('$poruka');</script>"; ?>

<div class="course-grid">

<?php
while($k = mysqli_fetch_assoc($rez)){
// Prikaz dostupnih kurseva    
echo "
    <div class='course-card'>
        <img src='images/{$k['slika_kursa']}' class='course-image'>
        <h3>{$k['naziv_kursa']}</h3>
        <p class='publisher'>Izdavac: {$k['naziv_distributera']}</p>
        <p><b>Kategorija:</b> {$k['kategorija']}</p>
        <p><b>Nivo:</b> {$k['nivo']}</p>
        <p><b>Cena:</b> {$k['cena']} €</p>  

        <form method='post' onsubmit='return potvrdiKupovinu();'>
            <input type='hidden' name='sifra_kursa' value='{$k['sifra_kursa']}'>
            <button class='buy-btn' name='kupi'>Kupi</button>
        </form>
    </div>
    ";
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
