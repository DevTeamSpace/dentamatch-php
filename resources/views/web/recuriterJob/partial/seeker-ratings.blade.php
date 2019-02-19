@if(!empty($avgRating))
  <span class="dropdown-toggle label {{ \App\Helpers\TemplateHelper::getRatingClassName($avgRating) }}"
        data-toggle="dropdown">
    {{number_format($avgRating, 1, '.', '')}}
  </span>
@else
  <span class="dropdown-toggle label label-success">Not Yet Rated</span>
@endif

<ul class="dropdown-menu rating-info seeker-rating-info">
  @component('web.recuriterJob.partial.rating', ['ratingValue' => $punctuality])
    Punctuality <span class="ex-text">(Did they show up & were they on time?)</span>
  @endcomponent

  @component('web.recuriterJob.partial.rating', ['ratingValue' => $timeManagement])
    Work performance <span class="ex-text">(Were they efficient? Were they a team player?)</span>
  @endcomponent

  @component('web.recuriterJob.partial.rating', ['ratingValue' => $skills])
    Skill & Aptitude <span class="ex-text">(Were the clinical skill on point? Was the candidate engaging with the patients and other members of the staff?)</span>
  @endcomponent
</ul>