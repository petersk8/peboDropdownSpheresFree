<?php
/*
Plugin Name: PEBO Dropdown Spheres Menus FREE EDITION
Plugin URI:  http://blog.pebo.pro/shop/wordpress/pebo-wordpress-masonry-pro/
Description: Boostraped show categories in fancy spheres with nice hover effects and a subcategory menu
Version:     1.0
Author:      Pedro E. Borrego R.
Author URI:  http://www.pebo.pro/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

//global prefix for namig : PEBO_WDSMP_FREE_

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


class PeboDropdowsSpheresWidgetFree_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'PeboDropdowsSpheresWidgetFree_Widget',
			'description' => 'catecory circles with hover effects and subcategories menu',
		);
		parent::__construct( 'PeboDropdowsSpheresWidgetFree_Widget', 'PEBO Spheres free', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		DEBUG($instance);
		//Enque the style sheets.
		wp_enqueue_style('pebo-hover-styles1', plugin_dir_url(__FILE__) . 'css/PEBO_WDSMP_FREE_style'.$instance['PEBO_WDSMP_FREE_EFFECT_NUMBER'].'.css');
		wp_enqueue_style('pebo-hover-styles2', plugin_dir_url(__FILE__) . 'css/PEBO_WDSMP_FREE_common.css');

		//get the categories to fill spheres info
		$categories = get_categories( array(
    		'orderby' => 'name',
    		'parent'  => 0
		) );
		
		/*This is the template for each block of the masonery*/
		$spheres_start= '<ul class="ch-grid">';	
		
		/*dropdown template*/
		/*
		<div class="dropdown">
			<button class="dropbtn">Dropdown</button>
			<div class="dropdown-content">
				<a href="#">Link 1</a>
				<a href="#">Link 2</a>
				<a href="#">Link 3</a>
			</div>
		</div>
		*/


		$spheres_end = '</ul>';
		$SphereBlock='';
		foreach ( $categories as $category ) {
			if ($instance["PEBO_WDSMP_FREE_CATTEGORY_".$category->name] == $category->name){
				switch ($instance['PEBO_WDSMP_FREE_EFFECT_NUMBER']){
				case 1:
				case 2:
					$SphereTemplate = '				
						<li>						
							<div class="ch-item PEBO_SPHERES_IMAGE_NUMBER">							
								<div class="ch-info">								
									<h3>PEBO_SPHERES_TITLE</h3>									
									<div class="dropdown">
										<p><a href="PEBO_SPHERES_LINK_URL">View category.</a></p>
										<div class="dropdown-content">
											PEBO_SPHERES_SUBCATEGORIES_LINKS												
										</div>
									</div>							
								</div>							
							</div>						
						</li>	
						';
				break;
				case 3:
					$SphereTemplate = '
						<li>
							<div class="ch-item">	
								<div class="ch-info">
									<h3>PEBO_SPHERES_TITLE</h3>
									<div class="dropdown">
										<p><a href="PEBO_SPHERES_LINK_URL">View category.</a></p>
										<div class="dropdown-content">
											PEBO_SPHERES_SUBCATEGORIES_LINKS												
										</div>
									</div>
								</div>
								<div class="ch-thumb PEBO_SPHERES_IMAGE_NUMBER"></div>
							</div>
						</li>
						';
				break;
				case 4:			
				case 5:
				case 6:
					$SphereTemplate = '
						<li>
							<div class="ch-item PEBO_SPHERES_IMAGE_NUMBER">				
								<div class="ch-info-wrap">
									<div class="ch-info">
										<div class="ch-info-front PEBO_SPHERES_IMAGE_NUMBER"></div>
										<div class="ch-info-back">
											<h3>PEBO_SPHERES_TITLE</h3>
											<div class="dropdown">
												<p><a href="PEBO_SPHERES_LINK_URL">View category.</a></p>
												<div class="dropdown-content">
													PEBO_SPHERES_SUBCATEGORIES_LINKS												
												</div>
											</div>
										</div>	
									</div>
								</div>
							</div>
						</li>
					';
					break;				
				case 7:
					$SphereTemplate = '
						<li>
							<div class="ch-item">				
								<div class="ch-info">
									<div class="ch-info-front PEBO_SPHERES_IMAGE_NUMBER"></div>
									<div class="ch-info-back">
										<h3>PEBO_SPHERES_TITLE</h3>
										<div class="dropdown">
											<p><a href="PEBO_SPHERES_LINK_URL">View category.</a></p>
											<div class="dropdown-content">
												PEBO_SPHERES_SUBCATEGORIES_LINKS												
											</div>
										</div>
									</div>	
								</div>
							</div>
						</li>
						';
				break;
			}
				switch ($instance["PEBO_WDSMP_FREE_SPHERE_IMAGE_".$category->name]){
					/*
					load image css class according to selection
					value="default1"
					value="default2"
					value="default3"
					value="Library"
					value="Custom_Color"
					*/
					case 'default1':
						$SphereTemplate = str_replace('PEBO_SPHERES_IMAGE_NUMBER', 'ch-img-1', $SphereTemplate);
					break;
					case 'default2':
						$SphereTemplate = str_replace('PEBO_SPHERES_IMAGE_NUMBER', 'ch-img-2', $SphereTemplate);
					break;
					case 'default3':
						$SphereTemplate = str_replace('PEBO_SPHERES_IMAGE_NUMBER', 'ch-img-3', $SphereTemplate);
					break;
					default:
					break;
				}

				$Sphere_withTittle = str_replace('PEBO_SPHERES_TITLE', esc_html( $category->name ), $SphereTemplate );
				$Sphere_withLink = str_replace('PEBO_SPHERES_LINK_URL', esc_url( get_category_link( $category->term_id ) ), $Sphere_withTittle );
				
				//form subcatagories liks if any
				$sub_categories = get_categories( array(
    				'orderby' => 'name',
    				'parent'  => $category->term_id
				));
				$subcatLinks = '';
					foreach ($sub_categories as $subcategory){
					$subcatLinks .='<a href="'. esc_url( get_category_link( $subcategory->term_id ) ).'">'.$subcategory->name.'</a>';
					}	
				
				$Sphere_withSubcatDorpdown = str_replace('PEBO_SPHERES_SUBCATEGORIES_LINKS', $subcatLinks, $Sphere_withLink);
        		$SphereBlock = $SphereBlock . $Sphere_withSubcatDorpdown; 				
			}				       	
		}		

		/*The final output*/
		echo($spheres_start . $SphereBlock . $spheres_end);		
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$categories = get_categories( array(
    		'orderby' => 'name',
    		'parent'  => 0
		) );

		$selectedEffectNum = $instance['PEBO_WDSMP_FREE_EFFECT_NUMBER'];
		
		/*Check last selection for the effect number*/
		$is1 = ($selectedEffectNum == 1 ? "selected" : "");
		$is2 = ($selectedEffectNum == 2 ? "selected" : "");
		$is3 = ($selectedEffectNum == 3 ? "selected" : "");
		$is4 = ($selectedEffectNum == 4 ? "selected" : "");
		$is5 = ($selectedEffectNum == 5 ? "selected" : "");
		$is6 = ($selectedEffectNum == 6 ? "selected" : "");
		$is7 = ($selectedEffectNum == 7 ? "selected" : "");		

		// outputs the options form on admin
		$title = '<h1>Spheres Configuration</h1><br>';		
		$EffectsNumberOption = 'Select the desired effect: 
		<select id="'. $this->get_field_id("PEBO_WDSMP_FREE_EFFECT_NUMBER") . '" 
			name= "' . $this->get_field_name("PEBO_WDSMP_FREE_EFFECT_NUMBER") . '">
			<option value="1" ' . $is1 . '>Caeruleum</option>
  			<option value="2" ' . $is2 . '>Aetate</option>
  			<option value="3" ' . $is3 . '>Latus</option>
  			<option value="4" ' . $is4 . '>Reditus</option>
  			<option value="5" ' . $is5 . '>Ratio</option>
			<option value="6" ' . $is6 . '>Cover</option>
			<option value="7" ' . $is7 . '>Girabit</option>
		</select><br><br>';
		
		//select wich categorie to display and assig pictures to their spheres
		$categoryControls = '';
		foreach ( $categories as $category ) {
			$checked = $instance["PEBO_WDSMP_FREE_CATTEGORY_".$category->name] == $category->name ? "checked" : "";

			/*Check last selection for the effect image on each category*/
			$categoryImageSelected = $instance["PEBO_WDSMP_FREE_SPHERE_IMAGE_".$category->name];
			$imageselected1 = $categoryImageSelected == "default1" ? 'selected' : '';
			$imageselected2 = $categoryImageSelected == "default2" ? 'selected' : '';
			$imageselected3 = $categoryImageSelected == "default3" ? 'selected' : '';
			$imageselected4 = $categoryImageSelected == "default4" ? 'selected' : '';
			$imageselected5 = $categoryImageSelected == "default5" ? 'selected' : '';
			$categoryControls .= '<input type="checkbox" name="' . $this->get_field_name("PEBO_WDSMP_FREE_CATTEGORY_".$category->name ) .
			'" value="'.$category->name.'" '.$checked.'>'.$category->name.'<br>';
			$categoryControls .= '<select id="'. $this->get_field_id("PEBO_WDSMP_FREE_SPHERE_IMAGE_".$category->name) . '" 
								name= "' . $this->get_field_name("PEBO_WDSMP_FREE_SPHERE_IMAGE_".$category->name) . '">
									<option value="default1" '.$imageselected1.'>default image 1</option>
									<option value="default2" '.$imageselected2.'>default image 2</option>
									<option value="default3" '.$imageselected3.'>default image 3</option>
									<option value="Library" '.$imageselected4.'>Choose from Library</option>
									<option value="Custom_Color" '.$imageselected5.'>Use a background color</option>
								</select><br><br>';
		}
							
		echo (
			$title 
			. $EffectsNumberOption 
			. $categoryControls
		);
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = $old_instance;
		
		//get the categories to fill spheres info
		$categories = get_categories( array(
    		'orderby' => 'name',
    		'parent'  => 0
		));
		foreach ($categories as $category){
			$instance["PEBO_WDSMP_FREE_CATTEGORY_".$category->name] = $new_instance["PEBO_WDSMP_FREE_CATTEGORY_".$category->name];
			$instance["PEBO_WDSMP_FREE_SPHERE_IMAGE_".$category->name] = $new_instance["PEBO_WDSMP_FREE_SPHERE_IMAGE_".$category->name];		
		}
    	$instance['PEBO_WDSMP_FREE_EFFECT_NUMBER'] = $new_instance['PEBO_WDSMP_FREE_EFFECT_NUMBER'];

    	return $instance;
	}
}


//Register the widget
add_action( 'widgets_init', function(){
	register_widget( 'PeboDropdowsSpheresWidgetFree_Widget' );
});


//Function made for debuging, remove for production
function DEBUG( $data ) {
	$output = "<script>console.log( 'Debug Objects: " . json_encode($data) . "' );</script>";
    echo $output;
}


