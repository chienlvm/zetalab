<?php

/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> <?php twentytwentyone_the_html_classes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500&family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">

	<script>
		window.__id = '<?php
										$session = new SecurityIdManager();
										echo $session->getSecurityId();
										?>'
	</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<?php get_template_part('template-parts/header/site-header'); ?>

		<div id="content" class="site-content">
			<div id="primary" class="content-area">
				<main id="main" class="site-main">
					<?php
					if (!is_page('sign-in')) { ?>
						<div id="app">
							<div class="bg-account-pages py-4 py-sm-0">
								<div class="account-home-btn d-none d-sm-block">
									<a href="/" class="text-white">
										<i class="mdi mdi-home h1"></i>
									</a>
								</div>
								<section class="height-100vh">
									<div class="display-table">
										<div class="display-table-cell">
											<div class="container">
												<div class="row justify-content-center">
													<div class="col-lg-5">
														<div class="card account-card">
															<div class="card-body">
																<div class="text-center mt-3">
																	<h3 class="font-weight-bold">
																		<a href="/" class="text-dark text-uppercase account-pages-logo">
																			<i class="mdi mdi-alien"></i>Hiric
																		</a>
																	</h3>
																	<p class="text-muted">Sign up for a new Account</p>
																</div>
																<div class="p-3">
																	<form>
																		<div class="form-group">
																			<label for="firstname">First Name</label>
																			<input type="text" class="form-control" id="firstname" placeholder="First Name" />
																		</div>

																		<div class="form-group">
																			<label for="email">Email</label>
																			<input type="password" class="form-control" id="email" placeholder="Enter Email" />
																		</div>

																		<div class="form-group">
																			<label for="userpassword">Password</label>
																			<input type="password" class="form-control" id="userpassword" placeholder="Enter password" />
																		</div>

																		<div class="custom-control custom-checkbox">
																			<input type="checkbox" class="custom-control-input" id="customControlInline" />
																			<label class="custom-control-label" for="customControlInline">Remember me</label>
																		</div>

																		<div class="mt-3">
																			<button type="submit" class="btn btn-custom btn-block">Sign in</button>
																		</div>

																		<div class="mt-4 mb-0 text-center">
																			<p class="mb-0">
																				Don't have an account ?
																				<router-link tag="a" to="/login" class="text-danger">Sign in</router-link>
																			</p>
																		</div>
																	</form>
																</div>
															</div>
														</div>
														<!-- end card -->
													</div>
													<!-- end col -->
												</div>
												<!-- end row -->
											</div>
										</div>
									</div>
								</section>
								<!-- end account-pages  -->
							</div>
						</div>
					<?php
					}
					?>