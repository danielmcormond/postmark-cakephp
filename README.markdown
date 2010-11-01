Postmark CakePHP component
==========================

Copyright 2010, Daniel McOrmond
Licensed under The MIT License
Redistributions of files must retain the above copyright notice.


Version
-------

Written for CakePHP 1.3+


Configuration
-------------

Add the following keys to your configuration:

	Configure::write('Postmark.uri', 'http://api.postmarkapp.com/email');
	Configure::write('Postmark.key', '810de23c-5ffb-44d9-a424-7a4a5c0fba1a');

If you want your connection to Postmark to be encrypted, simply change the uri to use https.

Make sure to modified the API key to match the credentials for your Postmark server rack instance.


Usage
-----

This component extends the base CakePHP email component, and works virtually the same.

Place the postmark.php file in your app/controllers/components/ folder.

In your controller, make sure the component is available:

	public $components = array('Postmark');   

Then, simply send messages like this:

	$this->Postmark->delivery = 'postmark';
	$this->Postmark->from = '<sender@domain.com>';
	$this->Postmark->to = '<recipient@domain.com>';
	$this->Postmark->subject = 'this is the subject';
	$messageBody = 'this is the message body';
	$this->Postmark->send($messageBody);

You can also use the following optional attributes:

	$this->Postmark->cc = '<recipient@domain.com>';
	$this->Postmark->bcc = '<recipient@domain.com>';
	$this->Postmark->replyTo = '<sender@domain.com>';

To send to multiple recipients, simply use an array of addresses in the 'to', 'cc', or 'bcc' fields. For example:

	$this->Postmark->cc = array('<recipient@domain.com>', '<another@domain.com>');

The syntax of all parameters is the same as the default CakePHP email component:

	http://book.cakephp.org/view/1283/Email

There is one additional attribute which can be used for setting the Postmark tag property:

	$this->Postmark->tag = 'contact';

For more information, see the Postmark API documentation:

	http://developer.postmarkapp.com/#message-format


Debugging
--------

You can see the response from Postmark in the return value when you send a message:

	$result = $this->Postmark->send($messageBody);
	$this->log($result, 'debug');

If there are any errors, they'll be included in the response. See the Postmark API documentation for error code detail:

	http://developer.postmarkapp.com/#api-error-codes
