define( 'THEME_DIR', get_template_directory() );
define( 'THEME_URL', get_template_directory_uri() );

function dvt_admin_scripts() {    
    // подключаем script
 	wp_enqueue_script( 'admin', THEME_URL . '/js/admin_js/main.js', array('jquery','media-upload','thickbox'), null, false );
    // подлючаем style
    wp_enqueue_style( 'admin', THEME_URL . '/css/admin_css/main.css' );
}
add_action( 'admin_print_scripts', 'dvt_admin_scripts' );



function dvt_get_images_post( $post_id=0, $size_img = "thumb-67", $size_link = "thumb-400", $meta_key = 'other_colors', $class="" ) {
    $out = '';
                
    $images = get_post_meta( $post_id, $meta_key, 1 );
        
    if ( empty( $images ) )
        return $out;
        
    if ( ! is_array( $images ) )
        $images = array( $images );    
     
    foreach ( $images AS $image ) :                         
        $small_url = wp_get_attachment_image_url( $image, $size_img );        				    
        if ( !$small_url ) continue;                            
        $big_url = wp_get_attachment_image_url( $image, $size_link );  
		$full_url = wp_get_attachment_image_url( $image, 'full' ); 
        $title = get_the_title($image);
        $out .= '<a href="'. $big_url .'" class="preview-thumb '.$class.'" full="'.$full_url.'"title="'.$title.'"><img src="'.$small_url.'" alt="'.$title.'" /></a>';                            
    endforeach; 
                        
    return $out;     
}



add_action( 'add_meta_boxes', 'add_dvt_upload_meta_box_interier' );  
	
function add_dvt_upload_meta_box_interier() {     

global $post;
if ( 'foobar.php' == get_post_meta( $post->ID, '_wp_page_template', true ) ) {


    add_meta_box(
        'dvt_upload_meta_box_interier',
        __('Слайдер','dvt'),
        'add_dvt_upload_meta_interier',
        'page',
        'normal',
        'high'
    );  

}



}  

add_action( 'add_meta_boxes', 'add_dvt_upload_meta_box_portfolio' );  
	
function add_dvt_upload_meta_box_portfolio() {     
    add_meta_box(
        'dvt_upload_meta_box_portfolio',
        __('Изображения','dvt'),
        'add_dvt_upload_meta_portfolio',
        'portfolio',
        'normal',
        'high'
    );    
}
	
function add_dvt_upload_meta($post) {
    wp_nonce_field( 'save_dvt_upload_meta', 'dvt_upload_meta' );
?> 
	
	<div id="myfor" class="sortabled-dvt">
<?php 
            $images = get_post_meta($post->ID, 'other_colors', true);
            
			if ( $images ) :
				foreach ( $images as $value ) :
				    $src = wp_get_attachment_image_url( $value );
				    if ( !$src ) continue; 
?>
    				<p class="draggable">
    					<input type="hidden" class="upload lopad" name="extra[other_colors][]" value="<?php echo $value ?>" />
                        <img class="lopad" src="<?php echo $src ?>" />
                        <span class="lopad"></span>
    				</p>
<?php  
                endforeach;				
            endif;
?>
	</div>
	
	<input type="button"  class="add-field button button-primary button-large add_new_image" value="<?php _e('Добавить изображение','dvt');?>" id="addnew" data-name="extra[other_colors][]" />	
    
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
    
	<?php 
}

function add_dvt_upload_meta_interier($post) {
    wp_nonce_field( 'save_dvt_upload_meta', 'dvt_upload_meta' );
?> 
	
	<div id="myfor" class="sortabled-dvt">
<?php 
            $images = get_post_meta($post->ID, 'interier', true);
            
			if ( $images ) :
            
                if ( ! is_array( $images ) )
                    $images = array( $images );     
                         
				foreach ( $images as $value) :
				    $src = wp_get_attachment_image_url( $value );
				    if ( !$src ) continue; 
?>
    				<p class="draggable">
    					<input type="hidden" class="upload lopad" name="extra[interier][]" value="<?php echo $value ?>" />
                        <img class="lopad" src="<?php echo $src ?>" />
                        <span class="lopad"></span>
    				</p>
<?php  
                endforeach;				
            endif;
?>
	</div>
	
	<input type="button"  class="add-field button button-primary button-large add_new_image" value="<?php _e('Добавить изображение','dvt');?>" id="addnew" data-name="extra[interier][]" />	
    
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
    
	<?php 
}

function add_dvt_upload_meta_portfolio($post) {
    wp_nonce_field( 'save_dvt_upload_meta', 'dvt_upload_meta' );
?> 
	
	<div id="myfor" class="sortabled-dvt">
<?php 
            $images = get_post_meta($post->ID, 'interier', true);
            
			if ( $images ) :
            
                if ( ! is_array( $images ) )
                    $images = array( $images );     
                         
				foreach ( $images as $value) :
				    $src = wp_get_attachment_image_url( $value );
				    if ( !$src ) continue; 
?>
    				<p class="draggable">
    					<input type="hidden" class="upload lopad" name="extra[images][]" value="<?php echo $value ?>" />
                        <img class="lopad" src="<?php echo $src ?>" />
                        <span class="lopad"></span>
    				</p>
<?php  
                endforeach;				
            endif;
?>
	</div>
	
	<input type="button"  class="add-field button button-primary button-large add_new_image" value="<?php _e('Добавить изображение','dvt');?>" id="addnew" data-name="extra[interier][]" />	
    
    <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
    
	<?php 
}


add_action('save_post', 'dvt_fields_update', 0);

/* Сохраняем данные, при сохранении поста */
function dvt_fields_update( $post_id ){
    if ( !isset( $_POST['extra_fields_nonce'] ) ) return false;
    if ( !wp_verify_nonce($_POST['extra_fields_nonce'], __FILE__) ) return false; 
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; 
	if ( !current_user_can('edit_post', $post_id) ) return false; 

	if( !isset($_POST['extra']) ) {
       return false;
	} 
    
    if ( ! isset( $_POST['extra']['exclude_in_home'] ) ) {
        delete_post_meta( $post_id, 'exclude_in_home' );
    }

	foreach( $_POST['extra'] as $key=>$value ){               
		if( empty($value) ){
			delete_post_meta($post_id, $key); 
			continue;
		}
		update_post_meta($post_id, $key, $value); 
	}
	return $post_id;
}  
