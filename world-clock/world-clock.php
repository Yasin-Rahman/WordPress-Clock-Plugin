<?php
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
	/*
		Plugin Name: World Time
		Plugin URI: http://www.example.com
		Author: Yasin Rahman
		Author URI: http://www.example.com
		Version: 1.0.0
		Description: its a plugin to show world time
	*/
		
	global $date_formats;
	$date_formats=array("YYYY-MM-DD"=>"YYYY-MM-DD","DD-MM-YYYY"=>"DD-MM-YYYY","MM-DD-YYYY"=>"MM-DD-YYYY","MMM-DD-YYYY"=>"Jul-11-2002","DD-MMM-YYYY"=>"11-Jul-2002","MMMM-DD-YYYY"=>"July-11-2002","DD-MMMM-YYYY"=>"11-July-2002");
	
	/* to register plugin */
	function register()
	{
		 //code here
	}
	register_activation_hook( __FILE__ ,'register');
	
	function uninstall()
	{
		delete_option('format');
		delete_option('layout');		
		delete_option('align');
		delete_option('sec');
		delete_option('zeros');
		delete_option('date');
		delete_option('date_separator');
		delete_option('week_day');
		/*------------------Advance------------------------*/
		for($i=1;$i<=4;$i++)
		{
			delete_option('clock'.$i.'_show');
			delete_option('clock'.$i.'_timezone');
			delete_option('clock'.$i.'_text');
		}
	}
	
	register_uninstall_hook( __FILE__ ,'uninstall');
	
	/* to create plugin menu in admin area */
	function my_scripts() {
	wp_enqueue_script( 'tabcontent-js', plugins_url( 'world-clock/js/tabcontent.js' , dirname(__FILE__) ) );
	wp_enqueue_style('tabcontent-css', plugins_url( 'world-clock/css/tabcontent.css' , dirname(__FILE__) ) );
	}		
	
	function create_menu()
	{
		$menu=add_menu_page( 'World-Clock', 'Clock', 'manage_options', 'world-clock/settings.php', '', plugins_url( 'world-clock/dtw_clock.png' ), 6 );	
		add_action('admin_enqueue_scripts', 'my_scripts');	
		add_action( 'admin_init', 'register_settings' );			
	}
	
	add_action('admin_menu','create_menu');
	
		
	/* to save settings of our plugin */
	function register_settings()
	{
		register_setting( 'settings_group', 'format' );
		register_setting( 'settings_group', 'layout' );		
		register_setting( 'settings_group', 'align'  );
		register_setting( 'settings_group', 'sec'    );
		register_setting( 'settings_group', 'zeros'  );
		register_setting( 'settings_group', 'date'   );
		register_setting( 'settings_group', 'date_separator');
		register_setting( 'settings_group', 'week_day');
		/*------------------Advance------------------------*/
		for($i=1;$i<=4;$i++)
		{
			register_setting( 'settings_group', 'clock'.$i.'_show');
			register_setting( 'settings_group', 'clock'.$i.'_timezone');
			register_setting( 'settings_group', 'clock'.$i.'_text');
		}		
	}
	
	/* widget */
	class Clock_Widget extends WP_Widget
	{		
	   
		//* Widget setup */
		function __construct()
		{
			// actual widget code that contains the function logic
			$widget_ops = array('classname' => 'Clock_Widget', 'description' =>__( 'Show World Clock on FrontEnd') );
			// The actual widget code goes here
			parent::WP_Widget( false, 'World Clock', $widget_ops );
		}
		
		function widget($args, $instance) {
				
			// display the widget on website
			//get widget arguments
			$format=get_option('format');
			$layout=get_option('layout');			
			$align=get_option('align');
			$sec=get_option( 'sec' );
			$zeros=get_option( 'zeros' );
			$date=get_option( 'date' );
			$separator=get_option( 'date_separator' )=="space" ? " " : get_option( 'date_separator' );			
			$clock1_show=get_option( 'clock1_show' );
			$clock2_show=get_option( 'clock2_show' );
			$clock3_show=get_option( 'clock3_show' );
			$clock4_show=get_option( 'clock4_show' );
			$weekday=get_option( 'week_day' );
			
			global $date_formats;
			$title = apply_filters('widget_title', $instance['title']);		
			echo $args['before_widget'];                    
            ?>					
						
			
			<style>
				.clocks{
					list-style:none;
					margin:0;
					
				}
				.clocks li
				{
					/* vertical */
					<?php if($layout==2){?>display:inline-block;<?php }?>	
					padding:2px;				
				}
				.digits
				{
					font-size:18px;
				}
			</style>
            
            
            <script src="<?php echo plugins_url( 'world-clock/js/moment.js' , dirname(__FILE__) ); ?>"></script>
			<script src="<?php echo plugins_url( 'world-clock/js/moment-timezone-all-years.js' , dirname(__FILE__) ); ?>"></script>
            <script>
			 // Format string
        /*
          Month
          -----------------------------
          M: 1 2 ... 11 12
          Mo: 1st 2nd ... 11th 12th
          MM: 01 02 ... 11 12
          MMM Jan Feb ... Nov Dec
          MMMM: January February ... November December

          Day of Month
          -----------------------------
          D: 1 2 ... 30 31
          Do: 1st 2nd ... 30th 31st
          DD: 01 02 ... 30 31
          Day of Year DDD 1 2 ... 364 365
          DDDo: 1st 2nd ... 364th 365th
          DDDD: 001 002 ... 364 365

          Day of Week
          -----------------------------
          d: 0 1 ... 5 6
          do: 0th 1st ... 5th 6th
          dd: Su Mo ... Fr Sa
          ddd: Sun Mon ... Fri Sat
          dddd: Sunday Monday ... Friday Saturday
          Day of Week (Locale): e 0 1 ... 5 6
          Day of Week (ISO): E 1 2 ... 6 7

          Week of Year
          -----------------------------
          w: 1 2 ... 52 53
          wo: 1st 2nd ... 52nd 53rd
          ww: 01 02 ... 52 53

          Week of Year (ISO)
          -----------------------------
          W: 1 2 ... 52 53
          Wo: 1st 2nd ... 52nd 53rd
          WW: 01 02 ... 52 53

          Year
          -----------------------------
          YY: 70 71 ... 29 30
          YYYY: 1970 1971 ... 2029 2030

          Week Year 
          -----------------------------
          gg: 70 71 ... 29 30
          gggg: 1970 1971 ... 2029 2030

          Week Year (ISO) 
          -----------------------------
          GG: 70 71 ... 29 30
          GGGG: 1970 1971 ... 2029 2030

          AM/PM 
          -----------------------------
          A: AM PM
          a: am pm

          Hour: 
          -----------------------------
          H: 0 1 ... 22 23
          HH: 00 01 ... 22 23
          h: 1 2 ... 11 12
          hh: 01 02 ... 11 12

          Minute
          -----------------------------
          m: 0 1 ... 58 59
          mm: 00 01 ... 58 59

          Second
          -----------------------------
          s: 0 1 ... 58 59
          ss: 00 01 ... 58 59

          Fractional Second 
          -----------------------------
          S: 0 1 ... 8 9
          SS: 0 1 ... 98 99
          SSS 0 1 ... 998 999

          Timezone
          -----------------------------
          z or zz: EST CST ... MST PST 
          Z: -07:00 -06:00 ... +06:00 +07:00
          ZZ: -0700 -0600 ... +0600 +0700

          Unix Timestamp
          -----------------------------
          X: 1360013296
        */
				function DisplayTime(span_id,timezone)
				{
					id=span_id.split("_");
					var now=moment.tz(timezone).format("<?php if($weekday){ ?>dddd, <?php } ?><?php echo $date;?>");
					//alert(now);
					var time=null;
					<?php if($format==1){?>
						time=moment.tz(timezone).format("HH:mm:ss");
						<?php if(!$sec){?>
							time=moment.tz(timezone).format("HH:mm");
						<?php } ?>
						
						<?php if($zeros==2){?>
							time=moment.tz(timezone).format("H:mm:ss");
							<?php if(!$sec){?>
							time=moment.tz(timezone).format("H:mm");
							<?php } ?>
						<?php } ?>
						<?php if($zeros==3){?>
							time=moment.tz(timezone).format("H:m:s");
							<?php if(!$sec){?>
							time=moment.tz(timezone).format("H:m");
							<?php } ?>
						<?php } ?>
						
					<?php }else{?>
						time=moment.tz(timezone).format("hh:mm:ss A");
						<?php if(!$sec){?>
							time=moment.tz(timezone).format("hh:mm A");
							<?php } ?>
						<?php if($zeros==2){?>
							time=moment.tz(timezone).format("h:mm:ss A");
							<?php if(!$sec){?>
							time=moment.tz(timezone).format("h:mm A");
							<?php } ?>
						<?php } ?>
						<?php if($zeros==3){?>
							time=moment.tz(timezone).format("h:m:s A");
							<?php if(!$sec){?>
							time=moment.tz(timezone).format("h:m A");
							<?php } ?>
						<?php } ?>
					<?php }?>
					
					//dnt=now.split(new RegExp('[-+T]','g'));
					
					dnt=now.replace(/-/,"<?php echo $separator ?>");
					dnt=dnt.replace(/-/,"<?php echo $separator ?>");
					//console.log(dnt);
					document.getElementById(span_id).innerHTML=time;
					//document.getElementById("date_"+id[1]).innerHTML=dnt[0]+'-'+dnt[1]+'-'+dnt[2];
					document.getElementById("date_"+id[1]).innerHTML=dnt;
				}
				
				
			</script>
           		
		   <?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
			}?>
        
			<ul class="clocks">
            <!------------clocks-------->
            <?php for($i=1;$i<=4;$i++){?>	
         		<?php if(get_option( 'clock'.$i.'_show' )){?>
				<li><div id="clock_<?php echo $i; ?>">
                	<?php if($align<=2){ if($align==1){?>
                <div><?php if(get_option('clock'.$i.'_text')!=''){ echo get_option('clock'.$i.'_text'); } ?> <!--top--></div>
                <?php }}else {if($align==3){ ?>
                <?php if(get_option('clock'.$i.'_text')!=''){ echo get_option('clock'.$i.'_text'); } ?><!--left-->  
                <?php }} ?>
                <span id="clock_<?php echo $i; ?>" class="digits"></span>
                <?php if($align<=2){if($align==2){?>
                <div><?php if(get_option('clock'.$i.'_text')!=''){ echo get_option('clock'.$i.'_text'); } ?> <!--bottom--></div>
                <?php }}else {if($align==4){ ?>
                <?php if(get_option('clock'.$i.'_text')!=''){ echo get_option('clock'.$i.'_text'); } ?><!--right-->  
                <?php }} ?>
                <?php if($date){?>
                <div class="date" id="date_<?php echo $i; ?>"> </div>
                <?php }?>
                <script>
				var t1 = setInterval(function(){DisplayTime('clock_<?php echo $i; ?>','<?php echo get_option('clock'.$i.'_timezone'); ?>')},500);
				</script>
                </div></li>	
                <?php } ?>
            <?php }//end of for loop ?>
            <!------------clocks--------> 
			
            </ul>			
			
            <?php echo $args['after_widget']; ?>
		<?php }		
		
		function update($new_instance, $old_instance) {
		// save widget options
			$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
		}
		function form($instance) {
		// form to display widget settings in WordPress admin
			$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
		}
	}//end of class Dtw_Clock_Widget
	
	function clock_register_widget()
	{
		register_widget( 'Dtw_Clock_Widget' );
	}
	/* Load the widget */
	add_action( 'widgets_init', 'clock_register_widget' );		
	
?>