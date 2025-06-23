<script setup lang="ts">
import { z } from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'
import type Post from "~/types/Post/Post";
import type Category from "~/types/Category/Category";
import type ParentCategory from "~/types/Category/ParentCategory";

const props = defineProps(['item'])


const isModalOpen = defineModel<boolean>()
function formatDate(dateStr: string | null): string {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleString('en-US', {
        year: 'numeric',
        day: 'numeric',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    });
}

const { data:categories, status: listStatus } = await useFetch(`http://localhost/api/blog/category/create`, {
    transform: (data: { id: number, id_title: string }[]) => {
        return data?.map(category => ({
            label: category.id_title,
            value: category.id,
        }))
    },
    lazy: true,
    server: false,
})

const schema = z.object({
    title: z.string().min(5, 'Має бути не менше 5 символів'),
    slug: z.string(),
    parent_id: z.number({ required_error: 'Категорія обовʼязкова' }),
    description: z.string().min(3, 'Має бути не менше 3 символів'),
})

type Schema = z.infer<typeof schema>

const state = reactive<Schema>({
    title: props.item?.title || '',
    slug: props.item?.slug || '',
    parent_id: props.item?.parent_id || 1,
    description: props.item?.description || '',
})

watch(() => props.item, (value) => {
    if (value) {
        Object.assign(state, {
            title: value.title,
            slug: value.slug,
            parent_id: value.parent_id,
            description: value.description
        })
    }
}, { immediate: true })

const emit = defineEmits(['update'])


const close = () => {
    isModalOpen.value = false;
};

const toast = useToast()

async function onSubmit(event: FormSubmitEvent<Schema>) {
    const response = await useFetch(`http://localhost/api/blog/category/edit/${props.item.id}`, {
        method: 'PUT',
        body: event.data,
        onResponse ({ response }) {
            if(response.status === 200){
                console.log(response.status)
                emit('update')
                toast.add({ title: 'Успіх', description: 'Категорія успішно оновлена', color: 'success' })
                close();
            }
        },
        onResponseError(){
            toast.add({ title: 'Помилка', description: 'Помилка при оновлені категорії.', color: 'error' })
        }
    });
}


</script>

<template>
    <UModal v-model:open="isModalOpen"  title="Створення категорії">
        <template #body >
            <div class="flex flex-col items-center justify-center">
                <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
                    <div class="flex flex-row gap-4">
                        <div>
                            <UFormField label="Назва" name="title">
                                <UInput v-model="state.title" />
                            </UFormField>

                            <UFormField label="Псевдонім" name="slug">
                                <UInput v-model="state.slug" />
                            </UFormField>
                            <UFormField label="Опис" name="description">
                                <UTextarea v-model="state.description" />
                            </UFormField>
                        </div>
                        <div>
                            <UFormField label="Батьківська категорія" name="parent_category">
                                <USelect
                                    v-model="state.parent_id"
                                    :items="categories"
                                    :loading="listStatus === 'pending'"
                                    value-key="value"
                                    placeholder="Оберіть категорію"
                                />
                            </UFormField>
                            <UFormField label="Дата створення" name="created">
                                <UInput :model-value="formatDate(props.item?.created_at)" disabled type="text" />
                            </UFormField>
                            <UFormField label="Дата останьої зміни" name="updated">
                                <UInput :model-value="formatDate( props.item?.updated_at)" disabled type="text" />
                            </UFormField>
                        </div>
                    </div>
                    <UButton type="submit">
                        Зберегти зміни
                    </UButton>
                </UForm>
            </div>
        </template>
    </UModal>
</template>


<style scoped>

</style>
