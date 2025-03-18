<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { defineProps, ref, watch, computed } from 'vue';

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

interface Item {
  id: number;
  name: string;
  description: string;
  content_type: string;
  active: boolean;
  content_id: number;
  weblink_url?: string;
  download_url?: string;
  info_content?: string;
}

// Array type requires a generic type argument.
const props = defineProps<{
    items: Item[];    
    search?: string;
}>();

const search = ref(props.search || '');

const filter = ref('All');

// Watch the search and filter values,
// and update the query string to maintain state when the user changes them.
watch([search, filter], ([newSearch, newFilter]) => {
    router.get(route('admin.items.index'),
     { search: newSearch, filter: newFilter }, 
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

// Filter the items based on selected content_type.
const filteredItems = computed(() => {
  if (filter.value === 'All' || !filter.value) {
    return props.items;
  }
  return props.items.filter(item => item.content_type === filter.value);
});

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-between">
                    <h3 class="text-2xl">Items</h3>

                    <!-- Type Filter Dropdown -->
                    <div class="ml-4">
                        <label for="content-type-filter" class="mr-2 text-lg">Filter by Type:</label>
                        <select
                            id="content-type-filter"
                            v-model="filter"
                            class="px-4 py-2 border rounded-lg text-black">
                            <option value="All">All</option>
                            <option value="info">Info</option>
                            <option value="download">Download</option>
                            <option value="WEBLINK">Weblink</option>
                        </select>
                    </div>

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
                            <TableRow v-for="item in filteredItems" :key="item.id">
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
                                    <Button :as="Link" variant="link" :href="route('admin.items.edit', { id: item.id })"> Edit </Button>
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
