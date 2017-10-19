@if (count($products))

    @foreach($products as $product)
        <div class='product-profile' id='product_{{ $product->id }}'>
            <div class='product-img'>
                <img src="{{ $product->images[0] }}" />
            </div>
            <div class='product-details'>
                <div class="profile-title">{{ $product->name }}</div>
                <div class="profile-brand">by {{ $product->brand }}</div>
                <div class="profile-cata">Catagory: {{ ucwords($product->type) }}</div>
                <div class="profile-btn">
                    <a href="/product/{{ $product->id }}-{{ $product->seo_name }}"
                        id="btn_{{ $product->id }}"
                        class="btn btn-sm btn-primary">
                    More Info</a>
                </div>

            </div>
            <div class="cb"></div>
        </div>
    @endforeach
@else
    <h3>There were no Products returned.</h3>

@endif
