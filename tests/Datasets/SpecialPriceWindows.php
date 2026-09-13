<?php

/**
 * A special price of 800 on a product priced at 1000, with the days from today its window
 * opens and closes and the price the shopper is charged as a result.
 */
sharedDataset('special price windows', [
    'inside its date range' => [-1, 30, 800],
    'after its date range ended' => [-30, -1, 1000],
    'before its date range starts' => [1, 30, 1000],
    'with no date range' => [null, null, 800],
]);
