<form method="post" name="cleanup_options" action="options.php">
<?php
global $mlsimport;
settings_fields($this->plugin_name.'_administrative_options');
do_settings_sections($this->plugin_name.'_administrative_options');
$options            =   get_option($this->plugin_name.'_administrative_options');


$mlsimport->admin->mlsimport_saas_setting_up();
//$mlsimport->admin->mlsimport_saas_start_doing_reconciliation();
//$prop_id='20';
//$mlsimport->admin->mlsimport_saas_start_cron_links_per_item( $prop_id);

mls_saas_single_write_import_custom_logs('CRETU');


print '<h1> Administrative Tools</h1>';


?>      
    
<h3 style="margin-bottom:0px;"> Clear cached data </h3>
<input class="mlsimport_button"  type="button" id="mlsimport-clear-cache" value="Clear Plugin Cached Data" />

<h3 style="margin-bottom:0px;"> Cron Jobs </h3>
<div class="cron_job_explainin">
    By default a syncronization event runs every hour. The action will be triggered when someone visits your site if the scheduled time has passed. This is the default, "out of the box" way to do things in Wordpress and it works very well in 99% of the cases.
    
    </br></br>If, for some reason, you want to force the syncronization event to run every two hours(minimum time frame permitted by this plugin) you can set a cron job on your server enviroment and call this url : http://yourwebsite.com/?mlsimport_cron=yes.
    </br></br><strong>Example : 0	*/2	*	*	*	wget https://yourwebsite.com/?mlsimport_cron=yes</strong> .
    
    
</div>
    
<fieldset class="mlsimport-fieldset" style="background-color: #eee;padding: 10px;border-radius: 5px;">
    
    
    <h3>Delete Properties</h3>
            
    <div id="mlsimport-delete-notification" >Please fill all the forms </div>
    
    
    <label class="mlsimport-label" for="<?php echo $this->plugin_name.'_administrative_options'; ?>-import" >
       <?php echo esc_html__('Delete from Category',$this->plugin_name); ?>
    </label></br>
    <input type="text" lass="mlsimport-input" id="mlsimport_delete_category"></br>
    </br>
    <label class="mlsimport-label" for="<?php echo $this->plugin_name.'_administrative_options'; ?>-import" >
       <?php echo esc_html__('Delete the term from category(use term slug)',$this->plugin_name); ?>
    </label></br>
    <input type="text" lass="mlsimport-input" id="mlsimport_delete_category_term"></br>
    </br>
    <label class="mlsimport-label" for="<?php echo $this->plugin_name.'_administrative_options'; ?>-import" >
        Pause the script between property delete processes (1=1 sec . For slow hosting use a number between 1 and 5) 
    </label>
    
    <input type="text" lass="mlsimport-input" id="mlsimport_delete_timeout" value="0">

    </br>
    <input class="button mlsimport_button"  type="button" id="mlsimport-delete-prop" value="Delete" />
</fieldset>
    
<?php submit_button( __( 'Save Changes', $this->plugin_name ), 'mlsimport_button','submit', TRUE ); ?>
</form>