<?php

namespace Webkul\Product\Exceptions;

use Exception;

/**
 * Class InsufficientProductInventoryException
 *
 * Thrown when a product does not have sufficient inventory
 * to fulfill the requested quantity during cart or order operations.
 *
 * This exception is typically raised while validating product
 * availability before adding items to the cart or placing an order.
 */
class InsufficientProductInventoryException extends Exception {}
