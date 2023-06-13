<?php
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly

/**
 * Elementor Post Filter
 *
 * Elementor widget for Post Filter.
 */
class Opening_Times_Table extends \Elementor\Widget_Base
{

  public function __construct($data = [], $args = null)
  {
    parent::__construct($data, $args);

    wp_register_style('opening-times-css', plugins_url('/assets/opening-times.css', __DIR__));
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
    return 'opening-times-table';
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
    return __('Opening Times Table', 'mirai-panino-giusto');
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
    return 'eicon-table-of-contents';
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
    return ['opening-times-css'];
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
    $post_id = get_the_ID();
    $fields = get_field('orari_store', $post_id);

    $days = array('lunedì', 'martedì', 'mercoledì', 'giovedì', 'venerdì', 'sabato', 'domenica');
    ?>
    <table class="opening-times-table">
      <tr>
        <th></th>
        <th class="service">Sit-in</th>
        <th class="service">Delivery</th>
      </tr>
      <?php

      for ($i = 0; $i < 7; $i++) {

        ?>
        <tr>
          <th class="day">
            <?php echo $days[$i] ?>
          </th>
          <td>
            <?php echo $fields["apertura_e_chiusura_sit_in_" . $days[$i]]; ?>
          </td>
          <td>
            <?php echo $fields["orario_apertura_chiusura_delivery_" . $days[$i]]; ?>
          </td>
        </tr>
        <?php
      }

      ?>
    </table>
    <?php

  }

}