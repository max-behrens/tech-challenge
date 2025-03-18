<script setup lang="ts">
import { defineProps, ref, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import Card from '@/components/Card.vue';
import { router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Define the item properties type.
type Item = {
    id: number;
    name: string;
    description: string;
    content_type: string;
    active: boolean;
    weblink_url?: string;
    download_url?: string;
    info_content?: string;
};

defineProps<{
    name?: string;
    items: Item[];
}>();

const search = ref('');

// Watch for changes in the search term and update the router query parameters.
watch(search, (value) => {
    router.get(route('dashboard'), { search: value }, { preserveState: true, preserveScroll: true });
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="p-6 bg-white rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <h3 class="text-black text-2xl">Search</h3>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search items..."
                    class="w-full px-4 py-2 border rounded-lg text-black"
                />
            </div>

            <div class="p-6 bg-white rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <h3 class="text-black text-2xl">Latest Items</h3>
                <div class="grid gap-4 grid-cols-6 mt-4">
                    <Card v-for="item in items" :key="item.id" :item="item" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
