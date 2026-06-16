<input type="hidden" name="rol" id="rol" value="<?php echo $_SESSION['rol']; ?>">
<aside class="main-sidebar sidebar-open">

	<section class="sidebar">
		<ul class="sidebar-menu">

			<?php if ($_SESSION['rol'] == "Administrador") { ?>
				<li class="header">MENÚ DE NAVEGACIÓN</li>
			<?php } ?>
			<li>
				<a href="categoria">
					<i class="fa fa-tags"></i>
					<span>Categorías</span>
				</a>
			</li>

			<li>
				<a href="producto">
					<i class="fa fa-cube"></i>
					<span>Producto</span>
				</a>
			</li>

			<li>
				<a href="index.php?ruta=inicio">
					<i class="fa fa-home"></i> Inicio
				</a>
			</li>

			<!--Configuraciones-->
			<li class="treeview">
				<a href="#">
					<i class="fa fa-cog"></i>
					<span>Configuraciones</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu mantenedor-scroll">
					<li><a href="actualizacionDatos"><i class="fa fa-key"></i>Actualizar Mis Datos</a></li>
					<li><a href="usuario"><i class="fa fa-bell-o"></i>Gestión de Usuarios</a></li>
				</ul>
			</li>
			<li><a href="salir"><i class="fa fa-sign-out text-red"></i> <span>Cerrar Sesión</span></a></li>
		</ul>

	</section>
</aside>

<style>
	.ui-autocomplete {
		height: 280px;
		overflow-y: scroll;
		overflow-x: hidden;
	}

	.ui-autocomplete {
		z-index: 2147483647;
	}

	.mantenedor-scroll {
		max-height: 300px;
		overflow-y: auto;
		scrollbar-width: thin;
		scrollbar-color: hsl(0, 0.60%, 35.10%);
	}

	.mantenedor-scroll {
		max-height: 300px;
		overflow-y: auto;
		scrollbar-width: none;
	}

	.mantenedor-scroll::-webkit-scrollbar {
		width: 0px;
		background: transparent;
	}

	.user-panel-custom {
		text-align: center;
		padding: 15px 10px;
	}

	.user-panel-custom .user-name {
		margin: 0;
		font-weight: 700;
		font-size: 14px;
		color: #ffffff;
		word-break: break-word;
	}

	.user-panel-custom .user-status {
		font-size: 12px;
		margin-top: 5px;
		color: #b8c7ce;
	}

	.sidebar-collapse .user-panel-custom {
		display: none !important;
	}
</style>