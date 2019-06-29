<?php

namespace Webkul\SAASCustomizer\Validation;

use Illuminate\Validation\DatabasePresenceVerifier as BaseVerifier;
use Company;

class DatabasePresenceVerifier extends BaseVerifier
{
    /**
     * Get a query builder for the given table.
     *
     * @param  string  $table
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table($table)
    {
        $company = Company::getCurrent();

        if (count(explode('as', $table)) == 2) {
            $table = explode('as', $table);
            $table = trim($table[0]);
        }

        if (isset($company->id) && $table != 'admins') {
            return $this->db->connection($this->connection)->table($table)->useWritePdo()->where ('company_id', '=', $company->id);
        } else {
            // apply the company id check dynamically here to eliminate unique validation woes
            return $this->db->connection($this->connection)->table($table)->useWritePdo();
        }
    }
}