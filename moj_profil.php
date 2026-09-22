<?php
session_start(); // Pokreće PHP sesiju kako bi se mogli koristiti $_SESSION podaci

if(!isset($_SESSION["id_korisnik"])){
    header("Location: prijava.php");          //provera da li ima id ovog korisnika u sesiji i vraca ga na prijavu
    exit();
}  ///sprecava da neko rucnoo otvori moj profil

if(isset($_POST["logout"])){            //zatvara sesiju i izbacuje korisnika iz sistema
    session_destroy();
    header("Location: prijava.php");
    exit();
}

$host="localhost";
$user="root";
$password="";
$database="cavoskill";
//konekcija sa bazom
$db = mysqli_connect($host,$user,$password,$database);
if($db==FALSE){
    echo "Greska u konekciji";   //ukoliko nije uspela
}

$id = $_SESSION["id_korisnik"]; //Uzima vrednost iz polja i korsti je za naredne potrebe u kodu
$poruka = "";

//provera da li je stisnuto dugme sacuvaj
if(isset($_POST["sacuvaj"])){
    //zastita podataka
    $ime = mysqli_real_escape_string($db,$_POST["ime"]);
    $email = mysqli_real_escape_string($db,$_POST["email"]);
    $lozinka = mysqli_real_escape_string($db,$_POST["lozinka"]);
    //promena samo za trenutno ulogovano korisnika
    $update = "UPDATE korisnik 
               SET ime_i_prezime='$ime',
                   email_korisnika='$email',
                   lozinka_korisnika='$lozinka'
               WHERE id_korisnik='$id'";// Update podatak u bazi
    $result1=mysqli_query($db,$update);       //izvrsavanje upita

    if($result1==TRUE){            //provera da li je update uspesan
        $_SESSION["ime"] = $ime;          //menja promenljivu da bi se taj update odmah video
        $poruka = "Podaci su sacuvani!";
    } else {
        $poruka = "Doslo je do greske pri cuvanju!";
    }
}

$select = "SELECT * FROM korisnik WHERE id_korisnik='$id'";        //cita podatke korisnika
$result2 = mysqli_query($db,$select);
$korisnik = mysqli_fetch_assoc($result2); //Uzima jedan red podataka iz baze i pravi asocijativni niz

$ime = $korisnik["ime_i_prezime"];
$email = $korisnik["email_korisnika"];
$lozinka = $korisnik["lozinka_korisnika"];
$slovo = strtoupper($ime[0]); // Prikaz podataka o korisniku iz baze
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Moj profil | CavoSkill</title>
<link rel="stylesheet" href="css/stil.css">
<style>
.profile-form{ max-width:400px; 
}
.profile-form input{ width:100%; 
padding:10px; 
margin-bottom:12px; 
}
.save-btn{ padding:10px 20px; 
background:#6c4cff; 
color:white; 
border:none; 
border-radius:8px; 
cursor:pointer;
 }
.user-box{ display:flex; 
align-items:center; 
gap:12px; 
}
.avatar{ width:36px; 
height:36px; 
background:#6c4cff; 
color:white; 
border-radius:50%;
 display:flex; 
 align-items:center; 
 justify-content:center; 
 font-weight:bold; 
}
.user-name{ font-weight:600; 
}
.logout-btn{ background:none; 
border:1px solid #6c4cff; 
color:#6c4cff; 
padding:6px 14px; 
border-radius:20px;
 cursor:pointer;
 }
.logout-btn:hover{ background:#6c4cff; 
color:white; 
}
.sidebar ul li{ margin-bottom:12px; 
}
.sidebar ul li a{ font-family:"Segoe UI",Arial;
 font-size:15px; 
 padding:8px 16px; 
 display:block;
  border-radius:10px; 
}
.sidebar ul li a:hover{ background:rgba(255,255,255,0.12);
 padding-left:22px; 
}
.sidebar ul li.active a{ background:rgba(255,255,255,0.18); 
}
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
<li><a href="moji_sertifikati.php">Moji sertifikati</a></li>
<li class="active"><a href="moj_profil.php">Moj profil</a></li>

</ul>
</aside>

<main class="content">

<h2>Moj profil</h2>

<?php if($poruka!="") echo "<p style='color:green'>$poruka</p>"; ?>



<form method="post" class="profile-form">

<label>Ime i prezime</label>
<input type="text" name="ime" value="<?php echo $ime; ?>" required>

<label>Email</label>
<input type="email" name="email" value="<?php echo $email; ?>" required>

<label>Lozinka</label>
<input type="text" name="lozinka" value="<?php echo $lozinka; ?>" required>

<button class="save-btn" name="sacuvaj">Sacuvaj izmene</button>

</form>

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
