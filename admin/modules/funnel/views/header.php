<?php
  $active_funnel_id = $this->funnel->get_id();
  $first_step_id = $funnel->get_first_step_id();
  $view_link = get_the_permalink($first_step_id);
?>
<div class="steps-page__header">
    <div class="steps-page__header-left">
        <div class="wpfnl-logo">
            <?php require_once WPFNL_DIR . '/admin/partials/icons/logo.php'; ?>
        </div>

        <a href="<?php echo $back_link; ?>" class="back-link">
            <?php require WPFNL_DIR . '/admin/partials/icons/angle-left-icon.php'; ?>
            <?php echo __('back', 'wpfnl'); ?>
        </a>

        <form action="" class="steps-page__fnl-name" id="wpfnl-change-funnel-name">
            <label><?php echo __('Funnel Name: ', 'wpfnl'); ?></label>
            <input class="funnel-name-input" type="text" name="funnel_name" value="<?php echo $this->funnel->get_funnel_name(); ?>" />
            <span class="funnel-name"><?php echo $this->funnel->get_funnel_name(); ?></span>
            <input type="hidden" name="funnel_id" value="<?php echo $this->funnel->get_id(); ?>">
            <a class="fnl-name-btn funnel-name-edit">
                <svg width="18" height="18" fill="#7A8B9A" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false">
                    <path d="M20.1 5.1L16.9 2 6.2 12.7l-1.3 4.4 4.5-1.3L20.1 5.1zM4 20.8h8v-1.5H4v1.5z"></path>
                </svg>
            </a>
            <button class="fnl-name-btn funnel-name-submit" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="18" height="18" viewBox="0 0 24 17" stroke-width="3" stroke="#7A8B9A" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 12l5 5l10 -10"></path>
                </svg>
            </button>
        </form>

        <div class="copy-clipboard ">
            <span class="link-icon">
                <?php require WPFNL_DIR . '/admin/partials/icons/link-icon.php'; ?>
            </span>
            <input type="text" id="fnlURL" value="<?php echo $view_link; ?>" readonly>
            <button type="button" title="Copy to Clipboard" class="wpfnl-copy-clipboard" data-id="fnlURL"><?php echo __('copy', 'wpfnl'); ?></button>
            <span class="copied-msg"></span>
        </div>

        <div class="fnl-preview-btn">
            <a href="<?php echo esc_url($view_link); ?>" target="_blank" class="btn-default">
                <?php echo __('Preview', 'wpfnl'); ?>
            </a>
        </div>
    </div>

    <!-- <div class="steps-page__header-center">
        <span class="title"><?php //echo __('Test Mode', 'wpfnl');?></span>

        <span class="wpfnl-switcher sm">
            <input type="checkbox" id="test-mode">
            <label for="test-mode"></label>
        </span>
    </div> -->

    <div class="steps-page__header-right">
        <span class="wpfnl-hamburger" id="steps-header-hamburger">
            <ul class="wpfnl-dropdown">
                <!-- <li class="fnl-preview">
                    <a href="">
                        <?php //require WPFNL_DIR . '/admin/partials/icons/eye-icon.php';?>
                        <?php //echo __('Preview', 'wpfnl');?>
                    </a>
                </li> -->
                <!-- <li class="fnl-state">
                    <a href="">
                        <?php //require WPFNL_DIR . '/admin/partials/icons/graph-icon.php';?>
                        <?php //echo __('Funnel Stats', 'wpfnl');?>
                    </a>
                </li> -->
                <li class="fnl-doc">
                    <a href="<?php echo WPFNL_DOCUMENTATION_LINK;?>" target="_blank">
                        <?php require WPFNL_DIR . '/admin/partials/icons/doc-icon.php'; ?>
                        <?php echo __('Documentation', 'wpfnl'); ?>
                    </a>
                </li>
            </ul>
        </span>
    </div>

</div>
