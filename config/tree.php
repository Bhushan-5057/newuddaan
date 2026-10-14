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

    'render_levels' => env('TREE_RENDER_LEVELS', 3),

    /*
    |--------------------------------------------------------------------------
    | Drill-down depth
    |--------------------------------------------------------------------------
    |
    | Clicking a node re-roots the tree on that member. This is how many levels
    | below them are drawn. Kept separate from render_levels so the first view
    | can show a little more than each subsequent hop.
    |
    */

    'drilldown_levels' => env('TREE_DRILLDOWN_LEVELS', 3),

];
