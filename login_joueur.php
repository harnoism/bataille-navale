<!DOCTYPE html>
<html>
  <head>
      <meta charset="UTF-8">
      <title>Joueur 1 / Joueur 2</title>
  </head>
  <body>
    <h1>Connexion aux rôles</h1>
    <h2>Votre rôle actuel : <strong><?= $role ?></strong></h2>
    <p>
      Joueur 1 : <?= $etat["j1"] ? "🟢 Occupé" : "🔴 Libre" ?><br>
      Joueur 2 : <?= $etat["j2"] ? "🟢 Occupé" : "🔴 Libre" ?>
    </p>

    <form method="post">
      <button type="submit" name="joueur1"
          <?= $etat["j1"] !== null ? "disabled" : "" ?>>
          🎮 Devenir Joueur 1
      </button>
      <button type="submit" name="joueur2"
          <?= $etat["j2"] !== null ? "disabled" : "" ?>>
          🎮 Devenir Joueur 2
      </button>
      <button type="submit" name="reset_total">
          ❌ Fin de partie (RESET)
      </button>
    </form>
  </body>
</html>