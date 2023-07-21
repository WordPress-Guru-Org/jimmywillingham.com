<?php
/*
* Display PLaces/Categories Slider function
*
*
*/


if( !function_exists('wpestate_places_slider') ):

function wpestate_places_slider($attributes, $content=null){

    global $full_page;
    global $is_shortcode;
    global $row_number_col;
    global $place_id;
    global $place_per_row;


    $is_shortcode       =1;
    $place_list         ='';
    $return_string      ='';
    $extra_class_name   ='';



    $attributes = shortcode_atts(
        array(
            'place_list'                       => '',
            'place_per_row'                    => 3,
            'extra_class_name'                 => '',

        ), $attributes) ;
    wp_enqueue_script('slick.min');
    $post_number_total = $attributes['place_per_row'];


    if ( isset($attributes['place_list']) ){
        $place_list=$attributes['place_list'];
    }
    if ( isset($attributes['place_per_row']) ){
        $place_per_row=$attributes['place_per_row'];
    }



    if($place_per_row>5){
        $place_per_row=5;
    }

    if( isset($attributes['extra_class_name'])){
        $extra_class_name = $attributes['extra_class_name'];
    }



    $all_places_array =  explode(',', $place_list);

	$slide_cont = '';
    foreach( $all_places_array as $single_term){
        $place_id =intval( $single_term  );

		ob_start();
                include( locate_template('templates/places_unit_type2.php' ) );
		$slide_cont_tmp =  ob_get_clean();
		if( $slide_cont_tmp && trim($slide_cont_tmp) != '' ){
			$slide_cont .=  '<div class="single_slide_container">';
			$slide_cont .= $slide_cont_tmp;
			$slide_cont .= '</div>';
		}


    }

    $return_string = '<div class="estate_places_slider '.$extra_class_name.'"  data-items-per-row="'.$place_per_row.'" data-auto="0" >'. $slide_cont.'</div>';
    //ob_end_clean();
    $is_shortcode       =0;

     $return_string.= '<script type="text/javascript">
                //<![CDATA[
                jQuery(document).ready(function(){
                   wpestate_enable_slick_places();
                });
                //]]>
            </script>';
    return $return_string;


}
endif;



/*
* Display PLaces/Categories function
*
*
*/

if( !function_exists('wpestate_places_list_function') ):
function wpestate_places_list_function($attributes, $content = null) {
    global $full_page;
    global $is_shortcode;
    global $row_number_col;
    global $place_id;
    global $place_per_row;
    $is_shortcode       =1;
    $place_list         ='';
    $return_string      ='';
    $extra_class_name   ='';
    $place_type         = '';
    $item_height        = '';

    $attributes = shortcode_atts(
        array(
            'place_list'                       => '',
            'item_height'                        =>  '',
            'place_per_row'                    => 4,
            'extra_class_name'                 => '',
            'place_type'                       =>  1,
            'place_type1_align'                 =>  'one row',
        ), $attributes) ;


    $post_number_total = $attributes['place_per_row'];
    if ( isset($attributes['place_per_row']) ){
        $row_number        = $attributes['place_per_row'];
    }
    if ( isset($attributes['place_type']) ){
        $place_type        = $attributes['place_type'];
    }

    $place_type1_align='';
    if ( isset($attributes['place_type1_align']) ){
        $place_type1_align        = str_replace(' ','_',$attributes['place_type1_align']);
    }



    // max 4 per row
    if($row_number>6){
        $row_number=4;
    }

    if( $row_number == 6 ){
        $row_number_col = 2; // col value is 3
    } else if( $row_number == 4 ){
        $row_number_col = 3; // col value is 3
    }else if( $row_number==3 ){
        $row_number_col = 4; // col value is 4
    }else if ( $row_number==2 ) {
        $row_number_col =  6;// col value is 6
    }else if ($row_number==1) {
        $row_number_col =  12;// col value is 12
        if( isset($attributes['align']) && $attributes['align']=='vertical'){
             $row_number_col =  0;
        }
    }


    if ( isset($attributes['place_list']) ){
        $place_list=$attributes['place_list'];
    }
    if ( isset($attributes['place_per_row']) ){
        $place_per_row=$attributes['place_per_row'];
    }
    if ( isset($attributes['item_height']) ){
        $item_height=$attributes['item_height'];
    }

    $item_height_style='';


    if($place_per_row>5){
        $place_per_row=5;
    }

    if( isset($attributes['extra_class_name'])){
        $extra_class_name=$attributes['extra_class_name'];
    }



    $all_places_array=  explode(',', $place_list);




    ob_start();

    foreach($all_places_array as $place_id){
        $place_id=intval($place_id);
        if($place_id!=0){
            if($place_type==1){
                include( locate_template('templates/places_unit.php' ) );
            }else if($place_type==2){
                include( locate_template('templates/places_unit_type2.php') );
              }else if($place_type==3){
                include( locate_template('templates/places_unit_type3.php') ); 
              }
        }
    }

    $return_string ='<div class="article_container places_list_'.$place_type.' '.$place_type1_align.' ">'. ob_get_contents().'</div>';
    ob_end_clean();
    $is_shortcode       =0;
    return $return_string;

}
endif;
