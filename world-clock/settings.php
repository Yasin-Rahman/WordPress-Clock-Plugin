<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<?php if( isset($_GET['settings-updated']) ) { ?>
<div id='message' class='updated notice is-dismissible'>
<p><strong><?php _e('Settings saved.') ?></strong></p>
</div>
<?php } ?>

<div style="width: 500px; margin: 0 auto; padding: 50px 0 40px;">
        <ul class="tabs" data-persist="true">
            <li><a href="#view1">General</a></li>
            <li><a href="#view2">Advanced</a></li>
            
        </ul>
        <form id="form" method="post" action="options.php">
<?php settings_fields( 'settings_group' ); ?>
        <div class="tabcontents">
            <div id="view1">
               
<h4>World Time Settings</h4>
<table>
<tr><td>Choose Time Format</td><td><select name="format">
<option value="1" <?php if(get_option( 'format' )=="1"){ ?> selected <?php }?>>24 H</option>
<option value="2" <?php if(get_option( 'format' )=="2"){ ?> selected <?php }?>>12 H</option>
</select></td></tr>
<tr><td>Clock Layout</td><td><select name="layout">
<option value="1" <?php if(get_option( 'layout' )=="1"){ ?> selected <?php }?>>Vertical</option>
<option value="2" <?php if(get_option( 'layout' )=="2"){ ?> selected <?php }?>>Horizontal</option>
</select></td></tr>
<tr><td>Display Text Allignment</td><td><select name="align">
<option value="1" <?php if(get_option( 'align' )=="1"){ ?> selected <?php }?>>Top</option>
<option value="2" <?php if(get_option( 'align' )=="2"){ ?> selected <?php }?>>Bottom</option>
<option value="3" <?php if(get_option( 'align' )=="3"){ ?> selected <?php }?>>Left</option>
<option value="4" <?php if(get_option( 'align' )=="4"){ ?> selected <?php }?>>Right</option>
</select></td></tr>
<tr><td>Display Seconds</td><td><select name="sec">
<option value="1" <?php if(get_option( 'sec' )=="1"){ ?> selected <?php }?>>Yes</option>
<option value="0" <?php if(get_option( 'sec' )=="0"){ ?> selected <?php }?>>No</option>
</select></td></tr>
<tr><td>Use Leading Zeors</td><td><select name="zeros">
<option value="1" <?php if(get_option( 'zeros' )=="1"){ ?> selected <?php }?>>Yes</option>
<option value="2" <?php if(get_option( 'zeros' )=="2"){ ?> selected <?php }?>>Not for hours</option>
<option value="3" <?php if(get_option( 'zeros' )=="3"){ ?> selected <?php }?>>Not at all</option>
</select></td></tr>
<tr><td>Dispay Date</td><td><select name="date">
<option value="0">No, don't dispaly</option>
<?php foreach($date_formats as $key=>$format){ ?>
	<option value="<?php echo $key; ?>" <?php if($key==get_option( 'date' )){?> selected <?php }?>><?php echo $format; ?></option>
<?php } ?>
</select></td></tr>
<tr>
<td>
Date Separator
</td>
<td>
<select name="date_separator">
<option value="-" <?php if(get_option( 'date_separator' )=="-"){ ?> selected <?php }?>>-</option>
<option value="/" <?php if(get_option( 'date_separator' )=="/"){ ?> selected <?php }?>>/</option>
<option value=":" <?php if(get_option( 'date_separator' )==":"){ ?> selected <?php }?>>:</option>
<option value="\\" <?php if(get_option( 'date_separator' )=="\\\\"){ ?> selected <?php }?>>\</option>
<option value="|" <?php if(get_option( 'date_separator' )=="|"){ ?> selected <?php }?>>|</option>
<option value="space" <?php if(get_option( 'date_separator' )=="space"){ ?> selected <?php }?>>space</option>
</select>
</td>
</tr>
<tr>
<td>Week Day</td>
<td>
<select name="week_day">
<option value="1" <?php if(get_option( 'week_day' )=="1"){ ?> selected <?php }?>>Yes</option>
<option value="0" <?php if(get_option( 'week_day' )=="0"){ ?> selected <?php }?>>No</option>
</select>
</td>
</tr>
</table>


                
            </div>            
            <div id="view2">
                <h4>World Time Settings</h4>
                <table>
                <?php
					
	   $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

	   		
			for($i=1;$i<=4;$i++){?>
                	<tr><td colspan="2"><b>Clock <?php echo $i; ?></b></td></tr>
                    <tr><td>Show Clock</td><td><select name="clock<?php echo $i; ?>_show"><option value='1'>Yes</option><option value='0' <?php if(!get_option('clock'.$i.'_show')){?> selected <?php } ?> >No</option></select></td></tr>
                    
                    <tr><td> Timezone</td><td><select name="clock<?php echo $i; ?>_timezone">
                    <?php foreach($tzlist as $country){?>
                    <option <?php if(get_option('clock'.$i.'_timezone')==$country){ ?>selected<?php } ?> value="<?php echo $country; ?>"><?php echo $country; ?></option>
                  <?php } ?>
                    </select></td></tr>
                    
                    <tr>
                    	<td>Display Text</td>
                        <td><input type="text" name="clock<?php echo $i; ?>_text" value="<?php if(get_option('clock'.$i.'_text')!=''){ echo get_option('clock'.$i.'_text'); } ?>"></td>
                    </tr>
                         
            <?php } ?> 
                </table>
            </div>
            <?php submit_button(); ?>
        </div>
        </form>
    </div>
