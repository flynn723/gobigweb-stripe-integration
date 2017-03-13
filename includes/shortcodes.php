<?php

function gobigweb_stripe_payment_form_advanced($atts, $content = null) {

	extract( shortcode_atts( array(
		'billing_interval' => '',
		'user_quantity' => ''
	), $atts ) );

	// echo $billing_interval . '<hr>';
	// echo $user_quantity . '<hr>';

	if ($billing_interval == "year"){
		$amount = (179 + (6 * $user_quantity)) * 12;
	} else { // $billing_interval == month
		$amount = 199 + (8 * $user_quantity);
	}

	global $stripe_options;

	ob_start();

	if(isset($_GET['payment']) && $_GET['payment'] == 'paid') {
		echo '<p class="success">' . __('Thank you for your payment.', 'gobigweb_stripe') . '</p>';
	} else { ?>
		<!-- </pre> --><!-- Escaping DOM Error, weird <pre> element gets inserted here -->  

		<form action="" method="POST" id="stripe-payment-form" class="row">
			<!-- Email form inputted from Account Info Form - GoBigWeb -->
			<div class="form-col hidden">
				<label class="form-label"><?php _e('Email', 'gobigweb_stripe'); ?></label>
				<input type="text" size="20" class="email" name="email"/>
			</div>
<div class="col-xs-12 form-col">
	<input id="billing-address-same-as-organization" type="checkbox" name="billing-address-same-as-organization" value="yes" checked>
	<label for="billing-address-same-as-organization" class="form-label" style="cursor: pointer;">Billing Address is the same as the Organization Address</label>
</div>
<?php
$states = array("AL", "AK", "AS", "AZ", "AR","CA", "CO", "CT", "DE", "DC", "FL", "GA", "GU", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MH", "MA", "MI", "FM", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "MP", "OH", "OK", "OR", "PW", "PA", "PR", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "VI", "WA", "WV", "WI", "WY");
$countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
$selectedCountry = "United States";
?>
<div class="billing-address-wrapper clearfix" style="display: none;">

	<div class="col-xs-12 col-sm-9 form-col">
		<label class="form-label">Street Address</label>
		<input class="form-control" name="billing_street_address" type="text" placeholder="Street Address" value="<?php if(isset($streetAddress)){ echo $streetAddress; } ?>" maxlength="85"/>
	</div>
	<div class="col-xs-12 col-sm-3 form-col">
		<label class="form-label">City</label>
		<input class="form-control" name="billing_city" type="text" placeholder="City" value="<?php if(isset($city)){ echo $city; } ?>" maxlength="85"/>
	</div>
	<div class="clearfix"></div>

	<div class="col-xs-12 col-sm-3 form-col">
		<label class="form-label">State</label>
		<select class="form-control" name="billing_state">
		  <?php
		  if (!isset($selectedState)){
		    echo '<option disabled selected>State</option>';
		  }
		  foreach($states as $state){ 
		    if (isset($selectedState) && $selectedState == $state){
		      echo '<option value="'.$state.'" selected>'.$state.'</option>';
		    } else {
		      echo '<option value="'.$state.'">'.$state.'</option>';
		    }
		  } ?>
		</select>
	</div>
	<div class="col-xs-12 col-sm-3 form-col">
		<label class="form-label">ZIP Code</label>
		<input class="form-control" name="billing_zip" type="text" placeholder="ZIP" value="<?php if(isset($zipCode)){ echo $zipCode; } ?>" maxlength="10"/>
	</div>	
	<div class="col-xs-12 col-sm-6 form-col">
		<label class="form-label">Country</label>
		<select class="form-control" name="billing_country">
		  <?php
		  if (!isset($selectedCountry)){
		    echo '<option disabled selected>Country</option>';
		  }
		  foreach($countries as $country){ 
		    if (isset($selectedCountry) && $selectedCountry == $country){
		      echo '<option value="'.$country.'" selected>'.$country.'</option>';
		    } else {
		      echo '<option value="'.$country.'">'.$country.'</option>';
		    }
		  } ?>
		</select>
	</div>
	<div class="clearfix"></div>

</div>
<script type="text/javascript">
jQuery(document).ready(function($){
	function isBillingAddressSameAsOrganization(){
		if ($('#billing-address-same-as-organization').is(':checked')) {
			$('#stripe-payment-form input[name="billing_street_address"], #stripe-payment-form input[name="billing_city"], #stripe-payment-form select[name="billing_state"], #stripe-payment-form input[name="billing_zip"], #strip-payment-form select[name="billing_country"]').prop('required', false);

			var organizationName = $('#account-info input[name="organization_name"]').val();

			var billingAddress = $('#account-info input[name="street_address"]').val();
			billingAddress += ', ';
			billingAddress += $('#account-info input[name="city"]').val();
			billingAddress += ', ';
			billingAddress += $('#account-info select[name="state"]').val();
			billingAddress += ' ';
			var zip = $('#account-info input[name="zipcode"]').val();
			billingAddress += zip;
			$('#stripe-payment-form input[name="billing_zip"]').val(zip);

			billingAddress = billingAddress.replace(/(<([^>]+)>)/ig,"");
			$('#stripe-payment-form input[name="billing_address"]').val('Billing Address: ' + billingAddress + 'United States');

			$('#stripe-payment-form input[name="billing_organization_name"]').val(organizationName);

			// hide address wrapper
			$('.billing-address-wrapper').slideUp();
		} else {
			$('#stripe-payment-form input[name="billing_zip"]').val(zip);
			$('#stripe-payment-form input[name="billing_address"]').val('Billing Address:');
			// show address wrapper
			$('.billing-address-wrapper').slideDown();
		}
	}
	function insertBillingAddressIntoStripeForm(){
		if ( ! ( $('#billing-address-same-as-organization').is(':checked') ) ) {
			$('#stripe-payment-form input[name="billing_street_address"], #stripe-payment-form input[name="billing_city"], #stripe-payment-form select[name="billing_state"], #stripe-payment-form input[name="billing_zip"], #strip-payment-form select[name="billing_country"]').prop('required', true);

			var billingAddress = $('#stripe-payment-form input[name="billing_street_address"]').val();
			billingAddress += ', ';
			billingAddress += $('#stripe-payment-form input[name="billing_city"]').val();
			billingAddress += ', ';
			billingAddress += $('#stripe-payment-form select[name="billing_state"]').val();
			billingAddress += ' ';
			billingAddress += $('#stripe-payment-form input[name="billing_zip"]').val();
			billingAddress += ' ';
			billingAddress += $('#stripe-payment-form select[name="billing_country"]').val();

			billingAddress = billingAddress.replace(/(<([^>]+)>)/ig,"");
			$('#stripe-payment-form input[name="billing_address"]').val('Billing Address: ' + billingAddress);
		}
	}
	/* On Load */
	isBillingAddressSameAsOrganization();
	/* On Change */
	$('#stripe-payment-form #billing-address-same-as-organization').on('change', function(){
		isBillingAddressSameAsOrganization();
	});
	/* On Enter */
	$('#stripe-payment-form input[name="billing_street_address"], #stripe-payment-form input[name="billing_city"], #stripe-payment-form select[name="billing_state"], #stripe-payment-form input[name="billing_zip"], #strip-payment-form select[name="billing_country"]').on('change', function(){
		insertBillingAddressIntoStripeForm();
	});
});
</script>

			<div class="col-xs-12 form-col">
				<label class="form-label"><?php _e('Card Number', 'gobigweb_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" class="form-control card-number" required/>
			</div>
			<div class="col-xs-6 col-sm-4 form-col">
				<label class="form-label" style="display: block;"><?php _e('CVC', 'gobigweb_stripe'); ?></label>
				<input type="text" size="4" autocomplete="off" class="form-control text-center card-cvc" style="max-width: 75px;" required/>
			</div>
			<div class="col-xs-6 col-sm-8 form-col">
				<label class="form-label" style="display: block;"><?php _e('Expiration (MM/YYYY)', 'gobigweb_stripe'); ?></label>
				<input type="text" size="2" class="form-control text-center card-expiry-month" style="display: inline-block; max-width: 50px;" required/>
				<span> / </span>
				<input type="text" size="4" class="form-control text-center card-expiry-year" style="display: inline-block; max-width: 100px;" required/>
			</div>
			<input type="hidden" name="action" value="stripe"/>
			<input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
			<input type="hidden" name="billing_interval" value="<?php echo $billing_interval; ?>"/>
			<input type="hidden" name="amount" value="<?php echo base64_encode($amount); ?>"/>

			<input name="billing_organization_name" type="hidden" value=""/>
			<input type="hidden" name="billing_address" value=""/>

			<input type="hidden" name="stripe_nonce" value="<?php echo wp_create_nonce('stripe-nonce'); ?>"/>
			<div class="col-xs-12 form-col">
				<button type="submit" id="stripe-submit" class="checkout-btn"><?php _e('Submit Payment', 'gobigweb_stripe'); ?> <i class="fa fa-arrow-circle-o-right"></i></button>
			</div>
		</form>
		<div class="payment-errors"></div>
		<?php
	}
	return ob_get_clean();
}
add_shortcode('payment_form_advanced', 'gobigweb_stripe_payment_form_advanced');