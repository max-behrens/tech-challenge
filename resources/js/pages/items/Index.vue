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
                                <TableHead>Active</TableHead>
                                <TableHead class="text-right"> Actions </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="item in items" :key="item.id">
                                <TableCell>
                                    {{ item.name }}
                                </TableCell>
                                <TableCell>{{ item.content_type }}</TableCell>
                                <TableCell>{{ item.active ? 'Active' : 'Inactive' }}</TableCell>
                                <TableCell class="text-right">
                                    <Button :as="Link" variant="link" :href="route('admin.items.edit', item.id)"> Edit </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
