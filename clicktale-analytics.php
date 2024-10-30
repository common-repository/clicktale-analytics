<?php
/* 
Plugin Name: ClickTale Customer Experience Analytics Integration
Version: 1.0
Plugin URI: 
Description: Integrate ClickTale Customer Experience Analytics into your blog. Get free <a href="http://tinyurl.com/yccx4xo">ClickTale account</a>, create project and then set your blog <a href="options-general.php?page=clicktale-analytics/clicktale-analytics.php">options</a>.
Author: 
Author URI: 
*/

function clicktale_admin_menu() {
    add_options_page('ClickTale options', 'ClickTale', 8, __FILE__, 'clicktale_options_manage'); 
}

function clicktale_options_manage() {
if (isset($_POST['update_message'])) {

    ?><div id="message" class="updated fade"><p><strong><?php

    update_option('clicktale_project_id', $_POST['project_id']);
    $freq = $_POST['freq'];
    if((is_numeric($freq) && floatval($freq) > 1)) $freq = $freq / 100.0;
    if($freq > 1) $freq = 1;
    update_option('clicktale_freq', $freq);

    echo "ClickTale Options Saved!";

    ?></strong></p></div><?php
}

$project_id = get_option('clicktale_project_id');
?>
    <div class=wrap>
    <h2>ClickTale Options</h2>
    <?php if(!empty($project_id)) { ?>
    <p><strong>Go to <a href="http://track.moreniche.com/hit.php?w=118499&p=2&s=57">ClickTale</a> to watch recordings and heatmaps.</strong></p>
    <?php } ?>
        <form name="clicktale_form" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                                                                  
            <fieldset class="options">
                <legend><p>ClickTale Project ID: <input name="project_id" id="projectid" type="text" value="<?php echo $project_id; ?>"></input></p>
			 </legend>
            </fieldset>
			 <fieldset class="options">
                <legend>Recording Ratio (0-100%): <input name="freq" id="freq" type="text" value="<?php echo round($freq*100); ?>" size="3"></input> %  (required)
                </legend>
            </fieldset>        
                <?php if(empty($project_id)) { ?><p><strong>Instructions for getting your project id:</strong><br /><br />1. Get free <a href="http://tinyurl.com/yccx4xo">ClickTale account</a><br />2. Create new project on ClickTale<br />3. Get your project ID from the generated code</p>
				<p>Finding your clicktale id</p>
				<p>if your clicktale project id is <b>8758</b> the code from clicktale will look like this:
					<pre>	&lt;!-- ClickTale Bottom part --&gt;
	&lt;div id=&quot;ClickTaleDiv&quot; style=&quot;display: none;&quot;&gt;&lt;/div&gt;
	&lt;script type=&quot;text/javascript&quot;&gt;
	if(document.location.protocol!='https:')
	  document.write(unescape(&quot;%3Cscript%20src='http://s.clicktale.net/WRb.js'%20type='text/javascript'%3E%3C/script%3E&quot;));
	&lt;/script&gt;
	&lt;script type=&quot;text/javascript&quot;&gt;
	if(typeof ClickTale=='function') ClickTale(<font size="+1"><b>8758</b></font>,1,&quot;www02&quot;);
	&lt;/script&gt;
	&lt;!-- ClickTale end of Bottom part --&gt;</pre>
				
				  <?php } ?>
               

            
            <p class="submit">
                <input type="submit" name="update_message" value="Save &raquo;" />
            </p>
        </form>
    </div>
<?php
}

function clicktale_footer() {
    global $userdata;
    get_currentuserinfo();
    
    $project_id = get_option('clicktale_project_id');
    $freq = get_option('clicktale_freq');    
    $eventsmask = get_option('clicktale_eventsmask');    
    
    $user = null;   
     
    if(!empty($userdata->ID)) {
        $user = $userdata->user_login;
    }
    
    if(!empty($freq) && !empty($project_id)) {
        echo '<!-- ClickTale Bottom part -->
        <div id="ClickTale" style="display: none;"></div>
        <script src="http://s.clicktale.net/WRb.js" type="text/javascript"></script>
        <script type="text/javascript">';
        if(!empty($eventmask)) echo 'if(typeof ClickTaleEventsMask != \'undefined\') ClickTaleEventsMask -= '.$eventsmask.';';
        echo '
        if(typeof ClickTale==\'function\') ClickTale('.$project_id.','.$freq.');';        
        echo '        
        </script>
        <!-- ClickTale end of Bottom part -->';  
    }  
}

function clicktale_head() {
echo '<!-- ClickTale Top part -->
<script type="text/javascript">
var WRInitTime=(new Date()).getTime();
</script>
<!-- ClickTale end of Top part -->';    
}




add_action ('wp_head', 'clicktale_head');  
add_action ('wp_footer', 'clicktale_footer'); 
add_action ('admin_menu', 'clicktale_admin_menu'); 
?>
