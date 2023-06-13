<?php
/**
 * Plugin Name: Mirai Panino Giusto
 * Description: Gallery categorie e forse altro
 * Plugin URI:  https://cosmo.cat
 * Version:     2.9
 * Author:      Nicola Cavallazzi
 * Author URI:  https://cosmo.cat/
 * Text Domain: mirai-panino-giusto
 */


if (!defined('ABSPATH'))
  exit; // Exit if accessed directly

add_action('elementor/elements/categories_registered', function ($elements_manager) {
  $elements_manager->add_category(
    'mirai-panino-giusto',
    [
      'title' => __('Panino Giusto', 'mirai-panino-giusto'),
      'icon' => 'fa fa-plug',
    ]
  );
});

add_action('elementor/widgets/widgets_registered', function () {
  require_once(__DIR__ . '/widgets/category-gallery.php');
  \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Category_Gallery());
  require_once(__DIR__ . '/widgets/stores-map.php');
  \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Stores_Map());
  require_once(__DIR__ . '/widgets/opening-times.php');
  \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Opening_Times_Table());
  require_once(__DIR__ . '/widgets/category-showcase.php');
  \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Category_Showcase());
  require_once(__DIR__ . '/widgets/panino-carousel.php');
  \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Panino_Carousel());
});

if (is_admin()) {
  add_action('admin_enqueue_scripts', function () {
    wp_enqueue_script('panino-admin-js', plugins_url('/assets/admin.js', __FILE__), array('jquery'));
  });
}