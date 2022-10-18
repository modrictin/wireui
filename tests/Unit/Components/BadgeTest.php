<?php

use Illuminate\Support\Facades\Blade;

it('should render the badge slot', function () {
    $html = Blade::render('<x-badge><b>Label From Slot</b></x-badge>');

    expect($html)->toContain('<b>Label From Slot</b>');
});

it('should render the badge default', function () {
    $html = Blade::render('<x-badge label="default" />');

    expect($html)->toContain('default')->toContain('text-slate-500');
});

it('should render the badge primary', function () {
    $html = Blade::render('<x-badge label="Primary" primary />');

    expect($html)->toContain('Primary')->toContain('bg-primary-500');
});

it('should render the badge secondary', function () {
    $html = Blade::render('<x-badge label="Secondary" secondary />');

    expect($html)->toContain('Secondary')->toContain('bg-secondary-500');
});

it('should render the badge positive', function () {
    $html = Blade::render('<x-badge label="Positive" positive />');

    expect($html)->toContain('Positive')->toContain('bg-positive-500');
});

it('should render the badge negative', function () {
    $html = Blade::render('<x-badge label="Negative" negative />');

    expect($html)->toContain('Negative')->toContain('bg-negative-500');
});

it('should render the badge warning', function () {
    $html = Blade::render('<x-badge label="Warning" warning />');

    expect($html)->toContain('Warning')->toContain('bg-warning-500');
});

it('should render the badge info', function () {
    $html = Blade::render('<x-badge label="Info" info />');

    expect($html)->toContain('Info')->toContain('bg-info-500');
});

it('should render the badge dark', function () {
    $html = Blade::render('<x-badge label="Dark" dark />');

    expect($html)->toContain('Dark')->toContain('bg-gray-700');
});

it('should render the badge white', function () {
    $html = Blade::render('<x-badge label="White" white />');

    expect($html)->toContain('White')->toContain('bg-white');
});

it('should render the badge with prepend', function () {
    $html = <<<EOT
        <x-badge primary label="Prepend">
            <x-slot name="prepend">
                <b>add prepend</b>
            </x-slot>
        </x-badge>
    EOT;

    $html = Blade::render($html);

    expect($html)->toContain('Prepend')->toContain('<b>add prepend</b>');
});

it('should render the badge with append', function () {
    $html = <<<EOT
        <x-badge primary label="Append">
            <x-slot name="append">
                <b>add append</b>
            </x-slot>
        </x-badge>
    EOT;

    $html = Blade::render($html);

    expect($html)->toContain('Append')->toContain('<b>add append</b>');
});
