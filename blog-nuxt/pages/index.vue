<script setup lang="ts">

import ButtonToPosts from "~/components/Buttons/ButtonToPosts.vue";
</script>

<template>
    <div class="flex place-items-center justify-center min-h-screen">
        <div class="flex flex-col items-center">
            <UIcon name="i-lucide-rocket" class="size-10" />
            <a class="text-emerald-300 underline text-lg" href="/blogposts">Таблиця постів</a>
            <a class="text-emerald-300 underline text-lg" href="/blogpostsui">Таблиця постів з nuxt table</a>
            <a class="text-emerald-300 underline text-lg" href="/post/11">Сторінка з постом</a>
            <a class="text-emerald-300 underline text-lg" href="/blogcategories">Таблиця з категоріями</a>
        </div>
    </div>
</template>

