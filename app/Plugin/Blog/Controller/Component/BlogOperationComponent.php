<?php
/**
 * BlogOperationComponentクラス
 *
 * <pre>
 * ブログ用削除、コピー、移動、ショートカット等操作クラス
 * 削除用関数等は、親から呼ばれるため、Model等のクラスは、このクラス内で完結している
 * </pre>
 *
 * @copyright     Copyright 2012, NetCommons Project
 * @package       app.Plugin.Announcement.Component
 * @author        Noriko Arai,Ryuji Masukawa
 * @since         v 3.0.0.0
 * @license       http://www.netcommons.org/license.txt  NetCommons License
 */
class BlogOperationComponent extends Object {

	public $Content = null;
	public $Htmlarea = null;

	public $Blog = null;
	public $BlogComment = null;
	public $BlogPost = null;
	public $BlogStyle = null;
	public $BlogTerm = null;
	public $BlogTermLink = null;

/**
 * 初期処理
 *
 * @param   void
 * @return  void
 * @since   v 3.0.0.0
 */
	public function startup() {
		App::uses('Content', 'Model');
		App::uses('Htmlarea', 'Model');

		App::uses('Blog', 'Blog.Model');
		App::uses('BlogComment', 'Blog.Model');
		App::uses('BlogPost', 'Blog.Model');
		App::uses('BlogStyle', 'Blog.Model');
		App::uses('BlogTerm', 'Blog.Model');
		App::uses('BlogTermLink', 'Blog.Model');

		$this->Content = new Content();
		$this->Htmlarea = new Htmlarea();

		$this->Blog = new Blog();
		$this->BlogComment = new BlogComment();
		$this->BlogPost = new BlogPost();
		$this->BlogStyle = new BlogStyle();
		$this->BlogTerm = new BlogTerm();
		$this->BlogTermLink = new BlogTermLink();
	}

/**
 * ブロック削除実行時に呼ばれる関数
 *
 * @param   Model Block   削除ブロック
 * @param   Model Content 削除コンテンツ
 * @param   Model Page    削除先ページ
 * @return  boolean
 * @since   v 3.0.0.0
 */
	public function delete_block($block, $content, $to_page) {
		$condition = array('block_id' => $block['Block']['id']);
		if(!$this->BlogStyle->deleteAll($condition)) {
			return false;
		}
		return true;
	}

/**
 * コンテンツ削除時に呼ばれる関数
 *
 * @param   Model Content 削除コンテンツ $content
 * @return  boolean
 * @since   v 3.0.0.0
 */
	public function delete($content) {
		if(isset($content['Content'])) {
			$tables = array('Blog', 'BlogComment', 'BlogPost', 'BlogTerm', 'BlogTermLink', 'Htmlarea');
			foreach($tables as $table) {
				$condition = array($table.'.content_id' => $content['Content']['master_id']);
				if(!$this->{$table}->deleteAll($condition)) {
					return false;
				}
			}
		}
		return true;
	}

/**
 * ショートカット実行時に呼ばれる関数
 *
 * @param   Model Block   移動元ブロック
 * @param   Model Block   移動先ブロック
 * @param   Model Content 移動元コンテンツ
 * @param   Model Content 移動先コンテンツ
 * @param   Model Page    移動元ページ
 * @param   Model Page    移動先ページ
 * @return  boolean
 * @since   v 3.0.0.0
 */
	public function shortcut($from_block, $to_block, $from_content, $to_content, $from_page, $to_page) {
		$condition = array('block_id' => $from_block['Block']['id']);
		$blog_styles = $this->BlogStyle->find('all', array('conditions' => $condition));
		if(isset($blog_styles[0])) {
			$this->BlogStyle->initInsert = true;
			foreach($blog_styles as $blog_style) {
				unset($blog_style['BlogStyle']['id']);
				$blog_style['BlogStyle']['block_id'] = $to_block['Block']['id'];
				$this->BlogStyle->create();
				if(!$this->BlogStyle->save($blog_style)) {
					return false;
				}
			}
		}
		return true;
	}


/**
 * コピー(ペースト)実行時に呼ばれる関数
 *
 * @param   Model Block   移動元ブロック
 * @param   Model Block   移動先ブロック
 * @param   Model Content 移動元コンテンツ
 * @param   Model Content 移動先コンテンツ
 * @param   Model Page    移動元ページ
 * @param   Model Page    移動先ページ
 * @return  boolean
 * @since   v 3.0.0.0
 */
	public function paste($from_block, $to_block, $from_content, $to_content, $from_page, $to_page) {
		if(!$this->shortcut($from_block, $to_block, $from_content, $to_content, $from_page, $to_page)) {
			return false;
		}

		$tables = array('Blog', 'BlogComment', 'BlogPost', 'BlogTerm', 'BlogTermLink', 'Htmlarea');
		foreach($tables as $table) {
			$condition = array($table.'.content_id' => $from_content['Content']['master_id']);
			$datas = $this->{$table}->find('all', array('conditions' => $condition));
			foreach($datas as $data) {
				unset($data[$table]['id']);
				$data[$table]['content_id'] = $to_content['Content']['id'];
				$this->{$table}->create();
				if(!$this->{$table}->save($data)) {
					return false;
				}
			}
		}
		return true;
	}

/**
 * ブロック追加実行時に呼ばれる関数
 *
 * @param   Model Block   追加ブロック
 * @param   Model Content 追加コンテンツ
 * @param   Model Page    追加先ページ
 * @return  boolean
 * @since   v 3.0.0.0
 */
//	public function add_block($block, $content, $to_page) {
//		return true;
//	}

/**
 * 別ルームに移動実行時に呼ばれる関数
 *
 * @param   Model Block   移動元ブロック
 * @param   Model Block   移動先ブロック
 * @param   Model Content 移動元コンテンツ
 * @param   Model Content 移動先コンテンツ
 * @param   Model Page    移動元ページ
 * @param   Model Page    移動先ページ
 * @return  boolean
 * @since   v 3.0.0.0
 */
//	public function move($from_block, $to_block, $from_content, $to_content, $from_page, $to_page) {
//		return true;
//	}
}