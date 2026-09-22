<?php
session_start();         //pocetak sesije

$host="localhost";
$user="root";
$password="";
$database="cavoskill";
//konekcija sa bazom
$db = mysqli_connect($host,$user,$password,$database);
if($db==FALSE){
    echo  "Greska sa bazom";
}

if(isset($_POST["login"])){        // Proverava da li je kliknuto dugme za prijavu (submit forme)

    $email = mysqli_real_escape_string($db,$_POST["email"]);
    $lozinka = mysqli_real_escape_string($db,$_POST["lozinka"]);

    $select = "SELECT * FROM korisnik WHERE email_korisnika='$email'";         //trazenje korisnika po email adresi
    $result = mysqli_query($db,$select);

    if(mysqli_num_rows($result)==0){    // Ako nije pronađen nijedan red, znači da email ne postoji u bazi
        echo "<script>alert('Email ne postoji');</script>";
    } 
    else {
        $row = mysqli_fetch_assoc($result);   // Ako postoji korisnik, preuzimamo njegove podatke iz baze u niz

        if($row["lozinka_korisnika"] != $lozinka){     // Poređenje lozinke iz baze sa lozinkom koju je korisnik uneo
            echo "<script>alert('Pogresna lozinka');</script>";
        } 
        else {                  //ukoliko je prijava dobra
            $_SESSION["id_korisnik"] = $row["id_korisnik"];
            $_SESSION["ime"] = $row["ime_i_prezime"];
            $_SESSION["email"] = $row["email_korisnika"];     // U sesiju se upisuje ID korisnika,ime i prezime i email

            header("Location: pocetna.php"); // Nakon uspešne prijave korisnik se preusmerava na početnu stranicu
            exit(); //Stani. Ne izvršavaj ništa dalje u ovom fajlu:)
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Prijava | CavoSkill</title>
<link rel="stylesheet" href="css/stil.css">
</head>

<body>

<div class="login-page">

<div class="login-card">

<div class="login-logo">
<img src="images/logo.png" alt="CavoSkill logo">
</div>

<h1>Prijavite se</h1>
<p class="login-subtitle">
Pristupite svojim kursevima i zapocnite ucenje.
</p>

<form class="login-form" method="post">

<input type="email" name="email" placeholder="Email adresa" required>
<input type="password" name="lozinka" placeholder="Lozinka" required>

<button type="submit" name="login" class="btn-primary full-width">
Prijavi se
</button>

</form>

<div class="login-divider"></div>

<p class="register-text">
Nemate nalog?
<a href="registracija.php">Registrujte se</a>
</p>

</div>
</div>

</body>
</html>
