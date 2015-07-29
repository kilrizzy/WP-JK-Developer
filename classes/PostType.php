<?php

class PostType {

	public $name;
	public $urlSlug;
	public $labelPlural;
	public $labelSingular;
	public $supports;
	public $excerptTitle;
	public $excerptHelp;

	public function __construct(){
		$this->supports = array(
			'title',
			'excerpt',
			'thumbnail',
		);
	}

	public function create(){
		register_post_type($this->name,
			array(
				'labels' => array(
					'name' => __( $this->labelPlural ),
					'menu_name' => __( $this->labelPlural ),
					'name_admin_bar' => __( $this->labelSingular ),
					'singular_name' => __( $this->labelSingular ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New '.$this->labelSingular ),
					'edit_item' => __( 'Edit '.$this->labelSingular ),
					'new_item' => __( 'New '.$this->labelSingular ),
					'view_item' => __( 'View '.$this->labelSingular ),
					'all_items' => __( 'All '.$this->labelPlural ),
					'search_items' => __( 'Search '.$this->labelPlural ),
					'parent_item_colon'  => __( 'Parent '.$this->labelPlural.':' ),
					'not_found'          => __( 'No '.$this->labelPlural.' found.' ),
					'not_found_in_trash' => __( 'No '.$this->labelPlural.' found in Trash.' )
				),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => $this->urlSlug),
				'supports' => $this->supports,
			)
		);
		//Text overrides
		add_filter('gettext', array($this,'_override_post_title_placeholder'));
		if($this->excerptTitle){
			add_filter('gettext',array($this,'_override_post_excerpt_title'));
		}
		if($this->excerptHelp){
			add_filter('gettext',array($this,'_override_post_excerpt_help'));
		}
		add_filter('gettext',array($this,'_override_featured_image_title'));
		add_filter('gettext',array($this,'_override_featured_image_link'));
	}

	public function _override_post_title_placeholder($input){
		global $post_type;
		if( is_admin() && 'Enter title here' == $input && $post_type == $this->name )
			return $this->labelSingular.' Title';
		return $input;
	}
	function _override_post_excerpt_title( $input ) {
		global $post_type;
		if( is_admin() && 'Excerpt' == $input && $post_type == $this->name )
			return $this->excerptTitle;
		return $input;
	}
	function _override_post_excerpt_help( $input ) {
		global $post_type;
		if( is_admin() && 'Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="https://codex.wordpress.org/Excerpt" target="_blank">Learn more about manual excerpts.</a>' == $input && $post_type == $this->name )
			return $this->excerptHelp;
		return $input;
	}
	function _override_featured_image_title( $input ) {
		global $post_type;
		if( is_admin() && 'Featured Image' == $input && $post_type == $this->name )
			return $this->labelSingular.' Image';
		return $input;
	}
	function _override_featured_image_link( $input ) {
		global $post_type;
		if( is_admin() && 'Set featured image' == $input && $post_type == $this->name )
			return 'Set '.$this->labelSingular.' Image';
		return $input;
	}
}