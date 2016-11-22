<?php

class PIXAN_API_Output {

	private static $instance = null;

	/**
	 * Get singleton instance of class
	 *
	 * @return null|PIXAN_Output
	 */
	public static function get() {

		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Constructor
	 */
	private function __construct() {
	}

	/**
	 * The correct way to ouput data in a webservice call
	 *
	 * @param $data
	 */
	public function output( $status, $code = 200,$message = '', $data = array() ) {

		$tmp_data = array(
			'status' => ($status)?'ok':'error',
			'status_code' => $code,
		);

		if( !empty($message) ){
			$tmp_data['message'] = $message;
		}



		if( !empty($data) ){
			$tmp_data['data'] = $data;
		}

		echo json_encode( $tmp_data );
		exit();
	}

} 