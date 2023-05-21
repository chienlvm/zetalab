<?php

/**
 * Displays the site header.
 *
 * @package WordPress
 * @subpackage ChienLVM
 * @since v1.0
 * @author chienlvm
 */

$wrapper_classes  = 'site-header';
$wrapper_classes .= has_custom_logo() ? ' has-logo' : '';
$wrapper_classes .= ( true === get_theme_mod( 'display_title_and_tagline', true ) ) ? ' has-title-and-tagline' : '';
$wrapper_classes .= has_nav_menu( 'primary' ) ? ' has-menu' : '';
?>

<header id="masthead" class="<?php echo esc_attr($wrapper_classes); ?>">
	<!-- <nav id="js-header" class="navbar navbar-expand-lg fixed-top navbar-custom sticky sticky-dark" id="navbar">
		<div class="container">
			<a class="navbar-brand logo text-uppercase" href="/">
				<i class="mdi mdi-alien"></i>Zata bim
			</a>
			<button class="navbar-toggler js-toggleMenu" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<i class="mdi mdi-menu"></i>
			</button>
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="navbar-nav navbar-center" id="mySidenav">
					<li class="nav-item">
						<a class="nav-link">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link">Services</a>
					</li>
					<li class="nav-item">
						<a class="nav-link">Tutorial</a>
					</li>
					<li class="nav-item">
						<a class="nav-link">Price</a>
					</li>
					<li class="nav-item">
						<a class="nav-link">About</a>
					</li>
					<li class="nav-item">
						<a class="nav-link">Contact</a>
					</li>
				</ul>
				<div class="nav-button ml-auto">
					<ul class="nav navbar-nav navbar-right">
						<li>
							<button type="button" class="btn btn-custom navbar-btn btn-rounded">Try it</button>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav> -->
</header>