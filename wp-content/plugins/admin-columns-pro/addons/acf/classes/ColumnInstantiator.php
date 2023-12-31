<?php

namespace ACA\ACF;

final class ColumnInstantiator {

	/**
	 * @var ConfigFactory
	 */
	private $config_factory;

	/**
	 * @var Search\ComparisonFactory
	 */
	private $search_factory;

	/**
	 * @var Sorting\ModelFactory
	 */
	private $sorting_factory;

	/**
	 * @var Editing\EditingModelFactory
	 */
	private $editing_factory;

	/**
	 * @var Filtering\ModelFactory
	 */
	private $filtering_factory;

	/**
	 * @var ConditionalFormatting\FormattableFactory
	 */
	private $formattable_factory;



	public function __construct(
		ConfigFactory $config_factory,
		Search\ComparisonFactory $search_factory,
		Sorting\ModelFactory $sorting_factory,
		Editing\EditingModelFactory $editing_factory,
		Filtering\ModelFactory $filtering_factory,
		ConditionalFormatting\FormattableFactory  $formattable_factory
	) {
		$this->config_factory = $config_factory;
		$this->search_factory = $search_factory;
		$this->sorting_factory = $sorting_factory;
		$this->editing_factory = $editing_factory;
		$this->filtering_factory = $filtering_factory;
		$this->formattable_factory = $formattable_factory;
	}

	public function initiate( Column $column ) {
		$config = $this->config_factory->create( $column->get_type() );

		if ( ! $config ) {
			return;
		}

		$column->set_config( $config );
		$column->set_label( $column->get_field()->get_settings()['label'] );

		if ( $column instanceof Search\SearchFactoryAware ) {
			$column->set_search_comparison_factory( $this->search_factory );
		}

		if ( $column instanceof Sorting\SortingFactoryAware ) {
			$column->set_sorting_model_factory( $this->sorting_factory );
		}

		if ( $column instanceof Editing\EditingFactoryAware ) {
			$column->set_editing_model_factory( $this->editing_factory );
		}

		if ( $column instanceof Filtering\FilteringFactoryAware ) {
			$column->set_filtering_model_factory( $this->filtering_factory );
		}

		if( $column instanceof ConditionalFormatting\FormattableFactoryAware ){
			$column->set_formattable_factory( $this->formattable_factory );
		}
	}

}