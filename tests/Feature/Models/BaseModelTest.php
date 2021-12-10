<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class BaseModelTest extends TestCase {
    use RefreshDatabase; // This helps to restore database after run testings
}