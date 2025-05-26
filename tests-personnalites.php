<?php
/**
 * Plugin Name: Tests Personnalités
 * Plugin URI: https://github.com/esperluweb/tests-personnalites
 * Description: Une petite extension qui ajoute la possibilité de créer des test de personnalités
 * Version: 1.2.0
 * Author: Grégoire Boisseau / EsperluWeb
 * Author URI: https://github.com/esperluweb
 * License: GPL2
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) exit;

// Organisation modulaire du plugin
require_once __DIR__.'/includes/cpt-personality-test.php';
require_once __DIR__.'/includes/metabox-personality-test.php';
require_once __DIR__.'/includes/shortcode-personality-test.php';