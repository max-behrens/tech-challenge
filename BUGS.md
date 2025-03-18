# BUGS DISCOVERED


## The items: Array in items/Index.vue, and Dashboard.vue requires an regument.

Defining the items array with a specific type improves type safety and allows for better code validation,
while <any> bypasses type checks and leaves the code prone to potential runtime issues, and leaving it undefined offers no type enforcement.
```bash
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

const props = defineProps<{
    items: Item[];    
    search?: string;
}>();
```


## Styling issue when dashboard search only returns items with no image attached.

CSS classes need modifying to adjust to searches that change the position of the item text when showing items with no image.
