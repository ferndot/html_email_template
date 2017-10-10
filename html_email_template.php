<?php

/* Based on https://stackoverflow.com/a/17274461 */

class html_email_template {
	private $to, $subject, $additional_headers, $additional_parameters, $template;

	function __construct($to, $subject = '', $additional_headers = [], $additional_parameters = '') {
		// Required html email headers
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers = array_merge($headers, $additional_headers);

		$this->to = $to;
		$this->subject = $subject;
		$this->additional_headers = implode("\r\n", $headers);
		$this->additional_parameters = $additional_parameters;
	}

	function fillTemplate($templateVars = [], $templateURL = null) {
		// Init a new Template object with a path to the template
		if (!$templateURL) {
			$templateURL = __DIR__.'/blank.html';
		}

		// Get template file contents
		$template = file_get_contents($templateURL);

		// Insert variables into template
		if (count($templateVars)) {
			foreach ($templateVars as $key => $value) {
					$template = str_replace('{{ '.$key.' }}', $value, $template);
			}
		}

		// Save template
		$this->template = $template;
	}

	function send() {
		// Send mail
		if (!$this->template) {
			$this->template = '';
		}
		mail($this->to, $this->subject, $this->template, $this->additional_headers, $this->additional_parameters);
	}
}
