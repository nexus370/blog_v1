@props(['post', 'zoom' => true])

<div {{ $attributes->merge(['class' => 'post___header']) }}>
    <div class="title">
        {{ $post->title }}
    </div>
    <div class="image-container {{ $zoom ? 'image-container--zoom' : ''}}">
        <img src="{{ $post->image_url }}" alt="{{ $post->title }}"/>
        <div class="category-name ">
            {{ $post->category->name}}
        </div>
        <div class="views-counter">
            {{ $post->viewsCount() }} 
            @if( $post->viewsCount() == 1) 
                view
            @else 
                views 
            @endif
        </div>
        <div class="middle">
            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="white" width="48px" height="48px">
                <g>
                    <rect fill="none" height="24" width="24"/>
                    <g>
                        <path d="M19,5v14H5V5H19 M19,3H5C3.9,3,3,3.9,3,5v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V5C21,3.9,20.1,3,19,3L19,3z"/>
                    </g>
                    <path d="M14,17H7v-2h7V17z M17,13H7v-2h10V13z M17,9H7V7h10V9z"/>
                </g>
            </svg>
        </div>
    </div>
    <div class="details">
        <div class="author">
            {{ $post->user->name }}
        </div>
        <div class="date">
            {{$post->created_at }}
        </div>
    </div>
</div>