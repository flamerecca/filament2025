<?php

declare(strict_types=1);

use App\Models\{Category, Product, Promotion};
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a product with a category', function (): void {
    $category = Category::factory()->create();

    $product = Product::factory()->create([
        'category_id' => $category->id,
    ]);

    expect($product->exists)->toBeTrue();
    expect($product->category->is($category))->toBeTrue();
});

it('attaches promotions to product and queries active ones', function (): void {
    $product = Product::factory()->create();

    $activePromotion = Promotion::factory()->create([
        'active' => true,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDay(),
    ]);

    $inactivePromotion = Promotion::factory()->create([
        'active' => false,
    ]);

    $product->promotions()->attach([$activePromotion->id, $inactivePromotion->id]);

    $active = $product->promotions()->currentlyActive()->get();

    expect($active->contains($activePromotion))->toBeTrue();
    expect($active->contains($inactivePromotion))->toBeFalse();
});
