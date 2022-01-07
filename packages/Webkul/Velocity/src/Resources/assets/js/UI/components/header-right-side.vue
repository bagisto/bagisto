<template>
    <ul type="none" class="no-margin">
        <li v-for="(content, index) in headerContent" :key="index">
            <a
                v-text="content.title"
                :href="`${$root.baseUrl}/${content['page_link']}`"
                v-if="
                    content['content_type'] == 'link' ||
                        content['content_type'] == 'category'
                "
                :target="content['link_target'] ? '_blank' : '_self'"
            >
            </a>
        </li>
    </ul>
</template>

<script>
export default {
    props: ['headerContent']
};
</script>
