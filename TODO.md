# TODO (If more time)

## Add relevant content from joined tables to Dashboard.vue, as well as when searching in items/Index.vue.

Refactor the filtering functionality in items/Index.vue, into Dashboard.vue.

## Add option for permenant deletion as well as soft delete.

Add modal that appears once Delete is clicked, asking user if they would like to delete permenantly, or just flag as inactive.
This could be done by crating a separate route in web.php for deleting an item permenantly, which would then be handled differently in ItemController.

## Change db locally from mysql to sqlite.

When setting up my local environment, it was easier to handle the data in my existing mysql connection where I created a new db 'laravel' to hold the the tables.
If I had more time, I would ensure the setup for sqlite was working locally so I could use this instead.


## Consider disabling Inertia progress bar in items/Index.vue

Hide the Inertia progess bar in the browser, so that the browser tab doesn't appear to load everytime the search box content is changed.
Could achieve this with:
```bash
npm install @inertiajs/progress

import { InertiaProgress } from '@inertiajs/progress';

InertiaProgress.init({ showSpinner: false });
```

## I have added discovered bugs to a separate .md file called BUGS.md.
