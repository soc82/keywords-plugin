<?php

require_once  'class.loader.php';
 global $wpdb;
if ( ! class_exists( 'adminPlugStart', false ) ) {

class adminPlugStart {

    protected $loader;
    protected $version;
    public $pluginName;
    public $tag;
    public $search_item;

    

    public function __construct() {

       
        
        $this->loader();
        $this->hooks();

        $this->pluginName = 'pa-keywords';
        $this->version = '1.0';
        $this->tag = 'get_keywords';

        if(isset($_GET['s']))
            $this->search_item = $_GET['s'];
    }

    //  ----- SETUP OF POST TYPE CREATOR

    private function loader() {
        $this->loader = new pluginClassLoader ($this->get_version());
    }

    
    public function run() {
        $this->loader->run();
    }

    //  ---- POST INFO
    public function run_post_id(){
    
        $post = get_post();
       
        if($post){
            return $post->ID;
        }
    }

    
    //  ---- HOOKS

    private function hooks() {
 // ---- TAG: APPROACH - USING CORE TAGS

        // --- PLEASE UNCOMMENT ME IF YOU WISH TO SEE

        //$this->loader->add_action( 'init',  $this, 'tags_all' );
        //$this->loader->add_action( 'restrict_manage_posts', $this, 'custom_taxonomy_filters' );
        //$this->loader->add_filter( 'get_terms_args', $this, 'order_terms_args' );


        // ---- ALTERNATIVE: APPROACH - METABOX with repeater

        
        $this->loader->add_action( 'admin_head', $this, 'run_add_meta' );
        $this->loader->add_action( 'save_post',  $this, 'run_save_meta');
        $this->loader->add_action( 'admin_enqueue_scripts', $this, 'run_scripts' );

        $this->loader->add_filter('manage_posts_columns', $this,'keywords_columns_head');
        $this->loader->add_action('manage_posts_custom_column',$this, 'keywords_columns_content', 10, 2);  
        $this->loader->add_action('posts_where_request', $this, 'include_meta_search');
        $this->loader->add_filter('posts_join_request', $this, 'include_meta_search_join');  

    }

    /* --------
    --- ALTERNATIVE : METABOX WITH REPEATER
    --------- */


    public function run_scripts(){
         
        wp_register_script( 'plugin-script', plugin_dir_url( __FILE__ )  .'../assets/js/custom.js', array('jquery'), time());
        wp_enqueue_script( 'plugin-script' );
    }


    // ----- CUSTOM COLUMN FOR ALL POSTS LIST PAGE

    public function keywords_columns_head($defaults) {
        $defaults['keywords_column'] = 'Keywords';
        return $defaults;
    }

    public function keywords_columns_content($column_name) {
        if ($column_name == 'keywords_column') {
           $post_keywords = get_post_meta($this->run_post_id(), '_'.$this->tag.'');
           foreach($post_keywords as $keywords){
                echo $keywords;
           }
        }
    }

    // ---- INCLUDE CUSTOM META INTO SEARCH FUNCTION
    public function include_meta_search($where)
    {
        print_r($this->search_item);
        if ($this->search_item != '') {
            global $wpdb, $wp;
            $where = "AND wp_posts.post_title LIKE '%".$this->search_item."%'  OR ($wpdb->postmeta.meta_key = '_{$this->tag}' AND $wpdb->postmeta.meta_value LIKE '%".$this->search_item."%' ) GROUP BY $wpdb->posts.ID ";
            
        }
      
        return $where;
    }

    function include_meta_search_join($join)
    {
        global $wpdb;
         if ($this->search_item != '') {
            return $join .= " LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id  ";
        }
    }

    // ---- ADD CUSTOM META BOXES TO POSTS

    public function run_add_meta(){
           add_meta_box('addkeywords', __('Add Keywords'), array($this,'run_meta_box_keywords'), 'post', 'side', 'high');
    }

    // ---- RUN CUSTOM META BOXES

    public function run_meta_box_keywords(){
           
            $this->loader->run_meta_box(''.$this->run_post_id().'', ''.$this->tag.'', '_'.$this->tag.'', 'repeater', 'Enter Keywords (seperate with a comma)');
    }

   // ---- SAVE META FUNCTIONS

    public function run_save_meta(){
   
        // --- GET KEYWORDS FOR SAVING 

        if(isset($_REQUEST[''.$this->tag.''])){
            $key_string = '';
            $keywords   = $_REQUEST[''.$this->tag.''];

            foreach($keywords as $key){
                if($key != ''){
                    $key_string .= $key.',';
                }
            }
            $this->loader->run_save(''.$this->run_post_id().'', ''.substr($key_string, 0, -1).'', '_'.$this->tag.'','' );
        }
        
    }

    /* --------
    --- TAG APPROACH - USING CORE TAGS
    --------- */
    public function tags_all() {
        $this->loader->register_tax_simple('post_tag', 'post');
        $this->loader->run_tax_rename('PA [TAGS]', 'keywords', 'post_tag');
    }

   

    public function custom_search_query( $vars ) {
       return $vars;
    }

    public function order_terms_args( $args ) {
        $args['orderby'] = 'term_id';
        $args['order'] = 'ASC';
        return $args;
    }

    public function custom_taxonomy_filters() {
       
     
        // Filter Taxonomies on Table List

        $taxonomies = get_terms('post_tag');
        $current_filter = isset($_POST['tag']);
        $output = '';
        
        $output .=  "<select name='tag' id='tag' class='postform'>";
                     $output .=  "<option value=''>Show All PA Keywords</option>";
        
            foreach ($taxonomies as $tax) {
              if($current_filter && $current_filter == $tax->slug){
                $output .= '<option value='.$tax->slug.' selected="selected">' . $tax->name .' (' . $tax->count .')</option>';
              }else{
                $output .= '<option value='.$tax->slug.'>' . $tax->name .' (' . $tax->count .')</option>'; 
              }              
                
        }
        $output .= "</select>";
        echo $output;
        
    }

    
    // ---- VERSION
    public function get_version() {
        return $this->version;
    }

    
 
}
}