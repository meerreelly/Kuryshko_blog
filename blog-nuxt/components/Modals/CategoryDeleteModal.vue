<script setup lang="ts">
const props = defineProps({
    id: Number,
    title: String
})
const emit = defineEmits(['update'])
const currentState = ref('confirm');
const isModalOpen = defineModel<boolean>()
const close = () => {
    if (currentState.value === 'loading') {
        return;
    }
    currentState.value = 'confirm';
    isModalOpen.value = false;
};
const submitData = async () => {
        currentState.value = 'loading';
        const response = await useFetch(`http://localhost/api/blog/category/${props.id}`, {
            method: 'DELETE',
            onResponse ({ response }) {
                if (response.status === 200) {
                    currentState.value = 'success';
                    emit('update')
                } else {
                    currentState.value = 'error';
                }
            },
        });
};
</script>

<template>
    <UModal v-model:open="isModalOpen" title="Видалення категорії">
        <template #body >

            <div v-if="currentState === 'confirm'" >
                <div class="text-center">
                    <p>Ви впевнені, що хочете видалити {{props.title}}?</p>
                    <p class="text-red-500">Ця дія не може бути скасована.</p>
                </div>
            </div>

            <div v-else-if="currentState === 'loading'" class="flex flex-col items-center justify-center">
                <UIcon name="i-heroicons-arrow-path" class="animate-spin h-12 w-12  mx-auto" />
                <p class="mt-4 text-lg ">Завантаження...</p>
            </div>

            <div v-else-if="currentState === 'success'"  class="flex flex-col items-center justify-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <UIcon name="i-heroicons-check" class="h-6 w-6 text-green-600" />
                </div>
                <h3 class="mt-4 text-lg font-medium ">Категорію успішно видалено!</h3>
            </div>


        <div v-else-if="currentState === 'error'" class="flex flex-col items-center justify-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <UIcon name="i-heroicons-x-mark" class="h-6 w-6 text-red-600" />
            </div>
            <h3 class="mt-4 text-lg font-medium ">Помилка!</h3>
            <p class="mt-1 text-sm ">
                Будь ласка, спробуйте пізніше.
            </p>
        </div>
</template>

<template #footer class="justify-end">
    <UButton
        v-if="currentState === 'confirm'"
        color="error"
        variant="outline"
        size="xl"
        class="min-w-[100px] text-center justify-center"
        @click="close"
        label="Ні"
    />
    <UButton
        v-if="currentState === 'confirm'"
        size="xl"
        class="min-w-[100px] text-center justify-center"
        @click="submitData"
        label="Так"
    />
    <UButton
        v-if="currentState === 'success' || currentState === 'error'"
        color="info"
        variant="outline"
        size="xl"
        class="min-w-[100px] text-center justify-center"
        @click="close"
        label="Закрити"
    />
    <UButton
        v-if="currentState === 'error'"
        color="warning"
        variant="outline"
        size="xl"
        class="min-w-[100px] text-center justify-center"
        @click="submitData"
        label="Спробувати знову"
    />
</template>
    </UModal>
</template>


<style scoped>

</style>
