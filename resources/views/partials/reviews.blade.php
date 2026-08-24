<section class="reviews section-shell" id="reviews">
    <div class="reviews-heading">
        <div><p class="eyebrow">Client stories</p><h2>Dipercaya untuk<br><em>terus melaju.</em></h2></div>
        <p class="reviews-summary">Kolaborasi yang baik terasa dari prosesnya, terlihat dari hasilnya.</p>
    </div>
    <div class="reviews-carousel" data-reviews-carousel>
        <div class="review-grid" data-reviews-track>
            @forelse ($reviews as $review)
                <article class="review-card review-card-{{ $loop->index % 4 }}">
                    <div class="review-top"><span class="review-stars" aria-label="Rating {{ $review->rating }} dari 5">{{ str_repeat('★', $review->rating) }}</span><span class="review-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} / {{ str_pad($reviews->count(), 2, '0', STR_PAD_LEFT) }}</span></div>
                    <blockquote>“{{ $review->comment }}”</blockquote>
                    <div class="review-client"><span class="client-mark">{{ strtoupper(substr($review->company, 0, 1)) }}</span><div><strong>{{ $review->company }}</strong><small>{{ $review->name }}</small></div><form class="review-delete-form" action="{{ route('reviews.destroy', $review) }}" method="POST" data-review-delete-form><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="delete_code" data-delete-code><button class="review-delete" type="submit" aria-label="Hapus ulasan {{ $review->company }}" title="Hapus ulasan">×</button></form></div>
                </article>
            @empty
                <p class="empty-reviews">Belum ada ulasan. Jadilah yang pertama berbagi pengalaman.</p>
            @endforelse
        </div>
        @if ($reviews->count() > 1)
            <div class="reviews-controls" aria-label="Kontrol ulasan">
                <button type="button" data-reviews-prev aria-label="Ulasan sebelumnya">←</button>
                <span data-reviews-status>01 / {{ str_pad($reviews->count(), 2, '0', STR_PAD_LEFT) }}</span>
                <button type="button" data-reviews-next aria-label="Ulasan berikutnya">→</button>
            </div>
        @endif
    </div>
</section>
<section class="review-form-section section-shell" id="beri-ulasan">
    <div class="review-form-intro"><p class="eyebrow">Your voice matters</p><h2>Bagikan<br><em>pengalamanmu.</em></h2><p>Sudah pernah bekerja sama dengan GASS? Ceritakan pengalamanmu agar calon partner kami bisa mengenal cara kerja kami.</p></div>
    <form class="review-form" action="{{ route('reviews.store') }}" method="POST">
        @csrf
        @if (session('review_success'))<div class="review-alert review-alert-success">{{ session('review_success') }}</div>@endif
        @if ($errors->any())<div class="review-alert review-alert-error">Mohon periksa kembali data ulasanmu.</div>@endif
        <label>Nama kamu<input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Rina Putri" required maxlength="100"></label>
        <label>Nama perusahaan<input type="text" name="company" value="{{ old('company') }}" placeholder="Contoh: Nama perusahaan" required maxlength="150"></label>
        <label>Rating
            <span class="rating-input" role="radiogroup" aria-label="Pilih rating">
                @for ($rating = 5; $rating >= 1; $rating--)
                    <input id="rating-{{ $rating }}" type="radio" name="rating" value="{{ $rating }}" @checked(old('rating', 5) == $rating) required><label for="rating-{{ $rating }}">★</label>
                @endfor
            </span>
        </label>
        <label>Ceritakan pengalamanmu<textarea name="comment" rows="4" placeholder="Apa yang paling kamu sukai dari kolaborasi bersama GASS?" required minlength="15" maxlength="600">{{ old('comment') }}</textarea></label>
        <button class="button button-dark" type="submit">Kirim ulasan <span>↗</span></button>
    </form>
</section>
