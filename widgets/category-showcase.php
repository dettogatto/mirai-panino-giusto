<?php
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly

/**
 * Elementor Post Filter
 *
 * Elementor widget for Post Filter.
 */
class Category_Showcase extends \Elementor\Widget_Base
{

  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);

    wp_register_style('category-showcase-css', plugins_url('/assets/category-showcase.css', __DIR__));
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
    return 'category-showcase';
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
    return __('Category Showcase', 'mirai-panino-giusto');
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
    return ['category-showcase-css'];
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
      'max_cats',
      [
        'label' => esc_html__('Max elements', 'mirai-panino-giusto'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'min' => 1,
        'max' => 100,
        'step' => 1,
        'default' => 6,
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
      'text_color',
      [
        'label' => __('Text color', 'mirai-panino-giusto'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .mirai-category-showcase-item h2' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_group_control(
      'typography',
      [
        'name' => 'typography_filter_text',
        'selector' => '{{WRAPPER}} .mirai-category-showcase-item h2',
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
    $tax = 'categoria_prodotti';
    $settings = $this->get_settings_for_display();
    $current_id = get_queried_object_id();
    $categories = get_terms(
      $tax,
      array(
        'hide_empty' => false,
        'parent' => 0,
        'orderby' => 'term_order',
      )
    );

    ?>
    <div class="mirai-category-showcase-container">
      <?php

      $count = 0;

      foreach ($categories as $cat) {
        if ($cat->term_id == $current_id) {
          continue;
        }
        $count++;
        if ($count > $settings['max_cats']) {
          break;
        }
        $img = get_field('Immagine', $tax . '_' . $cat->term_id);

        ?>
        <a class="mirai-category-showcase-item" href="<?php echo get_term_link($cat, $tax) ?>">
          <div class="mirai-category-showcase-img" style="background-image: url(<?php echo $img; ?>);"></div>
          <h2>
            <?php echo $cat->name; ?>
          </h2>
        </a>
        <?php
      }
      ?>
    </div>
    <?php

  }
}