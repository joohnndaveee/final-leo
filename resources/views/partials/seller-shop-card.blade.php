@php
    $isSale      = isset($product->sale_price) && (float)$product->sale_price > 0 && (float)$product->sale_price < (float)$product->price;
    $finalPrice  = $isSale ? (float)$product->sale_price : (float)$product->price;
    $discPct     = ($isSale && (float)$product->price > 0) ? round((1 - $finalPrice / (float)$product->price) * 100) : 0;
    $avgRating   = $product->reviews ? round($product->reviews->avg('rating') ?? 0, 1) : 0;
    $imgSrc      = !empty($product->image_01) ? asset('uploaded_img/' . $product->image_01) : asset('images/logo.png');
@endphp
<a href="{{ route('product.detail', $product->id) }}" class="ss-card">
    <img src="{{ $imgSrc }}"
         alt="{{ $product->name }}"
         class="ss-card-img"
         loading="lazy"
         onerror="this.src='{{ asset('images/logo.png') }}'">
    <div class="ss-card-body">
        <div class="ss-card-name">{{ $product->name }}</div>
        <div class="ss-card-price-row">
            <span class="ss-card-price">₱{{ number_format($finalPrice, 0) }}</span>
            @if($isSale)
                <span class="ss-card-price-old">₱{{ number_format((float)$product->price, 0) }}</span>
                <span class="ss-card-discount">-{{ $discPct }}%</span>
            @endif
        </div>
        <div class="ss-card-meta">
            @if($avgRating > 0)
                <span class="ss-card-stars">★</span>
                <span class="ss-card-rating">{{ $avgRating }}</span>
                <span>·</span>
            @endif
            @if($product->stock > 0)
                <span>{{ number_format($product->stock) }} in stock</span>
            @endif
        </div>
    </div>
</a>
