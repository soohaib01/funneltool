<template>
    <div class="wizard-single-content thankyou">
        <div class="step-header">
            <h4 class="title">You Are Ready!!</h4>
            <p class="description">You are all set. Now it's time to create your own funnels and start seeing more sales!!</p>
        </div>

        <div class="wizard-single-content__body">
            <div class="tutorial-video">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/P1TCNhdGTb8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>            </div>
        </div>

        <div class="wizard-single-content__footer">
            <a :href="dashboardUrl" class="wizard-btn btn-default">Start creating Funnels Now</a>
        </div>

    </div>
</template>

<script>
    export default {
        name: 'Thankyou',
        props: {
            dashboardUrl: String
        },

    }
</script>
