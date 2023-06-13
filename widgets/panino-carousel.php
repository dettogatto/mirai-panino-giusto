<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Control_Media;
use Elementor\Plugin;


/**
 * Elementor image carousel widget.
 *
 * Elementor widget that displays a set of images in a rotating carousel or
 * slider.
 *
 * @since 1.0.0
 */
class Panino_Carousel extends Widget_Base
{

  /**
   * Get widget name.
   *
   * Retrieve image carousel widget name.
   *
   * @since 1.0.0
   * @access public
   *
   * @return string Widget name.
   */
  public function get_name()
  {
    return 'image-carousel';
  }

  /**
   * Get widget title.
   *
   * Retrieve image carousel widget title.
   *
   * @since 1.0.0
   * @access public
   *
   * @return string Widget title.
   */
  public function get_title()
  {
    return esc_html__('Panino Carousel', 'mirai-panino-giusto');
  }

  /**
   * Get widget icon.
   *
   * Retrieve image carousel widget icon.
   *
   * @since 1.0.0
   * @access public
   *
   * @return string Widget icon.
   */
  public function get_icon()
  {
    return 'eicon-slider-push';
  }

  /**
   * Get widget keywords.
   *
   * Retrieve the list of keywords the widget belongs to.
   *
   * @since 2.1.0
   * @access public
   *
   * @return array Widget keywords.
   */
  public function get_keywords()
  {
    return ['image', 'photo', 'visual', 'carousel', 'slider'];
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
   * Register image carousel widget controls.
   *
   * Adds different input fields to allow the user to change and customize the widget settings.
   *
   * @since 3.1.0
   * @access protected
   */
  protected function register_controls()
  {
    $this->start_controls_section(
      'section_image_carousel',
      [
        'label' => esc_html__('Image Carousel', 'mirai-panino-giusto'),
      ]
    );

    $this->add_control(
      'carousel',
      [
        'label' => esc_html__('Add Images', 'mirai-panino-giusto'),
        'type' => Controls_Manager::GALLERY,
        'default' => [],
        'show_label' => false,
        'dynamic' => [
          'active' => true,
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Image_Size::get_type(),
      [
        'name' => 'thumbnail',
        // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
        'separator' => 'none',
      ]
    );

    $slides_to_show = range(1, 10);
    $slides_to_show = array_combine($slides_to_show, $slides_to_show);

    $this->add_responsive_control(
      'slides_to_show',
      [
        'label' => esc_html__('Slides to Show', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          '' => esc_html__('Default', 'mirai-panino-giusto'),
        ] + $slides_to_show,
        'frontend_available' => true,
        'render_type' => 'template',
        'selectors' => [
          '{{WRAPPER}}' => '--e-image-carousel-slides-to-show: {{VALUE}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'slides_to_scroll',
      [
        'label' => esc_html__('Slides to Scroll', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'description' => esc_html__('Set how many slides are scrolled per swipe.', 'mirai-panino-giusto'),
        'options' => [
          '' => esc_html__('Default', 'mirai-panino-giusto'),
        ] + $slides_to_show,
        'condition' => [
          'slides_to_show!' => '1',
        ],
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'image_stretch',
      [
        'label' => esc_html__('Image Stretch', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => 'no',
        'options' => [
          'no' => esc_html__('No', 'mirai-panino-giusto'),
          'yes' => esc_html__('Yes', 'mirai-panino-giusto'),
        ],
      ]
    );

    $this->add_control(
      'navigation',
      [
        'label' => esc_html__('Navigation', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => 'both',
        'options' => [
          'both' => esc_html__('Arrows and Dots', 'mirai-panino-giusto'),
          'arrows' => esc_html__('Arrows', 'mirai-panino-giusto'),
          'dots' => esc_html__('Dots', 'mirai-panino-giusto'),
          'none' => esc_html__('None', 'mirai-panino-giusto'),
        ],
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'navigation_previous_icon',
      [
        'label' => esc_html__('Previous Arrow Icon', 'mirai-panino-giusto'),
        'type' => Controls_Manager::ICONS,
        'fa4compatibility' => 'icon',
        'skin' => 'inline',
        'label_block' => false,
        'skin_settings' => [
          'inline' => [
            'none' => [
              'label' => 'Default',
              'icon' => 'eicon-chevron-left',
            ],
            'icon' => [
              'icon' => 'eicon-star',
            ],
          ],
        ],
        'recommended' => [
          'fa-regular' => [
            'arrow-alt-circle-left',
            'caret-square-left',
          ],
          'fa-solid' => [
            'angle-double-left',
            'angle-left',
            'arrow-alt-circle-left',
            'arrow-circle-left',
            'arrow-left',
            'caret-left',
            'caret-square-left',
            'chevron-circle-left',
            'chevron-left',
            'long-arrow-alt-left',
          ],
        ],
        'conditions' => [
          'relation' => 'or',
          'terms' => [
            [
              'name' => 'navigation',
              'operator' => '=',
              'value' => 'both',
            ],
            [
              'name' => 'navigation',
              'operator' => '=',
              'value' => 'arrows',
            ],
          ],
        ],
      ]
    );

    $this->add_control(
      'navigation_next_icon',
      [
        'label' => esc_html__('Next Arrow Icon', 'mirai-panino-giusto'),
        'type' => Controls_Manager::ICONS,
        'fa4compatibility' => 'icon',
        'skin' => 'inline',
        'label_block' => false,
        'skin_settings' => [
          'inline' => [
            'none' => [
              'label' => 'Default',
              'icon' => 'eicon-chevron-right',
            ],
            'icon' => [
              'icon' => 'eicon-star',
            ],
          ],
        ],
        'recommended' => [
          'fa-regular' => [
            'arrow-alt-circle-right',
            'caret-square-right',
          ],
          'fa-solid' => [
            'angle-double-right',
            'angle-right',
            'arrow-alt-circle-right',
            'arrow-circle-right',
            'arrow-right',
            'caret-right',
            'caret-square-right',
            'chevron-circle-right',
            'chevron-right',
            'long-arrow-alt-right',
          ],
        ],
        'conditions' => [
          'relation' => 'or',
          'terms' => [
            [
              'name' => 'navigation',
              'operator' => '=',
              'value' => 'both',
            ],
            [
              'name' => 'navigation',
              'operator' => '=',
              'value' => 'arrows',
            ],
          ],
        ],
      ]
    );

    $this->add_control(
      'link_to',
      [
        'label' => esc_html__('Link', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => 'none',
        'options' => [
          'none' => esc_html__('None', 'mirai-panino-giusto'),
          'file' => esc_html__('Media File', 'mirai-panino-giusto'),
          'custom' => esc_html__('Custom URL', 'mirai-panino-giusto'),
        ],
      ]
    );

    $this->add_control(
      'link',
      [
        'label' => esc_html__('Link', 'mirai-panino-giusto'),
        'type' => Controls_Manager::URL,
        'placeholder' => esc_html__('https://your-link.com', 'mirai-panino-giusto'),
        'condition' => [
          'link_to' => 'custom',
        ],
        'show_label' => false,
        'dynamic' => [
          'active' => true,
        ],
      ]
    );

    $this->add_control(
      'open_lightbox',
      [
        'label' => esc_html__('Lightbox', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'description' => sprintf(
          /* translators: 1: Link open tag, 2: Link close tag. */
          esc_html__('Manage your site’s lightbox settings in the %1$sLightbox panel%2$s.', 'mirai-panino-giusto'),
          '<a href="javascript: $e.run( \'panel/global/open\' ).then( () => $e.route( \'panel/global/settings-lightbox\' ) )">',
          '</a>'
        ),
        'default' => 'default',
        'options' => [
          'default' => esc_html__('Default', 'mirai-panino-giusto'),
          'yes' => esc_html__('Yes', 'mirai-panino-giusto'),
          'no' => esc_html__('No', 'mirai-panino-giusto'),
        ],
        'condition' => [
          'link_to' => 'file',
        ],
      ]
    );

    $this->add_control(
      'title_type',
      [
        'label' => esc_html__('Title', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => '',
        'options' => [
          '' => esc_html__('None', 'mirai-panino-giusto'),
          'title' => esc_html__('Title', 'mirai-panino-giusto'),
          'caption' => esc_html__('Caption', 'mirai-panino-giusto'),
          'description' => esc_html__('Description', 'mirai-panino-giusto'),
        ],
      ]
    );

    $this->add_control(
      'caption_type',
      [
        'label' => esc_html__('Caption', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => '',
        'options' => [
          '' => esc_html__('None', 'mirai-panino-giusto'),
          'title' => esc_html__('Title', 'mirai-panino-giusto'),
          'caption' => esc_html__('Caption', 'mirai-panino-giusto'),
          'description' => esc_html__('Description', 'mirai-panino-giusto'),
        ],
      ]
    );

    $this->add_control(
      'view',
      [
        'label' => esc_html__('View', 'mirai-panino-giusto'),
        'type' => Controls_Manager::HIDDEN,
        'default' => 'traditional',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_additional_options',
      [
        'label' => esc_html__('Additional Options', 'mirai-panino-giusto'),
      ]
    );

    $this->add_control(
      'lazyload',
      [
        'label' => esc_html__('Lazyload', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SWITCHER,
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'autoplay',
      [
        'label' => esc_html__('Autoplay', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => 'yes',
        'options' => [
          'yes' => esc_html__('Yes', 'mirai-panino-giusto'),
          'no' => esc_html__('No', 'mirai-panino-giusto'),
        ],
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'pause_on_hover',
      [
        'label' => esc_html__('Pause on Hover', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => 'yes',
        'options' => [
          'yes' => esc_html__('Yes', 'mirai-panino-giusto'),
          'no' => esc_html__('No', 'mirai-panino-giusto'),
        ],
        'condition' => [
          'autoplay' => 'yes',
        ],
        'render_type' => 'none',
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'pause_on_interaction',
      [
        'label' => esc_html__('Pause on Interaction', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => 'yes',
        'options' => [
          'yes' => esc_html__('Yes', 'mirai-panino-giusto'),
          'no' => esc_html__('No', 'mirai-panino-giusto'),
        ],
        'condition' => [
          'autoplay' => 'yes',
        ],
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'autoplay_speed',
      [
        'label' => esc_html__('Autoplay Speed', 'mirai-panino-giusto'),
        'type' => Controls_Manager::NUMBER,
        'default' => 5000,
        'condition' => [
          'autoplay' => 'yes',
        ],
        'render_type' => 'none',
        'frontend_available' => true,
      ]
    );

    // Loop requires a re-render so no 'render_type = none'
    $this->add_control(
      'infinite',
      [
        'label' => esc_html__('Infinite Loop', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => 'yes',
        'options' => [
          'yes' => esc_html__('Yes', 'mirai-panino-giusto'),
          'no' => esc_html__('No', 'mirai-panino-giusto'),
        ],
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'effect',
      [
        'label' => esc_html__('Effect', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => 'slide',
        'options' => [
          'slide' => esc_html__('Slide', 'mirai-panino-giusto'),
          'fade' => esc_html__('Fade', 'mirai-panino-giusto'),
        ],
        'condition' => [
          'slides_to_show' => '1',
        ],
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'speed',
      [
        'label' => esc_html__('Animation Speed', 'mirai-panino-giusto'),
        'type' => Controls_Manager::NUMBER,
        'default' => 500,
        'render_type' => 'none',
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'direction',
      [
        'label' => esc_html__('Direction', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => 'ltr',
        'options' => [
          'ltr' => esc_html__('Left', 'mirai-panino-giusto'),
          'rtl' => esc_html__('Right', 'mirai-panino-giusto'),
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_style_navigation',
      [
        'label' => esc_html__('Navigation', 'mirai-panino-giusto'),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
          'navigation' => ['arrows', 'dots', 'both'],
        ],
      ]
    );

    $this->add_control(
      'heading_style_arrows',
      [
        'label' => esc_html__('Arrows', 'mirai-panino-giusto'),
        'type' => Controls_Manager::HEADING,
        'separator' => 'before',
        'condition' => [
          'navigation' => ['arrows', 'both'],
        ],
      ]
    );

    $this->add_control(
      'arrows_position',
      [
        'label' => esc_html__('Position', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => 'inside',
        'options' => [
          'inside' => esc_html__('Inside', 'mirai-panino-giusto'),
          'outside' => esc_html__('Outside', 'mirai-panino-giusto'),
        ],
        'prefix_class' => 'elementor-arrows-position-',
        'condition' => [
          'navigation' => ['arrows', 'both'],
        ],
      ]
    );

    $this->add_control(
      'arrows_size',
      [
        'label' => esc_html__('Size', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SLIDER,
        'range' => [
          'px' => [
            'min' => 20,
            'max' => 60,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          'navigation' => ['arrows', 'both'],
        ],
      ]
    );

    $this->add_control(
      'arrows_color',
      [
        'label' => esc_html__('Color', 'mirai-panino-giusto'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'color: {{VALUE}};',
          '{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev svg, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next svg' => 'fill: {{VALUE}};',
        ],
        'condition' => [
          'navigation' => ['arrows', 'both'],
        ],
      ]
    );

    $this->add_control(
      'heading_style_dots',
      [
        'label' => esc_html__('Pagination', 'mirai-panino-giusto'),
        'type' => Controls_Manager::HEADING,
        'separator' => 'before',
        'condition' => [
          'navigation' => ['dots', 'both'],
        ],
      ]
    );

    $this->add_control(
      'dots_position',
      [
        'label' => esc_html__('Position', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'default' => 'outside',
        'options' => [
          'outside' => esc_html__('Outside', 'mirai-panino-giusto'),
          'inside' => esc_html__('Inside', 'mirai-panino-giusto'),
        ],
        'prefix_class' => 'elementor-pagination-position-',
        'condition' => [
          'navigation' => ['dots', 'both'],
        ],
      ]
    );

    $this->add_control(
      'dots_size',
      [
        'label' => esc_html__('Size', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SLIDER,
        'range' => [
          'px' => [
            'min' => 5,
            'max' => 10,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          'navigation' => ['dots', 'both'],
        ],
      ]
    );

    $this->add_control(
      'dots_inactive_color',
      [
        'label' => esc_html__('Color', 'mirai-panino-giusto'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          // The opacity property will override the default inactive dot color which is opacity 0.2.
          '{{WRAPPER}} .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background: {{VALUE}}; opacity: 1',
        ],
        'condition' => [
          'navigation' => ['dots', 'both'],
        ],
      ]
    );

    $this->add_control(
      'dots_color',
      [
        'label' => esc_html__('Active Color', 'mirai-panino-giusto'),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
        ],
        'condition' => [
          'navigation' => ['dots', 'both'],
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_style_image',
      [
        'label' => esc_html__('Image', 'mirai-panino-giusto'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_responsive_control(
      'gallery_vertical_align',
      [
        'label' => esc_html__('Vertical Align', 'mirai-panino-giusto'),
        'type' => Controls_Manager::CHOOSE,
        'options' => [
          'flex-start' => [
            'title' => esc_html__('Start', 'mirai-panino-giusto'),
            'icon' => 'eicon-v-align-top',
          ],
          'center' => [
            'title' => esc_html__('Center', 'mirai-panino-giusto'),
            'icon' => 'eicon-v-align-middle',
          ],
          'flex-end' => [
            'title' => esc_html__('End', 'mirai-panino-giusto'),
            'icon' => 'eicon-v-align-bottom',
          ],
        ],
        'condition' => [
          'slides_to_show!' => '1',
        ],
        'selectors' => [
          '{{WRAPPER}} .swiper-wrapper' => 'display: flex; align-items: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'image_spacing',
      [
        'label' => esc_html__('Spacing', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SELECT,
        'options' => [
          '' => esc_html__('Default', 'mirai-panino-giusto'),
          'custom' => esc_html__('Custom', 'mirai-panino-giusto'),
        ],
        'default' => '',
        'condition' => [
          'slides_to_show!' => '1',
        ],
      ]
    );

    $this->add_responsive_control(
      'image_spacing_custom',
      [
        'label' => esc_html__('Image Spacing', 'mirai-panino-giusto'),
        'type' => Controls_Manager::SLIDER,
        'range' => [
          'px' => [
            'max' => 100,
          ],
        ],
        'default' => [
          'size' => 20,
        ],
        'condition' => [
          'image_spacing' => 'custom',
          'slides_to_show!' => '1',
        ],
        'frontend_available' => true,
        'render_type' => 'none',
        'separator' => 'after',
      ]
    );

    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name' => 'image_border',
        'selector' => '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .swiper-slide-image',
      ]
    );

    $this->add_responsive_control(
      'image_border_radius',
      [
        'label' => esc_html__('Border Radius', 'mirai-panino-giusto'),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%', 'em', 'rem', 'custom'],
        'selectors' => [
          '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .swiper-slide-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_title',
      [
        'label' => esc_html__('Title', 'mirai-panino-giusto'),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
          'title_type!' => '',
        ],
      ]
    );

    $this->add_responsive_control(
      'title_align',
      [
        'label' => esc_html__('Alignment', 'mirai-panino-giusto'),
        'type' => Controls_Manager::CHOOSE,
        'options' => [
          'left' => [
            'title' => esc_html__('Left', 'mirai-panino-giusto'),
            'icon' => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'mirai-panino-giusto'),
            'icon' => 'eicon-text-align-center',
          ],
          'right' => [
            'title' => esc_html__('Right', 'mirai-panino-giusto'),
            'icon' => 'eicon-text-align-right',
          ],
          'justify' => [
            'title' => esc_html__('Justified', 'mirai-panino-giusto'),
            'icon' => 'eicon-text-align-justify',
          ],
        ],
        'default' => 'center',
        'selectors' => [
          '{{WRAPPER}} .elementor-image-carousel-title' => 'text-align: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'title_text_color',
      [
        'label' => esc_html__('Text Color', 'mirai-panino-giusto'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} .elementor-image-carousel-title' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'title_typography',
        'global' => [
          'default' => Global_Colors::COLOR_ACCENT,
        ],
        'selector' => '{{WRAPPER}} .elementor-image-carousel-title',
      ]
    );

    $this->add_group_control(
      Group_Control_Text_Shadow::get_type(),
      [
        'name' => 'title_shadow',
        'selector' => '{{WRAPPER}} .elementor-image-carousel-title',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_caption',
      [
        'label' => esc_html__('Caption', 'mirai-panino-giusto'),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
          'caption_type!' => '',
        ],
      ]
    );

    $this->add_responsive_control(
      'caption_align',
      [
        'label' => esc_html__('Alignment', 'mirai-panino-giusto'),
        'type' => Controls_Manager::CHOOSE,
        'options' => [
          'left' => [
            'title' => esc_html__('Left', 'mirai-panino-giusto'),
            'icon' => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__('Center', 'mirai-panino-giusto'),
            'icon' => 'eicon-text-align-center',
          ],
          'right' => [
            'title' => esc_html__('Right', 'mirai-panino-giusto'),
            'icon' => 'eicon-text-align-right',
          ],
          'justify' => [
            'title' => esc_html__('Justified', 'mirai-panino-giusto'),
            'icon' => 'eicon-text-align-justify',
          ],
        ],
        'default' => 'center',
        'selectors' => [
          '{{WRAPPER}} .elementor-image-carousel-caption' => 'text-align: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'caption_text_color',
      [
        'label' => esc_html__('Text Color', 'mirai-panino-giusto'),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} .elementor-image-carousel-caption' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'caption_typography',
        'global' => [
          'default' => Global_Colors::COLOR_ACCENT,
        ],
        'selector' => '{{WRAPPER}} .elementor-image-carousel-caption',
      ]
    );

    $this->add_group_control(
      Group_Control_Text_Shadow::get_type(),
      [
        'name' => 'caption_shadow',
        'selector' => '{{WRAPPER}} .elementor-image-carousel-caption',
      ]
    );

    $this->end_controls_section();

  }

  /**
   * Render image carousel widget output on the frontend.
   *
   * Written in PHP and used to generate the final HTML.
   *
   * @since 1.0.0
   * @access protected
   */
  protected function render()
  {
    $settings = $this->get_settings_for_display();

    $lazyload = 'yes' === $settings['lazyload'];

    if (empty($settings['carousel'])) {
      return;
    }

    $slides = [];

    foreach ($settings['carousel'] as $index => $attachment) {
      $image_url = Group_Control_Image_Size::get_attachment_image_src($attachment['id'], 'thumbnail', $settings);

      if (!$image_url && isset($attachment['url'])) {
        $image_url = $attachment['url'];
      }

      if ($lazyload) {
        $image_html = '<img class="swiper-slide-image swiper-lazy" data-src="' . esc_attr($image_url) . '" alt="' . esc_attr(Control_Media::get_image_alt($attachment)) . '" />';
      } else {
        $image_html = '<img class="swiper-slide-image" src="' . esc_attr($image_url) . '" alt="' . esc_attr(Control_Media::get_image_alt($attachment)) . '" />';
      }

      $link_tag = '';

      $link = $this->get_link_url($attachment, $settings);

      if ($link) {
        $link_key = 'link_' . $index;

        $this->add_lightbox_data_attributes($link_key, $attachment['id'], $settings['open_lightbox'], $this->get_id());

        if (Plugin::$instance->editor->is_edit_mode()) {
          $this->add_render_attribute($link_key, [
            'class' => 'elementor-clickable',
          ]);
        }

        $this->add_link_attributes($link_key, $link);

        $link_tag = '<a ' . $this->get_render_attribute_string($link_key) . '>';
      }

      $image_title = $this->get_image_title($attachment);
      $image_caption = $this->get_image_caption($attachment);

      $slide_html = '<div class="swiper-slide">' . $link_tag . '<figure class="swiper-slide-inner">' . $image_html;

      if ($lazyload) {
        $slide_html .= '<div class="swiper-lazy-preloader"></div>';
      }

      if (!empty($image_title)) {
        $slide_html .= '<figcaption class="elementor-image-carousel-title">' . wp_kses_post($image_title) . '</figcaption>';
      }

      if (!empty($image_caption)) {
        $slide_html .= '<figcaption class="elementor-image-carousel-caption">' . wp_kses_post($image_caption) . '</figcaption>';
      }

      $slide_html .= '</figure>';

      if ($link) {
        $slide_html .= '</a>';
      }

      $slide_html .= '</div>';

      $slides[] = $slide_html;

    }

    if (empty($slides)) {
      return;
    }

    $swiper_class = Plugin::$instance->experiments->is_feature_active('e_swiper_latest') ? 'swiper' : 'swiper-container';

    $this->add_render_attribute([
      'carousel' => [
        'class' => 'elementor-image-carousel swiper-wrapper',
      ],
      'carousel-wrapper' => [
        'class' => 'elementor-image-carousel-wrapper ' . $swiper_class,
        'dir' => $settings['direction'],
      ],
    ]);

    $show_dots = (in_array($settings['navigation'], ['dots', 'both']));
    $show_arrows = (in_array($settings['navigation'], ['arrows', 'both']));

    if ('yes' === $settings['image_stretch']) {
      $this->add_render_attribute('carousel', 'class', 'swiper-image-stretch');
    }

    $slides_count = count($settings['carousel']);

    ?>
    <div <?php $this->print_render_attribute_string('carousel-wrapper'); ?>>
      <div <?php $this->print_render_attribute_string('carousel'); ?>>
        <?php // PHPCS - $slides contains the slides content, all the relevent content is escaped above. ?>
        <?php echo implode('', $slides); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
      </div>
      <?php if (1 < $slides_count): ?>
        <?php if ($show_dots): ?>
          <div class="swiper-pagination"></div>
        <?php endif; ?>
        <?php if ($show_arrows): ?>
          <div class="elementor-swiper-button elementor-swiper-button-prev" role="button" tabindex="0">
            <?php $this->render_swiper_button('previous'); ?>
            <span class="elementor-screen-only">
              <?php echo esc_html__('Previous image', 'mirai-panino-giusto'); ?>
            </span>
          </div>
          <div class="elementor-swiper-button elementor-swiper-button-next" role="button" tabindex="0">
            <?php $this->render_swiper_button('next'); ?>
            <span class="elementor-screen-only">
              <?php echo esc_html__('Next image', 'mirai-panino-giusto'); ?>
            </span>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
    <?php
  }

  /**
   * Retrieve image carousel link URL.
   *
   * @since 1.0.0
   * @access private
   *
   * @param array $attachment
   * @param object $instance
   *
   * @return array|string|false An array/string containing the attachment URL, or false if no link.
   */
  private function get_link_url($attachment, $instance)
  {
    if ('none' === $instance['link_to']) {
      return false;
    }

    if ('custom' === $instance['link_to']) {
      if (empty($instance['link']['url'])) {
        return false;
      }

      return $instance['link'];
    }

    return [
      'url' => wp_get_attachment_url($attachment['id']),
    ];
  }

  /**
   * Retrieve image carousel title.
   *
   * @since 1.2.0
   * @access private
   *
   * @param array $attachment
   *
   * @return string The title of the image.
   */
  private function get_image_title($attachment)
  {
    $caption_type = $this->get_settings_for_display('title_type');

    if (empty($caption_type)) {
      return '';
    }

    $attachment_post = get_post($attachment['id']);

    if ('caption' === $caption_type) {
      return $attachment_post->post_excerpt;
    }

    if ('title' === $caption_type) {
      return $attachment_post->post_title;
    }

    return $attachment_post->post_content;
  }

  /**
   * Retrieve image carousel caption.
   *
   * @since 1.2.0
   * @access private
   *
   * @param array $attachment
   *
   * @return string The caption of the image.
   */
  private function get_image_caption($attachment)
  {
    $caption_type = $this->get_settings_for_display('caption_type');

    if (empty($caption_type)) {
      return '';
    }

    $attachment_post = get_post($attachment['id']);

    if ('caption' === $caption_type) {
      return $attachment_post->post_excerpt;
    }

    if ('title' === $caption_type) {
      return $attachment_post->post_title;
    }

    return $attachment_post->post_content;
  }

  private function render_swiper_button($type)
  {
    $direction = 'next' === $type ? 'right' : 'left';
    $icon_settings = $this->get_settings_for_display('navigation_' . $type . '_icon');

    if (empty($icon_settings['value'])) {
      $icon_settings = [
        'library' => 'eicons',
        'value' => 'eicon-chevron-' . $direction,
      ];
    }

    Icons_Manager::render_icon($icon_settings, ['aria-hidden' => 'true']);
  }
}