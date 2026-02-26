<?php

test('the admin login page returns a successful response', function () {
    $response = $this->get('/admin/login');

    $response->assertStatus(200);
});
