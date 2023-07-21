<?php
/**
 * Description of WpResidenceClass
 *
 * @author mlsimport
 */
class HouzezClass {
    
    public function __construct()    {
    }

   
    /** 
    * return custom post field
    *
    * @since    1.0.0
    * @access   protected
    * @var      string    $plugin_name   
    */
    public function get_property_post_type(){
        return 'property';
    }
    
    
    
     /**
    * return custom post field
    *
    * @since    1.0.0
    * @access   protected
    * @var      string    $plugin_name   
    */
    public function get_agent_post_type(){
        return 'houzez_agent';
    }
    
    /**
    *  image save
    *
    * @since    1.0.0
    * @access   protected
    * @var      string    $plugin_name   
    */
    public function enviroment_image_save($property_id,$attach_id){
      add_post_meta($property_id, 'fave_property_images',  intval($attach_id));		
    }
    
    
    public function return_extra_fields($property_id){
        return get_post_meta( $property_id, 'additional_features', true );
    }
    
     
    /**
    * Deal with extra meta
    *
    * 
    */
    
    public function mlsimport_saas_set_extra_meta($property_id,$property){
        $property_history   =   '';
        $extra_meta_log     =   '';
        $answer             =   array();
        $extra_fields       =   array();
        $options                               =    get_option('mlsimport_admin_fields_select'); 
       
        
        //save geo coordinates
        
        if( isset($property['meta']['houzez_geolocation_long']) && isset($property['meta']['houzez_geolocation_lat']) ){
            $savingx=$property['meta']['houzez_geolocation_lat'].','.$property['meta']['houzez_geolocation_long'];
            update_post_meta($property_id,'fave_property_location',$savingx); 
            $property_history.= 'Update Coordinates Meta with '.$savingx.'</br>';
            $extra_meta_log .= 'Property with ID '.$property_id. '  Update Coordinates Meta with '.$savingx.PHP_EOL;  
        }
        
        
        
        
        
        
        if( isset($property['extra_meta']) && is_array($property['extra_meta'])){
            $meta_properties = $property['extra_meta'];
            foreach($meta_properties as $meta_name => $meta_value ):
                if(is_array($meta_value)){
                    $meta_value= implode(',', $meta_value);
                }

                if( $meta_value!='' 
                        && isset( $options['mls-fields-label'][$meta_name] )
                        && $options['mls-fields-label'][$meta_name]!=''
                        && isset( $options['mls-fields'][$meta_name] )
                        && intval( $options['mls-fields'][$meta_name])==1
                        ){
                    
                    
                    
                    $feature_name=$options['mls-fields-label'][$meta_name];
                    if($feature_name==''){
                        $feature_name=$meta_name;
                    }
                    $element = array(
                            'fave_additional_feature_title'=>   $feature_name,
                            'fave_additional_feature_value'=>   $meta_value,
                    );
                    $extra_fields[]=$element;
                    
                    $property_history   .= 'Updated EXTRA Meta '.$meta_name.' with label '. $feature_name .' and value '.$meta_value.'</br>';
                    $extra_meta_log     .= 'Property with ID '.$property_id. '  Update EXTRA Meta '.$meta_name.' with value '.$meta_value.PHP_EOL;  
                }
                
            endforeach;
            
            
            update_post_meta( $property_id, 'additional_features', $extra_fields );
            
            
            $answer['property_history']=$property_history;
            $answer['extra_meta_log']=$extra_meta_log;
        }
        
        return $answer;
        
        
    }
    /**
    * set hardcode fields after updated
    *
    * @since    1.0.0
    * @access   protected
    * @var      string    $plugin_name   
    */
    public function correlation_update_after($is_insert,$property_id,$global_extra_fields,$new_agent){
        if($is_insert=='yes'){
            $options_mls = get_option('mlsimport_admin_mls_sync');
            update_post_meta( $property_id, 'fave_agents', $options_mls['property_agent'] );
            update_post_meta( $property_id, 'fave_agents', $new_agent );
            update_post_meta( $property_id, 'fave_agent_display_option','agent_info');
            update_post_meta($property_id,'fave_featured',0);
            //fave_agents
            
            update_post_meta( $property_id, 'fave_property_map', '1' );
            update_post_meta( $property_id, 'fave_property_map_street_view', 'show' );
            update_post_meta( $property_id, 'fave_single_top_area', 'global' );  
            update_post_meta( $property_id, 'fave_single_content_area', 'global' );   
            update_post_meta( $property_id, 'fave_additional_features_enable', 'enable' );
           // update_post_meta( $property_id, 'additional_features', $global_extra_fields );
            
            
            $fave_property_size_prefix=get_post_meta( $property_id, 'fave_property_size_prefix', true );
            if($fave_property_size_prefix==''){
                update_post_meta( $property_id, 'fave_property_size_prefix', 'Sq Ft' );
            }
                
            $fave_property_land_postfix=get_post_meta( $property_id, 'fave_property_land_postfix', true );
            if($fave_property_land_postfix==''){
                update_post_meta( $property_id, 'fave_property_land_postfix', 'Sq Ft' );
            }
            
            
         
                
            
        }
    }
    
    
    
    
    
    /**
    * save custom fields per environment
    *
    * 
    * 
    * 
    */
    public function enviroment_custom_fields($option_name){
        return;
        $theme_options  =   get_option('wpresidence_admin');
        $custom_fields  =   $theme_options['wpestate_custom_fields_list'];
        $custom_field_no=   100;
      
        $options        =   get_option($option_name.'_admin_fields_select');
             
        $custom_fields_admin    =   array();
        
        
        $test=0;
        foreach($options['mls-fields'] as $key => $value){
            $test++;
        
       
            if($value == 1 && $options['mls-fields-admin'][$key]==0){
                
                if( !in_array( $key, $custom_fields['add_field_name'] ) && $key !='' ) {                    
                    $custom_field_no++;
                    $custom_fields['add_field_name'][]        =   $key;
                    $custom_fields['add_field_label'][]       =   $key;
                    $custom_fields['add_field_type'][]        =   'short text';
                    $custom_fields['add_field_order'][]       =   $custom_field_no;
                    $custom_fields['add_dropdown_order'][]    =   '';
                }

                
            }else{
                //remove item from custom fields               
                $key_remove = array_search ($key, $custom_fields['add_field_name']);
                   
                unset( $custom_fields['add_field_name'][$key_remove] );       
                unset( $custom_fields['add_field_label'][$key_remove ]);      
                unset( $custom_fields['add_field_type'][$key_remove]);
                unset( $custom_fields['add_field_order'][$key_remove]);
                unset( $custom_fields['add_dropdown_order'][$key_remove]);
                
            }
       
        }

        
        
        $theme_options['wpestate_custom_fields_list']=$custom_fields;
        update_option('wpresidence_admin',$theme_options);
      
    }
    
    
    
    
    
    
    /**
    * return theme schema
    *
    * @since    1.0.0
    * @access   protected
    * @var      string    $plugin_name   
     * property_type - is type, property_status is action,property_feature is features,property_label is status, 
     * property_city is city, property_area is area,property_state is county state
    */
    
    public function return_theme_schema(){
  
       return;
                
    }
    
    
}
