<?php

test('the datagrid returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
