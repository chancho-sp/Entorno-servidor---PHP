<?php
$resultado = null;
$error = "";

// Recoger parámetros del formulario
$nombre = $_GET["nombre"] ?? "";
$tipo   = $_GET["tipo"] ?? "";
$region = $_GET["region"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "GET" && $nombre !== "") {

    // --- 1. Buscar por nombre ---
    $url = "https://pokeapi.co/api/v2/pokemon/" . strtolower($nombre);
    $json = @file_get_contents($url);

    if (!$json) {
        $error = "No se encontró ningún Pokémon con el nombre '$nombre'.";
    } else {
        $poke = json_decode($json);

        // --- 2. Comprobar tipo ---
        if ($tipo !== "") {
            $tipos = array_map(function($t){ return strtolower($t->type->name); }, $poke->types);
            if (!in_array(strtolower($tipo), $tipos)) {
                $error = "El Pokémon '$nombre' no es del tipo '$tipo'.";
            }
        }

        // --- 3. Comprobar región ---
        if ($error === "" && $region !== "") {
            $pokedex_json = @file_get_contents($region);
            if ($pokedex_json) {
                $region_data = json_decode($pokedex_json);

                $found = false;
                foreach ($region_data->pokemon_entries as $entry) {
                    if (strtolower($entry->pokemon_species->name) === strtolower($nombre)) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $error = "El Pokémon '$nombre' no pertenece a la región seleccionada.";
                }
            } else {
                $error = "Error al cargar la región.";
            }
        }

        if ($error === "") {
            $species_url = "https://pokeapi.co/api/v2/pokemon-species/" . $poke->id . "/";
            $resultado = [
                "nombre" => ucfirst($poke->name),
                "img"    => $poke->sprites->front_default,
                "url"    => "index.php?species=$species_url"
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pokemon - Búsqueda</title>
	<link rel="stylesheet" type="text/css" href="examen.css">
</head>
<body>

<header> Mi blog de &nbsp;&nbsp; <img src="img/International_Pokémon_logo.svg.png"></header>

<nav>
	<strong>
		<a href="index.php?region=https://pokeapi.co/api/v2/pokedex/2/">G1 Kanto</a> &nbsp;&nbsp;
		<a href="index.php?region=https://pokeapi.co/api/v2/pokedex/3/">G2 Johto</a> &nbsp;&nbsp;
		<a href="index.php?region=https://pokeapi.co/api/v2/pokedex/4/">G3 Hoenn</a>  &nbsp;&nbsp;
		<a href="index.php?region=https://pokeapi.co/api/v2/pokedex/5/">G4 Sinnoh</a>  &nbsp;&nbsp;
		<a href="index.php?region=https://pokeapi.co/api/v2/pokedex/8/">G5 Unova</a>  &nbsp;&nbsp;
		<a href="index.php?region=https://pokeapi.co/api/v2/pokedex/10/">G6 Kalos</a>  &nbsp;&nbsp;
		<a href="index.php?region=https://pokeapi.co/api/v2/pokedex/14/">G7 Alola</a> &nbsp;&nbsp;
		<a href="index.php?region=https://pokeapi.co/api/v2/pokedex/27/">G8 Galar</a> &nbsp;&nbsp;
		<a href="index.php?region=https://pokeapi.co/api/v2/pokedex/31/">G9 Paldea</a> &nbsp;&nbsp; 
		<a href="buscar.php">Búsqueda</a>
	</strong> 
</nav>

<!-- FORMULARIO -->
<div id="iniciales" style="padding:20px;">
	<h2>Buscar Pokémon</h2>
	<form method="GET" action="buscar.php">

		<label><strong>Nombre:</strong></label><br>
		<input type="text" name="nombre" placeholder="Ej: pikachu" value="<?= htmlspecialchars($nombre) ?>"><br><br>

		<label><strong>Tipo:</strong></label><br>
		<select name="tipo">
			<option value="">-- Seleccionar tipo --</option>
			<?php 
			$tipos = [
				"normal","fire","water","electric","grass","ice","fighting","poison",
				"ground","flying","psychic","bug","rock","ghost","dragon","dark",
				"steel","fairy"
			];
			foreach ($tipos as $t):
			?>
				<option value="<?= $t ?>" <?= ($tipo == $t ? "selected" : "") ?>>
					<?= ucfirst($t) ?>
				</option>
			<?php endforeach; ?>
		</select>
		<br><br>

		<label><strong>Región:</strong></label><br>
		<select name="region">
			<option value="">-- Seleccionar región --</option>
			<option value="https://pokeapi.co/api/v2/pokedex/2/"  <?= $region == "https://pokeapi.co/api/v2/pokedex/2/"  ? "selected" : "" ?>>Kanto</option>
			<option value="https://pokeapi.co/api/v2/pokedex/3/"  <?= $region == "https://pokeapi.co/api/v2/pokedex/3/"  ? "selected" : "" ?>>Johto</option>
			<option value="https://pokeapi.co/api/v2/pokedex/4/"  <?= $region == "https://pokeapi.co/api/v2/pokedex/4/"  ? "selected" : "" ?>>Hoenn</option>
			<option value="https://pokeapi.co/api/v2/pokedex/5/"  <?= $region == "https://pokeapi.co/api/v2/pokedex/5/"  ? "selected" : "" ?>>Sinnoh</option>
			<option value="https://pokeapi.co/api/v2/pokedex/8/"  <?= $region == "https://pokeapi.co/api/v2/pokedex/8/"  ? "selected" : "" ?>>Unova</option>
			<option value="https://pokeapi.co/api/v2/pokedex/10/" <?= $region == "https://pokeapi.co/api/v2/pokedex/10/" ? "selected" : "" ?>>Kalos</option>
			<option value="https://pokeapi.co/api/v2/pokedex/14/" <?= $region == "https://pokeapi.co/api/v2/pokedex/14/" ? "selected" : "" ?>>Alola</option>
			<option value="https://pokeapi.co/api/v2/pokedex/27/" <?= $region == "https://pokeapi.co/api/v2/pokedex/27/" ? "selected" : "" ?>>Galar</option>
			<option value="https://pokeapi.co/api/v2/pokedex/31/" <?= $region == "https://pokeapi.co/api/v2/pokedex/31/" ? "selected" : "" ?>>Paldea</option>
		</select><br><br>

		<button type="submit">Buscar</button>
	</form>
	<!-- RESULTADOS -->
	<?php if ($error !== ""): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php elseif ($resultado !== null): ?>
        <table style="width:100%; text-align:center; border-collapse:collapse; margin-top:20px;">
            <tr>
                <th>Imagen</th>
                <th>Pokémon</th>
                <th>Detalles</th>
            </tr>
            <tr>
                <td>
                    <?php if ($resultado["img"]): ?>
                        <img src="<?= $resultado["img"] ?>" style="width:80px;">
                    <?php else: ?>
                        <i>Sin imagen</i>
                    <?php endif; ?>
                </td>
                <td><?= $resultado["nombre"] ?></td>
                <td><a href="<?= $resultado["url"] ?>">Ver más</a></td>
            </tr>
        </table>
    <?php endif; ?>
</div>


<div class="abajo">
</div>

<footer> Trabajo &nbsp;<strong>Desarrollo Web en Entorno Servidor</strong>&nbsp; 2023/2024 IES Serra Perenxisa.</footer>

</body>
</html>
