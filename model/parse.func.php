<?php

// hook model_parse_start.php

// Markdown 轻量解析（常用子集）：标题、加粗、斜体、删除线、行内代码、代码块、引用、无序/有序列表、链接、图片、分割线
function xn_markdown_to_html($s) {
	// hook xn_markdown_to_html_start.php
	
	$s = str_replace("\r\n", "\n", $s);
	$s = str_replace("\r", "\n", $s);
	$lines = explode("\n", $s);
	$out = array();
	$in_code = FALSE;
	$in_ul = FALSE;
	$in_ol = FALSE;
	
	foreach($lines as $line) {
		// 代码块
		if(preg_match('/^```/', $line)) {
			if($in_code) {
				$out[] = '</code></pre>';
				$in_code = FALSE;
			} else {
				$out[] = '<pre><code>';
				$in_code = TRUE;
			}
			continue;
		}
		if($in_code) {
			$out[] = htmlspecialchars($line);
			continue;
		}
		
		$trim = trim($line);
		
		// 分割线
		if(preg_match('/^(-{3,}|\*{3,}|_{3,})$/', $trim)) {
			$out[] = '<hr>';
			continue;
		}
		
		// 标题
		if(preg_match('/^(#{1,6})\s+(.*)$/', $trim, $m)) {
			$n = strlen($m[1]);
			$out[] = "<h$n>".xn_markdown_inline($m[2])."</h$n>";
			continue;
		}
		
		// 引用
		if(preg_match('/^>\s?(.*)$/', $trim, $m)) {
			$out[] = '<blockquote>'.xn_markdown_inline($m[1]).'</blockquote>';
			continue;
		}
		
		// 无序列表
		if(preg_match('/^[-*+]\s+(.*)$/', $trim, $m)) {
			if(!$in_ul) { $out[] = '<ul>'; $in_ul = TRUE; }
			$out[] = '<li>'.xn_markdown_inline($m[1]).'</li>';
			continue;
		} else {
			if($in_ul) { $out[] = '</ul>'; $in_ul = FALSE; }
		}
		
		// 有序列表
		if(preg_match('/^\d+\.\s+(.*)$/', $trim, $m)) {
			if(!$in_ol) { $out[] = '<ol>'; $in_ol = TRUE; }
			$out[] = '<li>'.xn_markdown_inline($m[1]).'</li>';
			continue;
		} else {
			if($in_ol) { $out[] = '</ol>'; $in_ol = FALSE; }
		}
		
		// 空行
		if($trim === '') {
			$out[] = '';
			continue;
		}
		
		// 普通段落
		$out[] = '<p>'.xn_markdown_inline($trim).'</p>';
	}
	
	if($in_code) $out[] = '</code></pre>';
	if($in_ul) $out[] = '</ul>';
	if($in_ol) $out[] = '</ol>';
	
	$html = implode("\n", $out);
	
	// hook xn_markdown_to_html_end.php
	return $html;
}

// Markdown 行内解析：加粗、斜体、删除线、行内代码、链接、图片
function xn_markdown_inline($s) {
	// hook xn_markdown_inline_start.php
	
	$s = htmlspecialchars($s);
	
	// 行内代码 `code`
	$s = preg_replace('/`([^`]+)`/', '<code>$1</code>', $s);
	
	// 图片 ![alt](url)
	$s = preg_replace('/!\[([^\]]*)\]\(([^)]+)\)/', '<img src="$2" alt="$1">', $s);
	
	// 链接 [text](url)
	$s = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank">$1</a>', $s);
	
	// 加粗 **text** 或 __text__
	$s = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $s);
	$s = preg_replace('/__([^_]+)__/', '<strong>$1</strong>', $s);
	
	// 斜体 *text* 或 _text_
	$s = preg_replace('/(?<!\*)\*([^*]+)\*(?!\*)/', '<em>$1</em>', $s);
	
	// 删除线 ~~text~~
	$s = preg_replace('/~~([^~]+)~~/', '<del>$1</del>', $s);
	
	// hook xn_markdown_inline_end.php
	return $s;
}

// BBCode/UBB 解析
function xn_bbcode_to_html($s) {
	// hook xn_bbcode_to_html_start.php
	
	$s = htmlspecialchars($s);
	
	// 换行
	$s = nl2br($s);
	
	// 基础样式
	$s = preg_replace('/\[b\](.*?)\[\/b\]/is', '<strong>$1</strong>', $s);
	$s = preg_replace('/\[i\](.*?)\[\/i\]/is', '<em>$1</em>', $s);
	$s = preg_replace('/\[u\](.*?)\[\/u\]/is', '<u>$1</u>', $s);
	$s = preg_replace('/\[s\](.*?)\[\/s\]/is', '<del>$1</del>', $s);
	$s = preg_replace('/\[color=([^\]]+)\](.*?)\[\/color\]/is', '<span style="color:$1">$2</span>', $s);
	$s = preg_replace('/\[size=(\d+)\](.*?)\[\/size\]/is', '<span style="font-size:$1px">$2</span>', $s);
	
	// 链接
	$s = preg_replace('/\[url\](.*?)\[\/url\]/is', '<a href="$1" target="_blank">$1</a>', $s);
	$s = preg_replace('/\[url=([^\]]+)\](.*?)\[\/url\]/is', '<a href="$1" target="_blank">$2</a>', $s);
	
	// 图片
	$s = preg_replace('/\[img\](.*?)\[\/img\]/is', '<img src="$1">', $s);
	
	// 引用
	$s = preg_replace('/\[quote\](.*?)\[\/quote\]/is', '<blockquote>$1</blockquote>', $s);
	
	// 代码
	$s = preg_replace('/\[code\](.*?)\[\/code\]/is', '<pre><code>$1</code></pre>', $s);
	
	// 列表
	$s = preg_replace('/\[list\](.*?)\[\/list\]/is', '<ul>$1</ul>', $s);
	$s = preg_replace('/\[\*\](.*?)(?=\[\*\]|\[\\/list\]|$)/is', '<li>$1</li>', $s);
	
	// hook xn_bbcode_to_html_end.php
	return $s;
}

// hook model_parse_end.php

?>
