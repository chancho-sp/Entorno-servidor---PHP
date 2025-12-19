<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Desarrollo web en entorno servidor - MVC</title>
	
	<link rel="stylesheet" href="<?php echo 'css/'.Config::$mvc_vis_css ?>"/>
</head>
<body>
	<header>
		<h1>Información de alimentos</h1>
	</header>

	<nav class="nav">
		<ul class="nav-links">
			<li><a href="index.php?ctl=inicio">Inicio</a></li>
			<li><a href="index.php?ctl=listar">Ver alimentos</a></li>
			<li><a href="index.php?ctl=insertar">Insertar alimento</a></li>
			<li><a href="index.php?ctl=buscar">Buscar por nombre</a></li>
			<li><a href="index.php?ctl=buscarEnergia">Buscar por energía</a></li>
			<li><a href="index.php?ctl=buscarAlimentosCombinada">Búsqueda combinada</a></li>
		</ul>
	 </nav>

	<main>
		<?php echo $contenido ?>
	</main>

	<footer id="pie"> - Desarrollo Web en Entorno Servidor -</footer>
</body>
</html>