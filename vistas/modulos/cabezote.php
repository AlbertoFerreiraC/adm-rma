<header class="main-header header-modern">

	<a href="#" class="logo logo-usuario">

		<span class="logo-lg usuario-header">
			<?php echo strtoupper($_SESSION["nombre"]); ?>

			<small class="usuario-status">
				<i class="fa fa-circle"></i> EN SESIÓN
			</small>
		</span>

	</a>

	<nav class="navbar navbar-static-top navbar-modern" role="navigation">

		<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>

	</nav>

</header>

<style>
	/* ===== HEADER GENERAL ===== */
	.header-modern {
		background: linear-gradient(90deg, #00c6ff, #0072ff) !important;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
	}

	/* ===== NAVBAR ===== */
	.navbar-modern {
		background: transparent !important;
		border: none !important;
	}

	/* ===== LOGO USUARIO ===== */
	.logo-usuario {
		background: transparent !important;
		color: #ffffff !important;
		height: 50px !important;
		padding: 0 !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
	}

	/* ===== TEXTO USUARIO ===== */
	.usuario-header {
		font-weight: 700;
		font-size: 14px;
		letter-spacing: 1px;
		display: flex;
		flex-direction: column;
		align-items: center;
		line-height: 1.1;
	}

	/* ===== STATUS ===== */
	.usuario-status {
		font-size: 11px;
		margin-top: 3px;
		color: rgba(255, 255, 255, 0.85);
	}

	.usuario-status i {
		font-size: 8px;
		margin-right: 4px;
		color: #00ff90;
	}

	/* ===== SIDEBAR TOGGLE ===== */
	.sidebar-toggle {
		color: white !important;
	}

	.sidebar-toggle:hover {
		background: rgba(255, 255, 255, 0.15) !important;
	}

	/* ===== RESPONSIVE ===== */
	@media (max-width: 768px) {
		.usuario-header {
			font-size: 12px;
		}
	}
</style>