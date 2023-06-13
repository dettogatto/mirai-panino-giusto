<?php
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly

/**
 * Elementor Post Filter
 *
 * Elementor widget for Post Filter.
 */
class Stores_Map extends \Elementor\Widget_Base
{

  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);

    wp_register_script('stores-map-js', plugins_url('/assets/stores-map.js', __DIR__), ['jquery'], null, true);
    wp_register_style('stores-map-css', plugins_url('/assets/stores-map.css', __DIR__));
  }

  /**
   * Retrieve the widget name.
   *
   * @access public
   *
   * @return string Widget name.
   */
  public function get_name()
  {
    return 'stores-map';
  }

  /**
   * Retrieve the widget title.
   *
   * @access public
   *
   * @return string Widget title.
   */
  public function get_title()
  {
    return __('Stores Map', 'mirai-panino-giusto');
  }

  /**
   * Retrieve the widget icon.
   *
   * @access public
   *
   * @return string Widget icon.
   */
  public function get_icon()
  {
    return 'eicon-google-maps';
  }

  /**
   * Retrieve the list of categories the widget belongs to.
   *
   * Used to determine where to display the widget in the editor.
   *
   * Note that currently Elementor supports only one category.
   * When multiple categories passed, Elementor uses the first one.
   *
   * @access public
   *
   * @return array Widget categories.
   */
  public function get_categories()
  {
    return ['mirai-panino-giusto'];
  }

  /**
   * Retrieve the list of scripts the widget depended on.
   *
   * Used to set scripts dependencies required to run the widget.
   *
   * @access public
   *
   * @return array Widget scripts dependencies.
   */
  public function get_script_depends()
  {
    return ['stores-map-js'];
  }

  /**
   * Retrieve the list of styles the widget depended on.
   *
   * Used to set styles dependencies required to run the widget.
   *
   * @access public
   *
   * @return array Widget styles dependencies.
   */
  public function get_style_depends()
  {
    return ['stores-map-css'];
  }

  /**
   * Register the widget controls.
   *
   * Adds different input fields to allow the user to change and customize the widget settings.
   *
   * @access protected
   */
  protected function register_controls()
  {

    $this->start_controls_section(
      'section_content',
      [
        'label' => __('Content', 'mirai-panino-giusto'),
      ]
    );

    $this->add_control(
      'show_menu',
      [
        'label' => esc_html__('Show Menu', 'mirai-panino-giusto'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Show', 'mirai-panino-giusto'),
        'label_off' => esc_html__('Hide', 'mirai-panino-giusto'),
        'return_value' => 'yes',
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'single_store',
      [
        'label' => esc_html__('Single Store Mode', 'mirai-panino-giusto'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('ON', 'mirai-panino-giusto'),
        'label_off' => esc_html__('OFF', 'mirai-panino-giusto'),
        'return_value' => 'yes',
        'default' => '',
      ]

    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_style',
      [
        'label' => __('Style', 'mirai-panino-giusto'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'background_color_form',
      [
        'label' => esc_html__('Background Form', 'mirai-panino-giusto'),
        'name' => 'background_color_form',
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} #stores-map-menu .stores-map-form' => 'background-color: {{VALUE}};',
        ],
      ]
    );
    $this->add_control(
      'background_color_ristoranti',
      [
        'label' => esc_html__('Background Ristoranti', 'mirai-panino-giusto'),
        'name' => 'background_color_ristoranti',
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} #stores-map-list .ristorante' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'background_color_togo',
      [
        'label' => esc_html__('Background TO-GO', 'mirai-panino-giusto'),
        'name' => 'background_color_togo',
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} #stores-map-list .ristorante[data-type*="to-go"]' => 'background-color: {{VALUE}};',
        ],
      ]
    );


    $this->end_controls_section();


  }



  /**
   * Render the widget output on the frontend.
   *
   * Written in PHP and used to generate the final HTML.
   *
   * @access protected
   */
  protected function render()
  {
    $settings = $this->get_settings_for_display();
    $singleStoreMode = $settings['single_store'] == 'yes';
    $current_term = get_queried_object();

    $ristoranti = [];
    $ristorantiTypes = get_terms([
      'taxonomy' => 'categoria_stores',
      'hide_empty' => true,
    ]);
    $posts = [];

    if ($singleStoreMode) {
      $post_id = get_the_ID();
      $ristorante = [];
      $terms = get_the_terms($post_id, 'categoria_stores');
      $categories = [];
      foreach ($terms as $term) {
        $categories[] = $term->slug;
      }
      $ristorante['title'] = get_the_title();
      $ristorante['address'] = get_field('indirizzo', $post_id);
      $ristorante['phone'] = get_field('telefono', $post_id);
      $ristorante['coordinates'] = get_field('coordinate', $post_id);
      $ristorante['type'] = implode(',', $categories);
      $ristorante['link'] = get_field('direzioni', $post_id);
      $ristorante['city'] = get_field('citta', $post_id);
      $ristoranti[] = $ristorante;
    } else {
      $posts = get_posts([
        'post_type' => 'store',
        'post_status' => 'publish',
        'numberposts' => -1
        // 'order'    => 'ASC'
      ]);
      foreach ($posts as $post) {
        $ristorante = [];
        $terms = get_the_terms($post->ID, 'categoria_stores');
        $categories = [];
        foreach ($terms as $term) {
          $categories[] = $term->slug;
        }
        $ristorante['title'] = $post->post_title;
        $ristorante['address'] = get_field('indirizzo', $post->ID);
        $ristorante['phone'] = get_field('telefono', $post->ID);
        $ristorante['coordinates'] = get_field('coordinate', $post->ID);
        $ristorante['type'] = implode(',', $categories);
        $ristorante['link'] = get_permalink($post->ID);
        $ristorante['city'] = get_field('citta', $post->ID);
        $ristoranti[] = $ristorante;
      }
    }

    ?>
    <script>
      window.ristorantiData = <?php echo json_encode($ristoranti); ?>;
      window.markerIcon = "<?php echo plugin_dir_url(__DIR__) . '/assets/icons/map-marker.png'; ?>";
      window.markerIconToGo = "<?php echo plugin_dir_url(__DIR__) . '/assets/icons/map-marker-togo.png'; ?>";
    </script>
    <div class="stores-map-container <?php if ($settings['show_menu'] != 'yes') {
      echo "no-menu";
    } ?> <?php if ($settings['single_store'] == 'yes') {
        echo "single-store";
      } ?>">
      <div id="stores-map-menu">
        <div class="stores-map-form">
          <input type="text" id="stores-map-search" placeholder="Città...">
          <?php
          foreach ($ristorantiTypes as $type) {
            ?>
            <label>
              <input type="checkbox" name="store-type" value="<?php echo $type->slug; ?>" />
              <?php echo $type->name; ?>
            </label>
            <?php
          }
          ?>
        </div>
        <div id="stores-map-list">
          <?php
          foreach ($ristoranti as $ristorante) {
            ?>
            <div class="ristorante" data-type="<?php echo $ristorante['type'] ?>"
              data-coordinates="<?php echo $ristorante['coordinates']; ?>" data-city="<?php echo $ristorante['city']; ?>"
              data-name="<?php echo $ristorante['title']; ?>" data-link="<?php echo $ristorante['link']; ?>">
              <div class="ristorante-city">
                <?php echo $ristorante['city']; ?>
              </div>
              <div class="ristorante-title" <?php if (in_array('to-go', explode(',', $ristorante['type']))) {
                echo ('style="background-image: url(' . plugin_dir_url(__DIR__) . '/assets/icons/togo-icon.svg);"');
              } ?>>
                <?php echo $ristorante['title']; ?>
              </div>
              <div class="ristorante-address" <?php echo ('style="background-image: url(' . plugin_dir_url(__DIR__) . '/assets/icons/location-icon.svg);"');
              ?>>
                <?php echo $ristorante['address']; ?>
              </div>
              <div class="ristorante-phone" <?php echo ('style="background-image: url(' . plugin_dir_url(__DIR__) . '/assets/icons/phone-icon.svg);"');
              ?>>
                <?php echo $ristorante['phone']; ?>
              </div>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
      <div id="stores-map-canvas"></div>
    </div>
    <?php

  }

}