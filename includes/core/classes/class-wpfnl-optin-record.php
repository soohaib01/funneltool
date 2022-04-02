<?php

/**
 * Optin form record
 */

namespace WPFunnels\Optin;


class Optin_Record {

	public $form_data;

	public $fields = array();

	public function __construct( $form_data, $form = null ) {
		$this->form_data = $form_data;
		$this->set_fields();
	}


	private function set_fields() {
		if ($this->form_data) {
			foreach ($this->form_data as $key => $value) {
				$this->fields[$key] = $value;
			}
		}
	}


	public function get_fields() {
		return $this->fields;
	}
}
