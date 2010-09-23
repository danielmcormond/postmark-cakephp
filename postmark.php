<?php
/**
 * Postmark Component
 *
 * Copyright 2010, Daniel McOrmond
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author	Daniel McOrmond
 * @copyright	Copyright 2010, Daniel McOrmond
 * @version	0.1
 * @license	http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Component', 'Email');

/**
 * PostmarkComponent
 *
 * This component is used for sending email messages
 * using the Postmark API http://postmarkapp.com/
 *
 */
class PostmarkComponent extends EmailComponent {

/**
 * Postmark API URI
 *
 * @var string
 * @access public
 */
	var $uri = 'http://api.postmarkapp.com/email';

/**
 * Postmark API Key
 *
 * @var string
 * @access public
 */
	var $key = null;

/**
 * Postmark Tag property
 *
 * @var string
 * @access public
 */
	var $tag = null;

/**
 * Variable that holds Postmark connection
 *
 * @var resource
 * @access private
 */
	var $__postmarkConnection = null;

/**
 * Initialize component
 *
 * @param object $controller Instantiating controller
 * @access public
 */
	function initialize(&$controller, $settings = array()) {
		parent::initialize($controller, $settings);
		if (Configure::read('Postmark.uri') !== null) {
			$this->uri = Configure::read('Postmark.uri');
		}
		if (Configure::read('Postmark.key') !== null) {
			$this->key = Configure::read('Postmark.key');
		}
	}

/**
 * Sends out email via Postmark
 *
 * @return bool Success
 * @access private
 */
	function _postmark() {
		App::import('Core', 'HttpSocket');

		// Setup connection
		$this->__postmarkConnection =& new HttpSocket();

		// Construct message
		$message = array();

		// To
		$message['From'] = $this->_formatAddress($this->from);
		if (is_array($this->to)) {
			$message['To'] = implode(', ', array_map(array($this, '_formatAddress'), $this->to));
		} else {
			$message['To'] = $this->_formatAddress($this->to);
		}

		// Cc
		if (!empty($this->cc)) {
			if (is_array($this->cc)) {
				$message['Cc'] = implode(', ', array_map(array($this, '_formatAddress'), $this->cc));
			} else {
				$message['Cc'] = $this->_formatAddress($this->cc);
			}
		}

		// Bcc
		if (!empty($this->bcc)) {
			if (is_array($this->bcc)) {
				$message['Bcc'] = implode(', ', array_map(array($this, '_formatAddress'), $this->bcc));
			} else {
				$message['Bcc'] = $this->_formatAddress($this->bcc);
			}
		}

		// Subject
		$message['Subject'] = $this->_encode($this->subject);

		// Tag
		if (!empty($this->tag)) {
			$message['Tag'] = $this->tag;
		}

		// HtmlBody
		if ($this->sendAs === 'html' || $this->sendAs === 'both') {
			$message['HtmlBody'] = $this->htmlMessage;
		}

		// TextBody
		$message['TextBody'] = $this->textMessage;

		// ReplyTo
		if (!empty($this->replyTo)) {
			$message['ReplyTo'] = $this->_formatAddress($this->replyTo);
		}

		// Setup header
		$request = array(
			'header' => array(
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				'X-Postmark-Server-Token' => $this->key,
			),
		);

		// Send message
		return json_decode($this->__postmarkConnection->post($this->uri, json_encode($message), $request), true);
	}
}
