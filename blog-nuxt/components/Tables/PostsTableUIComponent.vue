<script setup lang="ts">
import { getPaginationRowModel } from '@tanstack/vue-table'
import type { TableColumn } from '@nuxt/ui'
import type Post from "~/types/Post/Post";

const table = useTemplateRef('table')
const columns: TableColumn<Post>[] = [
    {
        accessorKey: 'id',
        header: '#',
        cell: ({ row }) => row.getValue('id')
    },
    {
        accessorKey: 'username',
        header: 'Автор',
        cell: ({ row }) => row.original.user?.name ?? '—'
    },
    {
        accessorKey: 'category',
        header: 'Категорія',
        cell: ({row}) => row.original.category?.title ?? '—'
    },
    {
        accessorKey: 'title',
        header: 'Заголовок',
        cell: ({ row }) => row.getValue('title')
    },
    {
        accessorKey: 'published_at',
        header: 'Дата публікації',
        cell: ({ row }) => {
            if (!row.getValue('published_at')) {
                return '—'
            }else{
                return new Date(row.getValue('published_at')).toLocaleString('en-US', {
                    year: 'numeric',
                    day: 'numeric',
                    month: 'short',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                })
            }
        }
    }
]
const { data, status } = await useFetch<Post[]>('http://localhost/api/blog/posts', {
    server:false,
})
const pagination = ref({
    pageIndex: 0,
    pageSize: 10
})
</script>

<template>
    <div class="w-full space-y-4 pb-4">
        <UTable
            ref="table"
            v-model:pagination="pagination"
            :data="data"
            :columns="columns"
            :loading="status === 'pending'"
            :pagination-options="{
        getPaginationRowModel: getPaginationRowModel()
      }"
            class="flex-1"
        />

        <div class="flex justify-center border-t border-default pt-4">
            <UPagination
                :default-page="(table?.tableApi?.getState().pagination.pageIndex || 0) + 1"
                :items-per-page="table?.tableApi?.getState().pagination.pageSize"
                :total="table?.tableApi?.getFilteredRowModel().rows.length"
                @update:page="(p) => table?.tableApi?.setPageIndex(p - 1)"
            />
        </div>
    </div>
</template>
