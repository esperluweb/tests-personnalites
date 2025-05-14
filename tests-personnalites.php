<?php
/**
 * Plugin Name: Tests Personnalités
 * Plugin URI: https://github.com/esperluweb/tests-personnalites
 * Description: Une petite extension qui ajoute la possibilité de créer des test de personnalités
 * Version: 1.1.0
 * Author: Grégoire Boisseau / EsperluWeb
 * Author URI: https://github.com/esperluweb
 * License: GPL2
 */

// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) exit;

// Organisation modulaire du plugin
require_once __DIR__.'/includes/cpt-personality-test.php';
require_once __DIR__.'/includes/metabox-personality-test.php';
require_once __DIR__.'/includes/shortcode-personality-test.php';