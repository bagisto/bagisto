<?php

// ============================================================================
// Editor Formatting
// ============================================================================

it('should keep the float the editor gives an aligned image', function () {
    $html = clean_content('<p><img style="float: left;" src="https://example.com/a.png" alt="left" width="80" height="40"></p>');

    expect($html)->toContain('style="float:left;"');
});

it('should keep the column widths of a resized table', function () {
    $html = clean_content('<table border="1"><colgroup><col style="width: 30%;"></colgroup><tbody><tr><td>Cell</td></tr></tbody></table>');

    expect($html)->toContain('<col style="width:30%;"');
});

it('should keep a link the editor opens in a new tab, guarded against the opener', function () {
    $html = clean_content('<a href="https://example.com/" target="_blank">Link</a>');

    expect($html)->toContain('target="_blank"')
        ->and($html)->toContain('noopener')
        ->and($html)->toContain('noreferrer');
});

// ============================================================================
// Refusals
// ============================================================================

it('should drop a link target other than a new tab', function () {
    $html = clean_content('<a href="https://example.com/" target="_top">Link</a>');

    expect($html)->not->toContain('target=');
});

it('should replace a rel that hands the new tab its opener', function () {
    $html = clean_content('<a href="https://example.com/" target="_blank" rel="opener">Link</a>');

    expect($html)->not->toContain('rel="opener"')
        ->and($html)->toContain('noopener');
});

it('should still drop a script url from a link that opens in a new tab', function () {
    $html = clean_content('<a href="javascript:alert(1)" target="_blank">Link</a>');

    expect($html)->not->toContain('javascript');
});

it('should drop positioning while keeping the float', function () {
    $html = clean_content('<img src="https://example.com/a.png" alt="" style="float: left; position: fixed; top: 0;">');

    expect($html)->toContain('float:left;')
        ->and($html)->not->toContain('position')
        ->and($html)->not->toContain('top:');
});
