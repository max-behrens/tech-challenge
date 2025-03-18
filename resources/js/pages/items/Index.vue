<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { defineProps, ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/admin/dashboard',
    },
    {
        title: 'Items',
        href: '/admin/items',
    },
];
// Array type requires a generic type argument.
const props = defineProps<{
    items: Array<any>;    
    search?: string;
}>();

// Define the item search box.
const search = ref(props.search || '');

watch(search, (value) => {
    router.get(route('admin.items.index'),
     { search: value }, 
     { 
        preserveState: true, 
        preserveScroll: true,
        replace: true
    });
});

// Define delete route as a POST request.
const deleteItem = (id: number) => {
  if (confirm('Are you sure you want to delete this item?')) {
    router.delete(route('admin.items.delete', { id }));
  }
};

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-between">
                    <h3 class="text-2xl">Items</h3>
                    <Button :as="Link" :href="route('admin.items.create')">Create Item</Button>
                </div>

                <!-- Item Search Box -->
                <div class="mt-4">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search items..."
                        class="w-full px-4 py-2 border rounded-lg text-black"
                    />
                </div>

                <!-- Items Table -->
                <div class="mt-8 flex flex-col">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Type</TableHead>
                                <!-- Show Description & Content instead of Active column, only when search has a term. -->
                                <TableHead v-if="search">Description</TableHead>
                                <TableHead v-if="search">Content</TableHead>
                                <TableHead v-if="!search">Active</TableHead>
                                <TableHead class="text-right"> Actions </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="item in items" :key="item.id">
                                <TableCell>
                                    {{ item.name }}
                                </TableCell>
                                <TableCell>{{ item.content_type }}</TableCell>
                                <!-- Show Content & Description columns only when search has a term. -->
                                <TableCell v-if="search">{{ item.description }}</TableCell>
                                <TableCell v-if="search">
                                    <span v-if="item.content_type === 'WEBLINK'">
                                        <a :href="item.weblink_url" target="_blank" class="text-blue-500">{{ item.weblink_url }}</a>
                                    </span>
                                    <span v-if="item.content_type === 'download'">
                                        <a :href="item.download_url" target="_blank" class="text-blue-500">{{ item.download_url }}</a>
                                    </span>
                                    <span v-if="item.content_type === 'info'" v-html="item.info_content"></span>
                                </TableCell>
                                <!-- Show Active column only when search is empty. -->
                                <TableCell v-if="!search">
                                    {{ item.active ? 'Active' : 'Inactive' }}
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button :as="Link" variant="link" :href="route('admin.items.edit', item.id)"> Edit </Button>
                                    <Button type="button" variant="link" @click="deleteItem(item.id)">Delete</Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
