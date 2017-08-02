<?php
/*
Plugin Name: facebook foot panel
Plugin URI: http://www.wprooms.com
Description: This plugin makes a foot panel similar to the one facebook uses
Version: 1.0.1.1
Author: Janvier M @ JanvierDesigns
Author URI: http://www.janvierdesigns.com 
*/

/*  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php


// Hook for adding admin menus
add_action('admin_menu', 'fbfp_add_pages');

// action function for above hook
function fbfp_add_pages() {
    // Add a new submenu under Options:
    //add_options_page('Test Options', 'Test Options', 'administrator', 'testoptions', 'fbfp_options_page');

    // Add a new submenu under Manage:
    //add_management_page('Test Manage', 'Test Manage', 'administrator', 'testmanage', 'fbfp_manage_page');


	
	add_submenu_page( 'bp-general-settings', __( 'FB Footer Panel', 'buddypress'), __( 'FB Footer Panel General Settings', 'buddypress' ), 'manage_options', 'bp-fb-footer-options', 'fbfp_toplevel_page' );
}?>
<?php

// fbfp_toplevel_page() displays the page content for the custom Test Toplevel menu
function fbfp_toplevel_page() {?>
		<div style=" background:url(<?php echo get_option('home') ?>/wp-content/plugins/facebook-foot-panel/images/bg_top.jpg) repeat-x top center; min-height: 60px; color:#0099FF; font-size:30px; text-align:center; padding-top: 5px;">
		Facebook Foot Panel
		</div>
		<div>
			<?php 
			// variables for the field and option names 
			$opt_name = 'fbfp_disable_jquery';
			$hidden_field_name = 'fbfp_submit_hidden';
			$data_field_name = 'fbfp_disable_jquery';
		
			// Read in existing option value from database
			$opt_val = get_option( $opt_name );
		
			// See if the user has posted us some information
			// If they did, this hidden field will be set to 'Y'
			if( $_POST[ $hidden_field_name ] == 'Y' ) {
				// Read their posted value
				$opt_val = $_POST[ $data_field_name ];
		
				// Save the posted value in the database
				update_option( $opt_name, $opt_val );
		
				// Put an options updated message on the screen
		
			?>
			<div class="updated"><p><strong><?php _e('Options saved.', 'fbfp_trans_domain' ); ?></strong></p></div>
			<?php
		
			}
		
			// Now display the options editing screen
		
			echo '<div class="wrap">';
		
			// header
		
			echo "<h2>" . __( 'Facebook Foot Panel General options', 'fbfp_trans_domain' ) . "</h2>";
		
			// options form
			
			?>

			<?php echo (get_option('fbfp_disable_jquery')); ?>
			<form name="form1" method="post" action=""> 
			<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
			
			<p><?php _e("Disable Query:", 'fbfp_trans_domain' ); ?> <br />
			Yes : <input type="radio" name="fbfp_disable_jquery" value="Yes" <?php if(get_option('fbfp_disable_jquery') =='Yes'){echo 'checked="checked"';} else{}?>>  <br />
			No :<input type="radio" name="fbfp_disable_jquery" value="No" <?php if(get_option('fbfp_disable_jquery') =='No'){echo 'checked="checked"';}else{}; ?>>  <br />
			</p><hr />
			
			<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Update Options', 'fbfp_trans_domain' ) ?>" />
			</p>
			
			</form>

		</div>
<?php }?>
<?php function add_fb_foot_panel_header1(){
echo "<link rel='stylesheet' media='screen' href='".get_option('home')."/wp-content/plugins/facebook-foot-panel/css/foot_panelstyle.css' />";
?>
<script type="text/javascript" src="<?php echo get_option('home') ?>/wp-content/plugins/facebook-foot-panel/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript"> 
$(document).ready(function(){

	//Adjust panel height
	$.fn.adjustPanel = function(){ 
		$(this).find("ul, .subpanel").css({ 'height' : 'auto'}); //Reset subpanel and ul height
		
		var windowHeight = $(window).height(); //Get the height of the browser viewport
		var panelsub = $(this).find(".subpanel").height(); //Get the height of subpanel	
		var panelAdjust = windowHeight - 100; //Viewport height - 100px (Sets max height of subpanel)
		var ulAdjust =  panelAdjust - 25; //Calculate ul size after adjusting sub-panel (27px is the height of the base panel)
		
		if ( panelsub >= panelAdjust ) {	 //If subpanel is taller than max height...
			$(this).find(".subpanel").css({ 'height' : panelAdjust }); //Adjust subpanel to max height
			$(this).find("ul").css({ 'height' : ulAdjust}); //Adjust subpanel ul to new size
		}
		else if ( panelsub < panelAdjust ) { //If subpanel is smaller than max height...
			$(this).find("ul").css({ 'height' : 'auto'}); //Set subpanel ul to auto (default size)
		}
	};
	
	//Execute function on load
	$("#chatpanel").adjustPanel(); //Run the adjustPanel function on #chatpanel
	$("#alertpanel").adjustPanel(); //Run the adjustPanel function on #alertpanel
	
	//Each time the viewport is adjusted/resized, execute the function
	$(window).resize(function () { 
		$("#chatpanel").adjustPanel();
		$("#alertpanel").adjustPanel();
	});
	
	//Click event on Chat Panel + Alert Panel	
	$("#chatpanel a:first, #alertpanel a:first").click(function() { //If clicked on the first link of #chatpanel and #alertpanel...
		if($(this).next(".subpanel").is(':visible')){ //If subpanel is already active...
			$(this).next(".subpanel").hide(); //Hide active subpanel
			$("#footpanel li a").removeClass('active'); //Remove active class on the subpanel trigger
		}
		else { //if subpanel is not active...
			$(".subpanel").hide(); //Hide all subpanels
			$(this).next(".subpanel").toggle(); //Toggle the subpanel to make active
			$("#footpanel li a").removeClass('active'); //Remove active class on all subpanel trigger
			$(this).toggleClass('active'); //Toggle the active class on the subpanel trigger
		}
		return false; //Prevent browser jump to link anchor
	});
	
	//Click event outside of subpanel
	$(document).click(function() { //Click anywhere and...
		$(".subpanel").hide(); //hide subpanel
		$("#footpanel li a").removeClass('active'); //remove active class on subpanel trigger
	});
	$('.subpanel ul').click(function(e) { 
		e.stopPropagation(); //Prevents the subpanel ul from closing on click
	});
	
	//Delete icons on Alert Panel
	$("#alertpanel li").hover(function() {
		$(this).find("a.delete").css({'visibility': 'visible'}); //Show delete icon on hover
	},function() {
		$(this).find("a.delete").css({'visibility': 'hidden'}); //Hide delete icon on hover out
	});





	
});
</script>
<?php 



}
add_action('wp_head', 'add_fb_foot_panel_header1');
?>
<?php function the_foot_panels(){ ?>
<div id="footpanel">
	<ul id="mainpanel">    	
    <?php if(is_user_logged_in()) : ?>
    <?php 
	global $current_user; // Get user's information 

     			get_currentuserinfo();

				$user_level=$current_user->user_level;

				$user_id=$current_user->ID;

				$user_login=$current_user->user_login;

       			$user_email =$current_user->user_email;

				$user_firstname=$current_user->user_firstname;

				$user_lastname=$current_user->user_lastname;
				
				$home_link = get_option('home');
				 ?>
                
		<li><a href="<?php echo $home_link.""; ?>" class="home">JD<small>Janvier Designs</small></a></li>
       <li><a href="<?php echo $home_link."/members/".$user_login."/profile/"; ?>" class="profile">View Profile <small>View Profile</small></a></li>
        <li><a href="<?php echo $home_link."/members/".$user_login."/profile/edit/"; ?>" class="editprofile">Edit Profile <small>Edit Profile</small></a></li>

        <li><a href="<?php echo $home_link."/members/".$user_login."/messages/inbox/"; ?>" class="messages" title="Messages">Messages (10) <small>Messages</small></a></li>
        <?php else: ?>
             <li><a href="http://www.janvierdesigns.com" class="messages" title="Janvier Designs">Messages (<?php bp_total_unread_messages_count(); ?>) <small>Janvier Designs</small></a></li>
        <?php endif; ?>
        
        <li id="alertpanel">
        	<a href="#" class="alerts">Alerts</a>

            <div class="subpanel">
            <h3><span> &ndash; </span>Notifications</h3>
            <ul>
            <?php if ( bp_has_activities('max=10&per_page=10&action=friendship_created') ) : ?>

			<li class="view"><a href="<?php echo get_option('home') ?>/activity/" title="View all">View All</a></li>
	<?php while ( bp_activities() ) : bp_the_activity(); ?>

		<li >
				<a href="<?php bp_activity_user_link() ?>">
					<?php bp_activity_avatar( 'type=full&width=25&height=25' ) ?>
				</a>
		</li>

	<?php endwhile; ?>
<?php else : ?>
		<li>
		<?php _e( 'Sorry, there was no activity found. Please try a different filter.', 'buddypress' ) ?>
        </li>

	</ul>


            <?php endif; ?>
            </div>
        </li>
        <li id="chatpanel">
        	<a href="#" class="chat">Friends Online</a>
            <div class="subpanel"> 
            <h3><span> &ndash; </span>Friends Online</h3>
			<?php global $current_user; get_currentuserinfo(); 
				$current_user_ID = $current_user->ID ;

				?>
            <ul>
                <li><span>My friends</span></li>
                <?php if ( bp_has_members('max=10&per_page=10&type=online&user_id='.$current_user_ID.'') ) : ?>


			<?php do_action( 'bp_before_directory_members_list' ) ?>
        
            <?php while ( bp_members() ) : bp_the_member(); ?>
            <li><a href="<?php bp_member_permalink() ?>" title="<?php bp_member_name() ?>"><?php bp_member_avatar( 'type=full&width=25&height=25') ?> 	<?php bp_member_name() ?></a>
            <div class="clear"></div>
            </li>
        
            <?php endwhile; ?>
        
        
            <?php else: ?>
            
            No friends are online!
            <?php endif; ?>
                </li>
        
        
			</ul>
</div>
<?php } ?>
<?php add_action('wp_footer', 'the_foot_panels'); ?>