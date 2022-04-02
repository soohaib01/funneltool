<template>
    <div class="category-filter-wrapper">
        <select name="category" id="wpfnl-create-funnel__filter-nav" class="wpfnl-create-funnel__filter-nav" @change="doCatFilter">
            <option value="">All</option>
            <option v-for="(data, index) in categories"
                    :value="data.slug"
                    :key="index"
            >{{ data.name }}</option>
        </select>
        <div class="category-select-dropdown"></div>
    </div>
</template>

<script>
var j = jQuery.noConflict()
export default {
    name: 'CategoryNav',
    props: {
        categories: Array,
        activeCategory: String
    },
    mounted: function() {
        var vm = this;
        j(this.$el.getElementsByTagName('select'))
            .select2({ 
                data: this.categories,
                //dropdownParent: j('.category-select-dropdown')
            })
            .val(this.value)
            .trigger("change")
            .on("change", function() {
                vm.$emit("doCatFilter", this.value);
                vm.$emit("doStepCatFilter", this.value);
            });

        j(document).on('select2:open', function() {
            document.querySelector('.select2-search__field').focus();
        });
    },
    data: function () {
        return {
            activeClass: '',
            activeCat: 'all'
        }
    },
    methods: {
        doCatFilter: function (e) {
            this.$emit('doCatFilter', e)
        }
    }

}
</script>
