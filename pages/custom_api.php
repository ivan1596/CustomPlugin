<?php
require_api( 'access_api.php' );
require_api( 'authentication_api.php' );
require_api( 'bug_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'current_user_api.php' );
require_api( 'database_api.php' );
require_api( 'filter_constants_inc.php' );
require_api( 'helper_api.php' );
require_api( 'project_api.php' );
require_api( 'string_api.php' );
require_api( 'user_api.php' );
require_api( 'utility_api.php' );
/**
 * This function shows the number of bugs submitted in every month of the year
 *
 * @param array $p_month_array An array of integers representing month.
 * @return void
 */
function summary_print_by_month( array $p_month_array ) {
	foreach( $p_month_array as $t_days ) {
		$t_new_count = summary_new_bug_count_by_month( $t_days );
		$t_resolved_count = summary_resolved_bug_count_by_month( $t_days );

		$t_start_date = mktime( 0, 0, 0, date( 'm' ), ( date( 'd' ) - $t_days ), date( 'Y' ) );
		$t_new_bugs_link = '<a class="subtle" href="' . config_get( 'bug_count_hyperlink_prefix' )
				. '&amp;' . FILTER_PROPERTY_FILTER_BY_DATE_SUBMITTED . '=on'
				. '&amp;' . FILTER_PROPERTY_DATE_SUBMITTED_START_YEAR . '=' . date( 'Y', $t_start_date )
				. '&amp;' . FILTER_PROPERTY_DATE_SUBMITTED_START_MONTH . '=' . date( 'm', $t_start_date )
				. '&amp;' . FILTER_PROPERTY_DATE_SUBMITTED_START_DAY . '=' . date( 'd', $t_start_date )
				. '&amp;' . FILTER_PROPERTY_HIDE_STATUS . '=' . META_FILTER_NONE . '">';

		echo '<tr>' . "\n";
		echo '    <td class="width50">' . $t_days . '</td>' . "\n";

		if( $t_new_count > 0 ) {
			echo '    <td class="align-right">' . $t_new_bugs_link . $t_new_count . '</a></td>' . "\n";
		} else {
			echo '    <td class="align-right">' . $t_new_count . '</td>' . "\n";
		}
		echo '    <td class="align-right">' . $t_resolved_count . '</td>' . "\n";

		$t_balance = $t_new_count - $t_resolved_count;
		$t_style = '';
		if( $t_balance > 0 ) {

			# we are talking about bugs: a balance > 0 is "negative" 
			$t_style = ' red';
			$t_balance = sprintf( '%+d', $t_balance );

		} else if( $t_balance < 0 ) {
			$t_style = ' green';
			$t_balance = sprintf( '%+d', $t_balance );
		}

		echo '    <td class="align-right' . $t_style . '">' . $t_balance . "</td>\n";
		echo '</tr>' . "\n";
	}
}


/**
 * Prints the bugs submitted for month
 *
 * @param integer $p_month_days Number of the month.
 * @return integer
 */
function summary_new_bug_count_by_month( $p_num_mounth) {
	
	$t_project_id = helper_get_current_project();

	$t_specific_where = helper_project_specific_where( $t_project_id );
	if( ' 1<>1' == $t_specific_where ) {
		return 0;
	}

    db_param_push();
    $query = 'SELECT date_submitted FROM {bug} WHERE ' .$t_specific_where ;
	$result = db_query( $query);

	$rows = [];
	while($row = db_fetch_array($result))
	{
		$rows[] = $row;
	}
	

    $month_count = 0;
    foreach ($rows as $row) {
		$timestamp = $row['date_submitted']; 
		$format = 'd-m-Y';
		$date = date($format, $timestamp);
		$d = date_parse_from_format("d-m-Y", $date);
		$month = $d['month'];
        if ($month == $p_num_mounth) {
            $month_count ++; 
        }
    }
	return $month_count;
}

/**
 * Returns the number of bugs resolved for months.
 *
 * @param integer $p_month_days Number of month.
 * @return integer
 */
function summary_resolved_bug_count_by_month( $p_num_month ) {
	$t_resolved = config_get( 'bug_resolved_status_threshold' );


	$t_project_id = helper_get_current_project();

	$t_specific_where = helper_project_specific_where( $t_project_id );
	if( ' 1<>1' == $t_specific_where ) {
		return 0;
	}

	db_param_push();
	$r_query = 'SELECT date_modified FROM {bug_history} b 
				LEFT JOIN {bug} h ON b.bug_id = h.id
				WHERE b.field_name = "resolution"
				AND '. $t_specific_where ;
	$r_result = db_query( $r_query );
	$rows = [];
	while($row = db_fetch_array($r_result))
	{
		$rows[] = $row;
	}
	

    $month_count = 0;
    foreach ($rows as $row) {
		$timestamp = $row['date_modified']; 
		$format = 'd-m-Y';
		$date = date($format, $timestamp);
		$d = date_parse_from_format("d-m-Y", $date);
		$month = $d['month'];
		if ($month == $p_num_month) {
            $month_count ++; 
        }
    }
	return $month_count;
	
}

?>