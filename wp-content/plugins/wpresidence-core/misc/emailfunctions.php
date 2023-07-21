<?php
//////////////////////////////////////////////////////////////////////////////
/// Ajax adv search contact function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_agent_contact_form', 'wpestate_ajax_agent_contact_form' );
add_action( 'wp_ajax_wpestate_ajax_agent_contact_form', 'wpestate_ajax_agent_contact_form' );

if( !function_exists('wpestate_ajax_agent_contact_form') ):
    function wpestate_ajax_agent_contact_form(){

        // check for POST vars
        $hasError                     =  false;
        $allowed_html                 =  array();
        $to_print                     =  '';
        $is_elementor_contact_builder =  intval($_POST['is_elementor']);


        if ( !wp_verify_nonce( $_POST['nonce'], 'ajax-property-contact')) {
            exit("No naughty business please");
        }

        if($is_elementor_contact_builder==0){
            if ( isset($_POST['name']) ) {
                if( trim($_POST['name']) =='' || trim($_POST['name']) ==esc_html__('Your Name','wpresidence-core') ){
                    echo json_encode(array('sent'=>false, 'response'=>esc_html__('The name field is empty !','wpresidence-core') ));
                    exit();
                }else {
                    $name = sanitize_text_field (wp_kses( trim($_POST['name']),$allowed_html) );
                }
            }

            //Check email
            if ( isset($_POST['email']) || trim($_POST['name']) ==esc_html__('Your Email','wpresidence-core') ) {
                if( trim($_POST['email']) ==''){
                    echo json_encode(array('sent'=>false, 'response'=>esc_html__('The email field is empty','wpresidence-core' ) ) );
                    exit();
                } else if( filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) === false) {
                    echo json_encode(array('sent'=>false, 'response'=>esc_html__('The email doesn\'t look right !','wpresidence-core') ) );
                    exit();
                } else {
                    $email = sanitize_text_field ( wp_kses( trim($_POST['email']),$allowed_html) );
                }
            }

            $phone   = sanitize_text_field(wp_kses( trim($_POST['phone']),$allowed_html) );
            $subject =esc_html__('Contact form from ','wpresidence-core') . esc_url( home_url('/') ) ;

            //Check comments
            if ( isset($_POST['comment']) ) {
                if( trim($_POST['comment']) =='' || trim($_POST['comment']) ==esc_html__('Your Message','wpresidence-core')){
                    echo json_encode(array('sent'=>false, 'response'=>esc_html__('Your message is empty !','wpresidence-core') ) );
                    exit();
                }else {
                    $comment = (wp_kses($_POST['comment'] ,$allowed_html ));
                }
            }
        }else{
            $comment  = wp_kses($_POST['comment'] ,$allowed_html);
            $phone    = sanitize_text_field(wp_kses( trim($_POST['phone']),$allowed_html) );
            $email    = sanitize_text_field ( wp_kses( trim($_POST['email']),$allowed_html) );
            $name     = sanitize_text_field (wp_kses( trim($_POST['name']),$allowed_html) );
        }

        $comment=str_replace('/n',PHP_EOL,$comment);







        $message        =   '';
        $schedule_mesaj =   '';
        $propid     =   intval($_POST['propid']);
        $agent_id   =   intval($_POST['agent_id']);
        $schedule_hour  =   esc_html($_POST['schedule_hour']);
        $schedule_day   =   esc_html($_POST['schedule_day']);

        if($schedule_hour!='' && $schedule_day!=''){
            $schedule_mesaj = sprintf (esc_html__('I would like to schedule a viewing on %s at %s. Please confirm the meeting via email or private message. ','wpresidence-core'),$schedule_day,$schedule_hour);
        }
        if($propid!=0){
            $permalink  = esc_url( get_permalink(  $propid ));

            if($agent_id!=0){
                $agent_agency_dev_id    =   intval(get_post_meta($agent_id,'user_meda_id',true));
                $receiver_email         =   get_the_author_meta( 'user_email' , $agent_agency_dev_id );

                if($receiver_email==''){
                    $receiver_email =   esc_html( get_post_meta($agent_id, 'agent_email', true) );
                }
                if( get_post_type($agent_id) == 'estate_agency' ){
                    $receiver_email =   esc_html(get_post_meta($agent_id, 'agency_email', true));
                }
                if( get_post_type($agent_id) == 'estate_developer' ){
                    $receiver_email =   esc_html(get_post_meta($agent_id, 'developer_email', true));
                }

            }else{
                $the_post       =   get_post( $propid);
                $author_id      =   $the_post->post_author;
                $receiver_email =   get_the_author_meta( 'user_email' ,$author_id );
            }
            wp_estate_count_page_contacts($propid);
        }else if($agent_id!=0){
            $permalink      =    esc_url( get_permalink(  $agent_id ) );

            $agent_agency_dev_id    =   intval(get_post_meta($agent_id,'user_meda_id',true));
            $receiver_email         =   get_the_author_meta( 'user_email' , $agent_agency_dev_id );
            if($agent_agency_dev_id==0 && $receiver_email==''){
                $receiver_email =   esc_html( get_post_meta($agent_id, 'agent_email', true) );
            }
            
             if( get_post_type($agent_id) == 'estate_agency' ){
                    $receiver_email =   esc_html(get_post_meta($agent_id, 'agency_email', true));
                }
                if( get_post_type($agent_id) == 'estate_developer' ){
                    $receiver_email =   esc_html(get_post_meta($agent_id, 'developer_email', true));
                }
            
            
        }else{
            $permalink      =   'contact page';
            $receiver_email =   esc_html( wpresidence_get_option('wp_estate_email_adr', ''));
        }


        $headers                      =  'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        // if we have contact form elementor
        if(isset($_POST['elementor_wpresidence_form_id'])){
            $wpresidence_form_id =  sanitize_text_field( $_POST['elementor_wpresidence_form_id'] );
            $form_email_options= get_option($wpresidence_form_id);
            if(!$form_email_options){
                exit('no form data');
            }
            $receiver_email = $form_email_options['email_to'];
            
        }
      
        if(isset($_POST['elementor_email_subject'])){
          $subject        = sanitize_text_field( $_POST['elementor_email_subject']);
        }




        $message    .=  '<strong>'.esc_html__('Client Name','wpresidence-core')."</strong>: " . $name . PHP_EOL;
        $message    .=  esc_html__('Email','wpresidence-core').": " . $email . PHP_EOL;
        $message    .=  esc_html__('Phone','wpresidence-core').": " . $phone . PHP_EOL;
        $message    .=  esc_html__('Subject','wpresidence-core').": " . $subject . PHP_EOL;
        $message    .=  esc_html__('Content','wpresidence-core').": \n " . $comment. PHP_EOL;
        $message    .=  esc_html__('Message sent from ','wpresidence-core').$permalink. PHP_EOL;
        if($schedule_mesaj!=''){
            $message .= PHP_EOL.$schedule_mesaj;
        }



        if(isset($form_email_options['send_copy_to'])){
            $email_cc       = sanitize_text_field($form_email_options['send_copy_to']);
            $headers    .=   "CC:".   $email_cc    . "\r\n";
        }
        
        if(isset($form_email_options['send_ccopy_to'])){
            $email_cc       = sanitize_text_field( $form_email_options['send_ccopy_to']);
            $headers    .=   "BCC:".   $email_cc    . "\r\n";
        }
$headers.='Content-Type: text/html; charset=UTF-8\r\n';

        $message = stripslashes ($message);

        //$message                    =   stripslashes($message);
        $mail                       =   @wp_mail($receiver_email, $subject, $message, $headers);
        $duplicate_email_adr        =   esc_html ( wpresidence_get_option('wp_estate_duplicate_email_adr','') );

        if( $duplicate_email_adr!='' ){
            $message = $message.' '.esc_html__('Message was also sent to ','wpresidence-core').$receiver_email;
            wp_mail($duplicate_email_adr, $subject, $message, $headers);
        }

        if($propid!=0){
            $agents_secondary   =   get_post_meta($propid, 'property_agent_secondary', true);
            if(is_array($agents_secondary)):
              foreach($agents_secondary  as $key=>$value){
                  $receiver_email= esc_html( get_post_meta($value, 'agent_email', true) );
                  wp_mail($receiver_email, $subject, $message, $headers);
              }
            endif;

        }

        $response   =esc_html__('The message was sent!' ,'wpresidence-core');
        if( $schedule_mesaj!=''){
            $response.=esc_html__('Your showing request will be confirmed via email or private message.','wpresidence-core');
        }

        echo json_encode(array('sent'=>true,'response'=>$response) );
        die();
    }
endif; // end   wpestate_ajax_agent_contact_form



add_action( 'wp_ajax_nopriv_wpestate_ajax_agent_contact_form_forcrm', 'wpestate_ajax_agent_contact_form_forcrm' );
add_action( 'wp_ajax_wpestate_ajax_agent_contact_form_forcrm', 'wpestate_ajax_agent_contact_form_forcrm' );

if( !function_exists('wpestate_ajax_agent_contact_form_forcrm') ):
    function wpestate_ajax_agent_contact_form_forcrm(){

        if(!function_exists('wpestate_crm_add_lead')){
          die();
        }
        // check for POST vars
        $hasError       =   false;
        $allowed_html   =   array();
        $to_print       =   '';

        if ( !wp_verify_nonce( $_POST['nonce'], 'ajax-property-contact')) {
            exit("No naughty business please");
        }


        if ( isset($_POST['name']) ) {
            if( trim($_POST['name']) =='' || trim($_POST['name']) ==esc_html__('Your Name','wpresidence-core') ){
                exit();
            }else {
                $name = sanitize_text_field (wp_kses( trim($_POST['name']),$allowed_html) );
            }
        }

        //Check email
        if ( isset($_POST['email']) || trim($_POST['name']) ==esc_html__('Your Email','wpresidence-core') ) {
            if( trim($_POST['email']) ==''){
                exit();
            } else if( filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) === false) {
                exit();
            } else {
                $email = sanitize_text_field ( wp_kses( trim($_POST['email']),$allowed_html) );
            }
        }



        $phone   = sanitize_text_field(wp_kses( trim($_POST['phone']),$allowed_html) );
        $subject =esc_html__('Contact form from ','wpresidence-core') . esc_url( home_url('/') ) ;

        //Check comments
        if ( isset($_POST['comment']) ) {
            if( trim($_POST['comment']) =='' || trim($_POST['comment']) ==esc_html__('Your Message','wpresidence-core')){
                exit();
            }else {
                $comment = (wp_kses($_POST['comment'] ,$allowed_html ));
            }
        }

        $message    =   '';
        $propid     =   intval($_POST['propid']);
        $agent_id   =   intval($_POST['agent_id']);

        $schedule_mesaj =   '';
        $schedule_hour  =   sanitize_text_field($_POST['schedule_hour']);
        $schedule_day   =   sanitize_text_field($_POST['schedule_day']);

        if($schedule_hour!='' && $schedule_day!=''){
            $schedule_mesaj = sprintf (esc_html__('I would like to schedule a viewing on %s at %s. Please confirm the meeting via email or private message. ','wpresidence-core'),$schedule_day,$schedule_hour);
            $comment =   $schedule_mesaj .$comment;
        }


        if($propid!=0){
            $permalink  = esc_url( get_permalink(  $propid ));

        }else if($agent_id!=0){
            $permalink      =    esc_url( get_permalink(  $agent_id ) );

        }else{
            $permalink      =   'contact page';

        }



       $def_arguments=array(
           'source'            =>'',
           'page_source'       =>$permalink,
           'title'             =>$subject,
           'details'           =>$comment,
           'contact_name'      =>$name,
           'contact_email'     =>$email,
           'contact_mobile'    =>$phone,
           'agent_id'          =>$agent_id,
           'propid'            =>$propid
       );
       wpestate_crm_add_lead($def_arguments);

       echo json_encode(array('sent'=>true,'crm_records'=>$def_arguments) );
        die();
    }
endif; // end   wpestate_ajax_agent_contact_form

/*
* Ajax adv search contact function
*
*
*
*/
add_action( 'wp_ajax_nopriv_wpestate_ajax_contact_form_footer', 'wpestate_ajax_contact_form_footer' );
add_action( 'wp_ajax_wpestate_ajax_contact_form_footer', 'wpestate_ajax_contact_form_footer' );

if( !function_exists('wpestate_ajax_contact_form_footer') ):
  function wpestate_ajax_contact_form_footer(){
        check_ajax_referer( 'ajax-footer-contact', 'nonce');
        $hasError       = false;
        $to_print       =   '';
        $allowed_html   =   array();



        if ( isset($_POST['name']) ) {
            if( trim($_POST['name']) =='' || trim($_POST['name']) ==esc_html__('Your Name','wpresidence-core') ){
                echo json_encode(array('sent'=>false, 'response'=>esc_html__('The name field is empty !','wpresidence-core') ));
                exit();
            }else {
                $name = wp_kses( trim($_POST['name']),$allowed_html );
            }
        }

        //Check email
        if ( isset($_POST['email']) || trim($_POST['name']) ==esc_html__('Your Email','wpresidence-core') ) {
            if( trim($_POST['email']) ==''){
                echo json_encode(array('sent'=>false, 'response'=>esc_html__('The email field is empty','wpresidence-core' ) ) );
                exit();
            } else if( filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) === false) {
                echo json_encode(array('sent'=>false, 'response'=>esc_html__('The email doesn\'t look right !','wpresidence-core') ) );
                exit();
            } else {
                $email = wp_kses( trim($_POST['email']),$allowed_html );
            }
        }



        $phone = wp_kses( trim($_POST['phone']),$allowed_html );

        //Check comments
        if ( isset($_POST['contact_coment']) ) {
            if( trim($_POST['contact_coment']) ==''){
                echo json_encode(array('sent'=>false, 'response'=>esc_html__('Your message is empty !','wpresidence-core') ) );
                exit();
            }else {
                $comment = wp_kses( trim ($_POST['contact_coment'] ) ,$allowed_html);

            }
        }


        $receiver_email =   esc_html( wpresidence_get_option('wp_estate_email_adr', ''));
        $message        =   '';

        $subject        =   esc_html__('Contact form from ','wpresidence-core') . esc_url( home_url('/') ) ;
        $message        .=  esc_html__('Client Name','wpresidence-core').": ". $name . "\n\n".esc_html__('Email','wpresidence-core').": " . $email . " \n\n ".esc_html__('Phone','wpresidence-core').": " . $phone . " \n\n ".esc_html__("Subject",'wpresidence-core').": " . $subject . " \n\n".esc_html__('Message','wpresidence-core').":\n " . $comment;
        $message        .=  "\n\n ".esc_html__('Message sent from footer contact form','wpresidence-core');
        $message        =   stripslashes($message);
        $headers        =   'From: noreply  <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n".
                            'Reply-To: noreply@'.$_SERVER['HTTP_HOST']. "\r\n" .
                            'X-Mailer: PHP/' . phpversion();
        wp_mail($receiver_email, $subject, $message, $headers);

        echo json_encode(array('sent'=>true, 'response'=>esc_html__('The message was sent !','wpresidence-core') ) );
        die();

    }
endif; // end   ajax_agent_contact_form


/*
* CRM function for footer contact form
*
*
*
*/
add_action( 'wp_ajax_nopriv_wpestate_ajax_contact_form_footer_crm', 'wpestate_ajax_contact_form_footer_crm' );
add_action( 'wp_ajax_wpestate_ajax_contact_form_footer_crm', 'wpestate_ajax_contact_form_footer_crm' );

if( !function_exists('wpestate_ajax_contact_form_footer_crm') ):
  function wpestate_ajax_contact_form_footer_crm(){
        if(!function_exists('wpestate_crm_add_lead')){
          die();
        }
        check_ajax_referer( 'ajax-footer-contact', 'nonce');
        $hasError       = false;
        $to_print       =   '';
        $allowed_html   =   array();



        if ( isset($_POST['name']) ) {
            if( trim($_POST['name']) =='' || trim($_POST['name']) ==esc_html__('Your Name','wpresidence-core') ){
                exit();
            }else {
                $name = wp_kses( trim($_POST['name']),$allowed_html );
            }
        }

        //Check email
        if ( isset($_POST['email']) || trim($_POST['name']) ==esc_html__('Your Email','wpresidence-core') ) {
            if( trim($_POST['email']) ==''){
                exit();
            } else if( filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) === false) {
                exit();
            } else {
                $email = wp_kses( trim($_POST['email']),$allowed_html );
            }
        }



        $phone = wp_kses( trim($_POST['phone']),$allowed_html );

        //Check comments
        if ( isset($_POST['contact_coment']) ) {
            if( trim($_POST['contact_coment']) ==''){
                exit();
            }else {
                $comment = wp_kses( trim ($_POST['contact_coment'] ) ,$allowed_html);
            }
        }



        $subject        =   esc_html__('Contact form from ','wpresidence-core') . esc_url( home_url('/') ) ;

        global $wp;
        $def_arguments=array(
           'source'            => '',
           'page_source'       => home_url( $wp->request ),
           'title'             =>$subject,
           'details'           =>$comment,
           'contact_name'      =>$name,
           'contact_email'     =>$email,
           'contact_mobile'    =>$phone,
           'agent_id'          => 0,
           'propid'            => 0
       );
       wpestate_crm_add_lead($def_arguments);

       echo json_encode(array('sent'=>true,'crm_records'=>$def_arguments) );
       die();

    }
endif; // end   ajax_agent_contact_form





if (!function_exists('wpestate_select_email_type')):
    function wpestate_select_email_type($user_email,$type,$arguments){
        $value          =   wpresidence_get_option('wp_estate_'.$type,'');
        $value_subject  =   wpresidence_get_option('wp_estate_subject_'.$type,'');

        if (function_exists('icl_translate') ){
            $value          =  icl_translate('wpresidence-core','wp_estate_email_'.$value, $value ) ;
            $value_subject  =  icl_translate('wpresidence-core','wp_estate_email_subject_'.$value_subject, $value_subject ) ;
        }

        if( trim($value_subject)=='' || trim($value)=='' ){
            return;
        }

        wpestate_emails_filter_replace($user_email,$value,$value_subject,$arguments);
    }
endif;


if( !function_exists('wpestate_emails_filter_replace')):
    function  wpestate_emails_filter_replace($user_email,$message,$subject,$arguments){
        $arguments ['website_url'] = get_option('siteurl');
        $arguments ['website_name'] = get_option('blogname');
        $arguments ['user_email'] = $user_email;



        $user= get_user_by('email',$user_email);
	if( isset($user->user_login) ){
            $arguments ['username'] = $user->user_login;
        }


        foreach($arguments as $key_arg=>$arg_val){
            $subject = str_replace('%'.$key_arg, $arg_val, $subject);
            $message = str_replace('%'.$key_arg, $arg_val, $message);
        }

        wpestate_send_emails($user_email, $subject, $message );
    }
endif;



if( !function_exists('wpestate_send_emails') ):
    function wpestate_send_emails($user_email, $subject, $message ){
        $headers[] = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        @wp_mail(
            $user_email,
            stripslashes($subject),
            stripslashes($message),
            $headers
            );
    };
endif;




if( !function_exists('wpestate_email_management') ):
    function wpestate_email_management(){
        print '<div class="wpestate-tab-container">';
        print '<h1 class="wpestate-tabh1">'.esc_html__('Email Management','wpresidence-core').'</h1>';
        print '<a href="http://help.wpresidence.net/#" target="_blank" class="help_link">'.esc_html__('help','wpresidence-core').'</a>';


        $emails=array(
            'new_user'                  =>  esc_html__('New user  notification','wpresidence-core'),
            'admin_new_user'            =>  esc_html__('New user admin notification','wpresidence-core'),
            'purchase_activated'        =>  esc_html__('Purchase Activated','wpresidence-core'),
            'password_reset_request'    =>  esc_html__('Password Reset Request','wpresidence-core'),
            'password_reseted'          =>  esc_html__('Password Reseted','wpresidence-core'),
            'agent_review'              =>  esc_html__('Agent Review Posted','wpresidence-core'),
            'purchase_activated'        =>  esc_html__('Purchase Activated','wpresidence-core'),
            'approved_listing'          =>  esc_html__('Approved Listings','wpresidence-core'),
            'new_wire_transfer'         =>  esc_html__('New wire Transfer','wpresidence-core'),
            'admin_new_wire_transfer'   =>  esc_html__('Admin - New wire Transfer','wpresidence-core'),
            'admin_expired_listing'     =>  esc_html__('Admin - Expired Listing','wpresidence-core'),
            'matching_submissions'      =>  esc_html__('Matching Submissions','wpresidence-core'),
            'paid_submissions'          =>  esc_html__('Paid Submission','wpresidence-core'),
            'featured_submission'       =>  esc_html__('Featured Submission','wpresidence-core'),
            'account_downgraded'        =>  esc_html__('Account Downgraded','wpresidence-core'),
            'membership_cancelled'      =>  esc_html__('Membership Cancelled','wpresidence-core'),
            'downgrade_warning'         =>  esc_html__('Downgrade Warning','wpresidence-core'),
            'free_listing_expired'      =>  esc_html__('Free Listing Expired','wpresidence-core'),
            'new_listing_submission'    =>  esc_html__('New Listing Submission','wpresidence-core'),
            'listing_edit'              =>  esc_html__('Listing Edit','wpresidence-core'),
            'recurring_payment'         =>  esc_html__('Recurring Payment','wpresidence-core'),
            'membership_activated'      =>  esc_html__('Membership Activated','wpresidence-core'),
            'agent_update_profile'      =>  esc_html__('Update Profile','wpresidence-core'),
            'agent_added'               =>  esc_html__('New Agent','wpresidence-core')


        );


        print '<div class="email_row">'.esc_html__('Global variables: %website_url as website url,%website_name as website name, %user_email as user_email, %username as username','wpresidence-core').'</div>';


        foreach ($emails as $key=>$label ){

            print '<div class="email_row">';
            $value          = stripslashes( wpresidence_get_option('wp_estate_'.$key,'') );
            $value_subject  = stripslashes( wpresidence_get_option('wp_estate_subject_'.$key,'') );

            print '<label for="subject_'.$key.'">'.esc_html__('Subject for','wpresidence-core').' '.$label.'</label>';
            print '<input type="text" name="subject_'.$key.'" value="'.$value_subject.'" />';

            print '<label for="'.$key.'">'.esc_html__('Content for','wpresidence-core').' '.$label.'</label>';
            print '<textarea rows="10" cc="111" name="'.$key.'">'.$value.'</textarea>';
            print '<div class="extra_exp"> '.wpestate_emails_extra_details($key).'</div>';
            print '</div>';

        }

        print'<p class="submit">
               <input type="submit" name="submit" id="submit" class="button-primary"  value="'.esc_html__('Save Changes','wpresidence-core').'" />
            </p>';

        print '</div>';
    }
endif;


if( !function_exists('wpestate_emails_extra_details') ):
    function wpestate_emails_extra_details($type){
        $return_string='';
        switch ($type) {
            case "new_user":
                    $return_string=esc_html__('%user_login_register as new username, %user_pass_register as user password, %user_email_register as new user email' ,'wpresidence-core');
                    break;

            case "admin_new_user":
                    $return_string=esc_html__('%user_login_register as new username and %user_email_register as new user email' ,'wpresidence-core');
                    break;

            case "password_reset_request":
                    $return_string=esc_html__('%reset_link as reset link','wpresidence-core');
                    break;

            case "password_reseted":
                    $return_string=esc_html__('%user_pass as user password','wpresidence-core');
                    break;
            case "agent_review":
                    $return_string=esc_html__('%agent_name as agent name, %user_post as username(the one who posted)','wpresidence-core');
                    break;
            case "purchase_activated":
                    $return_string='';
                    break;

            case "approved_listing":
                    $return_string=esc_html__('* you can use %post_id as listing id, %property_url as property url and %property_title as property name','wpresidence-core');
                    break;

            case "new_wire_transfer":
                    $return_string=  esc_html__('* you can use %invoice_no as invoice number, %total_price as $totalprice and %payment_details as  $payment_details','wpresidence-core');
                    break;

            case "admin_new_wire_transfer":
                    $return_string=  esc_html__('* you can use %invoice_no as invoice number, %total_price as $totalprice and %payment_details as  $payment_details','wpresidence-core');
                    break;

            case "admin_expired_listing":
                    $return_string=  esc_html__('* you can use %submission_title as property title number, %submission_url as property submission url','wpresidence-core');
                    break;

            case "matching_submissions":
                    $return_string=  esc_html__('* you can use %matching_submissions as matching submissions list','wpresidence-core');
                    break;

            case "paid_submissions":
                    $return_string= '';
                    break;

            case  "featured_submission":
                    $return_string=  '';
                    break;

            case "account_downgraded":
                    $return_string=  '';
                    break;

            case "free_listing_expired":
                    $return_string=  esc_html__('* you can use %expired_listing_url as expired listing url and %expired_listing_name as expired listing name','wpresidence-core');
                    break;

            case "new_listing_submission":
                    $return_string=  esc_html__('* you can use %new_listing_title as new listing title and %new_listing_url as new listing url','wpresidence-core');
                    break;

            case "listing_edit":
                    $return_string=  esc_html__('* you can use %editing_listing_title as editing listing title and %editing_listing_url as editing listing url','wpresidence-core');
                    break;

            case "recurring_payment":
                    $return_string=  esc_html__('* you can use %recurring_pack_name as recurring packacge name and %merchant as merchant name','wpresidence-core');
                    break;

            case "membership_activated":
                    $return_string=  '';
                    break;



        }
        return $return_string;
    }
endif;
