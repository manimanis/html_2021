<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vérification des réponses</title>
  <style>
    body {
      font-size: 20pt;
    }

    .error {
      color: red;
    }
  </style>
</head>

<body>
  <?php
  $errors = false;
  echo "<ul>";
  if ($_SERVER['REQUEST_METHOD'] != "POST") {
    echo "<li>Utiliser la méthode POST dans la balise &lt;form&gt;</li>";
    $errors = true;
  }

  if (!isset($_POST['genre'])) {
    echo "<li>Répondre à la Question 1.<br>Utiliser <strong>name=&quot;genre&quot;</strong> pour les radio de la Question 1.</li>";
    $errors = true;
  }

  if (!isset($_POST['metier'])) {
    echo "<li>Utiliser <strong>name=&quot;metier&quot;</strong> pour la liste de choix de la Question 2.</li>";
    $errors = true;
  }

  if (!isset($_POST['fruits'])) {
    echo "<li>Répondre à la Question 3.<br>Utiliser <strong>name=&quot;fruits[]&quot;</strong> pour les cases à cocher de la Question 3.</li>";
    $errors = true;
  }

  if (!isset($_POST['nom_fille'])) {
    echo "<li>Utiliser <strong>name=&quot;nom_fille&quot;</strong> pour le 1<sup>er</sup> champs de texte de la Question 4.</li>";
    $errors = true;
  }

  if (!isset($_POST['age_fille'])) {
    echo "<li>Utiliser <strong>name=&quot;age_fille&quot;</strong> pour le 2<sup>ème</sup> champs de texte de la Question 4.</li>";
    $errors = true;
  }

  if (!isset($_POST['phrases'])) {
    echo "<li>Utiliser <strong>name=&quot;phrases&quot;</strong> pour la zone de texte de la Question 5.</li>";
    $errors = true;
  }

  if (!isset($_POST['fete'])) {
    echo "<li>Répondre à la Question 6.<br>Utiliser <strong>name=&quot;fete&quot;</strong> pour les boutons radio de la Question 6.</li>";
    $errors = true;
  }

  echo "</ul>";

  if ($errors) {
    echo "<p>Corriger l'ensemble des erreurs indiquées avant de poursuivre.</p>";
    die();
  }

  $genre = $_POST['genre'];
  $metier = $_POST['metier'];
  $fruits = $_POST['fruits'];
  $nom_fille = $_POST['nom_fille'];
  $age_fille = intval($_POST['age_fille']);
  $phrases = $_POST['phrases'];
  $fete = $_POST['fete'];

  $note = 6;
  if ($genre != 'Garçon') {
    echo "<p>Réponse Question 1 : <strong>Garçon</strong></p>";
    $note--;
  }

  if ($metier != 'Boucher') {
    echo "<p>Réponse Question 2 : <strong>Boucher</strong></p>";
    $note--;
  }

  if (count($fruits) != 6) {
    echo "<p>Réponse Question 3 : Tous les fruits existent dans l'image : Orange, Raisin, Ananas, Grenade, Banane, Pastèque ) </p>";
    $note--;
  }

  if ($nom_fille != 'Siwar') {
    echo "<p>Réponse Question 4 : La fille s'appelle <strong>Siwar</strong></p>";
    $note--;
  }

  if ($age_fille != 2) {
    echo "<p>Réponse Question 4 : La fille a <strong>2</strong> ans</p>";
    $note--;
  }

  if ($phrases != "Sami est le plus âgé.\r\nSiwar est la moins âgée.") {
    echo "<p>Réponse Question 5 : <pre><strong>Sami est le plus âgé.\r\nSiwar est la moins âgée.</strong></pre></p>";
    $note--;
  }

  if ($fete != 'Anniversaire') {
    echo "<p>Réponse Question 6 : <strong>Anniversaire</strong></p>";
    $note--;
  }

  echo "<h2>Note finale : $note / 6</h2>";

  if ($note != 6) {
    echo "<p>Corriger votre formulaire ou vos réponses et vérifiez une nouvelle fois.</p>";
  }
  ?>
</body>

</html>