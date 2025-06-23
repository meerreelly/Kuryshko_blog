<script setup lang="ts">
import { getPaginationRowModel } from '@tanstack/vue-table'
import { h, resolveComponent } from 'vue'
import type { Row } from '@tanstack/vue-table'
import type { TableColumn } from '@nuxt/ui'
import type Category from "~/types/Category/Category";
import CategoryDeleteModal from "~/components/Modals/CategoryDeleteModal.vue";
import CategoryCreateModal from "~/components/Modals/CategoryCreateModal.vue";
import CategoryEditModal from "~/components/Modals/CategoryEditModal.vue";

const UButton = resolveComponent('UButton')
const UBadge = resolveComponent('UBadge')
const UDropdownMenu = resolveComponent('UDropdownMenu')
const table = useTemplateRef('table')

const columns: TableColumn<Category>[] = [
    {
        accessorKey: 'id',
        header: '#',
        cell: ({ row }) => row.getValue('id')
    },
    {
        accessorKey: 'title',
        header: 'Назва',
        cell: ({ row }) => row.original.title
    },
    {
        accessorKey: 'category',
        header: 'Батьківська категорія',
        cell: ({row}) => row.original.parent_category?.title ?? '—'
    },
    {
        accessorKey: 'updated_at',
        header: 'Дата зміни',
        cell: ({ row }) => {
            if (!row.getValue('updated_at')) {
                return '—'
            }else{
                return new Date(row.getValue('updated_at')).toLocaleString('en-US', {
                    year: 'numeric',
                    day: 'numeric',
                    month: 'short',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                })
            }
        }
    },
    {
        id: 'actions',
        cell: ({ row }) => {
            return h(
                'div',
                { class: 'text-right' },
                h(
                    UDropdownMenu,
                    {
                        content: {
                            align: 'end'
                        },
                        items: getRowItems(row),
                        'aria-label': 'Actions dropdown'
                    },
                    () =>
                        h(UButton, {
                            icon: 'i-lucide-ellipsis-vertical',
                            color: 'neutral',
                            variant: 'ghost',
                            class: 'ml-auto',
                            'aria-label': 'Actions dropdown'
                        })
                )
            )
        }
    }
]

function getRowItems(row: Row<Category>) {
    return [
        {
            type: 'label',
            label: 'Дії'
        },
        {
            label: 'Редагувати',
            onSelect() {
                data?.value.forEach((category) => {
                    if (category.id === row.getValue('id')) {
                        edit_item.value = category
                        console.log(edit_item.value)
                        is_edit_modal_open.value = true
                        return
                    }
                })
            }
        },
        {
            label: 'Видалити',
            onSelect() {
                delete_id.value = row.getValue('id')
                delete_title.value = row.getValue('title')
                is_delete_modal_open.value = true
            }
        }
    ]
}

const getCategories =  async ()=> {
    return useFetch<Category[]>('http://localhost/api/blog/categories', {
        server: false,
        onResponse({response}) {
            console.log(response)
        },
    });
}

const { data, status } = await getCategories();

const pagination = ref({
    pageIndex: 0,
    pageSize: 5
})

const is_delete_modal_open = ref(false)
const delete_id = ref(0)
const delete_title = ref('')

const is_create_modal_open = ref(false)

const is_edit_modal_open = ref(false)
const edit_item = ref<Category | null>(null)
</script>

<template>
    <div class="w-full space-y-4 pb-4">
        <UButton class="m-2" @click="is_create_modal_open = true" size="md">Додати категорію</UButton>
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
    <CategoryDeleteModal v-model="is_delete_modal_open" :id="delete_id" :title="delete_title" @update="getCategories"/>
    <CategoryCreateModal v-model="is_create_modal_open" @update="getCategories"/>
    <CategoryEditModal v-model="is_edit_modal_open" :item="edit_item" @update="getCategories"/>
</template>
