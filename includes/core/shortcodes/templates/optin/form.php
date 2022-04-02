


<div class="wpfnl-optin-form wpfnl-shortcode-optin-form-wrapper" >
	<form method="post">
		<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>" />
		<input type="hidden" name="admin_email" value="<?php echo $this->attributes['admin_email']; ?>" />
		<input type="hidden" name="admin_email_subject" value="<?php echo $this->attributes['admin_email_subject']; ?>" />
		<input type="hidden" name="redirect_url" value="<?php echo $this->attributes['redirect_url']; ?>" />
		<input type="hidden" name="notification_text" value="<?php echo $this->attributes['notification_text']; ?>" />
		<input type="hidden" name="post_action" value="<?php echo $this->attributes['post_action']; ?>" />


		<div class="wpfnl-optin-form-wrapper" >
			<?php if( 'true' == $this->attributes['first_name'] ){ ?>
				<div class="wpfnl-optin-form-group first-name">

					<label for="wpfnl-first-name">
						First Name
					</label>
					<span class="input-wrapper">
						<span class="field-icon">
							<img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/user-icon.svg'; ?>" alt="icon">
						</span>
						<input type="text" name="first_name" id="wpfnl-first-name" placeholder="First name"/>
					</span>

				</div>
			<?php } ?>

			<?php if( 'true' == $this->attributes['last_name'] ){ ?>
				<div class="wpfnl-optin-form-group last-name">
					<label for="wpfnl-last-name">
						Last Name
					</label>

					<span class="input-wrapper">
						<span class="field-icon">
							<img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/user-icon.svg'; ?>" alt="icon">
						</span>
						<input type="text" name="last_name" id="wpfnl-last-name" placeholder="last Name"/>
					</span>
				</div>
			<?php } ?>

			<div class="wpfnl-optin-form-group email">
				<label for="wpfnl-email">
					Email
				</label>
				<span class="input-wrapper">
					<span class="field-icon">
						<img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/email-open-icon.svg'; ?>" alt="icon">
					</span>
					<input type="email" name="email" id="wpfnl-email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" />
				</span>
			</div>

			<?php if( 'true' == $this->attributes['phone'] ){ ?>
				<div class="wpfnl-optin-form-group phone">
					<label for="wpfnl-phone">
						Phone
					</label>

					<span class="input-wrapper">
						<span class="field-icon">
							<img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/phone.svg'; ?>" alt="icon">
						</span>
						<input type="text" name="phone" id="wpfnl-phone" placeholder="Phone"/>
					</span>
				</div>
			<?php } ?>

			<?php
			if( 'true' == $this->attributes['acceptance_checkbox'] ){
			?>
				<div class="wpfnl-optin-form-group acceptance-checkbox">
					<input type="checkbox" name="acceptance_checkbox" id="wpfnl-acceptance_checkbox"/>
					<label for="wpfnl-acceptance_checkbox">
						<span class="check-box"></span>
						<?php
							echo __('I have read and agree the Terms & Condition.', 'wpfnl');
						?>
					</label>
				</div>
			<?php
			}
			?>
			<div class="wpfnl-optin-form-group submit align-center">
				<button type="submit" class="btn-optin <?php echo $this->attributes['btn_class'] ?>">
					<span>
						Submit
					</span>
					<span class="wpfnl-loader"></span>
				</button>
			</div>
		</div>
	</form>

	<div class="response"></div>
</div>
