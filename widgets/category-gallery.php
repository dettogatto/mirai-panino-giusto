<?php
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly

/**
 * Elementor Post Filter
 *
 * Elementor widget for Post Filter.
 */
class Category_Gallery extends \Elementor\Widget_Base
{

  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);

    // wp_register_script('category-gallery-js', plugins_url('/assets/js/category-gallery.js', __DIR__), ['jquery'], null, true);
    // wp_register_style('category-gallery-css', plugins_url('/assets/css/category-gallery.css', __DIR__));
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
    return 'category-gallery';
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
    return __('Category Gallery', 'mirai-panino-giusto');
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
    return 'eicon-gallery-grid';
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
    return [];
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
    return [];
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

    $this->end_controls_section();

    $this->start_controls_section(
      'section_style',
      [
        'label' => __('Style', 'mirai-panino-giusto'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
      ]
    );


    $this->add_control(
      'background_image',
      [
        'label' => esc_html__('Background Image', 'mirai-panino-giusto'),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
          'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
      ]
    );


    $this->add_control(
      'title_color',
      [
        'label' => __('Title color', 'mirai-panino-giusto'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .mirai-category-card .card-title' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_group_control(
      'typography',
      [
        'name' => 'typography_filter',
        'selector' => '{{WRAPPER}} .mirai-category-card .card-title',
      ]
    );

    $this->add_control(
      'text_color',
      [
        'label' => __('Text color', 'mirai-panino-giusto'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .mirai-category-card .card-text' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_group_control(
      'typography',
      [
        'name' => 'typography_filter_text',
        'selector' => '{{WRAPPER}} .mirai-category-card .card-text',
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
    $current_term = get_queried_object();

    $termchildren = array(
      'hierarchical' => 1,
      'show_option_none' => '',
      'hide_empty' => 0,
      'parent' => $current_term->term_id,
      'taxonomy' => $current_term->taxonomy
    );

    echo ('<div class="mirai-category-gallery">');

    $subcats = get_categories($termchildren);

    if (!empty($subcats)) {

      foreach ($subcats as $cat) {
        $img = get_field('Immagine', $cat->taxonomy . '_' . $cat->term_id);
        ?>
        <a href="<?php echo (get_term_link($cat)); ?>" class="mirai-category-card">
          <div class="mirai-category-card-image-container"
            style="background-image: url(<?php echo ($settings['background_image']['url']) ?>)">
            <img src="<?php echo ($img); ?>">
          </div>
          <div class="mirai-category-card-text-container">
            <p class="card-title">
              <?php echo ($cat->name); ?>
            </p>
          </div>
        </a>
        <?php
      }
    } else {
      $products = get_posts(
        array(
          'post_type' => 'menu',
          'posts_per_page' => -1,
          'tax_query' => array(
            array(
              'taxonomy' => $current_term->taxonomy,
              'field' => 'slug',
              'terms' => $current_term->slug,
            )
          )
        )
      );
      foreach ($products as $product) {
        $img = get_the_post_thumbnail_url($product->ID);
        $ingredienti = get_field("ingredienti", $product->ID);
        $ingredienti = implode(", ", $ingredienti);
        ?>
        <a href="<?php echo (get_permalink($product->ID)); ?>" class="mirai-category-card">
          <div class="mirai-category-card-image-container"
            style="background-image: url(<?php echo ($settings['background_image']['url']) ?>)">
            <img src="<?php echo ($img); ?>">
          </div>
          <div class="mirai-category-card-text-container">
            <p class="card-title">
              <?php echo ($product->post_title); ?>
            </p>
            <p class="card-text">
              <?php echo ($ingredienti); ?>
            </p>
          </div>
        </a>
        <?php
      }
    }
    echo ('</div>');

  }

}