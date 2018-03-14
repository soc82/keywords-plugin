<?php


class pluginClassLoader {

    protected $actions;
    protected $filters;

    public function __construct() {
        $this->actions = array();
        $this->filters = array();
    }

    public function add_action( $hook, $component, $callback ) {
        $this->actions = $this->add( $this->actions, $hook, $component, $callback );
    }

    public function add_filter( $hook, $component, $callback ) {
        $this->filters = $this->add( $this->filters, $hook, $component, $callback );
    }

    private function add( $hooks, $hook, $component, $callback ) {

        $hooks[] = array(
            'hook'      => $hook,
            'component' => $component,
            'callback'  => $callback
        );

        return $hooks;

    }

    // ---- SHORTCODES

    public function run_shortcode($hook, $array){
        add_shortcode(''.$hook.'', $array);
    }

    // ---- CUSTOM POST TYPES

    

    public function register_post($type, $hook, $name, $supports, $singular , $dashicon, $menuorder, $rewrite){ //$menuposition
       
        register_post_type( ''.$type.'',
            array(
                    'labels' => array(
                        'name'               => __( ''.$name.'', ''.$hook.'' ),
                        'singular_name'      => __( ''.$singular.'', ''.$hook.'' ),
                        'all_items'          => __( 'All '.$name.'', ''.$hook.'' ),
                        'add_new'            => __( 'Add New', ''.$hook.'' ),
                        'add_new_item'       => __( 'Add New '.$name.'', ''.$hook.'' ),
                        'edit'               => __( 'Edit', ''.$hook.'' ),
                        'edit_item'          => __( 'Edit '.$name.'', ''.$hook.'' ),
                        'new_item'           => __( 'New '.$name.'', ''.$hook.'' ),
                        'view'               => __( 'View '.$name.'', ''.$hook.'' ),
                        'view_item'          => __( 'View '.$name.'', ''.$hook.'' ),
                        'search_items'       => __( 'Search '.$name.'', ''.$hook.'' ),
                        'not_found'          => __( 'No '.$name.' found', ''.$hook.'' ),
                        'not_found_in_trash' => __( 'No '.$name.' found in trash', ''.$hook.'' ),
                        'parent'             => __( 'Parent '.$name.'', ''.$hook.'' )
                    ),
                    'description'         => __( 'This is where you can add new '.$name.'.', ''.$hook.'' ),
                    'public'              => false,
                    'show_ui'             => true,
                    'map_meta_cap'        => true,
                    'menu_icon'           => ''.$dashicon.'',
                    'publicly_queryable'  => true,
                    'exclude_from_search' => false,
                    'hierarchical'        => false, 
                    'query_var'           => true,
                    'supports'            => $supports,
                    'has_archive'         => true,
                    'show_in_nav_menus'   => true,
                    'menu_position'       => $menuorder,
                    //'taxonomies'          => array(''.$hook.'', 'post_tag'),
                    'rewrite'             => array(
                                            'slug'                       => ''.$rewrite.'',
                                            'with_front'                 => true,
                                            'hierarchical'               => true,
                                            )
                    
                )
            );
    flush_rewrite_rules();
    }

    public function register_scripts($name, $type){
       
        if ($type == 'style'):
            wp_enqueue_style ($name.'_style', plugin_dir_url( __FILE__ ). ''.$name.'.css' );
        endif;
        if ($type == 'script'):
            wp_enqueue_script ($name.'scripts', ''.$name.'' );
        endif;
        
    }

    // ---- CUSTOM TAXONOMIES

    public function register_tax($type, $hook, $name, $singular, $dashicon, $rewrite, $cptype) {
    
        $labels = array(
            'label'                       => _x( ''.$name.'', 'text_domain' ),
            'name'                       => _x( ''.$name.'', 'text_domain' ),
            'singular_name'              => _x( ''.$name.'', 'text_domain' ),
            'menu_name'                  => __( ''.$name.'', 'text_domain' ),
            'all_items'                  => __( 'All Items', 'text_domain' ),
            'parent_item'                => __( 'Parent Item', 'text_domain' ),
            'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
            'new_item_name'              => __( 'New Item Name', 'text_domain' ),
            'add_new_item'               => __( 'Add New Item', 'text_domain' ),
            'edit_item'                  => __( 'Edit Item', 'text_domain' ),
            'update_item'                => __( 'Update Item', 'text_domain' ),
            'view_item'                  => __( 'View Item', 'text_domain' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
            'popular_items'              => __( 'Popular Items', 'text_domain' ),
            'search_items'               => __( 'Search Items', 'text_domain' ),
            'not_found'                  => __( 'Not Found', 'text_domain' ),
            'no_terms'                   => __( 'No items', 'text_domain' ),
            'items_list'                 => __( 'Items list', 'text_domain' ),
            'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => false,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        register_taxonomy( ''.$type.'', ''.$cptype.'', $args );
        
    }

    public function register_tax_simple($type, $location){
            register_taxonomy_for_object_type(''.$type.'', ''.$location.'');
    }

    // ---- TAX RENAMES

    public function run_tax_rename($name, $type, $post_type){
        global $wp_taxonomies;
        
        $wp_taxonomies[''.$post_type.'']->labels->name = ''.$name.' '.$type.'';
        $wp_taxonomies[''.$post_type.'']->labels->menu_name = ''.$name.' '.$type.'';
        $wp_taxonomies[''.$post_type.'']->labels->singular_name = ''.$name.' '.$type.'';
        $wp_taxonomies[''.$post_type.'']->labels->search_items = 'Search '.$name.' '.$type.'s';

        $wp_taxonomies[''.$post_type.'']->label = ''.$name.' '.$type.'s';

    }

    

    // ---- RUN FUNCTIONS

    public function run_save($postid, $field, $meta, $editor){
        if($postid){
                if($editor == 'editor'):
                    $save_url    = $field;
                else:
                    $save_url    = sanitize_text_field( $field );
                endif;
                update_post_meta( $postid, ''.$meta.'', $save_url );
        } else { // for metaboxes saved external from custom post type
                $post = get_post();
            if($post){
                if($editor == 'editor'):
                    $save    = $field;
                else:
                    $save    = sanitize_text_field( $field );
                endif;
                update_post_meta( $post->ID, ''.$meta.'', $save);
            }
        }
    }

    public function run_meta_box($postid, $field, $meta, $type, $outputText){
           global $wpdb;
           $metVal   = '';
           $selected = '';

           $posts = get_posts(array( 'post_type'=>'post', 'posts_per_page'=>'-1', 'numberposts'=>'-1', 'orderby' => 'post_title', 'order' => 'ASC') );
           $pages = get_posts(array( 'post_type'=>'page', 'posts_per_page'=>'-1', 'numberposts'=>'-1', 'orderby' => 'post_title', 'order' => 'ASC') );

            if($postid):
             $metVal   = get_post_meta($postid, ''.$meta.'', true );
            endif;

             if ($type == 'img'){
                 $output = $outputText;
                // $output .= '<br/><label>Url of the Image (get from media library)</label>';
                 $output .= '<input type="text" value="'.$metVal.'" name="'.$field.'" placeholder="" class="input full" style="width:100%; padding:10px;">';
                
             }

             if ($type == 'url'){
                 $output = $outputText;
                 $output .= '<input type="text" value="'.$metVal.'" name="'.$field.'" placeholder="" class="input full" style="width:100%; padding:10px;">';
                
             }
             if ($type == 'iframe'){
                 $output = $outputText;
                 $output .= '<input type="text" value="'.$metVal.'" name="'.$field.'" placeholder="" class="input full" style="width:100%; padding:10px;">';
                
             }
             if ($type == 'repeater'){
                 $value = explode(",", $metVal);
                 $output = $outputText;
                 $output .= '<input type="text" name="'.$field.'[]" id="repeater" placeholder="Enter New Keyword" class="input full" style="width:100%; padding:10px;"><input type="button" id="pa-repeater-btn" value="Go">';
                 
                 $output .='<div id="pa-repeater-area">';
                 
                 if($value):
                    foreach ($value as $val=>$v):
                        $output .= '<input type="text" value="'.$v.'" name="'.$field.'[]" placeholder="" class="input full" style="width:100%; padding:10px;">';
                    endforeach;
                  endif;
                  $output .='</div>';
                    
                
             }
             if ($type == 'textarea'){
                 $output = $outputText;
                 

                $settings_editor = array(
                    'wpautop'       => true,
                    'quicktags' => true,
                    'tinymce' => true,
                    'textarea_rows' => 8,
                    'media_buttons' => true,
                    'textarea_name' => ''.$field.'',
                    'editor_class'  => 'wp-editor-area',
                );

                wp_editor($metVal, ''.$field.'', $settings_editor);
                
             }

             if ($type == 'text'){
                 $output = $outputText;
                 $output .= '<input type="text" value="'.$metVal.'" name="'.$field.'" placeholder="" class="input full" style="width:100%; padding:10px;">';
                
             }
             

            echo  $output;
          
    }

    public function run() {

        foreach ( $this->filters as $hook ) {
            add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
        }

        foreach ( $this->actions as $hook ) {
            add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
        }

    }

}