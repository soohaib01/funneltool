<?php

/**
 * this code snippet will check if pro addons is activated or not. if not activated
 * total number of funnels will be maximum 3, otherwise customer can add as more funnels
 * as they want
 */
$is_pro_active = apply_filters( 'wpfunnels/is_pro_license_activated', false );
$count_funnels = wp_count_posts('wpfunnels')->publish + wp_count_posts('wpfunnels')->draft;
$total_allowed_funnels = 3;
if ( $is_pro_active ) {
	$total_allowed_funnels = -1;
}

?>


<div class="wpfnl">
    <div class="wpfnl-dashboard">
        <nav class="wpfnl-dashboard__nav">
            <?php use WPFunnels\Wpfnl_functions;
            require_once WPFNL_DIR . '/admin/partials/dashboard-nav.php'; ?>
        </nav>

        <div class="dashboard-nav__content">
            <div id="templates-library"></div>

            <div class="wpfnl-dashboard__header overview-header">
                <form class="funnel-search" method="get">
                    <?php
                        $s = '';
                        if (isset($_GET['s'])) {
                            $s = sanitize_text_field( $_GET['s'] );
                        }
                    ?>

                    <div class="search-group">
                        <input name="page" type="hidden" value="<?php echo WPFNL_MAIN_PAGE_SLUG; ?>">
                        <?php require_once WPFNL_DIR . '/admin/partials/icons/search-icon.php'; ?>
                        <input name="s" type="text" value="<?php echo $s; ?>" placeholder="<?php echo __('Search for a funnel...', 'wpfnl'); ?>">
                    </div>
                </form>
                <?php


                ?>
                <a href="#" class="btn-default add-new-funnel-btn"><?php echo __('Add new Funnel', 'wpfnl'); ?></a>
            </div>

            <div class="wpfnl-dashboard__inner-content <?php echo count($this->funnels) ? '' : 'no-funnel' ?>">
                <div class="funnel-list__wrapper">
                    <?php
                      if (count($this->funnels) > 0) {
                          ?>
                          <!-- <div class="funnel__single-list funnel-list__bulk-select" >
                              <span class="wpfnl-checkbox no-title">
                                  <input type="checkbox" name="funnel-list__bulk-select" id="funnel-list__bulk-select">
                                  <label for="funnel-list__bulk-select"></label>
                              </span>

                              <button class="btn-default bulk-delete" title="Delete Funnel" id="funnel__bulk-delete" >
                                  <?php echo __('Delete', 'wpfnl'); ?>
                              </button>
                          </div> -->
                          <?php
                      }
                    ?>

                    <?php
                    if (count($this->funnels)) {
                        foreach ($this->funnels as $funnel) {
                            $edit_link = add_query_arg(
                                [
                                    'page' => WPFNL_EDIT_FUNNEL_SLUG,
                                    'id' => $funnel->get_id(),
                                    'step_id' => $funnel->get_first_step_id(),
                                ],
                                admin_url('admin.php')
                            );
                            $isAutomationEnable = get_post_meta( $funnel->get_id(), 'is_automation_enabled', true );
							$isAutomationData 	= get_post_meta( $funnel->get_id(),'funnel_automation_data',true);
                            $isGbfInstalled 	= is_plugin_active( 'wpfunnels-pro-gbf/wpfnl-pro-gb.php' );
                            $start_condition 	= get_post_meta( $funnel->get_id(), 'global_funnel_start_condition', true );
                            $builder 			= Wpfnl_functions::get_page_builder_by_step_id($funnel->get_id());

                            $isGbf = get_post_meta( $funnel->get_id(), 'is_global_funnel', true );
                            $first_step_id = $funnel->get_first_step_id();
                            if ($first_step_id) {
                                $view_link = get_the_permalink($first_step_id);
                            } else {
                                $view_link = '#';
                            }
                            if($this->utm_settings['utm_enable'] == 'on') {
                                $utm_params  = '?utm_source='.$this->utm_settings['utm_source'].'&utm_medium='.$this->utm_settings['utm_medium'].'&utm_campaign='.$this->utm_settings['utm_campaign'];
                                $utm_params .= ((!empty($this->utm_settings['utm_content'])) ? '&utm_content='.$this->utm_settings['utm_content'] : '');
                                $utm_url     = $view_link.$utm_params;
                                $view_link   = strtolower($utm_url);
                            }
                            ?>

                            <div class="funnel__single-list">
                                <!-- <div class="funnel-list__bulk-action">
                                    <span class="wpfnl-checkbox no-title">
                                        <input type="checkbox" name="funnel-list-select" id="funnel-list<?php echo $funnel->get_id(); ?>-select" data-id="<?php echo $funnel->get_id(); ?>">
                                        <label for="funnel-list<?php echo $funnel->get_id(); ?>-select"></label>
                                    </span>
                                </div> -->
                                <div class="list-cell product-name">
                                    <?php if( $builder ){ ?>
                                        <span class="builder-logo" title="<?php echo str_replace('-',' ',ucfirst($builder));?>">
                                            <?php include WPFNL_DIR . '/admin/partials/icons/'.$builder.'.php'; ?>
                                        </span>

                                    <?php } else{ ?>
                                        <span class="builder-logo" title="No Builder Found">
                                        </span>
                                    <?php } ?>

                                    <a href="<?php echo esc_url_raw($edit_link); ?>" class="name"> <?php echo $funnel->get_funnel_name() ?></a>
                                    <span class="steps"><?php echo $funnel->get_total_steps(). ' '. Wpfnl_functions::get_formatted_data_with_phrase($funnel->get_total_steps(), 'step', 'steps'); ?></span>
                                </div>

								<?php if ($is_pro_active) { ?>
									<div class="list-cell has-automation">
										<?php if( $isAutomationEnable == 'true' && !empty($isAutomationData) ) { ?>
											<span class="automation-tag automation-active">
                                            <?php include WPFNL_DIR . '/admin/partials/icons/success-icon-green.php'; ?>
                                            Integration
                                            <span class="tooltip">Integration is set for this funnel.</span>
                                        </span>
										<?php } elseif($isAutomationEnable == 'true'){ ?>
											<span class="automation-tag automation-inactive">
                                            <?php include WPFNL_DIR . '/admin/partials/icons/success-icon-warning.php'; ?>
                                            Integration

                                            <span class="tooltip">Opps.. It looks like you did not define any events for integration. Please edit the funnel, go to integrations, and define events to set tags and lists.</span>
                                        </span>
										<?php  } else{ ?>
											<span class="automation-tag automation-inactive">
                                            <?php include WPFNL_DIR . '/admin/partials/icons/success-icon-gray.php'; ?>
                                            Integration

                                            <span class="tooltip">Integration is not set for this funnel.</span>
                                        </span>
										<?php  } ?>
									</div>

								<?php } ?>

                                <?php if ($isGbfInstalled) { ?>
									<div class="list-cell has-automation">
										<?php if( $isGbf == 'yes' && !empty($start_condition) ) { ?>
											<span class="automation-tag automation-active">
                                            <?php include WPFNL_DIR . '/admin/partials/icons/success-icon-green.php'; ?>
                                            Global funnel
                                            <span class="tooltip">Global funnel is set for this funnel.</span>
                                        </span>
                                        <?php } elseif( $isGbf == 'yes' && !$start_condition ){ ?>
											<span class="automation-tag automation-inactive">
                                            <?php include WPFNL_DIR . '/admin/partials/icons/success-icon-warning.php'; ?>
                                            Global funnel
                                            <span class="tooltip">Opps.. It looks like you did not set any condition for global funnel.</span>
                                        </span>
										<?php } else{ ?>
											<span class="automation-tag automation-inactive">
                                            <?php include WPFNL_DIR . '/admin/partials/icons/success-icon-gray.php'; ?>
                                            Global funnel

                                            <span class="tooltip">Global funnel is not set for this funnel.</span>
                                        </span>
										<?php  } ?>
									</div>
                                <?php } ?>



								<?php if(WPFNL_IS_REMOTE) {?>
									<div class="list-cell builder-type">
										<?php
										$builders = wp_get_post_terms( $funnel->get_id(), 'template_builder', array( 'fields' => 'all' ) );
										if($builders) {
											foreach ($builders as $builder) {
												echo "<span>{$builder->name}</span>";
											}
										}
										?>
									</div>
								<?php } ?>


                                <div class="list-cell publish-status">
                                    <span class="post-status"><?php echo $funnel->get_status() ?></span>
                                    <span class="post-date"><?php echo $funnel->get_published_date() ?></span>
                                </div>

                                <div class="list-cell list-action">
									<?php
										$disable_view_button = apply_filters( 'wpfunnels/disable_funnel_view_button', false, $funnel->get_id() );

                                        if( $disable_view_button ){
                                            ?>
                                            <a class="view <?php echo $disable_view_button ? 'disabled' : ''; ?>" title="<?php esc_attr_e( 'This is a Global Funnel', 'wpfnl' ) ?>">
                                                <?php require WPFNL_DIR . '/admin/partials/icons/eye-icon.php'; ?>
                                                <?php echo __('view', 'wpfnl'); ?>
                                            </a>
                                            <?php
                                        }else{
                                            ?>
                                            <a href="<?php echo esc_url_raw($view_link); ?>" class="view <?php echo $disable_view_button ? 'disabled' : ''; ?>" target="_blank">
                                                <?php require WPFNL_DIR . '/admin/partials/icons/eye-icon.php'; ?>
                                                <?php echo __('view', 'wpfnl'); ?>
                                            </a>
                                            <?php
                                        }
									?>

                                    <a href="<?php echo esc_url_raw($edit_link); ?>" class="edit">
                                        <?php require WPFNL_DIR . '/admin/partials/icons/edit-icon.php'; ?>
                                        <?php echo __('edit', 'wpfnl'); ?>
                                    </a>
                                    <span class="more-action funnel-list__more-action">
                                        <?php require WPFNL_DIR . '/admin/partials/icons/dot-icon.php'; ?>

                                        <ul class="more-actions wpfnl-dropdown">
											<?php if( $is_pro_active || $count_funnels < 3 ): ?>
												<li>
													<a href="#" class="duplicate wpfnl-duplicate-funnel" id="wpfnl-duplicate-funnel" data-id="<?php echo $funnel->get_id(); ?>">
														<?php require WPFNL_DIR . '/admin/partials/icons/duplicate-icon.php'; ?>
														<?php echo __('Duplicate', 'wpfnl'); ?>
														<span class="wpfnl-loader"></span>
													</a>
												</li>
											<?php endif; ?>
                                            <!--                                            <li>-->
                                            <!--                                                <a href="" class="analytics">-->
                                            <!--                                                    --><?php //require WPFNL_DIR . '/admin/partials/icons/graph-icon.php';?>
<!--                                                    --><?php //echo __('Analytics', 'wpfnl');?>
<!--                                                </a>-->
                                            <!--                                            </li>-->
                                            <li>
                                                <a href="" class="delete wpfnl-delete-funnel" id="wpfnl-delete-funnel" data-id="<?php echo $funnel->get_id(); ?>">
                                                    <?php require WPFNL_DIR . '/admin/partials/icons/delete-icon.php'; ?>
                                                    <?php echo __('Delete', 'wpfnl'); ?>
                                                </a>
                                            </li>
                                            <!--                                            <li>-->
                                            <!--                                                <a href="" class="archived">-->
                                            <!--                                                    --><?php //require WPFNL_DIR . '/admin/partials/icons/archive-iocn.php';?>
<!--                                                    --><?php //echo __('Archived', 'wpfnl');?>
<!--                                                </a>-->
                                            <!--                                            </li>-->
                                        </ul>
                                    </span>
                                </div>
                                <!-- /list-action -->

                            </div>

                            <?php
                        } //--end foreach--
                    } else {
                        if (isset($_GET['s'])) {
                            echo __('Sorry No Funnels Found', 'wpfnl');
                        } else {
                            $create_funnel_link = add_query_arg(
                                [
                                    'page' => WPFNL_CREATE_FUNNEL_SLUG,
                                ],
                                admin_url('admin.php')
                            ); ?>

                            <div class="create-new-funnel">
                                <a href="#" class="btn-default add-new-funnel-btn"><?php echo __('Create Your First Funnel', 'wpfnl'); ?></a>
                            </div>
                            <?php
                        }
                    } ?>

                </div>
                <!-- /funnel-list__wrapper -->

                <!-- funnel pagination -->
                <?php if ($this->pagination) {
                        $s = '';
                        if (isset($_GET['s'])) {
                            $s = '&s='. sanitize_text_field($_GET['s']);
                        } ?>
                    <div class="wpfnl-pagination">
                        <a href="<?php if ($this->current_page <= 1) {
                            echo '#';
                        } else {
                            echo "?page=wp_funnels&pageno=".($this->current_page - 1).$s;
                        } ?>" class="nav-link prev <?php if ($this->current_page <= 1) {
                            echo 'disabled';
                        } ?>">
                            <?php require WPFNL_DIR . '/admin/partials/icons/angle-left-icon.php'; ?>
                        </a>

                        <?php
                        for ($i = 1; $i <= $this->total_page; $i ++) {
                            if ($i < 1) {
                                continue;
                            }
                            if ($i > $this->total_funnels) {
                                break;
                            }
                            if ($i == $this->current_page) {
                                $class = "active";
                            } else {
                                $class = "";
                            } ?>
                            <a href="?page=wp_funnels&pageno=<?php echo $i.$s; ?>" class="nav-link <?php echo $class; ?>"><?php echo $i; ?></a>
                            <?php
                        } ?>

                        <a href="<?php if ($this->current_page == $this->total_page) {
                            echo '#';
                        } else {
                            echo "?page=wp_funnels&pageno=".($this->current_page + 1);
                        } ?>" class="nav-link next <?php if ($this->current_page >= $this->total_funnels) {
                            echo 'disabled';
                        } ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 15" width="10" height="15">
                                <defs>
                                    <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                        <path d="M-785 -663L535 -663L535 314L-785 314Z" />
                                    </clipPath>
                                </defs>
                                <style>
                                    .next-icon { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.7 }
                                </style>
                                <g id="Carts" clip-path="url(#cp1)">
                                    <g id="Paginations">
                                        <g id="Check Copy">
                                            <g id="Check">
                                                <g id="chevron-down">
                                                    <path id="Path" class="next-icon" d="M2.5 12.5L7.5 7.5L2.5 2.5" />
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </div>
                <?php
                    } ?>

            </div>
        </div>
    </div>

</div>
<!-- /.wpfnl -->
