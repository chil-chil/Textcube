<?php

function Recaptcha_AddInputValidatorRule($target, $mother) {
	$signed_in = (doesHaveOwnership() || doesHaveMembership());
	if ($mother == 'interface/blog/comment/add/') {
		$target['POST']['g-recaptcha-response'] = array('string', 'default' => '', 'mandatory' => !$signed_in);
	}
	return $target;
}

function Recaptcha_Header($target) {
	global $configVal, $pluginURL;
	$config = Setting::fetchConfigVal($configVal);
	if (!is_null($config) && isset($config['siteKey'])) {
		$target .= <<<EOS
<script src="https://www.google.com/recaptcha/api.js?render=explicit"></script>
<script type="text/javascript">
var recaptcha_wait_trials = 0;
function recaptchaWaitForElement(selector, cb) {
	var $ = jQuery;
	var finder = function() {
		var o = $(selector);
		if (o.length > 0) {
			recaptcha_wait_trials = 0;
			cb(o);
		} else {
			recaptcha_wait_trials ++;
			if (recaptcha_wait_trials > 50) {
				alert("Recaptcha Plugin: Cannot open the comment form! (5 sec timeout)");
			} else {
				window.setTimeout(finder, 100);
			}
		}
	};
	window.setTimeout(finder, 100);
}
</script>
EOS;
	}
	return $target;
}

function Recaptcha_Footer($target) {
	global $configVal, $pluginURL;
	$config = Setting::fetchConfigVal($configVal);
	if (!is_null($config) && isset($config['siteKey'])) {
		$target .= <<<EOS
<script type="text/javascript">
(function($) {
if (!doesHaveOwnership) {
	$('a[id^=commentCount]').click(function() {
		recaptchaWaitForElement('.write.comments button[type=submit]', function(o) {
			// TODO: entryId 넣어서 여러 form을 여는 경우 처리?
			var divId = 'comment_recaptcha';
			$(o).css({'display': 'inline-block'});
			$(o).before('<div style="display:inline-block; margin-right:1em; vertical-align:middle" id="' + divId + '"></div>');
			grecaptcha.render(divId, {
				'sitekey': '{$config['siteKey']}'
			});
		});
	});
}
})(jQuery);
</script>
EOS;
	}
	return $target;
}

function Recaptcha_ConfigHandler($data) {
	$config = Setting::fetchConfigVal($data);
	return true;
}

function Recaptcha_AddingCommentHandler($target, $mother)
{
	global $configVal, $pluginURL;
	$config = Setting::fetchConfigVal($configVal);
	if (doesHaveOwnership() || doesHaveMembership()) return true;  /* Skip validation if signed-in. */
	if (!is_null($config) && isset($config['secretKey'])) {
		$recaptcha_response = $_POST["g-recaptcha-response"];
		$reqURL = "https://www.google.com/recaptcha/api/siteverify?secret={$config['secretKey']}&response={$recaptcha_response}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $reqURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		if ($output === false) {
			Respond::PrintResult(array('error' => 2, 'description' => 'Cannot connect to the Google reCAPTCHA server.'));
			return false;
		} else {
			$resp = json_decode($output, true);
			if ($resp['success'] === true) {
				/* Yay! The user is human. */
				return true;
			} else {
				$err = implode(' ', $resp['error-codes']);
				if (strpos($err, 'missing-input-secret') !== false) {
					Respond::PrintResult(array('error' => 2, 'description' => 'Missing reCAPTCHA secret key!'));
				} elseif (strpos($err, 'missing-input-response') !== false) {
					Respond::PrintResult(array('error' => 2, 'description' => 'Missing reCAPTCHA response!'));
				} elseif (strpos($err, 'invalid-input-secret') !== false) {
					Respond::PrintResult(array('error' => 2, 'description' => 'Invalid reCAPTCHA secret key.'));
				} elseif (strpos($err, 'invalid-input-response') !== false) {
					Respond::PrintResult(array('error' => 2, 'description' => 'Invalid reCAPTCHA response.'));
				}
			}
		}
		/* It seems to be a robot! Silently fail. */
		return false;
	}
	/* If the plugin is not configured yet, bypass validation. */
	return true;
}

/* vim: set noet ts=4 sts=4 sw=4: */
?>