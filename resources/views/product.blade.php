@extends('layout')
@section('content')

<div>
    <h3>{{ $product->name }}</h3>
    @if( $product->active==0)
        <h4 style='color:red;'>Product is no longer Available</h4>
    @endif
    <!-- <div style="text-align:right;"><a href="/" class="btn btn-sm btn-primary">Home</a></div> -->
    <div id="layout" class="layout">
        <div class="ind-product">
            <div class="list-img">

                @foreach ($product->images as $k=>$image)
                    <div><img src='{{ $image }}' alt="Image {{ $k }} {{ $product->name }}" id="listimg_{{ $k }}" /></div>
                @endforeach

            </div>
            <div class='main-img'>
                <img src='{{ $product->images[0] }}' alt="Main Image {{ $product->name }}" id="mainimg"/>
            </div>
            <div class="mobile-cb"></div>
            <div class='ind-product-profile'>
                <div>
                <label class="plabel">Brand:</label> {{ $product->brand }}
                </div>
                <div>
                <label class="plabel">Type:</label> {{ ucwords($product->type) }}
                </div>
                @if ($product->aboveground >=0)
                    <div>
                        <label  class="plabel">Above Ground:</label> {{ ($product->aboveground)?'Yes':'No' }}
                    </div>
                @endif
                <div>
                    <label  class="plabel">Description:</label><br/><div class='ind-prod-desc'>{{ $product->description }}</div>
                </div>
            </div>
            <div class="cb"></div>
        <div style="margin-top:20px;">
            <h3>Similar Products</h3>
            <div class="similar-products">
                @foreach($similar_products as $sm_product)
                <div class="sp-shell" data-dest="{{ $sm_product->id}}-{{ $sm_product->seo_name }}">
                    <div class="sp-image">
                        <img src="{{ $sm_product->images[0] }}" />
                    </div>
                    <div class="sp-name">
                        {{ $sm_product->name }}
                    </div>

                </div>
                @endforeach


            </div>

    </div>
</div>

<script>
    $(()=>{
        $.each($("img[id^='listimg']"),function(){

            $(this).mouseover(function(){

                $("#mainimg").attr('src',$(this).attr('src'));
            });

        });
        $.each($('.sp-shell'),function(){
            $(this).click(event=>{
                event.preventDefault();
                location.href = $(this).data('dest');
            });
        });


    });
</script>


@stop
