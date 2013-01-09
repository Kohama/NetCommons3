<?php if($this->Paginator->hasPage(null, 2)): ?>
<div class="pages-menu-paginator-outer clearfix">
	<ul class="nc-paginator">
		<?php echo $this->Paginator->prev(__('<'), array('tag' => 'li', 'data-ajax-replace' => '#nc-pages-setting-dialog')); ?>
		<?php echo $this->Paginator->numbers(array('ellipsis' => '<li>...</li>','last'=>1,'tag' => 'li', 'separator' => '', 'modulus' => $views,'data-ajax-replace' => '#nc-pages-setting-dialog')); ?>
		<?php echo $this->Paginator->next(__('>'), array('tag' => 'li', 'data-ajax-replace' => '#nc-pages-setting-dialog')); ?>
	</ul>
	<?php /* カレント再表示用 */ echo $this->Paginator->link("current", null, array('class' => 'display-none', 'data-ajax-replace' => '#nc-pages-setting-dialog')) ?>
</div>
<?php endif; ?>