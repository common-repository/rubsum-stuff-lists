<?php

/*
 * Plugin Name: RubSum Stuff Lists
 * Plugin URI:  https://www.wordpress.org/plugins/rubsum-stuff-lists
 * Description: Standard and easy to use for your stuffs lists.
 * Version:     1.0.0
 * Author:      Mohammad Rubel Hossain
 * Author URI:  https://profiles.wordpress.org/rubelbdp/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rs_sl
 * Domain Path: /languages
 */

class Stuffs {
	public function __construct(){

		add_action('init', array($this, 'rs_stuff_default_init'));

		add_action('add_meta_boxes', array($this, 'rs_stuff_metabox_callback'));

		add_action('save_post', array($this, 'rs_stuff_metabox_save')); // value database e save korar jonno

		add_action('admin_enqueue_scripts', array($this, 'jquery_ui_tabs') ); // link korano lagenai karon already workpresse link koranoi ase. (arr back end e paowanor jonno admin_enque_scripts)

		//frontend style
		add_action('wp_enqueue_scripts', array($this, 'rs_stuff_styles') );

		// front end e show korar jonno callback function

		add_shortcode('stuff-lists', array($this, 'rs_stuff_list_callback'));

	}

	public function rs_stuff_styles(){
		wp_enqueue_style('stuff-frontend-style', PLUGINS_URL('css/front-end-style.css', __FILE__) );
	}

	public function jquery_ui_tabs(){
		wp_enqueue_script('jquery-ui-tabs');

		wp_enqueue_script('stuff-script', PLUGINS_URL('js/custom.js', __FILE__), array('jquery', 'jquery-ui-tabs') );

		wp_enqueue_style('stuff-custom-style', PLUGINS_URL('css/custom.css', __FILE__) );

	}

	function rs_stuff_default_init() {
		$labels = array(
			'name'               => _x( 'Stuffs', 'Stuff Admin Menu Name', 'rs_sl' ),
			'singular_name'      => _x( 'Stuff', 'stuff type singular name', 'rs_sl' ),
			'menu_name'          => _x( 'Stuffs', 'admin menu', 'rs_sl' ),
			'name_admin_bar'     => _x( 'Stuff', 'add new on admin bar', 'rs_sl' ),
			'add_new'            => _x( 'Add New', 'stuff', 'rs_sl' ),
			'add_new_item'       => __( 'Add New Stuff', 'rs_sl' ),
			'new_item'           => __( 'New Stuff', 'rs_sl' ),
			'edit_item'          => __( 'Edit Stuff', 'rs_sl' ),
			'view_item'          => __( 'View Stuff', 'rs_sl' ),
			'all_items'          => __( 'All Stuffs', 'rs_sl' ),
			'search_items'       => __( 'Search Stuffs', 'rs_sl' ),
			'parent_item_colon'  => __( 'Parent Stuffs:', 'rs_sl' ),
			'not_found'          => __( 'No stuffs found.', 'rs_sl' ),
			'not_found_in_trash' => __( 'No stuffs found in Trash.', 'rs_sl' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Stuff Lists.', 'rs_sl' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'stuff' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'			 => 'dashicons-id-alt',
			'supports'           => array( 'title', 'editor', 'thumbnail')
		);

		register_post_type( 'rs_stuff_lists', $args );

		// Add new taxonomy, Employee Types

		$label = array(
			'name'                       => _x( 'Stuff Type', 'Stuff general name', 'rs_sl' ),
			'singular_name'              => _x( 'Stuff', 'Stuff singular name', 'rs_sl' ),
			'search_items'               => __( 'Search Stuff Type', 'rs_sl' ),
			'popular_items'              => __( 'Popular Stuff Type', 'rs_sl' ),
			'all_items'                  => __( 'All Stuff Type', 'rs_sl' ),
			'parent_item'                => __('Parent Stuff Type', 'rs_sl'),
			'parent_item_colon'          => __('Parent Stuff Type', 'rs_sl'),
			'edit_item'                  => __( 'Edit Stuff Type', 'rs_sl' ),
			'update_item'                => __( 'Update Stuff Type', 'rs_sl' ),
			'add_new_item'               => __( 'Add New Stuff Type', 'rs_sl' ),
			'new_item_name'              => __( 'New Stuff Type Name', 'rs_sl' ),
			'separate_items_with_commas' => __( 'Separate stuffs type with commas', 'rs_sl' ),
			'add_or_remove_items'        => __( 'Add or remove stuffs type', 'rs_sl' ),
			'choose_from_most_used'      => __( 'Choose from the most used stuffs type', 'rs_sl' ),
			'not_found'                  => __( 'No stuffs type found.', 'rs_sl' ),
			'menu_name'                  => __( 'Stuff Type', 'rs_sl' ),
		);

		$argum = array(
			'hierarchical'          => true,
			'labels'                => $label,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'type' ),
		);

		register_taxonomy( 'stuff_type', array('rs_stuff_lists'), $argum );


	}

	public function rs_stuff_metabox_callback(){
		// metabox for stuffs
		add_meta_box('stuff-info', 'Stuff Informations', array($this, 'stuff_informations'), 'rs_stuff_lists', 'normal', 'high' );
	}
	public function stuff_informations(){

		// here get_post_meta, get_the_id, metabox id, single value show korbe kina

		$value = get_post_meta(get_the_id(), 'stuff-info', true);
		
			// Back end work:  update_post_meta er madhome database jabe ar get_post_meta er madhomme admin dashboard e show korbe
			// key gula die variable banabo
			$father 	= get_post_meta(get_the_id(), 'rs_stuff_father', true);
			$mother 	= get_post_meta(get_the_id(), 'rs_stuff_mother', true);
			$gender 	= get_post_meta(get_the_id(), 'rs_stuff_gender', true);
			$dateofbi	= get_post_meta(get_the_id(), 'rs_stuff_dateofbi', true);
			$presnaddr  = get_post_meta(get_the_id(), 'rs_stuff_presnaddr', true);
			$permaaddr  = get_post_meta(get_the_id(), 'rs_stuff_permaaddr', true);
			$department = get_post_meta(get_the_id(), 'rs_stuff_department', true);
			$designa  	= get_post_meta(get_the_id(), 'rs_stuff_designa', true);
			$joindate 	= get_post_meta(get_the_id(), 'rs_stuff_joindate', true);
			$sscyear	= get_post_meta(get_the_id(), 'rs_stuff_sscyear', true);
			$hscyear	= get_post_meta(get_the_id(), 'rs_stuff_hscyear', true);
			$degreyear	= get_post_meta(get_the_id(), 'rs_stuff_degreyear', true);
			$experin 	= get_post_meta(get_the_id(), 'rs_stuff_experin', true);
			// then ei variable gulake value er modhe echo korbo
		 ?>

		<div id="tabs">
			<ul>
				<li><a href="#personal">Personal Information</a></li>
				<li><a href="#official">Official Information</a></li>
				<li><a href="#academic">Accademic Information</a></li>
				<li><a href="#experience">Professional Experience</a></li>
			</ul>
			<div id="personal">
				<p><label for="father">Father's Name:</label></p>
				<p><input type="text" class="regular-text" name="father" value="<?php echo $father; ?>" id="father"></p>

				<p><label for="mother">Mother's Name:</label></p>
				<p><input type="text" class="regular-text" name="mother" value="<?php echo $mother; ?>" id="mother"></p>

				<p><label for="gendr">Gender:</label></p>
				<p id="gendr">
					<input type="radio" name="gender" value="Male" id="male" <?php if( $gender = 'Male' ){echo "checked";} ?>>
					<label for="male">Male</label> <br>
					<input type="radio" name="gender" value="Female" id="female" <?php if( $gender = 'Female' ){echo "checked";} ?>>
					<label for="female">Female</label>
				</p>

				<p><label for="birthdate">Date of Birth:</label></p>
				<p><input type="date" class="regular-text" name="birthdate" value="<?php echo $dateofbi; ?>" id="birthdate"></p>

				<p><label for="presentaddress">Present Address:</label></p>
				<p><input class="widefat" type="text" name="presentaddress" value="<?php echo $presnaddr; ?>" id="presentaddress"></p>

				<p><label for="permanentaddress">Permanent Address:</label></p>
				<p><input class="widefat" type="text" name="permanentaddress" value="<?php echo $permaaddr; ?>" id="permanentaddress"></p>
			</div>
			<div id="official">
				<p><label for="department">Department:</label></p>
				<p><input type="text" class="regular-text" name="department" value="<?php echo $department; ?>" id="department"></p>

				<p><label for="designation">Designation:</label></p>
				<p><input class="regular-text" type="text" name="designation" value="<?php echo $designa; ?>" id="designation"></p>

				<p><label for="joiningdate">Joining Date:</label></p>
				<p><input type="date" class="regular-text" name="joiningdate" value="<?php echo $joindate; ?>" id="joiningdate"></p>
			</div>
			<div id="academic">
				<p><label for="sscyear">SSC Year:</label></p>
				<p><input class="normal" type="year" name="sscyear" value="<?php echo $sscyear; ?>" id="sscyear"></p>

				<p><label for="hscyear">HSC Year:</label></p>
				<p><input class="normal" type="year" name="hscyear" value="<?php echo $hscyear; ?>" id="hscyear"></p>

				<p><label for="bscorhonerse">Batchelor Degree Year:</label></p>
				<p><input type="year" name="bscorhonerse" value="<?php echo $degreyear; ?>" id="bscorhonerse"></p>
			</div>
			<div id="experience">
				<p><label for="expskills">Skills:</label></p>
				<p><input class="widefat" type="textarea" name="expskills" value="<?php echo $experin; ?>" id="expskills"></p>
			</div>

		</div>

		<?php 
	}

	// Backend work: post save korar jonno. update_post_meta er madhome database jabe ar get_post_meta er madhomme admin dashboard e show korbe

	public function rs_stuff_metabox_save($post_id){
		// prothome valua gulake dhorbo

		$father 	= sanitize_text_field( $_POST['father'] );
		$mother 	= sanitize_text_field( $_POST['mother'] );
		$gender 	= sanitize_text_field( $_POST['gender'] );
		$dateofbi	= sanitize_text_field( $_POST['birthdate'] );
		$presnaddr  = sanitize_text_field( $_POST['presentaddress'] );
		$permaaddr  = sanitize_text_field( $_POST['permanentaddress'] );
		$department = sanitize_text_field( $_POST['department'] );
		$designa  	= sanitize_text_field( $_POST['designation'] );
		$joindate 	= sanitize_text_field( $_POST['joiningdate'] );
		$sscyear	= sanitize_text_field( $_POST['sscyear'] );
		$hscyear	= sanitize_text_field( $_POST['hscyear'] );
		$degreyear	= sanitize_text_field( $_POST['bscorhonerse'] );
		$experin 	= sanitize_text_field( $_POST['expskills'] );

		//ei jinish gula ke database e pathanor jonno
		// prothom ta post er id, jekono key, value
		// ei key er madhome database e jabe abar ei key er madhome database theke anabo
		// er por database theke nia value gulate boshabo
		update_post_meta(get_the_id(), 'rs_stuff_father', $father); 

		update_post_meta(get_the_id(), 'rs_stuff_mother', $mother); 

		update_post_meta(get_the_id(), 'rs_stuff_gender', $gender); 

		update_post_meta(get_the_id(), 'rs_stuff_dateofbi', $dateofbi); 

		update_post_meta(get_the_id(), 'rs_stuff_presnaddr', $presnaddr); 

		update_post_meta(get_the_id(), 'rs_stuff_permaaddr', $permaaddr); 

		update_post_meta(get_the_id(), 'rs_stuff_department', $department); 

		update_post_meta(get_the_id(), 'rs_stuff_designa', $designa); 

		update_post_meta(get_the_id(), 'rs_stuff_joindate', $joindate); 

		update_post_meta(get_the_id(), 'rs_stuff_sscyear', $sscyear); 

		update_post_meta(get_the_id(), 'rs_stuff_hscyear', $hscyear); 

		update_post_meta(get_the_id(), 'rs_stuff_degreyear', $degreyear); 

		update_post_meta(get_the_id(), 'rs_stuff_experin', $experin); 
		
	}

	// front end Show koranor jonno

	public function rs_stuff_list_callback($attr, $content){
		ob_start();

		// pagination add korar jonno

		$atts = shortcode_atts(array(
			'count'	=> -1
		), $attr);

		extract($atts);

		?>

		<div class="stuff-lists">
			<?php 
				// Dui jaygate use korar jonno upore ana hoyse
				if( get_query_var('paged') ){
					$current_page = get_query_var('paged');
				}else {
					$current_page = 1;
				}

				$stuffs = new WP_Query(array(
					'post_type'			=> 'rs_stuff_lists',
					'posts_per_page'	=> $count,
					'paged'				=> $current_page,
				));

				while($stuffs->have_posts() ) : $stuffs->the_post();
			?>
			<article class="rs-stuffs">
				<div class="rs-stuff-photo">
					<?php the_post_thumbnail(); ?>
				</div>
				<div class="rs-stuff-details">
					<h3>Name: <?php the_title(); ?></h3>

					<p><u><strong>Official Informations:</strong></u></p>

					<p><strong>Designation:</strong> <?php echo get_post_meta(get_the_id(), 'rs_stuff_designa', true); ?></p>
					<p><strong>Joining Date:</strong> <?php echo get_post_meta(get_the_id(), 'rs_stuff_joindate', true); ?></p>
					<p><strong>Department:</strong> <?php echo get_post_meta(get_the_id(), 'rs_stuff_department', true); ?></p>
					<p><strong>Job Skills:</strong> <?php echo get_post_meta(get_the_id(), 'rs_stuff_experin', true); ?></p>
					<p><strong>About the Stuff:</strong> <?php echo the_content(); ?></p>

					<p><u><strong>Personal Informations:</strong></u></p>

					<p><strong>Father's Name:</strong> <?php echo get_post_meta(get_the_id(), 'rs_stuff_father', true); ?></p>
					<p><strong>Mother's Name:</strong> <?php echo get_post_meta(get_the_id(), 'rs_stuff_mother', true); ?></p>
					<p><strong>Gender:</strong > <?php echo get_post_meta(get_the_id(), 'rs_stuff_gender', true); ?></p>
					<p><strong>Date of Birth:</strong> <?php echo get_post_meta(get_the_id(), 'rs_stuff_dateofbi', true); ?></p>
					<p><strong>Present Address:</strong> <?php echo get_post_meta(get_the_id(), 'rs_stuff_presnaddr', true); ?></p>
					<p><strong>Permanent Address:</strong> <?php echo get_post_meta(get_the_id(), 'rs_stuff_permaaddr', true); ?></p>

					<p><u><strong>Accademic Information:</strong></u></p>

					<p><strong>SSC Year:</strong> <?php echo get_post_meta(get_the_id(), 'rs_stuff_sscyear', true); ?></p>
					<p><strong>HSC Year:</strong> <?php echo get_post_meta(get_the_id(), 'rs_stuff_hscyear', true); ?></p>
					<p><strong>Batchelor Degree Year:</strong> <?php echo get_post_meta(get_the_id(), 'rs_stuff_degreyear', true); ?></p>


				</div>
			</article>
			<hr>
			<?php endwhile; ?>
	
			<?php 
				// paginate_links wordpress likhe google e search korbo.
				echo paginate_links(array(
					'current'	=> $current_page,
					'total'		=> $stuffs->max_num_pages, // WP_Query theke max_num_pages ber korbo
					'prev_text'	=> 'Previous Page',
					'next_text'	=> 'Next Page',
					'show_all'	=> true,
				));
			?>
		</div>

		<?php return ob_get_clean();
	}

	// For Visual Composer

	public function visual_composer_support(){
		if(function_exists('vc_map')){
			vc_map(array(
				'name'		=> 'Stuff Lists',
				'base'		=> 'stuff-lists',
				'id'		=> 'stuff-lists',
				'params'	=> array(
					array(
						'heading'		=> 'How many stuffs to show',
						'param_name'	=> 'count', // attribute likte hoy
						'type'			=> 'textfield'
					)
				)
			));
		}
	}

}

$stuffs = new Stuffs(); // instantiate kore


// for vc map

$stuffs->visual_composer_support();

