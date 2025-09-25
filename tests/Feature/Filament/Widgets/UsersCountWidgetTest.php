<?php

declare(strict_types=1);

use App\Filament\Widgets\UsersCountWidget;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('renders the users count widget with current user total', function (): void {
    // Authenticate as any user to access the Filament panel/dashboard.
    $user = User::factory()->create();
    actingAs($user);

    // Create a few more users to verify the count is reflected.
    User::factory()->count(3)->create();

    Filament::setCurrentPanel('admin');

    $expectedCount = (string) User::query()->count();

    Livewire::test(UsersCountWidget::class)
        ->assertOk()
        ->assertSee('Users')
        ->assertSee($expectedCount);
});
