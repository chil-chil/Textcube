<?php
/// Copyright (c) 2004-2008, Needlworks / Tatter Network Foundation
/// All rights reserved. Licensed under the GPL.
/// See the GNU General Public License for more details. (/doc/LICENSE, /doc/COPYRIGHT)
define('__TEXTCUBE_IPHONE__', true);
require ROOT . '/lib/includeForBlog.php';
requireView('iphoneView');
if(empty($suri['id'])) {
	printIphoneHtmlHeader();
	?>
	<div class="toolbar">
		<h1 id="pageTitle"><?php echo htmlspecialchars(User::getName($blogid));?> Blog</h1>
		<a id="backButton" class="button" href="#"></a>
		<a class="button" href="#searchForm" onclick="searchAction(true);">Search</a>
	</div>
	<ul id="home" title="<?php echo htmlspecialchars(User::getName($blogid));?> Blog" selected="true">
	<?php
		$blogAuthor = User::getName($blogid);
		$blogLogo = !empty($blog['logo']) ? $blogURL . "/imageResizer?f=" . $blog['logo'] . "&m=80" : "{$service['path']}/style/iphone/image/textcube_logo.png";
		$itemsView .= '<li class="blog_info">'.CRLF;
		$itemsView .= '	<div class="logo"><img src="' . $blogLogo . '" /></div>'.CRLF;
		$itemsView .= '	<div class="blog_container">'.CRLF;
		$itemsView .= '		<span class="title">' . htmlspecialchars($blog['title']). '</span>'.CRLF;
		$itemsView .= '		<span class="author">by ' . $blogAuthor . '</span>'.CRLF;
		$itemsView .= '		<span class="description">' . htmlspecialchars($blog['description']) . '</span>'.CRLF;
		$itemsView .= '	</div>'.CRLF;
		$itemsView .= '</li>'.CRLF;
		print $itemsView;
	?>
		<li><a href="<?php echo $blogURL;?>/entry" class="link">Posts</a></li>
		<li><a href="#categories" class="link">Categories</a></li>
		<li><a href="#archives" class="link">Archives</a></li>
		<li><a href="#tags" class="link">Tags</a></li>
		<li><a href="<?php echo $blogURL;?>/link" class="link">Links</a></li>
	<?php
		if (doesHaveOwnership()) {
	?>
		<li><a href="<?php echo $blogURL;?>/logout" class="link logout">Logout</a></li>
	<?php
		}else{
	?>
		<li><a href="<?php echo $blogURL;?>/login" class="link">Login</a></li>
	<?php
		}
	?>
		<li><a href="#textcube" class="link"><span class="colorText"><span class="c1">T</span><span class="c2">e</span><span class="c3">x</span><span class="c4">t</span><span class="c5">c</span><span class="c6">u</span><span class="c7">b</span><span class="c8">e</span></span></a></li>
	</ul>

	<ul id="categories" title="Categories" selected="false">
	<?php
		$totalPosts = getEntriesTotalCount($blogid);
		$categories = getCategories($blogid);
		print printIphoneCategoriesView($totalPosts, $categories, true);	
	?>
	</ul>

	<ul id="archives" title="Archives" selected="false">
	<?php
		$archives = printIphoneArchives($blogid);
		print printIphoneArchivesView($archives);	
	?>
	</ul>

	<ul id="tags" title="Tags" selected="false">
		<li class="group"><span class="left">Random Tags (100)</span><span class="right">&nbsp;</span></li>
		<li class="panel">
		<div class="content padding5">
			<ul class="tag_list">
				<?php
					$tags = printIphoneTags($blogid, 'random', 100);
					print printIphoneTagsView($tags);	
				?>	
			</ul>
		</div>
		</li>
	</ul>

    <form id="searchForm" method="GET" class="dialog snug editorBar" action="<?php echo $blogURL;?>/search">
        <fieldset>
            <h1>Post Search</h1>
            <a class="button leftButton" type="cancel" onclick="searchAction(false);">Cancel</a>
            <a class="button blueButton" type="submit">Search</a>
            
            <div class="searchIcon"></div>
			<img id="clearButton" class="clearButton" src="<?php echo $service['path'];?>/image/spacer.gif" onclick="cancelAction(this);" />
			<input id="qString" type="text" name="search" autocomplete="off" unedited="true" class="search" onkeyup="searchKeywordCheck(this);" onkeydown="searchKeywordCheck(this);" />
		</fieldset>
    </form>

	<div id="textcube" title="TEXTCUBE" selected="false">
		<div class="textcubeLogo">&nbsp;</div>
		<div class="textcubeVersion">
			Brand yourself! : <?php echo TEXTCUBE_NAME;?> <?php echo TEXTCUBE_VERSION;?>
		</div>
		<div class="textcubeDescription">
			<ul>
				<li class="group">Textcube</li>
				<li>
					<?php echo _t('텍스트큐브(Textcube) 는 웹에서 자신의 생각이나 일상을 기록하고 표현하기 위한 도구입니다.').' '._t('텍스트큐브는 개인 사용자부터 서비스 구축까지 넓은 폭으로 사용할 수 있으며, 플러그인과 테마 시스템, 다국어 지원을 통하여 무한한 확장성을 제공합니다.');?><br/><br/>
					<?php echo _t('2007년 4월 태터앤프렌즈(TNF)는 태터 네트워크 재단(TNF, Tatter Network Foundation) 계획과 함께 적극적 참여 집단인 니들웍스(Needlworks) 를 발표하였습니다. 또한 태터툴즈를 기반으로 하는 오픈소스 블로그 소프트웨어인 S2 개발 계획도 발표하였습니다.').' '._t('2007년 4월 23일 TNF의 박용주님에 의하여 S2는 텍스트큐브로 명명 되었으며, 이후 개발 기간을 거쳐 2007년 8월 16일 TNF에 의하여 텍스트큐브의 첫 정식 버전인 텍스트큐브 1.5가 발표되었습니다.');?><br/><br/>
				</li>
			</ul>
		</div>
	</div>
<?php
	printIphoneHtmlFooter();
}
?>
