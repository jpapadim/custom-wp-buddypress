<?php
/*
Plugin Name:       Add TML custom fields
Plugin URI:        https://github.com/jpapadim
Description:       Add and validate custom fields of birthday. 
Version:           1.0.0
Author:            John Papadimitriou
License:           MIT 
Text Domain:       TML-custom-fields
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
	 
	elseif (intval($parts[3]) < 1890)
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
	if ( !empty( $_POST['first_name'] ) )
		update_user_meta( $user_id, 'first_name', $_POST['first_name'] );
	if ( !empty( $_POST['last_name'] ) )
		update_user_meta( $user_id, 'last_name', $_POST['last_name'] );
	if ( !empty( $_POST['last_name'] ) )
		update_user_meta( $user_id, 'user_bday', $_POST['user_bday'] );
}
add_action( 'user_register', 'tml_user_register' );
/* ------------------------*/
?>
