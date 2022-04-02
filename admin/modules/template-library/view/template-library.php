<script type="text/template" id="tmpl-wpfnl-templates-category-filter">
    <%= name %>
</script>


<script type="text/template" id="tmpl-wpfnl-templates-view">
    <% if (is_pro) { %>
        <span class="pro-tag">coming soon</span>
    <% } %>
    <div class="importar-loader">
        <span class="title-wrapper">
            <span class="title">Import Started</span>
            <span class="dot-wrapper">
                <span class="dot-one">.</span>
                <span class="dot-two">.</span>
                <span class="dot-three">.</span>
            </span>
        </span>
    </div>
    <img src="<%= featured_image %>" alt="funnel template" class="template-img">
    <div class="funnel-template-info">
        <span class="title"><%= title.rendered %></span>
        <div class="template-action">
            <span class="steps"><%= steps_order.length %> steps</span>
            <a href="<%= link %>" target="_blank" class="btn-default preview"><?php echo __('preview', 'wpfnl'); ?></a>
        </div>
    </div>
</script>


<script type="text/template" id="tmpl-wpfnl-step-templates-view">
    <% if (is_pro) { %>
        <span class="pro-tag">coming soon</span>
    <% } %>
    <div class="importar-loader">
        <span class="title-wrapper">
            <span class="title">Import Started</span>
            <span class="dot-wrapper">
                <span class="dot-one">.</span>
                <span class="dot-two">.</span>
                <span class="dot-three">.</span>
            </span>
        </span>
    </div>
    <img src="<%= featured_image %>" alt="funnel template" class="template-img">
    <div class="funnel-template-info">
        <span class="title"><%= title.rendered %></span>
        <div class="template-action">
            <a href="<%= link %>" target="_blank" class="btn-default preview"><?php echo __('preview', 'wpfnl'); ?></a>
            <a href="#" class="btn-default import wpfnl-import-step" data-funnel-id="<?php if(isset($_GET['id'])){ echo sanitize_text_field($_GET['id']);} ?>"><?php echo __('import', 'wpfnl'); ?></a>
        </div>
    </div>
</script>
