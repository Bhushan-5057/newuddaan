<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Binary tree render depth
    |--------------------------------------------------------------------------
    |
    | How many levels below the root the genealogy tree renders on the server.
    | The view reveals three levels on load and expands one more on click, so 4
    | keeps the interaction intact. Rendering the whole downline instead - which
    | is what this page used to do - costs three queries per node and produces a
    | nested document the browser cannot lay out.
    |
    */

    // Sponsor opens with immediate left + right only. Deeper members appear
    // only after that member is clicked (see drilldown_levels).
    'render_levels' => env('TREE_RENDER_LEVELS', 1),

    /*
    |--------------------------------------------------------------------------
    | Drill-down depth
    |--------------------------------------------------------------------------
    |
    | Clicking a left/right member opens this many levels under them in place
    | (sponsor and upper members stay visible). Default 3 matches the genealogy
    | “open three levels on one click” behaviour.
    |
    */

    'drilldown_levels' => env('TREE_DRILLDOWN_LEVELS', 3),

];
