<script setup lang="ts">
import type Post from "~/types/Post/Post";

const props = defineProps(['id'])
const { data, status } = await useFetch<Post>(() => `http://localhost/api/blog/post/${props.id}`,{
    server: false,
    transform(data: Post) {
        if (data.published_at) {
            data.published_at = new Date(data.published_at).toLocaleString('en-US', {
                year: 'numeric',
                day: 'numeric',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            })
        }
        return data
    }
})

</script>

<template>
        <NotFoundComponent v-if="status==='error'"/>
        <div v-if="status === 'success'" class="m-4 bg-gray-500 rounded-lg p-4 flex flex-col gap-4">
            <div class="flex flex-col">
                <div>
                    <h2 class="text-xl font-bold text-white mb-2">
                        {{data?.title}}
                    </h2>
                </div>
                <div class="flex flex-row items-center gap-2">
                    <img :src=data?.user?.profile_photo_url alt="avatar" class="rounded-full max-w-6" />
                    <p class="text-gray-300">{{data?.user?.name}}</p>
                </div>
            </div>
            <p>
                {{data?.content_raw}}
            </p>
            <div class="flex flex-col items-end justify-end">
                <div class="flex flex-row items-center gap-2 w-max">
                    <span class="text-gray-300">Категорія:</span>
                    <span class="text-gray-300">{{data?.category?.title}}</span>
                </div>
                <div class="flex flex-row items-center gap-2 w-max">
                    <span class="text-gray-300">Дата публікації:</span>
                    <span class="text-gray-300">{{data?.published_at}}</span>
                </div>
            </div>
        </div>
</template>

