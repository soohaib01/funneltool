<?php

namespace WPFunnels\Conditions;


use WPFunnels\Traits\SingletonTrait;

class Wpfnl_Condition_Checker {

	use SingletonTrait;


	/**
	 * Conditional node logic check
	 *
	 * @param $funnel_id
	 * @param $order
	 * @param $condition_identifier
	 * @param $current_page_id
	 * @param $checker
	 * @return bool
	 *
	 * @since 2.0.2
	 */
	public function check_condition( $funnel_id, $order, $condition_identifier, $current_page_id, $checker = 'accept' )
	{
		$group_conditions = get_post_meta( $funnel_id, $condition_identifier, true );
		
		if ($group_conditions) {
			
			// Loop through group condition.
			foreach ($group_conditions as $group) {
				
				if (empty($group)) {
					continue;
				}

				$match_group = true;
				// Loop over rules and determine if all rules match.
				foreach ($group as $rule) {
					if (!$this->match_rule( $rule, $order, $current_page_id, $checker )) {
						$match_group = false;
						break;
					}
				}

				// If this group matches, show the field group.
				if ($match_group) {
					return true;
				}else{
					return false;
				}
			}
		}

		if( $checker == 'accept' ){
			return true;
		}
		// Return default.
		return false;
	}


	/**
	 * check if rule is matched
	 *
	 * @param $rule
	 * @param $order
	 * @param $current_page_id
	 * @param $checker
	 * @return mixed
	 *
	 * @since 2.0.2
	 */
	public function match_rule( $rule, $order, $current_page_id, $checker )
	{

		if ($rule['field'] == 'downsell') {
			$rule['field'] = 'upsell';
		}
		$checker_function = $rule['field'] . '_condition_checker';
		return self::$checker_function( $rule, $order, $current_page_id, $checker );
	}


	/**
	 * @param $data
	 * @param $order
	 * @param $current_page_id
	 * @return bool
	 */
	public function orderbump_condition_checker( $data, $order, $current_page_id, $checker = 'accept' )
	{
		$order_bump_accepted = WC()->session->get('order_bump_accepted');
		WC()->session->set('order_bump_accepted', null);
		return $data['value'] == $order_bump_accepted;
	}


	/**
	 * @param $data
	 * @param $order
	 * @param $current_page_id
	 * @return bool
	 */
	public function carttotal_condition_checker( $data, $order, $current_page_id, $checker = 'accept' )
	{
		$cart_total = $order->get_total();

		$checker = false;
		if ($data['condition'] == 'greater') {
			if ($cart_total > $data['value']) {
				$checker = true;
			}
		} elseif ($data['condition'] == 'equal') {
			if ($cart_total == $data['value']) {
				$checker = true;
			}
		} elseif ($data['condition'] == 'less') {
			if ($cart_total < $data['value']) {
				$checker = true;
			}
		}
		return $checker;
	}



	public function upsell_condition_checker($data, $order, $current_page_id, $checker)
	{

		if ($data['value'] == 'yes') {
			// need to write a function (check_if_upsell_accepted) to see if upsell is
			// added to the order.
			// If present return true,
			// else return false

			if ( $checker == 'accept' ) {
				return true;
			} else {
				return false;
			}

		} else if ($data['value'] == 'no') {
			// if check_if_upsell_accepted() == true , return false
			// else return true
			if ($checker == 'reject') {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}



}
