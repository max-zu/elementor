<?php

namespace Elementor\Modules\PlayingCards\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Playing Card widget.
 *
 * Elementor widget that displays a playing card with a number and a suit.
 *
 * @since 3.16.0
 */
class PlayingCardsWidget extends Widget_Base {
	private const CARD_OPTIONS = [
		'A' => 'A',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
		'6' => '6',
		'7' => '7',
		'8' => '8',
		'9' => '9',
		'10' => '10',
		'J' => 'J',
		'Q' => 'Q',
		'K' => 'K',
	];

	private const SUITES  = [
		'♠️' => [
			'name' => 'Spades',
			'color' => 'black',
		],
		'♣️' => [
			'name' => 'Club',
			'color' => 'black',
		],
		'♦️'=> [
			'name' => 'Diamonds',
			'color' => 'red',
		],
		'❤️' => [
			'name' => 'Hearts',
			'color' => 'red',
		],
	];
	public function get_name() {
		return 'playing-card';
	}

	public function get_title() {
		return esc_html__( 'Playing Cards', 'elementor' );
	}

	public function get_icon() {
		return 'eicon-shape';
	}
	protected function register_controls() {
		$this->register_content_section();
		$this->register_style_section();
	}

	private function register_content_section() {
		$repeater = new Repeater();

		$this->start_controls_section(
			'section_playing_cards',
			[
				'label' => esc_html__( 'Playing Cards', 'elementor' ),
				''
			]
		);

		$repeater->start_controls_tabs( 'cards_repeater' );

		$repeater->start_controls_tab(
			'content',
			[
				'label' => esc_html__( 'Content', 'elementor' ),
			]
		);

		$repeater->add_control(
			'card_value',
			[
				'label' => esc_html__( 'Card Value', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'A',
				'options' => PlayingCardsWidget::CARD_OPTIONS,
			]
		);

		$repeater->add_control(
			'card_suit',
			[
				'label' => esc_html__( 'Card Suit', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '♦️',
				'options' => array_map( fn ( $suite ) => $suite['name'], PlayingCardsWidget::SUITES ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'style',
			[
				'label' => esc_html__( 'Style', 'elementor' ),
			]
		);

		$repeater->add_responsive_control(
			'card_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '--e-playing-cards-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$repeater->end_controls_tab();

		$this->add_control(
			'cards_list',
			[
				'label' => esc_html__( 'Playing Cards', 'elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'playing_card' => [
							'card_value' => 'A',
							'card_suit' => '❤️',
						],
					],
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_style_section() {
		$this->start_controls_section(
			'section_playing_cards_style',
			[
				'label' => esc_html__( 'Playing Cards', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_render_attribute(
			'playing_cards_item',
			[
				'class' => 'e-playing-cards-item',
			]
		);

		$this->add_control(
			'playing_cards_item_space_between',
			[
				'label' => esc_html__( 'Space between Items', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-playing-cards-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'card_size',
			[
				'label' => esc_html__( 'Card Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
					'px' => [
						'max' => 200,
						'step' => 10,
					],
				],
				'default' => [
					'size' => 200,
				],
				'tablet_default' => [
					'size' => 200,
				],
				'mobile_default' => [
					'size' => 200,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-playing-card-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'playing_card_box_shadow',
//				'selector' => '{{WRAPPER}} .e-playing-cards_card-box-shadow',
				'selectors' => [
					'{{CURRENT_ITEM}}' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		echo '<div class="e-playing-cards">';
		foreach ( $settings['cards_list'] as $index => $item ) {
			$this->render_card_item( 'card_' . $index, $item );
		}
		echo '</div>';
	}

	private function render_card_item( $card_id, $card ) {
		$card_number      = $card['card_value'];
		$card_suit        = $card['card_suit'];
		$card_color_class = PlayingCardsWidget::SUITES[ $card_suit ]['color'] . '_suit';

		$this->add_render_attribute( $card_id, 'class', [
			'e-playing-cards-item',
			'elementor-repeater-item-' . $card['_id'],
			$card_color_class,
		]);

		?>
			<div <?php $this->print_render_attribute_string( $card_id ); ?>>
				<div class="e-playing-cards-item-top"><?php echo esc_html( $card_suit ); ?></div>
				<div class="e-playing-cards-item-center e-playing-cards-item-number"><?php echo esc_html( $card_number ); ?></div>
				<div class="e-playing-cards-item-bottom"><?php echo esc_html( $card_suit ); ?></div>
			</div>
		<?php
	}
}