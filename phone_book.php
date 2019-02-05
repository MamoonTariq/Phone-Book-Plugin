<?php
/*
Plugin Name: Phone Book;
Plugin URI:https://www.google.com
Author: M Mamoon Tariq
Author URI: https://www.facebook.com/mamoontariq786
License: 1.00
*/


	// For ADD JS and CSS Files
define('PLUGIN_DIR_URL',  plugin_dir_url( __FILE__ ));
	//For include Files
define('PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ));

define('PLUGIN_VERSION', "1.0");  //Add Plugin Version


add_action('wp_default_scripts', function ($scripts) {
    if (!empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
    }
});


function add_css_js_files(){

	wp_enqueue_style('bootstrap-css', PLUGIN_DIR_URL.'assets/css/bootstrap.css');
	wp_enqueue_style('custom-css', PLUGIN_DIR_URL.'assets/css/custom.css');
	wp_enqueue_script('jquery-js', PLUGIN_DIR_URL.'assets/js/jquery.js');
	wp_enqueue_script('bootstrap-js', PLUGIN_DIR_URL.'assets/js/bootstrap.js',"",PLUGIN_VERSION,true);
	wp_enqueue_script('custom-js', PLUGIN_DIR_URL.'assets/js/custom.js',"",PLUGIN_VERSION,true);


}
add_action("init","add_css_js_files");
add_action('admin_enqueue_scripts', "add_css_js_files");




function add_table_db(){
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	if (count($wpdb->get_var('SHOW TABLES LIKE "wp_phone_book"')) == 0){
		$sql =  "CREATE TABLE `wp_phone_book` (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `name` varchar(255) NOT NULL,
				 `phone_no` varchar(255) NOT NULL,
				 PRIMARY KEY (`id`),
				 UNIQUE KEY `name` (`name`)
				) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1";
		dbDelta($sql);
	}
}
register_activation_hook(__FILE__,"add_table_db");



function delete_table_db(){
	global $wpdb;
	$wpdb->query("DROP table IF EXISTS wp_phone_book");
}
register_deactivation_hook(__FILE__,"delete_table_db");





add_shortcode('phone-book','phone_book');
function phone_book(){ ?>

	<ul class="tabs">
		<li class="tab-link current" data-tab="tab-1">Add Phone No</li>
		<li class="tab-link" data-tab="tab-2">All Phone No's</li>
	
	</ul>

	<div id="tab-1" class="tab-content current">
		<script>
			var ajaxurl = "<?php echo admin_url('admin-ajax.php');?>";
		</script>
		<form action="" id="submit_phone_no">
		  <div class="form-group">
		    <label for="name">Name:</label>
		    <input type="name" class="form-control" id="name">
		  </div>
		  <div class="form-group">
		    <label for="phone">Phone No:</label>
		    <input type="number" class="form-control" id="phone">
		  </div>
		  <button type="button" class="btn btn-default">Submit</button>
		</form>
	</div>



	<div id="tab-2" class="tab-content">
		<?php
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM wp_phone_book"); 
		$data = json_decode(json_encode($results),true); ?>
		  <table class="table">
		    <thead>
		      <tr>
		        <th>Name</th>
		        <th>Phone</th>
		        <th>DELETE</th>
		      </tr>
		    </thead>
		    <tbody class="inner">
		    <?php foreach ($data as $row) { ?>
		    	<script>
					var ajaxurl = "<?php echo admin_url('admin-ajax.php');?>";
				</script>
		      <tr class="de-<?php echo $row['id']; ?>">

		        <td class="old-name-<?php echo $row['id']; ?>">
		        	<?php echo $row['name'] ;?>
		        </td>
		        <td style="display: none;" class="new-name-<?php echo $row['id']; ?>">
		        	<input type="text" name="name" id="up-nm-<?php echo $row['id']; ?>" value="<?php echo $row['name'] ;?>">
		        </td>

		        <td class="old-phone-<?php echo $row['id']; ?>">
		        	<?php echo $row['phone_no'] ;?>	
		        </td>
		        <td style="display: none;" class="new-phone-<?php echo $row['id']; ?>">
		        	<input type="text" name="name" id="up-ph-<?php echo $row['id']; ?>" value="<?php echo $row['phone_no'] ;?>">
		        </td>

		        <td class="old-btns-<?php echo $row['id']; ?>">
		        	<input type="button" value="Delete" class="delete" id="<?php echo $row['id'];?>">
		        	<input type="button" value="Edit" class="edit" id="<?php echo $row['id'];?>">
		        </td>

		        <td style="display: none;" class="new-btns-<?php echo $row['id']; ?>">
		        	<input type="button" value="Update" class="updates" id="<?php echo $row['id'];?>">
		        </td>

		      </tr>
		<?php }?>   
		    </tbody>
	  </table>
  	</div>
	
<?php
}

add_action("wp_ajax_phone_book","prifix_ajax_phone_book");
function prifix_ajax_phone_book(){
	global $wpdb;

	$name = $_REQUEST['name'];
	$phone = $_REQUEST['phone'];
	
	$success = $wpdb->insert("wp_phone_book",
		array(
			"name" => $name,
			"phone_no" => $phone
		)
	);	
	$id =  $wpdb->insert_id;
	if ($success) {
		echo $id;
	}
	wp_die();
}

add_action("wp_ajax_delete","prifix_ajax_delete");
function prifix_ajax_delete(){
	
	global $wpdb;
	
	$id = $_REQUEST['id'];

	$wpdb->delete("wp_phone_book",array('id'=>$id));

	wp_die();
}





add_action("wp_ajax_update_phone_book","prifix_ajax_update_phone_book");
function prifix_ajax_update_phone_book(){
	global $wpdb;

	$name = $_REQUEST['name'];
	$phone = $_REQUEST['phone'];
	$id = $_REQUEST['id'];
	
	$success = $wpdb->update("wp_phone_book",
		array(
			"name" => $name,
			"phone_no" => $phone
		),
		array(
			'id' => $id
		)
	);	
	
	wp_die();
}



// function add_my_custom_page() {
//     // Create post object
//     $my_post = array(
//       'post_title'    => wp_strip_all_tags( 'My Custom Page' ),
//       'post_content'  => '[phone-book]',
//       'post_status'   => 'publish',
//       'post_author'   => 1,
//       'post_type'     => 'page',
//     );

//     // Insert the post into the database
//     $post_id = wp_insert_post( $my_post );
//     add_option("Custom_plugin_page_id" , $post_id);
// }

// register_activation_hook(__FILE__, 'add_my_custom_page');



// 	function drop_page(){

// 		$the_post_id = get_option("Custom_plugin_page_id");

// 		if (!empty($the_post_id)) {
// 			wp_delete_post($the_post_id);
// 		}
// 	}
// register_deactivation_hook( __FILE__, "drop_page");



















