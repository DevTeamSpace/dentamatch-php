<li>
  <div class="rating_on"> {{ $slot }}</div>
  <ul class="rate_me">
    @for($i=1; $i <= 5; $i++)
      @if($i <= round($ratingValue))
        <li>
          <span class="{{ \App\Helpers\TemplateHelper::getRatingClassName($ratingValue) }}"></span>
        </li>
      @else
        <li><span></span></li>
      @endif
    @endfor
  </ul>
  <label class="total-count "><span class="counter">{{ round($ratingValue) }}</span>/5</label>
</li>