<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(1, 'color');

// hook admin_style_start.php

if($action == 'color') {
	
	// hook admin_style_color_get_post.php
	
	if($method == 'GET') {
		
		// hook admin_style_color_get_start.php
		
		$header['title']        = lang('admin_style_color');
		$header['mobile_title'] = lang('admin_style_color');
		
		// 默认配色
		$style = array(
			'header_bg'      => array_value($conf, 'style_header_bg', '#343a40'),
			'header_text'    => array_value($conf, 'style_header_text', '#ffffff'),
			'footer_bg'      => array_value($conf, 'style_footer_bg', '#343a40'),
			'footer_text'    => array_value($conf, 'style_footer_text', '#999999'),
			'primary'        => array_value($conf, 'style_primary', '#1f4977'),
			'body_bg'        => array_value($conf, 'style_body_bg', '#ffffff'),
			'breadcrumb_bg'  => array_value($conf, 'style_breadcrumb_bg', '#e9ecef'),
			'cardheader_bg'  => array_value($conf, 'style_cardheader_bg', '#f7f7f9'),
			'threadlist_bg'  => array_value($conf, 'style_threadlist_bg', '#ffffff'),
			'aside_bg'       => array_value($conf, 'style_aside_bg', '#ffffff'),
		);
		
		$input = array();
		$input['header_bg']      = form_text('header_bg', $style['header_bg']);
		$input['header_text']    = form_text('header_text', $style['header_text']);
		$input['footer_bg']      = form_text('footer_bg', $style['footer_bg']);
		$input['footer_text']    = form_text('footer_text', $style['footer_text']);
		$input['primary']        = form_text('primary', $style['primary']);
		$input['body_bg']        = form_text('body_bg', $style['body_bg']);
		$input['breadcrumb_bg']  = form_text('breadcrumb_bg', $style['breadcrumb_bg']);
		$input['cardheader_bg']  = form_text('cardheader_bg', $style['cardheader_bg']);
		$input['threadlist_bg']  = form_text('threadlist_bg', $style['threadlist_bg']);
		$input['aside_bg']       = form_text('aside_bg', $style['aside_bg']);
		
		// hook admin_style_color_get_end.php
		
		include _include(ADMIN_PATH."view/htm/style_color.htm");
	
	} elseif($method == 'POST') {
		
		$header_bg      = param('header_bg', '#343a40', FALSE);
		$header_text    = param('header_text', '#ffffff', FALSE);
		$footer_bg      = param('footer_bg', '#343a40', FALSE);
		$footer_text    = param('footer_text', '#999999', FALSE);
		$primary        = param('primary', '#1f4977', FALSE);
		$body_bg        = param('body_bg', '#ffffff', FALSE);
		$breadcrumb_bg  = param('breadcrumb_bg', '#e9ecef', FALSE);
		$cardheader_bg  = param('cardheader_bg', '#f7f7f9', FALSE);
		$threadlist_bg  = param('threadlist_bg', '#ffffff', FALSE);
		$aside_bg       = param('aside_bg', '#ffffff', FALSE);
		
		// hook admin_style_color_post_start.php
		
		// 简单校验颜色值
		foreach(array($header_bg, $header_text, $footer_bg, $footer_text, $primary, $body_bg, $breadcrumb_bg, $cardheader_bg, $threadlist_bg, $aside_bg) as $color) {
			if(!preg_match('/^#[0-9a-fA-F]{3,8}$/', $color)) {
				message(-1, lang('style_color_invalid', array('color'=>$color)));
			}
		}
		
		$replace = array();
		$replace['style_header_bg']      = $header_bg;
		$replace['style_header_text']    = $header_text;
		$replace['style_footer_bg']      = $footer_bg;
		$replace['style_footer_text']    = $footer_text;
		$replace['style_primary']        = $primary;
		$replace['style_body_bg']        = $body_bg;
		$replace['style_breadcrumb_bg']  = $breadcrumb_bg;
		$replace['style_cardheader_bg']  = $cardheader_bg;
		$replace['style_threadlist_bg']  = $threadlist_bg;
		$replace['style_aside_bg']       = $aside_bg;
		
		$r = file_replace_var(APP_PATH.'conf/conf.php', $replace);
		$r === FALSE AND message(-1, lang('style_save_failed'));
		
		// hook admin_style_color_post_end.php
		
		message(0, lang('save_successfully'));
	}
}

// hook admin_style_end.php

?>
