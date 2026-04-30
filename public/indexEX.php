<?php
$x = 5;
 
echo $x . '<br>';
 
var_dump($x);
 
$s = 'Meireles Guilherma da Silva';
echo '<br>';
var_dump($s);
echo '<br>';
for ($x = 0; $x <= 10; $x++) {
  echo "The number is: $x <br>";
}
 
$carros = ["Mercedes", "Audi", "Tata"];
 
 
var_dump($carros);
 
echo ('<br>' . $carros[2] . '<br>');
 
// DECLARA UM arrau das 3 melhores equipas de futebol em pt
$equipas = ["FCP", "FCP", "FCP"];
 
$comidas = [
  "Bacalhá à Braz",
  "Souflé de bacalhau",
  "Pastel de bacalhau",
  "Bacalhau à braga",
  "Bacalhau à Gomes",
  "Bacalhua com natas",
  "Bacalhua à zé"
];
 
// Verificar par
$isEven = (3 % 2) == 0;
 
var_dump($isEven);
 
 
 
 
?>
<!DOCTYPE html>
<html lang="en">
 
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $carros[1]; ?></title>
 
  <style>
    .activo {
      background-color: green;
      color: red;
    }
 
    .inactivo {
      background-color: red;
      color: green;
    }

    .bold {
      font-weight: bold;
    }

    .italic {
      font-style: italic;
    }
  </style>
 
 
</head>
 
<body>
  <p style="color: #275EF5; font-size: 3em;">
    <?php
    echo $x;
    ?>
  </p>
 
  <p style="color: #275EF5; font-size: 3em;">
    <?= $x; ?>
  </p>
 
  <h1>
    For
  </h1>
  <?php for ($x = 0; $x <= 10; $x++): ?>
    <h2>OLha <?= $x; ?> o html dentro do for</h2>
 
  <?php endfor ?>
 
  <h1>Carros</h1>
 
  <?php for ($ic = 0; $ic < count($carros); $ic++): ?>
 
    <p><?= $carros[$ic] ?></p>
 
  <?php endfor ?>
 
  <!-- Iterar e imprimir o arrau com as 3 melhores equipas de futebol em pt -->
  <?php for ($ie = 0; $ie < count($equipas); $ie++): ?>
 
    <p>
      <span style="background-color: black;color:#FFF;"><?= $ie ?></span>
      <?= $equipas[$ie] ?>
    </p>
 
  <?php endfor ?>
 
 
 
  <?php for ($icomidas = 0; $icomidas < count($comidas); $icomidas++): ?>
 
    <p>
      <?php if (($icomidas % 2) == 0): ?>
        <span style="background-color: green;color:#FFF;"><?= $icomidas ?></span>
      <?php else: ?>
        <span style="background-color: red;color:#FFF;"><?= $icomidas ?></span>
 
      <?php endif ?>
 
 
 
 
      <?= $comidas[$icomidas] ?>
    </p>
 
  <?php endfor ?>
 
 
 
  <?php for ($icomidas2 = 0; $icomidas2 < count($comidas); $icomidas2++): ?>
 
    <p>
     
        <span class="<?= ($icomidas2 % 2) == 0 ? 'activo' : 'inactivo' ?>"><?= $icomidas2 ?></span>
 
 
 
 
      <?= $comidas[$icomidas2] ?>
    </p>
 
  <?php endfor ?>

  <?php for ($icomidas2 = 0; $icomidas2 < count($comidas); $icomidas2++): ?>
 
    <p>
     
        <span class="<?= ($icomidas2 % 2) == 0 ? 'bold' : 'italic' ?>"><?= $icomidas2 ?></span>
 
 
 
 
      <?= $comidas[$icomidas2] ?>
    </p>
 
  <?php endfor ?>
</body>
 
</html>