<?php
/*
 *  Lopp troght the listings and get Listing key
 * 
 * 
 * 
 * 
 * 
 * */

function mlsimport_saas_reconciliation_event_function(){
    global $mlsimport; 
 
    $options    =   get_option('mlsimport_admin_options');
     
    if( isset($options['mlsimport_mls_name']) && $options['mlsimport_mls_name']!='' ){
        $mlsimport->admin->mlsimport_saas_start_doing_reconciliation();
    }
}

