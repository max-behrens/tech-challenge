# TODO (If more time)


## Add option for permenant deletion as well as soft delete.

Add modal that appears once Delete is clicked, asking user if they would like to delete permenantly, or just flag as inactive.
This could be done by crating a separate route in web.php for deleting an item permenantly, which would then be handled differently in ItemController.


## Consider disabling Inertia progress bar in items/Index.vue

Hide the Inertia progess bar in the browser, so that the browser tab doesn't appear to load everytime the search box content is changed.
Could achieve this with:
```bash
npm install @inertiajs/progress

import { InertiaProgress } from '@inertiajs/progress';

InertiaProgress.init({ showSpinner: false });
```

## I have added discovered bugs to a separate .md file called BUGS.md.
