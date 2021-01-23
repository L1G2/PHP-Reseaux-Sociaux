<?php

// Importer le ficier de connexion
require_once "../scripts/config.php";
 
// Initialization des variables
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processus suivis quand une requete est envoyée
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Vérifier le username
    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrez votre nom d'utilisateur svp !!";
    } else{
        // Préparer une requete select 
        $sql = "SELECT id FROM User WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Ce nomd'utilisateur existe déjà !!";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Désolé! Une erreur s'est produite! réessayer ultérieurement";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuiller entrez votre nom d'utilisateur svp !!";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Le mot de passe doit contenir au moins 6 caractères.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Confirmer votre mot de passe svp";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Les mots de passes ne correspondent pas";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO User (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Une erreur s'est produite! réessayer ultérieurement";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
	<title>floppy | Social Network</title>
    <link rel="icon" href="../assets/images/fav.png" type="image/png" sizes="16x16"> 
    
    <link rel="stylesheet" href="../assets/css/main.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/color.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">

</head>
<body>
<!--<div class="se-pre-con"></div>-->
<div class="theme-layout">
	<div class="container-fluid pdng0">
		<div class="row merged">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="land-featurearea">
					<div class="land-meta">
						<h1>floppy</h1>
						<p>
							Partageons notre passion ensemble et pour toujours. 
						</p>
						<div class="friend-logo">
							<span><img src="../assets/images/wink.png" alt=""></span>
						</div>
						<a href="#" title="" class="folow-me">Suivez nous</a>
					</div>	
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="login-reg-bg">
					<div class="log-reg-area sign">
						<h2 class="log-title">Se connecter</h2>
							<p>
								Pas encore de compte? <a href="#" title=""> Créer </a> en un
							</p>
						<form method="post">
							<div class="form-group">	
							  <input type="text" id="input" required="required"/>
							  <label class="control-label" for="input"> Nom d'utilisateur</label><i class="mtrl-select"></i>
							</div>
							<div class="form-group">	
							  <input type="password" required="required"/>
							  <label class="control-label" for="input"> Mot de passe</label><i class="mtrl-select"></i>
							</div>
							<div class="checkbox">
							  <label>
								<input type="checkbox" checked="checked"/><i class="check-box"></i>Se souvenir de moi
							  </label>
							</div>
							<a href="#" title="" class="forgot-pwd">Mot de passe oublié</a>
							<div class="submit-btns">
								<button class="mtr-btn signin" type="button"><span>Se connecter</span></button>
								<button class="mtr-btn signup" type="button"><span>S'enregistrer</span></button>
							</div>
						</form>
					</div>
					<div class="log-reg-area reg">
						<h2 class="log-title">S'enregistrer</h2>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">	
							  <input type="text" required="required"/>
							  <label class="control-label" for="input"> Prenom</label><i class="mtrl-select"></i>
							</div>
							<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">	
							  <input type="text" required="required" value="<?php echo $password; ?>"/>
							  <label class="control-label" for="input"> Nom d'utilisateur</label><i class="mtrl-select">
							  </i>
							  <span class="help-block"><?php echo $username_err; ?></span>
							</div>
							<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">	
							  <input type="password" required="required" value="<?php echo $password; ?>" />
							  <label class="control-label" for="input"> Mot de passe</label><i class="mtrl-select"></i>
							  <span class="help-block"><?php echo $password_err; ?></span>
							</div>
							<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">	
							  <input type="password" required="required" value="<?php echo $confirm_password; ?>"/>
							  <label class="control-label" for="input"> Confirmer mot de passe</label><i class="mtrl-select"></i>
							  <span class="help-block"><?php echo $confirm_password_err; ?></span>
							</div>
							<div class="form-radio">
							  <div class="radio">
								<label>
								  <input type="radio" name="radio" checked="checked"/><i class="check-box"></i>Mâle
								</label>
							  </div>
							  <div class="radio">
								<label>
								  <input type="radio" name="radio"/><i class="check-box"></i>Femêle
								</label>
							  </div>
							</div>
							<div class="checkbox">
							  <label>
								<input type="checkbox" checked="checked"/><i class="check-box"></i>Accepter les termes et les conditions ?
							  </label>
							</div>
							<a href="#" title="" class="already-have">Already have an account</a>
							<div class="submit-btns">
								<input class="mtr-btn signup" type="submit" value="s'enreistrer"/>
							</div>
						</form>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
	<script src="../assets/js/main.min.js"></script>
	<script src="../assets/js/script.js"></script>

</body>	

</html>
