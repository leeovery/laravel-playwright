<?php

declare(strict_types=1);

use Leeovery\LaravelPlaywright\Commands\Database\Schema\GrammarFactory;
use Leeovery\LaravelPlaywright\Commands\Database\Schema\Grammars\MySQL;
use Leeovery\LaravelPlaywright\Commands\Database\Schema\Grammars\PgSQL;

it('grammar factory creates mysql grammar', function () {
    $factory = new GrammarFactory;
    $grammar = $factory->make('mysql');

    expect($grammar)->toBeInstanceOf(MySQL::class);
});

it('grammar factory creates pgsql grammar', function () {
    $factory = new GrammarFactory;
    $grammar = $factory->make('pgsql');

    expect($grammar)->toBeInstanceOf(PgSQL::class);
});

it('grammar factory throws for unknown driver', function () {
    $factory = new GrammarFactory;
    $factory->make('unknown');
})->throws(RuntimeException::class);

it('mysql grammar creates database statement', function () {
    $grammar = new MySQL;
    $sql = $grammar->compileCreateDatabase(['database' => 'testdb', 'charset' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);

    expect($sql)->toContain('testdb');
});

it('mysql grammar drops database statement', function () {
    $grammar = new MySQL;
    $sql = $grammar->compileDropDatabase('testdb');

    expect($sql)->toContain('testdb');
});

it('pgsql grammar creates database statement', function () {
    $grammar = new PgSQL;
    $sql = $grammar->compileCreateDatabase(['database' => 'testdb', 'charset' => 'UTF8', 'collation' => 'en_US.UTF-8']);

    expect($sql)->toContain('testdb');
});

it('pgsql grammar drops database statement', function () {
    $grammar = new PgSQL;
    $sql = $grammar->compileDropDatabase('testdb');

    expect($sql)->toContain('testdb');
});
