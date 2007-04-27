<?php 
/// Copyright (c) 2004-2007, Needlworks / Tatter Network Foundation
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/doc/LICENSE, /doc/COPYRIGHT)

$confirmString = '';

if (empty($comment['name']) && isset($_COOKIE['guestName']))
	$comment['name'] = $_COOKIE['guestName'];
if ((empty($comment['homepage']) || $comment['homepage'] == 'http://') && isset($_COOKIE['guestHomepage']) && $_COOKIE['guestHomepage'] != 'http://')
	$comment['homepage'] = $_COOKIE['guestHomepage'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
	<title><?php echo $pageTitle ;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $service['path'] . $adminSkinSetting['skin'];?>/popup-comment.css" />
	<script type="text/javascript">
		//<![CDATA[
			var servicePath = "<?php echo $service['path'];?>";
			var blogURL = "<?php echo $blogURL;?>";
			var adminSkin = "<?php echo $adminSkinSetting['skin'];?>";
		//]]>
	</script>
	<script type="text/javascript" src="<?php echo $service['path'];?>/script/common2.js"></script>
	<script type="text/javascript">
		//<![CDATA[
			function submitComment() {
				var oForm = document.commentToComment;
				trimAll(oForm);
<?php 
if (!doesHaveMembership()) {
?>
				if (!checkValue(oForm.name, '<?php echo _text('이름을 입력해 주십시오.');?>')) return false;
<?php 
}
?>
				if (!checkValue(oForm.comment, '<?php echo _text('댓글을 입력해 주십시오.');?>')) return false;
				oForm.submit();
			}
			function confirmOverwrite() {
				return confirm('<?php echo _text('관리자가 방문객의 댓글을 수정하시면 작성자 이름을 관리자 아이디로 덮어 쓰게 됩니다.\n계속 하시겠습니까?');?>');
			}
		//]]>
	</script>
</head>
<?php
 if (doesHaveOwnership())
 	$writerClass = ' class="admin-comment"';
 else
	$writerClass = '';

if (!doesHaveMembership()) {
?>
<body<?php echo $writerClass;?> onLoad="document.commentToComment.name.focus()">
<?php 
} else {
?>
<body<?php echo $writerClass;?> onload="document.commentToComment.comment.focus()">
<?php 
}
?>
	<form name="commentToComment" method="post" action="<?php echo $suri['url'];?>">
		<input type="hidden" name="mode" value="commit" />
		<input type="hidden" name="oldPassword" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '';?>" />
		
		<div id="comment-reply-box">
			<img src="<?php echo $service['path'] . $adminSkinSetting['skin'];?>/image/img_comment_popup_logo.gif" alt="<?php echo _text('텍스트큐브 로고');?>" />
			
			<div class="title"><span class="text"><?php echo $pageTitle ;?></span></div>
	      	<div id="command-box">
<?php 
if (!doesHaveOwnership()) {
	if (!doesHaveMembership()) {
?>
				<dl class="name-line">
					<dt><label for="name"><?php echo _text('이름');?></label></dt>
					<dd><input type="text" id="name" class="input-text" name="name" value="<?php echo htmlspecialchars($comment['name']);?>" /></dd>
				</dl>
				<dl class="password-line">
					<dt><label for="password"><?php echo _text('비밀번호');?></label></dt>
					<dd><input type="password" class="input-text" id="password" name="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '';?>" /></dd>
				</dl>
    			<dl class="homepage-line">
					<dt><label for="homepage"><?php echo _text('홈페이지');?></label></dt>
					<dd><input type="text" class="input-text" id="homepage" name="homepage" value="<?php echo (empty($comment['homepage']) ? 'http://' : htmlspecialchars($comment['homepage']));?>" /></dd>
				</dl>
<?php 
	}
?>
				<dl class="secret-line">
					<dd>
						<input type="checkbox" class="checkbox" id="secret" name="secret"<?php echo ($comment['secret'] ? ' checked="checked"' : false);?> />
						<label for="secret"><?php echo _text('비밀글로 등록');?></label>
					</dd>
				</dl>
	<?php 
}

if (doesHaveOwnership() && array_key_exists('replier', $comment) && (is_null($comment['replier']) || ($comment['replier'] != getUserId()))) {
	$confirmString = "if( confirmOverwrite() )";
}
?>			
				<dl class="content-line">
					<dt><label for="comment"><?php echo _text('내용');?></label></dt>
					<dd><textarea id="comment" name="comment" cols="45" rows="9" style="height: <?php echo (!doesHaveOwnership() && !doesHaveOwnership()) ? 150 : 242;?>px;"><?php echo htmlspecialchars($comment['comment']);?></textarea></dd>
				</dl>
				
				<div class="button-box">
					<input type="button" class="input-button" value="<?php echo _text('완료');?>" onclick="<?php echo $confirmString;?> submitComment()" />
				</div>
			</div>
		</div>
	</form>
</body>
</html>
