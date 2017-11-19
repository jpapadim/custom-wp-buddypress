<?php
/*
Plugin Name:       Custom login functionality
Plugin URI:        https://github.com/jpapadim
Description:       Validate custom fields of birthday and process accordingly. 
Version:           1.0.1
Author:            John Papadimitriou
License:           GPL-2.0+   
Text Domain:       custom-login-functionality
*/
 
/* Date validation*/
add_filter( 'registration_errors', 'registration_custom_errors', 0, 3 );

function registration_custom_errors( $errors, $sanitized_user_login, $user_email ) {
	
	if ( ! isset( $_POST['user_bday'] ) || $_POST['user_bday']=='' ) 
	$errors->add( 'user_bday_error', __( '<strong>ERROR</strong>: You must enter Date of Birth.', 'theme-my-login' ) );
	
	elseif (!preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $_POST['user_bday'], $parts))
		// Check the format
		$errors->add( 'user_bday_error', __( '<strong>ERROR</strong>: Not a valid date or format.', 'theme-my-login' ) );
	
	elseif (!checkdate($parts[2],$parts[1],$parts[3]))
		$errors->add( 'user_bday_error', __( '<strong>ERROR</strong>: Month range must be 01 - 12 with a valid day.', 'theme-my-login' ) );
	 
	elseif (intval($parts[3]) < (date('Y')-125) )
      // Make sure that the user has a reasonable birth year
      $errors->add( 'user_bday_error', __( '<strong>ERROR</strong>: You must be alive to use this service!', 'theme-my-login' ) );
	  // Check 18+ 
	elseif
         // If not older than 19y or 18 by year month and day 
       (!((intval($parts[3]) < (intval(date("Y") - 19))) ||
       (intval($parts[3]) == (intval(date("Y")) - 18) && (intval($parts[2]) < intval(date("m")))) ||      
       (intval($parts[3]) == (intval(date("Y")) - 18) && (intval($parts[2]) ==  intval(date("m"))) && (intval($parts[1]) <= intval(date("d"))))))
       $errors->add( 'user_bday_error', __( '<strong>ERROR</strong>: You must be 18+ years of age to use this service.', 'theme-my-login' ) );  
 
  return $errors;
}
function tml_user_register( $user_id ) {
	
	/*error_log(print_r($_POST['user_bday'], true));*/
	
	// convert user_bday input to comlete date format
	preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $_POST['user_bday'], $parts);
	$userDob = $parts[3] .'-'. $parts[2] .'-'. $parts[1]." 00:00:00" ;
	
	/*error_log(print_r($userDob, true));*/
	
	if ( !empty( $_POST['first_name'] ) ){
		update_user_meta( $user_id, 'first_name', $_POST['first_name'] );
		xprofile_set_field_data('First name', $user_id, $_POST['first_name'] ); 
	}
	if ( !empty( $_POST['last_name'] ) ) {
		update_user_meta( $user_id, 'last_name', $_POST['last_name'] );
		xprofile_set_field_data('Last Name', $user_id, $_POST['last_name'] ); 
	}
	if ( !empty( $_POST['user_bday'] ) ) {
		//update_user_meta( $user_id, 'user_bday', $_POST['user_bday'] );
		//Have to change date for dd/mm/yyyy to yyyy-mm-dd to save in db.		
	    xprofile_set_field_data('Date of Birth', $user_id, $userDob ); 
	}
}
add_action( 'user_register', 'tml_user_register' );
/* ------------------------*/
 
//Redirect users to profile edit when registration complete
function redirect_on_first_login( $redirect_to, $redirect_url_specified, $user ) {

	$redirect_to = home_url('/') ;
  
    //check if we have a valid user?
    if ( is_wp_error( $user ) ) {
        return $redirect_to;
    }
 
    //check for user's last activity
    $last_activity = bp_get_user_last_activity($user->ID);
    
    if ( empty( $last_activity ) ) {
        // it is the first login update redirect url
        // redirecting to user's profile here
	
      $redirect_to = bp_core_get_user_domain($user->ID). BP_XPROFILE_SLUG . '/edit/' ;
    }    
	
    return $redirect_to;
}
 
  add_filter( 'login_redirect', 'redirect_on_first_login' , 110, 3 );

?>