<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "cavoskill";
//konekcija sa bazom
$db = mysqli_connect($host, $user, $password, $database);
if($db==FALSE){
    echo "Greska sa bazom";
}

if(isset($_POST["registracija"])){          //provera da li je forma poslata samo kada se stisne dugme 
 
    $ime = mysqli_real_escape_string($db,$_POST["ime"]);
    $prezime = mysqli_real_escape_string($db,$_POST["prezime"]);
    $ime_prezime = $ime." ".$prezime;  //Spaja ime i prezime za prikaz u bazi

    $datum = mysqli_real_escape_string($db,$_POST["datum"]);
    $email = mysqli_real_escape_string($db,$_POST["email"]);
    $lozinka = mysqli_real_escape_string($db,$_POST["lozinka"]);
    $potvrda = mysqli_real_escape_string($db,$_POST["potvrda"]);

    $godine = date_diff(date_create($datum), date_create('today'))->y;  //Racuna koliko godina ima korisnik na osnovu datuma rodjenja.
                                                                                                                    //date_create pravi datum od unesenog datuma od strane korisnika
    if($godine < 18 || strlen($lozinka) < 6 || $lozinka != $potvrda || !filter_var($email, FILTER_VALIDATE_EMAIL)){  //Proverava da li string ima format email adrese
        echo "<script>alert('Registracija nije uspela');</script>";
    }
    else{

        // Generisanje id_korisnik
        $q = "SELECT id_korisnik FROM korisnik ORDER BY CAST(SUBSTRING(id_korisnik,2) AS UNSIGNED) DESC LIMIT 1"; //Izvlaci broj iz id_korisnik i oslobadja ga od znakova kao sto je -
        $r = mysqli_query($db,$q);

        if(mysqli_num_rows($r)==0){       
            $novi_id = "K1";
        } else {
            $row = mysqli_fetch_assoc($r);
            $broj = intval(substr($row["id_korisnik"],1)); //Generise id K1 ukoliko ne postoji nijedan,a ako ima sledeci id je K + sledeci broj
            $novi_id = "K".($broj+1);
        }
       
        //ubacivanje u bazu 
        $insert = "
        INSERT INTO korisnik
        (id_korisnik, ime_i_prezime, email_korisnika, lozinka_korisnika, datum_rodjenja)
        VALUES
        ('$novi_id','$ime_prezime','$email','$lozinka','$datum')";

        $result=mysqli_query($db,$insert);
        if($result==TRUE){
            echo "<script>alert('Uspesna registracija. Vas ID je $novi_id'); window.location='prijava.php';</script>";
        }
        else{
            echo "<script>alert('Greska u bazi ili email vec postoji');</script>"; // Ubacivanje podataka u bazu podatak,konkretno u tabelu korisnik
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<title>Registracija | CavoSkill</title>
<link rel="stylesheet" href="css/stil.css">
</head>

<body>

<div class="login-page">
<div class="login-card">

<div class="login-logo">
<img src="images/logo.png" alt="CavoSkill logo">
</div>

<h1>Registracija</h1>
<p class="login-subtitle">Kreirajte nalog i zapocnite razvoj svojih vestina.</p>

<form class="login-form" method="post" onsubmit="return proveriFormu()"> <!-- Kada korisnik pokuša da pošalje formu, pozovi funkciju proveriFormu() i ako ona vrati false – nemoj poslati formu. -->
<input type="text" name="ime" placeholder="Ime" required>
<input type="text" name="prezime" placeholder="Prezime" required>
<input type="date" name="datum" required>
<input type="email" name="email" placeholder="Email adresa" required>

<div style="position:relative;">
<input type="password" id="lozinka" name="lozinka" placeholder="Lozinka" required>
<span onclick="toggleLozinka('lozinka')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer;">👁</span>
</div>

<div style="position:relative;">
<input type="password" id="potvrda" name="potvrda" placeholder="Potvrda lozinke" required>
<span onclick="toggleLozinka('potvrda')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer;">👁</span><!-- Kada se klikne na ovaj element, pozovi JavaScript funkciju toggleLozinka i prosledi joj ID polja koje treba da se sakrije ili prikaže -->
</div>

<button type="submit" name="registracija" class="btn-primary full-width">
Registruj se
</button>

</form>

<div class="login-divider"></div>

<p class="register-text">
Vec imate nalog?
<a href="prijava.php">Prijavite se</a>
</p>

</div>
</div>

<script>
function toggleLozinka(id){
    let polje = document.getElementById(id);
    polje.type = polje.type === "password" ? "text" : "password"; //Za prikaz sifre tokom unosa
}

function proveriFormu() {
    let lozinka = document.getElementById("lozinka").value;
    let potvrda = document.getElementById("potvrda").value;
    let email = document.querySelector("input[name='email']").value;
    let datum = document.querySelector("input[name='datum']").value;

    let danas = new Date();
    let rodjenje = new Date(datum);
    let godine = danas.getFullYear() - rodjenje.getFullYear();

    if (lozinka.length < 6) {
        alert("Lozinka mora imati minimum 6 karaktera");
        return false;
    }

    if (lozinka !== potvrda) {
        alert("Lozinke se ne poklapaju");
        return false;
    }

    if (!email.includes("@")) {
        alert("Email mora sadrzati @");
        return false;
    }

    if (godine < 18) {
        alert("Morate imati najmanje 18 godina");
        return false;
    }

    return true;
}
</script>

</body>
</html>
