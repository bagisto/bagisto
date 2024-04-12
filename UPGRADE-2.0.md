# UPGRADE FROM 2.1.2 to 2.1.3

In recent updates, we've made changes to how the customised data grid header and body is utilized in our components. 

## Changes in Header Parameter Naming
Previously, the data grid header was customized using parameters such as `columns`, `records`, `sortPage`, `selectAllRecords`, `applied`, and `isLoading`. However, with the latest updates, the parameter names have been revised for clarity and consistency across components.

* ***Old Parameters***:
~~~
<template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading}">
    <!-- Header customization code -->
</template>
~~~
* ***New Parameters***:
~~~
<template #header="{
    isLoading,
    available,
    applied,
    selectAll,
    sort,
    performAction
}">
    <!-- Updated header customization code -->
</template>
~~~

## Changes in Body Parameter Naming
Previously, the data grid body was customized using parameters such as `columns`, `records`, `setCurrentSelectionMode`, `applied`, and `isLoading`. However, with the latest updates, the parameter names have been revised for clarity and consistency across components.

* ***Old Parameters***:
~~~
<template #body="{ columns, records, setCurrentSelectionMode, applied, isLoading }">
    <!-- Updated body customization code -->
</template>
~~~
* ***New Parameters***:
~~~
<template #body="{
    isLoading,
    available,
    applied,
    selectAll,
    sort,
    performAction
}">
    <!-- Updated header customization code -->
</template>
~~~

### Chanege Mass-Action Select Record Vue method name 

* ***Before***:
~~~
    <input
        type="checkbox"
        name="mass_action_select_all_records"
        id="mass_action_select_all_records"
        class="hidden peer"
        :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
        @change="selectAllRecords"
    >
~~~
* ***After***:
~~~
    <input
        type="checkbox"
        name="mass_action_select_all_records"
        id="mass_action_select_all_records"
        class="hidden peer"
        :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
        @change="selectAll"
    >
~~~