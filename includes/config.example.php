<?php
// Copy to config.php on each environment (config.php is gitignored).
//
// LIVE (guitarghar.unaux.com): base_path MUST be ''
//   Fill db_* from your InfinityFree / unaux control panel.
//
// LOCAL XAMPP: base_path '/guitarghar' is fine; db often root with empty password.

return [
    'openrouter_api_key' => 'sk-or-v1-YOUR_KEY_HERE',
    'base_path' => '',

    // Live MySQL (from hosting panel) — required on unaux
    'db_host' => 'sqlXXX.infinityfree.com',
    'db_name' => 'if0_XXXX_guitarghar',
    'db_user' => 'if0_XXXX',
    'db_pass' => 'YOUR_DB_PASSWORD',
    // 'db_port' => 3306,
];
