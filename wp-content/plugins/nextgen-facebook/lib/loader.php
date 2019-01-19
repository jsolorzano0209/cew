<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2018 Jean-Sebastien Morisset (https://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'NgfbLoader' ) ) {

	class NgfbLoader {

		private $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->mod_load();
		}

		private function mod_load( $has_action = false ) {

			if ( is_admin() ) {
				// save time on known admin pages we don't modify
				switch ( basename( $_SERVER['PHP_SELF'] ) ) {
					case 'index.php':		// Dashboard
					case 'edit-comments.php':	// Comments
					case 'themes.php':		// Appearance
					case 'plugins.php':		// Plugins
					case 'tools.php':		// Tools
						if ( $this->p->debug->enabled ) {
							$this->p->debug->log( 'no modules required for current page' );
						}
						return;
				}
			}

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark( 'load modules' );	// begin timer
				if ( $has_action ) {
					$this->p->debug->log( 'loading modules for action '.$has_action );
				}
			}

			foreach ( $this->p->cf['plugin'] as $ext => $info ) {

				$type = $this->p->check->aop( $this->p->lca, true, $this->p->avail['*']['p_dir'] ) &&
					$this->p->check->aop( $ext, true, NGFB_UNDEF_INT ) === NGFB_UNDEF_INT ? 'pro' : 'gpl';

				if ( ! isset( $info['lib'][$type] ) ) {
					if ( $this->p->debug->enabled ) {
						$this->p->debug->log( $ext.' lib/'.$type.' not defined' );
					}
					continue;
				}

				foreach ( $info['lib'][$type] as $sub => $libs ) {

					$log_prefix = 'loading '.$ext.' '.$type.'/'.$sub.': ';

					if ( $sub === 'admin' ) {
						if ( ! is_admin() ) {	// load admin sub-folder only in back-end
							if ( $this->p->debug->enabled ) {
								$this->p->debug->log( $log_prefix.'ignored - not in admin back-end' );
							}
							continue;
						} elseif ( $type === 'gpl' && ! empty( $this->p->options['plugin_hide_pro'] ) ) {
							if ( $this->p->debug->enabled ) {
								$this->p->debug->log( $log_prefix.'ignored - pro features hidden' );
							}
							continue;
						}
					}

					foreach ( $libs as $id_key => $label ) {

						/**
						 * Example:
						 *	'article' => 'Item Type Article',
						 *	'article#news:no_load' => 'Item Type NewsArticle',
						 *	'article#tech:no_load' => 'Item Type TechArticle',
						 */
						list( $id, $stub, $action ) = SucomUtil::get_lib_stub_action( $id_key );

						$log_prefix = 'loading '.$ext.' '.$type.'/'.$sub.'/'.$id.': ';

						if ( $this->p->avail[$sub][$id] ) {

							/**
							 * Compare $action from library id with $has_action method argument.
							 * This is usually / almost always a false === false comparison.
							 */
							if ( $action !== $has_action ) {
								if ( $this->p->debug->enabled ) {
									$this->p->debug->log( $log_prefix.'ignored for action '.$has_action );
								}
								continue;
							}

							$lib_path = $type.'/'.$sub.'/'.$id;
							$classname = apply_filters( $ext.'_load_lib', false, $lib_path );

							if ( is_string( $classname ) ) {

								if ( class_exists( $classname ) ) {

									if ( $ext === $this->p->lca ) {
										if ( $this->p->debug->enabled ) {
											$this->p->debug->log( $log_prefix.'new library module for '.$classname );
										}
										if ( ! isset( $this->p->m[$sub][$id] ) ) {
											$this->p->m[$sub][$id] = new $classname( $this->p );
										} elseif ( $this->p->debug->enabled ) {
											$this->p->debug->log( $log_prefix.'library module already defined' );
										}
									} elseif ( ! isset( $this->p->m_ext[$ext][$sub][$id] ) ) {
										$this->p->m_ext[$ext][$sub][$id] = new $classname( $this->p );
									} elseif ( $this->p->debug->enabled ) {
										$this->p->debug->log( $log_prefix.'library ext module already defined' );
									}

								} elseif ( $this->p->debug->enabled ) {
									$this->p->debug->log( $log_prefix.'library class "'.$classname.'" is missing' );
								}

							} elseif ( $this->p->debug->enabled ) {
								$this->p->debug->log( $log_prefix.'library file "'.$lib_path.'" not found' );
							}

						} elseif ( $this->p->debug->enabled ) {
							$this->p->debug->log( $log_prefix.'avail is false' );
						}
					}
				}
			}

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark( 'load modules' );	// end timer
			}
		}
	}
}
