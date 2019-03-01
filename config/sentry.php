<?php

return array(
    'dsn' => env('SENTRY_LARAVEL_DSN'),

    'send_default_pii' => true,

    // capture release as git sha
    // 'release' => trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD')),

    // Capture bindings on SQL queries
    'breadcrumbs.sql_bindings' => true,
);
