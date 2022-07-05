<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\Http\BaseHttp;

abstract class BaseModelTest extends BaseHttp
{
    use RefreshDatabase;
}
