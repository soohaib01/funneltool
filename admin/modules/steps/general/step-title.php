<?php
    $is_step_settings = isset($_GET["show_settings"]);
    $title = $this->step->get_title();
    $type = $this->step->get_type();

    if ($title == 'thankyou') {
      $title = 'Thank You';
    }
?>

<div class="steps-page__content-title-wrapper">
    <div class="title-area">
        <form action="#">
            <input type="text" id="<?php echo 'step-name-input-'.$this->get_id(); ?>" name="step-name" value="<?php echo $this->step->get_title(); ?>" class="step-name-input" placeholder="Write Step Name" />
            <h1 class="step-name"><?php echo $title; ?></h1>
            <span class="type"> <?php echo '('.$type.')'; ?></span>

            <div class="btn-area">
                <button type="button" class="step-name-edit">
                    <svg width="18" height="18" fill="#7A8B9A" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false">
                        <path d="M20.1 5.1L16.9 2 6.2 12.7l-1.3 4.4 4.5-1.3L20.1 5.1zM4 20.8h8v-1.5H4v1.5z"></path>
                    </svg>
                </button>
                <button data-id="<?php echo $this->get_id(); ?>" type="submit" class="step-name-update">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="18" height="18" viewBox="0 0 24 17" stroke-width="3" stroke="#7A8B9A" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 12l5 5l10 -10"></path>
                    </svg>
                </button>
                <button type="submit" class="step-name-noupdate">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.1667 1.72266L1.72223 11.1671" stroke="#6E42D3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1.72223 1.72266L11.1667 11.1671" stroke="#6E42D3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

        </form>
    </div>

    <?php if (1 == $is_step_settings) { ?>
        <div class="edit-button">
            <?php
            $post_edit_link = get_edit_post_link($this->get_id());
            $builder = get_option('_wpfunnels_general_settings');
            if (isset($builder['builder']) && $builder['builder'] == 'elementor') {
                $post_edit_link = home_url() . '/wp-admin/post.php?post=' . $this->get_id() . '&action=elementor';
            }
            ?>
            <a href="<?php echo $post_edit_link; ?>" target="_blank" class="btn-default edit-wp-page">
                <?php echo __('Edit', 'wpfnl'); ?>
            </a>
        </div>
    <?php } ?>
</div>
