/**
 * ブログ js
 *
 * @copyright     Copyright 2012, NetCommons Project
 * @package       Plugin.Blog.webroot.js
 * @author        Noriko Arai,Ryuji Masukawa
 * @since         v 3.0.0.0
 * @license       http://www.netcommons.org/license.txt  NetCommons License
 */
;(function($) {
	$.fn.Blog = function(id) {
		// 表示件数
		var chgLimit = function() {
			// 表示件数
			$('select.blog-widget-selectbox:visible', $('#' + id)).chosen({disable_search : true}).change( function(e){
				$(e.target).attr('data-ajax-url', $(this).val());
				$.Common.ajax(e, $(this));
			} );
		};
		chgLimit();

		// コンテンツdblclick処理
		$('div.blog-entry-content-highlight', $(this)).dblclick(function(event) {
			// jqueryのclick()イベントをfireしてもhrefまで実行してくれないため、get(0).click()とする。
			$($(this).attr('data-edit-id')).get(0).click();
		}).hover(function() {
			$(this).stop(false, true).effect("highlight", {}, 2000);
		}, function(){});
	};
	$.Comment ={
		clkCmtCancel : function(id) {
			$('#' + id + 'edit-respond').remove();
		},
	};
})(jQuery);