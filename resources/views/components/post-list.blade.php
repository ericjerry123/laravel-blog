@foreach ($posts as $post)
    <x-post-card :post="$post" />
@endforeach

<div class="mt-8 flex justify-center">
    {{ $posts->links() }}
</div> 