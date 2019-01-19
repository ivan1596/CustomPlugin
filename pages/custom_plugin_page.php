
<?php

require_once( 'core.php' );
require_api( 'access_api.php' );
require_api( 'authentication_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'database_api.php' );
require_api( 'gpc_api.php' );
require_api( 'helper_api.php' );
require_api( 'html_api.php' );
require_api( 'lang_api.php' );
require_api( 'print_api.php' );
require_api( 'summary_api.php' );
require_api( 'user_api.php' );
require_once('custom_api.php');

$g_month_partitions = array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12);

$f_project_id = gpc_get_int( 'project_id', helper_get_current_project() );

# Override the current page to make sure we get the appropriate project-specific configuration
$g_project_override = $f_project_id;

access_ensure_project_level( config_get( 'view_summary_threshold' ) );

$t_time_stats = summary_helper_get_time_stats( $f_project_id );

$t_summary_header_arr = explode( '/', lang_get( 'summary_header' ) );

$t_summary_header = '';
foreach ( $t_summary_header_arr as $t_summary_header_name ) {
	$t_summary_header .= '<th class="align-right">';
	$t_summary_header .= $t_summary_header_name;
	$t_summary_header .= '</th>';
}

layout_page_header( lang_get( 'summary_link' ) );

layout_page_begin( __FILE__ );

print_summary_menu( 'summary_page.php' );
print_summary_submenu();
?>

<div class="col-md-12 col-xs-12">
<div class="space-10"></div>

<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-bar-chart-o">Metriche e carichi di lavoro</i>
		
	</h4>
</div>

<div class="widget-body">
<div class="widget-main no-padding">


<!-- LEFT COLUMN -->
<div class="col-md-6 col-xs-12">

	<!-- BY PROJECT -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-35"><?php echo lang_get( 'by_project' ) ?></th>
				<?php echo $t_summary_header ?>
			</tr>
		</thead>
		<?php summary_print_by_project(); ?>
	</table>
	</div>
	

	<!-- TIME STATS -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th colspan="2"><?php echo lang_get( 'time_stats' ) ?></th>
			</tr>
		</thead>
		<tr>
			<td><?php echo lang_get( 'longest_open_bug' ) ?></td>
			<td class="align-right"><?php
				if( $t_time_stats['bug_id'] > 0 )  {
					print_bug_link( $t_time_stats['bug_id'] );
				}
			?></td>
		</tr>
		<tr>
			<td><?php echo lang_get( 'longest_open' ) ?></td>
			<td class="align-right"><?php echo $t_time_stats['largest_diff'] ?></td>
		</tr>
		<tr>
			<td><?php echo lang_get( 'average_time' ) ?></td>
			<td class="align-right"><?php echo $t_time_stats['average_time'] ?></td>
		</tr>
		<tr>
			<td><?php echo lang_get( 'total_time' ) ?></td>
			<td class="align-right"><?php echo $t_time_stats['total_time'] ?></td>
		</tr>
	</table>
	</div>

	
</div>
<div class="col-md-6 col-xs-12">

	<!-- BY MONTH -->
	<div class="space-10"></div>
	<div class="widget-box table-responsive">
		<table class="table table-hover table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="width-35">Per Mese</th>
				<th class="align-right"><?php echo lang_get( 'opened' ); ?></th>
				<th class="align-right"><?php echo lang_get( 'resolved' ); ?></th>
				<th class="align-right"><?php echo lang_get( 'balance' ); ?></th>
			</tr>
		</thead>
		<?php summary_print_by_month( $g_month_partitions ) ?>
	</table>
	</div>
</div>





	



</div>
</div>
<div class="clearfix"></div>
<div class="space-10"></div>
</div>
</div>

<?php
layout_page_end();
?>