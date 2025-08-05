<x-mail::message>
    # ⚠️ Low Stock Alert

    The following product has dropped below the minimum stock level:

    **Product:** {{ $product->name }}
    **Current Stock:** {{ $product->stock }}
    **Minimum Required Stock:** {{ $product->min_stock }}
    **SKU:** {{ $product->base_sku }}
    @if($product->category)
        **Category:** {{ $product->category->name }}
    @endif

    <x-mail::button :url="url('/products/' . $product->id)">
        View Product
    </x-mail::button>

    Please take action to restock or investigate this product.

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
