<?php
	$lista_pokemon = null;
	$datos_reales = null;

	if (isset($_GET['region'])) {
		$url_species = $_GET['region'];
		$pokemon_json = file_get_contents($url_species);
		$lista_pokemon = json_decode($pokemon_json);
	}

	else if (isset($_GET['species'])) {
		$url_species = $_GET['species'];
		$pokemon_json = file_get_contents($url_species);
		$datos_pokemon = json_decode($pokemon_json);
		foreach ($datos_pokemon->varieties as $variety) {
			if ($variety->is_default) {
				$url_real = $variety->pokemon->url;
				break;
			}
		}
		$json_real = file_get_contents($url_real);
		$datos_reales = json_decode($json_real);
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pokemon</title>
	<link rel="stylesheet" type="text/css" href="examen.css">
</head>
<body>
 
<header> Mi blog de &nbsp;&nbsp; <img src="img/International_Pokémon_logo.svg.png"></header>

<div></div>

<nav>
	<strong>
		<a href="?region=https://pokeapi.co/api/v2/pokedex/2/">G1 Kanto</a> &nbsp;&nbsp;
		<a href="?region=https://pokeapi.co/api/v2/pokedex/3/">G2 Johto</a> &nbsp;&nbsp;
		<a href="?region=https://pokeapi.co/api/v2/pokedex/4/">G3 Hoenn</a>  &nbsp;&nbsp;
		<a href="?region=https://pokeapi.co/api/v2/pokedex/5/">G4 Sinnoh</a>  &nbsp;&nbsp;
		<a href="?region=https://pokeapi.co/api/v2/pokedex/8/">G5 Unova</a>  &nbsp;&nbsp;
		<a href="?region=https://pokeapi.co/api/v2/pokedex/10/">G6 Kalos</a>  &nbsp;&nbsp;
		<a href="?region=https://pokeapi.co/api/v2/pokedex/14/">G7 Alola</a> &nbsp;&nbsp;
		<a href="?region=https://pokeapi.co/api/v2/pokedex/27/">G8 Galar</a> &nbsp;&nbsp;
		<a href="?region=https://pokeapi.co/api/v2/pokedex/31/">G9 Paldea</a> &nbsp;&nbsp; 
		<a href= "buscar.php">Búsqueda</a>
	</strong> 
</nav>

<div id="iniciales">
	<?php
	if ($datos_reales):
		// Mostrar información del Pokémon
	?>
		<img src="<?= $datos_reales->sprites->other->{'official-artwork'}->front_default ?>" 
			alt="<?= $datos_reales->name ?>" style="width:200px; display: block; margin:0 auto;">
		<h1 style="text-align: center;"><?= ucfirst($datos_reales->name) ?></h1>

		<h2>Movimientos aprendidos por nivel</h2>
		<table style="width:100%; border-collapse: collapse;">
			<tr>
				<th style="border:1px solid #ccc; padding:5px;">Movimiento</th>
				<th style="border:1px solid #ccc; padding:5px;">Nivel</th>
			</tr>

			<?php
			$movimientos = [];

			// Filtrar movimientos con nivel > 0
			foreach ($datos_reales->moves as $move_entry) {
				$nivel = 0;
				foreach ($move_entry->version_group_details as $detail) {
					if ($detail->level_learned_at > 0) {
						$nivel = $detail->level_learned_at;
						break;
					}
				}

				if ($nivel > 0) {
					$movimientos[] = [
						'name' => $move_entry->move->name,
						'level' => $nivel
					];
				}
			}

			// Ordenar por nivel ascendente
			usort($movimientos, function($a, $b) {
				return $a['level'] - $b['level'];
			});

			// Mostrar movimientos en la tabla
			foreach ($movimientos as $m) {
				echo "<tr>";
				echo "<td style='border:1px solid #ccc; padding:5px;'>" . ucfirst($m['name']) . "</td>";
				echo "<td style='border:1px solid #ccc; padding:5px;'>" . $m['level'] . "</td>";
				echo "</tr>";
			}
			?>
		</table>

	<?php elseif ($lista_pokemon): ?>

		<table style="width:100%; border-collapse:collapse; text-align:center;">
			<tr>
			<?php
			// Ordenar por entry_number
			usort($lista_pokemon->pokemon_entries, function($a, $b){
				return $a->entry_number - $b->entry_number;
			});

			$col = 0;

			foreach ($lista_pokemon->pokemon_entries as $entry) {

				$numero = $entry->entry_number;
				$nombre = $entry->pokemon_species->name;
				$species_url = $entry->pokemon_species->url;

				echo "<td style='border:1px solid #ccc; padding:10px;'>
						<strong>$numero</strong><br>
						<a href='index.php?species=$species_url'>" . ucfirst($nombre) . "</a>
					</td>";

				$col++;

				if ($col % 5 == 0) {
					echo "</tr><tr>";
				}
			}

			if ($col % 5 != 0) echo "</tr>";
			?>
		</table>

	<?php endif; ?>
</div>

<div class="abajo">
	
</div>

<footer> Trabajo &nbsp;<strong> Desarrollo Web en Entorno Servidor </strong>&nbsp; 2023/2024 IES Serra Perenxisa.</footer>

</body>
</html>