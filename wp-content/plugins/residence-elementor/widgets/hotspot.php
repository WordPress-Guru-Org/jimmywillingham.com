<?php

namespace ElementorWpResidence\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Icons_Manager;
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Elementor Properties Widget.
 * @since 2.0
 */

class Wpresidence_HotSpots extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve widget name.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Wpresidence_HotSpots';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Wpresidence HotSpots', 'residence-elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
       return 'eicon-image-hotspot';
    }

    public function get_categories() {
        return ['wpresidence'];
    }

   	protected function register_controls() {
		parent::register_controls();


		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'wpresidence-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->end_controls_section();

		/**
		 * begin  Hotspot
		 */
		$this->start_controls_section(
			'hotspot',
			[
				'label' => esc_html__( 'Hotspot', 'wpresidence-elementor'),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'hotspot_repeater' );

		$repeater->start_controls_tab(
			'hotspot_content_tab',
			[
				'label' => esc_html__( 'Content', 'wpresidence-elementor'),
			]
		);

                    $repeater->add_control(
                            'hotspot_label',
                            [
                                    'label' => esc_html__( 'Label', 'wpresidence-elementor'),
                                    'type' => Controls_Manager::TEXT,
                                    'default' => '',
                                    'label_block' => true,
                                    'dynamic' => [
                                            'active' => true,
                                    ],
                            ]
                    );

                    $repeater->add_control(
                            'hotspot_link',
                            [
                                    'label' => esc_html__( 'Link', 'wpresidence-elementor'),
                                    'type' => Controls_Manager::URL,
                                    'dynamic' => [
                                            'active' => true,
                                    ],
                                    'placeholder' => esc_html__( 'https://your-link.com', 'wpresidence-elementor'),
                            ]
                    );

                    $repeater->add_control(
                            'hotspot_icon',
                            [
                                    'label' => esc_html__( 'Icon', 'wpresidence-elementor'),
                                    'type' => Controls_Manager::ICONS,
                                    'skin' => 'inline',
                                    'label_block' => false,
                            ]
                    );


                    $repeater->add_control(
                            'point_size',
                            [
                                    'label' => esc_html__( 'Hotspot Size', 'wpresidence-elementor'),
                                    'type' => Controls_Manager::SWITCHER,
                                    'label_off' => esc_html__( 'Off', 'wpresidence-elementor'),
                                    'label_on' => esc_html__( 'On', 'wpresidence-elementor'),
                                    'default' => 'no',
                                  
                            ]
                    );

                    $repeater->add_control('point_width',
                            [
                                    'label' => esc_html__( 'Minimum Width', 'wpresidence-elementor'),
                                    'type' => Controls_Manager::SLIDER,
                                    'range' => [
                                            'px' => [
                                                    'min' => 0,
                                                    'max' => 500,
                                                    'step' => 1,
                                            ],
                                    ],
                                    'size_units' => [ 'px' ],
                                    'selectors' => [
                                            '{{WRAPPER}} {{CURRENT_ITEM}} .wpestate_hotspot_icon_wrapper' => 'min-width: {{SIZE}}{{UNIT}}',
                                    ],
                                    'condition' => [
                                            'point_size' => 'yes',
                                    ],
                            ]
                    );

                    $repeater->add_control(
                            'point_height',
                            [
                                    'label' => esc_html__( 'Minimum Height', 'wpresidence-elementor'),
                                    'type' => Controls_Manager::SLIDER,
                                    'range' => [
                                            'px' => [
                                                    'min' => 0,
                                                    'max' => 500,
                                                    'step' => 1,
                                            ],
                                    ],
                                    'size_units' => [ 'px' ],
                                    'selectors' => [
                                            '{{WRAPPER}} {{CURRENT_ITEM}} .wpestate_hotspot_icon_wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
                                    ],
                                    'condition' => [
                                            'point_size' => 'yes',
                                    ],
                            ]
                    );

                    $repeater->add_control(
                            'tooltip_text',
                            [
                                    'render_type' => 'template',
                                    'label' => esc_html__( ' Content', 'wpresidence-elementor'),
                                    'type' => Controls_Manager::WYSIWYG,
                                    'default' => esc_html__( 'Add Your Content', 'wpresidence-elementor'),
                            ]
                    );

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'hotspot_position_tab',
			[
				'label' => esc_html__( 'POSITION', 'wpresidence-elementor'),
			]
		);

                
             
                    $repeater->add_responsive_control(
                            'hotspot_offset_x',
                            [
                                    'label' => esc_html__( 'Offset', 'wpresidence-elementor'),
                                    'type' => Controls_Manager::SLIDER,
                                    'size_units' => [ '%' ],
                                    'default' => [
                                            'unit' => '%',
                                            'size' => '50',
                                    ],
                                    'selectors' => [
                                            '{{WRAPPER}} {{CURRENT_ITEM}}' =>
                                                            'left: {{SIZE}}%; --hotspot-translate-x: {{SIZE}}%;',
                                    ],
                            ]
                    );

                
                    $repeater->add_responsive_control(
                            'hotspot_offset_y',
                            [
                                    'label' => esc_html__( 'Offset', 'wpresidence-elementor'),
                                    'type' => Controls_Manager::SLIDER,
                                    'size_units' => [ '%' ],
                                    'default' => [
                                            'unit' => '%',
                                            'size' => '50',
                                    ],
                                    'selectors' => [
                                            '{{WRAPPER}} {{CURRENT_ITEM}}' =>
                                                            'top: {{SIZE}}%; --hotspot-translate-y: {{SIZE}}%;',
                                    ],
                            ]
                    );



	 	$repeater->end_controls_tab();

		$repeater->end_controls_tabs();


     
        $this->add_control(
			'list',
			[
				'label' => __( 'Repeater List', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					
				],
				'title_field' => '{{{ hotspot_label  }}}',
			]
		);
	
		$this->end_controls_section();

		
                
                
                /**
		 * Tooltip Section
		 */
		$this->start_controls_section(
			'tooltip_section',
			[
				'label' => esc_html__( 'Tooltip', 'wpresidence-elementor'),
			]
		);

		$this->add_responsive_control(
			'tooltip_position',
			[
				'label' => esc_html__( 'Position', 'wpresidence-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'top',
				'toggle' => false,
				'options' => [
					'right' => [
						'title' => esc_html__( 'Left', 'wpresidence-elementor'),
						'icon' => 'eicon-h-align-left',
					],
					'bottom' => [
						'title' => esc_html__( 'Top', 'wpresidence-elementor'),
						'icon' => 'eicon-v-align-top',
					],
					'left' => [
						'title' => esc_html__( 'Right', 'wpresidence-elementor'),
						'icon' => 'eicon-h-align-right',
					],
					'top' => [
						'title' => esc_html__( 'Bottom', 'wpresidence-elementor'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpestate_hotspot_tooltip' => 'right: initial;bottom: initial;left: initial;top: initial;{{VALUE}}: calc(100% + 5px );',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'tooltip_action',
			[
				'label' => esc_html__( 'Tooltip display on ?', 'wpresidence-elementor'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'hover' => esc_html__( 'Hover', 'wpresidence-elementor'),
					'click' => esc_html__( 'Click', 'wpresidence-elementor'),
			
				],
				'default' => 'click',
				'frontend_available' => true,
			]
		);

	


		$this->end_controls_section();
                
                
                    /**
		 *  Hotspot  styuling
                 * 
		 */
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'wpresidence-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
                
                $this->add_control(
			'mainimge_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpresidence-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
                                    '{{WRAPPER}} .wpestate_hotspot_main_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                    '{{WRAPPER}} .wpestate_hotspot_main_image_overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                    
                                    
				],
				'default' => [
					'unit' => 'px',
				],
			]
		);

                
                $this->add_control(
                    'overlay_color', [
                    'label' => esc_html__('Image Overlay color', 'residence-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .wpestate_hotspot_main_image_overlay' => 'background-color: {{VALUE}}',
                    ],
                        ]
                );
                
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_hotspot_box_shadow',
				'selector' => '
					{{WRAPPER}} .wpestate_hotspot_main_image
				',
			]
		);
                
                
                
                
                $this->end_controls_section();
                /**
		 *  Hotspot  styuling
                 * 
		 */
		$this->start_controls_section(
			'section_style_hotspot',
			[
				'label' => esc_html__( 'Hotspot', 'wpresidence-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		
                
                $this->add_control(
                    'hotspot_color', [
                    'label' => esc_html__('Color', 'residence-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .wpestate_hotspot' => 'color: {{VALUE}}',
                    ],
                        ]
                );
                $this->add_control(
                    'hotspot_hover_color', [
                    'label' => esc_html__(' Hover Color', 'residence-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .wpestate_hotspot:hover' => 'color: {{VALUE}}',
                    ],
                        ]
                );
                  $this->add_control(
                    'hotspot_back_color', [
                    'label' => esc_html__(' Hotspot Background Color', 'residence-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .wpestate_hotspot' => 'background-color: {{VALUE}}',
                    ],
                        ]
                );
                  $this->add_control(
                    'hotspot_hover_back_color', [
                    'label' => esc_html__(' Hotspot Hover Background Color', 'residence-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .wpestate_hotspot:hover' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .wpestate_hotspot:before' => 'background-color: {{VALUE}}',
                    
                    ],
                        ]
                );

		$this->add_responsive_control(
			'hotspot_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpresidence-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wpestate_hotspot_icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                                    '{{WRAPPER}} .wpestate_hotspot_icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

                    $this->add_group_control(
                        Group_Control_Typography::get_type(), [
                    'name' => 'hotspot_typografy',
                    'label' => esc_html__('Typography', 'residence-elementor'),
                    'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .wpestate_hotspot_label',
                        ]
                );



		$this->add_responsive_control(
			'hotspot_padding',
			[
				'label' => esc_html__( 'Padding', 'wpresidence-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wpestate_hotspot' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
				],
			]
		);

		$this->add_control(
			'hotspot_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpresidence-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpestate_hotspot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_hotspot_box_shadow',
				'selector' => '
					{{WRAPPER}} .wpestate_hotspot
				',
			]
		);

		$this->end_controls_section();
                
                
                
                   /**
		 *  Hotspot  styuling
                 * 
		 */
		$this->start_controls_section(
			'section_style_tooltip',
			[
				'label' => esc_html__( 'ToolTip', 'wpresidence-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

                
                  $this->add_control(
                    'hotspot_tooltip_color', [
                    'label' => esc_html__(' Tooltip Color', 'residence-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .wpestate_hotspot_tooltip' => 'color: {{VALUE}}',
                    ],
                        ]
                );
                  $this->add_control(
                    'tooltop_back_color', [
                    'label' => esc_html__(' Tooltip  Background Color', 'residence-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .wpestate_hotspot_tooltip ' => 'background-color: {{VALUE}}',
                    ],
                        ]
                );
                  
                    $this->add_group_control(
                        Group_Control_Typography::get_type(), [
                    'name' => 'tooltop_typografy',
                    'label' => esc_html__('Typography', 'residence-elementor'),
                    'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .wpestate_hotspot_tooltip',
                        ]
                );
                  
                    
                $this->add_responsive_control(
			'tooltip_width',
			[
				'label' => esc_html__( 'Tooltip width', 'wpresidence-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wpestate_hotspot_tooltip' => 'width: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
				],
			]
		);
                    
                    
                    $this->add_responsive_control(
			'tooltip_padding',
			[
				'label' => esc_html__( 'Padding', 'wpresidence-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wpestate_hotspot_tooltip' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
				],
			]
		);
                  
                    $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_tooltip_box_shadow',
				'selector' => '
					{{WRAPPER}} .wpestate_hotspot_tooltip 
				',
			]
		);
                    
                    
                    $this->add_control(
			'tooltip_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpresidence-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpestate_hotspot_tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
				],
			]
		);
                $this->end_controls_section();
                
                
                
                
                
                
                

	}

    protected function render() {
        global $post;
        $settings = $this->get_settings_for_display();
        $is_tooltip_direction_animation ='';
        $show_tooltip = 'none';
        print '<div class="wpestate_hotspot_wrapper">';

      
        print '<div class="wpestate_hotspot_main_image_overlay"></div>';

        print '<img class="wpestate_hotspot_main_image" src="'.esc_url($settings['image']['url']).'" alt="hotspot">';
        
        foreach ( $settings['list'] as $key => $hotspot ) :
			$is_circle = ! $hotspot['hotspot_label'] && ! $hotspot['hotspot_icon']['value'];
			$is_only_icon = ! $hotspot['hotspot_label'] && $hotspot['hotspot_icon']['value'];
			$hotspot_position_x = '%' === $hotspot['hotspot_offset_x']['unit'] ? 'wpestate-position' : '';
			$hotspot_position_y = '%' === $hotspot['hotspot_offset_y']['unit'] ? 'wpestate-position'  : '';
			$is_hotspot_link = ! empty( $hotspot['hotspot_link']['url'] );
			$hotspot_element_tag = $is_hotspot_link ? 'a' : 'div';

			// hotspot attributes
			$hotspot_repeater_setting_key = $this->get_repeater_setting_key( 'hotspot', 'hotspots', $key );
			$this->add_render_attribute(
				$hotspot_repeater_setting_key, [
					'class' => [
						'wpestate_hotspot',
						'elementor-repeater-item-' . $hotspot['_id'],
						$hotspot_position_x,
						$hotspot_position_y,
						
					],
				]
			);
			
			if ( $is_only_icon ) {
				$this->add_render_attribute( $hotspot_repeater_setting_key, 'class', 'wpestate-hotspotx-icon' );
			}

			if ( $is_hotspot_link ) {
				$this->add_link_attributes( $hotspot_repeater_setting_key, $hotspot['hotspot_link'] );
			}

			// hotspot trigger attributes
			$trigger_repeater_setting_key = $this->get_repeater_setting_key( 'trigger', 'hotspots', $key );
			$this->add_render_attribute(
				$trigger_repeater_setting_key, [
					'class' => [
						'wpestate_hotspot_icon_wrapper',

					],
				]
			);

			//direction mask attributes
			$direction_mask_repeater_setting_key = $this->get_repeater_setting_key( 'e-hotspot__direction-mask', 'hotspots', $key );
			$this->add_render_attribute(
				$direction_mask_repeater_setting_key, [
					'class' => [
						'e-hotspot__direction-mask',
						( $is_tooltip_direction_animation ) ? 'e-hotspot--tooltip-position' : '',
					],
				]
			);

			//tooltip attributes
			$tooltip_custom_position = ( $is_tooltip_direction_animation && $hotspot['hotspot_tooltip_position'] && $hotspot['hotspot_position'] ) ? 'e-hotspot--override-tooltip-animation-from-' . $hotspot['hotspot_position'] : '';
			$tooltip_repeater_setting_key = $this->get_repeater_setting_key( 'tooltip', 'hotspots', $key );
			$this->add_render_attribute(
				$tooltip_repeater_setting_key, [
					'class' => [
						'wpestate_hotspot_tooltip',
						$tooltip_custom_position,
					],
				]
			); ?>

			<?php // Hotspot ?>
			<<?php Utils::print_validated_html_tag( $hotspot_element_tag ); ?> <?php $this->print_render_attribute_string( $hotspot_repeater_setting_key ); ?>>

				<?php // Hotspot Trigger ?>
				<div <?php $this->print_render_attribute_string( $trigger_repeater_setting_key ); ?>>
					<?php if ( $is_circle ) : ?>
						<div class="e-hotspot__outer-circle"></div>
						<div class="e-hotspot__inner-circle"></div>
					<?php else : ?>
						<?php if ( $hotspot['hotspot_icon']['value'] ) : ?>
							<div class="wpestate_hotspot_icon"><?php Icons_Manager::render_icon( $hotspot['hotspot_icon'] ); ?></div>
						<?php endif; ?>
						<?php if ( $hotspot['hotspot_label'] ) : ?>
							<div class="wpestate_hotspot_label"><?php
								
								echo $hotspot['hotspot_label']; 
							?></div>
						<?php endif; ?>
					<?php endif; ?>
				</div>

				<?php // Hotspot Tooltip ?>
				<?php if ( $hotspot['tooltip_text'] && ! (   $is_hotspot_link ) ) : ?>
					<?php if ( $is_tooltip_direction_animation ) : ?>
						<div <?php $this->print_render_attribute_string( $direction_mask_repeater_setting_key ); ?>>
					<?php endif; ?>
					<div <?php $this->print_render_attribute_string( $tooltip_repeater_setting_key ); ?> >
						<?php
						
						echo $hotspot['tooltip_text'];
						?>
					</div>
					<?php if ( $is_tooltip_direction_animation ) : ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
                             
			</<?php Utils::print_validated_html_tag( $hotspot_element_tag ); ?>>

	<?php endforeach; 
        
        
        print '</div>';
    
 print ' <script type="text/javascript">
             //<![CDATA[
             jQuery(document).ready(function(){';
                if($settings['tooltip_action']=='hover'){
                    ?>
                    wpestate_hotspots_hover();
                    <?php
                }else{
                      ?>
                    wpestate_hotspots_click();
                    <?php
                }
              
             print '});
             //]]>
         </script>';
        

    }

   

  

}

//end class
