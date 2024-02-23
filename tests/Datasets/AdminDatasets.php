<?php

use App\Models\Admin;

dataset('admin', runConcurrentTests(fn () => Admin::factory()->create()));
