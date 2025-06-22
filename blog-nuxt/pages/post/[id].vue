<script setup lang="ts">
import PostComponent from "~/components/Post/PostComponent.vue";
import ButtonToPosts from "~/components/Buttons/ButtonToPosts.vue";
const route = useRoute()
const id = computed(() => route.params.id)

</script>
<template>
    <ButtonToPosts/>
    <PostComponent :id=id />
</template>
