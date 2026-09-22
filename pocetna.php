<?php
session_start(); // Pokreće PHP sesiju kako bi se mogli koristiti $_SESSION podaci

if(!isset($_SESSION["id_korisnik"])){                // Provera da li je korisnik ulogovan
    header("Location: prijava.php");         // Ako nije – preusmeri na stranicu za prijavu
    exit();                                          // Prekida dalje izvršavanje skripte
}

if(isset($_POST["logout"])){                        // Provera da li je kliknuto dugme "Odjavi se"
    session_destroy();                              // Briše sve podatke sesije (korisnik se izloguje)
    header("Location: prijava.php");        // Preusmerava korisnika na login stranicu
    exit();                                         // Prekida dalje izvršavanje skripte
}

$ime = $_SESSION["ime"]; // Iz sesije se uzima ime korisnika
$slovo = strtoupper($ime[0]); // Uzimamo prvo slovo imena i pretvaramo ga u veliko (za avatar)

$host="localhost";
$user="root";
$password="";
$database="cavoskill";

$db = mysqli_connect($host,$user,$password,$database);
if(!$db){
    echo "Greska sa bazom";}

  //Preporuceni kursevi

$preporuceni = "SELECT sifra_kursa, naziv_kursa, slika_kursa           
    FROM kurs
    LIMIT 3";

$result=mysqli_query($db,$preporuceni);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pocetna | CavoSkill</title>
    <link rel="stylesheet" href="css/stil.css">
    <style>
.user-box{
    display: flex;
    align-items: center;
    gap: 15px;
}

.avatar{
    width: 36px;
    height: 36px;
    background-color: #6c4cff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.user-name{
    font-weight: 600;
}

.logout-btn{
    background: none;
    border: 1px solid #6c4cff;
    color: #6c4cff;
    padding: 6px 14px;
    border-radius: 20px;
    cursor: pointer;
}

.logout-btn:hover{
    background: #6c4cff;
    color: white;
}

.course-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(230px,1fr));
    gap:20px;
    margin-top:20px;
}

.course-card{
    background:#fff;
    border-radius:12px;
    padding:15px;
    box-shadow:0 10px 20px rgba(0,0,0,.08);
    text-align:center;
}

.course-image{
    width:100%;
    height:130px;
    object-fit:cover;
    border-radius:8px;
    margin-bottom:10px;
}
.sidebar ul li{margin-bottom:12px;}
.sidebar ul li a{font-family:"Segoe UI","Inter",Arial,sans-serif;font-size:15px;padding:8px 16px;display:block;border-radius:10px;}
.sidebar ul li a:hover{background:rgba(255,255,255,0.12);}
.sidebar ul li.active a{background:rgba(255,255,255,0.18);}

.all-courses-btn{
    margin-top:20px;
    display:inline-block;
    padding:10px 20px;
    border-radius:20px;
    background:#6c4cff;
    color:white;
    text-decoration:none;
    font-weight:600;
}
.kom{padding: 50px;}
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
    <div class="logo">
        <img src="images/logo.png" alt="CavoSkill logo">
    </div>

    <div class="top-actions">
        <div class="user-box">
            <span class="avatar"><?php echo $slovo; ?></span>
            <span class="user-name"><?php echo $ime; ?></span>

            <form method="post" style="margin:0;">
                <button type="submit" name="logout" class="logout-btn">Odjavi se</button>
            </form>
        </div>
    </div>
</header>

<div class="layout">

    <aside class="sidebar">
        <ul>
            <li class="active"><a href="pocetna.php">Početna</a></li>
            <li><a href="moji_kursevi.php">Moji kursevi</a></li>
            <li><a href="dostupni_kursevi.php">Dostupni kursevi</a></li>
            <li><a href="moji_sertifikati.php">Moji sertifikati</a></li>
            <li><a href="moj_profil.php">Moj profil</a></li>
           
        </ul>
    </aside>

    <main class="content">

        <h2>Dobrodošli, <?php echo $ime; ?> 👋</h2>
        <p>Otkrijte nove prilike za profesionalni razvoj i unapredite svoje poslovne veštine kroz online kurseve na platformi CavoSkill</p>
<br>
        <h3>Preporučeni kursevi</h3>
<br>
        <div class="course-grid">
            <?php
            while($k = mysqli_fetch_assoc($result)){
                echo "
                <div class='course-card'>
                    <img src='images/{$k['slika_kursa']}' class='course-image'>
                    <h4>{$k['naziv_kursa']}</h4>
                </div>
                ";
            }
            ?>
        </div>

        <a href="dostupni_kursevi.php" class="all-courses-btn">
            Pogledaj sve kurseve
            <br>
        </a>
        <br>
        <p>
            <br>
            <h2>Komentari zadovoljnih korisnika:</h2>
            
<center><img src="images/kom.png" class="kom"></center>
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
