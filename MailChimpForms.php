<?php
// ensure that the script can't be executed outside of MediaWiki
if ( !defined( 'MEDIAWIKI' ) ) {
    die( 'Not a valid entry point.' );
}

//Avoid unstubbing $wgParser on setHook() too early on modern (1.12+) MW versions, as per r35980
if ( defined( 'MW_SUPPORTS_PARSERFIRSTCALLINIT' ) ) {
	$wgHooks['ParserFirstCallInit'][] = 'efMailChimpFormsSetup';
} else { // Otherwise do things the old fashioned way
	$wgExtensionFunctions[] = 'efMailChimpFormsSetup';
}

// Extension credits that will show up on Special:Version
$wgExtensionCredits['parserhook'][] = array(
	'name'         => 'MailChimpForms',
	'version'      => '1.0.2',
	'author'       => 'Andrew Mahr',
	'url'          => 'https://www.mediawiki.org/wiki/Extension:MailChimpForms',
	'description'  => 'Allows easy insertion of mailchimp forms via a special tag'
);
$wgMessagesDirs['MailChimpForms'] = __DIR__ . "/i18n";

function efMailChimpFormsSetup() {
	global $wgParser;
	$wgParser->setHook( 'mailchimpforms', 'efMailChimpForms' );
       return true;
}

function efMailChimpForms( $input, $args, $parser  ) {

/* The following lines can be used to get the variable values directly:
        $to = $args['to'] ;
        $email = $args['email'] ;
*/

	$account_id = 	urlencode($args['account']);
	$list_id = 		urlencode($args['list']);
	$type = 		$args['type'];
	$border_css =	isset($args['bordercss']) ? str_replace('"', '\"', $args['bordercss']) : '';
	$close_link = 	isset($args['closelink']) ? $args['closelink'] : '';
	$prefix	=	isset($args['prefix']) ? urlencode($args['prefix']) : '';

	if($close_link == 'true')
		$insert_close_link = '<a href="#" id="mc_embed_close" class="mc_embed_close">Close</a>';
	else
		$insert_close_link = '';
	if($border_css == 'none' || !isset($border_css))
		$border_style = "style='border: 0'";
	else
		$border_style = "style=\"border: {$border_css}\"";

	if($type == 'subscribe') {
               $inputMailLabel = wfMessage( 'mailchimpforms-input-email');
               $subscribetButtonText = wfMessage( 'mailchimpforms-suscribe-button');

		$form_code = <<<FORM
<!-- Begin MailChimp Signup Form -->
<!--[if IE]>
<style type="text/css" media="screen">
	#mc_embed_signup fieldset {position: relative;}
	#mc_embed_signup legend {position: absolute; top: -1em; left: .2em;}
</style>
<![endif]-->

<!--[if IE 7]>
<style type="text/css" media="screen">
	.mc-field-group {overflow:visible;}
</style>
<![endif]--><script type="text/javascript">
// delete this script tag and use a "div.mc_inline_error{ XXX !important}" selector
// or fill this in and it will be inlined when errors are generated
var mc_custom_error_style = '';
</script>
<script type="text/javascript" src="http://{$prefix}.us12.list-manage.com/js/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="http://{$prefix}.us12.list-manage.com/js/jquery.validate.js"></script>
<script type="text/javascript" src="http://{$prefix}.us12.list-manage.com/js/jquery.form.js"></script>
<script type="text/javascript" src="http://{$prefix}.us12.list-manage.com/subscribe/xs-js?u={$account_id}&amp;id={$list_id}"></script>
<div id="mc_embed_signup">
<form action="http://{$prefix}.us12.list-manage.com/subscribe/post?u={$account_id}&amp;id={$list_id}" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
	<fieldset {$border_style}>

<div class="mc-field-group">
<input type="text" value="" name="EMAIL" placeholder="Email" class="required email" id="mce-EMAIL">
</div>
		<div id="mce-responses">
			<div class="response" id="mce-error-response" style="display:none"></div>
			<div class="response" id="mce-success-response" style="display:none"></div>
		</div>
		<div><input type="submit" value="{$subscribetButtonText}" name="subscribe" id="mc-embedded-subscribe" class="btn" ></div>
	</fieldset>
	{$insert_close_link}
</form>
</div>
<!--End mc_embed_signup-->
FORM;

		} else {

			return '';

	}

	return $form_code;

}
