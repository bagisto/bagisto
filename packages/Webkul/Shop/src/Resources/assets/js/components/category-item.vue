
<template>
    <li>
        <a :href="url+'/categories/'+this.item['translations'][0].slug">{{ this.item['translations'][0].name }}&emsp;<i class="icon dropdown-right-icon"
        v-if="haveChildren && item.parent_id != null"></i></a>

        <i :class="[show ? 'icon arrow-down-icon mt-15' : 'icon dropdown-right-icon mt-15']"
        v-if="haveChildren || item.parent_id == null"  @click="showOrHide"></i>

        <ul v-if="haveChildren && show">
            <category-item
                v-for="(child, index) in item.children"
                :key="index"
                :url="url"
                :item="child">
            </category-item>
        </ul>
    </li>
</template>
<script>
// define the item component
export default {
	props: {
		item:  Object,
        url: String,
    },
    data(){
        return {
            items_count:0,
            show: false,
        };
    },
    mounted: function() {
        if(window.innerWidth > 770){
            this.show = true;
        }
    },
    computed: {
        haveChildren() {
            return this.item.children.length ? true : false;
        }
    },
    methods: {
        showOrHide: function() {
            alert('gfgf');
            this.show = !this.show;
        }
    }
}
</script>