<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- ----- jQuery UI Datepicker ------->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#bDay" ).datepicker(
	{
      minDate: new Date(1900,1-1,1), maxDate: '-18Y',
      dateFormat: 'dd/mm/yy',
      defaultDate: new Date(1975,1-1,1),
      changeMonth: true,
      changeYear: true,
      yearRange: '-110:-18'
    });
  } );
  </script>

<div class="tml tml-register" id="theme-my-login<?php $template->the_instance(); ?>">
	<?php $template->the_action_template_message( 'register' ); ?>
	<?php $template->the_errors(); ?>
	<form name="registerform" id="registerform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'register', 'login_post' ); ?>" method="post">
		 
		<?php if ( 'email' != $theme_my_login->get_option( 'login_type' ) ) : ?>
			<input required type="text" name="user_login" placeholder="Username" class="tml-user-login-wrap" id="user_login <?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_login' ); ?>" size="20" />
		<?php endif; ?>
	 	
			<input required type="text" name="first_name" placeholder="First Name" id="first_name<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'first_name' ); ?>" size="10" />
			<input required type="text" name="last_name" placeholder="Last name" id="last_name<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'last_name' ); ?>" size="20" />		
			<input required type="text" name="user_email" placeholder="e-mail" class="tml-user-email-wrap" id="user_email<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_email' ); ?>" size="20" />
		
		<p class="tml-registration-confirmation" id="reg_passmail<?php $template->the_instance(); ?>"><?php echo apply_filters( 'tml_register_passmail_template_message', __( 'Registration confirmation will be e-mailed to you.', 'theme-my-login' ) ); ?></p>
		
			<input required type="text" name="user_bday" id="bDay" class="input datetype" placeholder ="Birthday (DD/MM/YYYY)" value="<?php $template->the_posted_value( 'user_bday' ); ?>" size="20" />
		<?php echo $errors; ?>
		<?php do_action( 'register_form' ); ?>

		<p class="tml-submit-wrap">
			<input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php esc_attr_e( 'Register', 'theme-my-login' ); ?>" />
			<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'register' ); ?>" />
			<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
			<input type="hidden" name="action" value="register" />
		</p>
	</form>

	<?php $template->the_action_links( array( 'register' => false ) ); ?>
</div>
