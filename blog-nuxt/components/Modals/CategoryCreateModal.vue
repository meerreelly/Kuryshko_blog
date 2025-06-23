<script setup lang="ts">
import { z } from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'
import type Post from "~/types/Post/Post";
import type Category from "~/types/Category/Category";
import type ParentCategory from "~/types/Category/ParentCategory";

const { data:categories, status } = await useFetch('http://localhost/api/blog/category/create', {
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
    title: '',
    slug: '',
    parent_id: 1,
    description: '',
})

const emit = defineEmits(['update'])

const isModalOpen = defineModel<boolean>()

const close = () => {
    isModalOpen.value = false;
};

const toast = useToast()

async function onSubmit(event: FormSubmitEvent<Schema>) {
    console.log('Submitted data:', event.data);
    const response = await useFetch(`http://localhost/api/blog/category/create`, {
        method: 'POST',
        body: event.data,
        onResponse ({ response }) {
            console.log(response.status)
            if (response.status === 201) {
                emit('update')
                toast.add({ title: 'Успіх', description: 'Категорія успішно створена', color: 'success' })
                close();
            } else {
                toast.add({ title: 'Помилка', description: 'Помилка при створені категорії.', color: 'error' })
            }
        },
    });

}

</script>

<template>
    <UModal v-model:open="isModalOpen" title="Створення категорії">
        <template #body >
            <div class="flex flex-col items-center justify-center">
                <UForm :schema="schema" :state="state" class="space-y-4" @submit="onSubmit">
                    <div class="flex flex-row">
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
                                    :loading="status === 'pending'"
                                    value-key="value"
                                    placeholder="Оберіть категорію"
                                />
                            </UFormField>
                        </div>
                    </div>
                    <UButton type="submit">
                        Створити категорію
                    </UButton>
                </UForm>
            </div>
        </template>
    </UModal>
</template>


<style scoped>

</style>
