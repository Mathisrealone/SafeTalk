<?php
require "./classes/db.php";
require "./classes/form.php";
require "./classes/login.php";

session_start();

$form = new Form;
$login = new Login;
$params = [];
$errors = [];
$message = "";

if ($form->isSubmitted()) {
  $email = $_POST["email"];
  $mdp = $_POST["password"];
  if ($form->isValidLoginForm()) {
    if ($login->checkAccess($email, $mdp)) {
      var_dump($_POST);
      $userData = $form->getUserData($email)[0];
      // ajouter un array avec toutes les infos du man avec une fonction de membre
      $_SESSION["is_connected"] = true;
      $_SESSION["is_admin"] = boolval($userData["is_admin"]);
      $_SESSION["membre_id"] = $userData["id"];
      $_SESSION["membre_prenom"] = $userData["prenom"];
      $_SESSION["membre_nom"] = $userData["nom"];
      $_SESSION["membre_email"] = $userData["email"];
      $login->connect();
    } else {
      $message = "Email ou mot de passe incorrect";
      header("Location: loginForm.php?error");
    }
  } else {
    $errors = $form->getErrorsLogin();
  }
}

?>
<!DOCTYPE html>
<html lang='fr'>

<head>
  <meta charset='UTF-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title>Authentification — SafeTalk</title>
  <meta name='description' content="Page d'authentification de SafeTalk">
  <link rel='stylesheet' href='./assets/style/style.css' />
  <link rel='shortcut icon' type='image/png' href='' />
  <script src='' defer></script>
</head>

<body>
  <main>
    <div class="form-cont">
      <h1>Log in</h1>
      <form method="post">
        <div><label for="email">Email</label><input type="email" name="email" id="email" value="<?php if ($form->isSubmitted()) {
                                                                                                  if (!empty($_POST["email"])) {
                                                                                                    echo $_POST["email"];
                                                                                                  }
                                                                                                } ?>">
          <p><?php if ($form->isSubmitted()) {
                if (!($form->isValidLoginForm($params))) {
                  echo $errors["email"];
                }
              } ?></p>
        </div>
        <div><label for="password">Mot de passe</label><input type="password" name="password" id="password">
          <p><?php if ($form->isSubmitted()) {
                if (!($form->isValidLoginForm($params))) {
                  echo $errors["password"];
                }
              } ?></p>
        </div>
        <button>Se connecter</button>
      </form>
    </div>
    <?php
    if (isset($_GET["error"])) :
    ?>
      <p>Email ou mot de passe incorrect</p>
      <p><label for="email">Réessaie</label> ou <a href="newAccount.php">crée un compte</a></p>
    <?php
    endif;
    ?>
  </main>

</body>

</html>